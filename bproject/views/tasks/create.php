<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";
require_once "../../config/csrf.php";

$page_title = "Nouvelle tâche";
$role_id    = $_SESSION['role_id'];

if (!in_array($role_id, [1, 2, 3])) {
    header('Location: ' . bp_url('dashboard.php'));
    exit();
}

$title = $description = $due_date = $error = "";
$project_id  = 0;
$assigned_to = null;
$status      = "À faire";
$priority    = "Moyenne";

$projects = mysqli_query($conn, "SELECT id, title FROM projects ORDER BY title");
$users    = mysqli_query($conn,
    "SELECT u.id, u.name, r.name AS role_name
     FROM users u
     JOIN roles r ON u.role_id = r.id
     ORDER BY u.name"
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verifyCSRFToken($_POST['csrf_token'] ?? '');

    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $project_id  = intval($_POST['project_id']  ?? 0);
    $assigned_to = !empty($_POST['assigned_to']) ? intval($_POST['assigned_to']) : null;
    $status      = $_POST['status']   ?? 'À faire';
    $priority    = $_POST['priority'] ?? 'Moyenne';
    $due_date    = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

    if (empty($title) || empty($project_id)) {
        $error = "Le titre et le projet sont obligatoires.";
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO tasks
             (title, description, project_id, assigned_to, status, priority, due_date, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
        );
        mysqli_stmt_bind_param($stmt, "ssiisss",
            $title, $description, $project_id,
            $assigned_to, $status, $priority, $due_date
        );
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php?success=Tâche+créée+avec+succès");
            exit();
        } else {
            $error = "Erreur lors de la création de la tâche.";
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-plus-square-fill me-2" style="color:var(--accent);font-size:13px;"></i>
    Nouvelle tâche
  </span>
  <a href="index.php" class="btn-bp-ghost">
    <i class="bi bi-arrow-left"></i> Retour
  </a>
</div>

<div class="bp-content">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-7">
      <div class="bp-card">
        <div class="bp-card-title">Informations de la tâche</div>

        <?php if ($error): ?>
          <div class="bp-alert bp-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form method="POST">
          <input type="hidden" name="csrf_token"
                 value="<?php echo generateCSRFToken(); ?>">

          <div class="bp-form-group">
            <label class="bp-label">
              Titre <span style="color:#ef4444;">*</span>
            </label>
            <input type="text" name="title" class="bp-input"
                   placeholder="Ex : Créer la page d'accueil"
                   value="<?php echo htmlspecialchars($title); ?>"
                   required autofocus>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">Description</label>
            <textarea name="description" class="bp-textarea"
                      placeholder="Détails de la tâche..."><?php echo htmlspecialchars($description); ?></textarea>
          </div>

          <div class="row g-3">
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">
                  Projet <span style="color:#ef4444;">*</span>
                </label>
                <select name="project_id" class="bp-select" required>
                  <option value="">-- Choisir --</option>
                  <?php while ($pr = mysqli_fetch_assoc($projects)): ?>
                    <option value="<?php echo $pr['id']; ?>"
                      <?php echo $project_id == $pr['id'] ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($pr['title']); ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">Assigné à</label>
                <select name="assigned_to" class="bp-select">
                  <option value="">-- Non assigné --</option>
                  <?php while ($u = mysqli_fetch_assoc($users)): ?>
                    <option value="<?php echo $u['id']; ?>"
                      <?php echo $assigned_to == $u['id'] ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($u['name'] . ' (' . $u['role_name'] . ')'); ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-4">
              <div class="bp-form-group">
                <label class="bp-label">Statut</label>
                <select name="status" class="bp-select">
                  <?php foreach (['À faire','En cours','Terminé','Bloquée'] as $s): ?>
                    <option value="<?php echo $s; ?>"
                      <?php echo $status === $s ? 'selected' : ''; ?>>
                      <?php echo $s; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="bp-form-group">
                <label class="bp-label">Priorité</label>
                <select name="priority" class="bp-select">
                  <?php foreach (['Basse','Moyenne','Haute'] as $p): ?>
                    <option value="<?php echo $p; ?>"
                      <?php echo $priority === $p ? 'selected' : ''; ?>>
                      <?php echo $p; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-4">
              <div class="bp-form-group">
                <label class="bp-label">Date d'échéance</label>
                <input type="date" name="due_date" class="bp-input"
                       value="<?php echo $due_date; ?>">
              </div>
            </div>
          </div>

          <div class="divider"></div>
          <div class="d-flex gap-2 justify-content-end">
            <a href="index.php" class="btn-bp-ghost">Annuler</a>
            <button type="submit" class="btn-bp-primary">
              <i class="bi bi-check-lg"></i> Créer la tâche
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "../layout/footer.php"; ?>