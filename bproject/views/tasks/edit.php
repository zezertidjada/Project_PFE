<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";
require_once "../../config/csrf.php";

$page_title = "Modifier la tâche";
$role_id    = $_SESSION['role_id'];

if (!in_array($role_id, [1, 2, 3])) {
    header("Location: /bproject/dashboard.php");
    exit();
}

if (empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Récupérer tâche
$stmt = mysqli_prepare($conn, "SELECT * FROM tasks WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit();
}

$task = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$projects = mysqli_query($conn, "SELECT id, title FROM projects ORDER BY title");
$users    = mysqli_query($conn,
    "SELECT u.id, u.name, r.name AS role_name
     FROM users u
     JOIN roles r ON u.role_id = r.id
     ORDER BY u.name"
);

// Pré-remplissage
$title       = $task['title'];
$description = $task['description']  ?? '';
$project_id  = $task['project_id'];
$assigned_to = $task['assigned_to'];
$status      = $task['status'];
$priority    = $task['priority']     ?? 'Moyenne';
$due_date    = $task['due_date']     ?? '';
$error       = '';

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
        $update = mysqli_prepare($conn,
            "UPDATE tasks
             SET title=?, description=?, project_id=?, assigned_to=?,
                 status=?, priority=?, due_date=?
             WHERE id=?"
        );
        mysqli_stmt_bind_param($update, "ssiisssi",
            $title, $description, $project_id,
            $assigned_to, $status, $priority, $due_date, $id
        );
        if (mysqli_stmt_execute($update)) {
            mysqli_stmt_close($update);
            header("Location: index.php?success=Tâche+modifiée+avec+succès");
            exit();
        } else {
            $error = "Erreur lors de la modification.";
            mysqli_stmt_close($update);
        }
    }
}
?>

<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-pencil-fill me-2" style="color:var(--accent);font-size:13px;"></i>
    Modifier — <?php echo htmlspecialchars($task['title']); ?>
  </span>
  <a href="index.php" class="btn-bp-ghost">
    <i class="bi bi-arrow-left"></i> Retour
  </a>
</div>

<div class="bp-content">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-7">
      <div class="bp-card">
        <div class="bp-card-title">Modifier la tâche</div>

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
                   value="<?php echo htmlspecialchars($title); ?>"
                   required autofocus>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">Description</label>
            <textarea name="description" class="bp-textarea"><?php echo htmlspecialchars($description); ?></textarea>
          </div>

          <div class="row g-3">
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">
                  Projet <span style="color:#ef4444;">*</span>
                </label>
                <select name="project_id" class="bp-select" required>
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
              <i class="bi bi-check-lg"></i> Enregistrer les modifications
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "../layout/footer.php"; ?>