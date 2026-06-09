<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";

$page_title = "Nouveau projet";
$role_id    = $_SESSION['role_id'];

if (!in_array($role_id, [1, 2])) {
    header("Location: /bproject/dashboard.php");
    exit();
}

$title = $description = $start_date = $end_date = $error = "";
$client_id = "";
$status    = "En cours";
$progress  = 0;

$clients = mysqli_query($conn, "SELECT id, name, company FROM clients ORDER BY name");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $client_id   = intval($_POST['client_id'] ?? 0);
    $start_date  = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date    = !empty($_POST['end_date'])   ? $_POST['end_date']   : null;
    $status      = $_POST['status']   ?? 'En cours';
    $progress    = max(0, min(100, intval($_POST['progress'] ?? 0)));

    if (empty($title) || empty($client_id)) {
        $error = "Le titre et le client sont obligatoires.";
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO projects
             (title, description, client_id, start_date, end_date, status, progress, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
        );
        mysqli_stmt_bind_param($stmt, "ssisssi",
            $title, $description, $client_id,
            $start_date, $end_date, $status, $progress
        );
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php?success=Projet+créé+avec+succès");
            exit();
        } else {
            $error = "Erreur lors de la création du projet.";
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-folder-plus me-2" style="color:var(--accent);font-size:13px;"></i>
    Nouveau projet
  </span>
  <a href="index.php" class="btn-bp-ghost">
    <i class="bi bi-arrow-left"></i> Retour
  </a>
</div>

<div class="bp-content">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-7">
      <div class="bp-card">
        <div class="bp-card-title">Informations du projet</div>

        <?php if ($error): ?>
          <div class="bp-alert bp-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form method="POST">

          <div class="bp-form-group">
            <label class="bp-label">Titre <span style="color:#ef4444;">*</span></label>
            <input type="text" name="title" class="bp-input"
                   placeholder="Ex : Plateforme e-commerce"
                   value="<?php echo htmlspecialchars($title); ?>"
                   required autofocus>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">Description</label>
            <textarea name="description" class="bp-textarea"
                      placeholder="Décrivez le projet..."><?php echo htmlspecialchars($description); ?></textarea>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">Client <span style="color:#ef4444;">*</span></label>
            <select name="client_id" class="bp-select" required>
              <option value="">-- Choisir un client --</option>
              <?php while ($cl = mysqli_fetch_assoc($clients)): ?>
                <option value="<?php echo $cl['id']; ?>"
                  <?php echo ($client_id == $cl['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($cl['name'] . ($cl['company'] ? ' — '.$cl['company'] : '')); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="row g-3">
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">Date de début</label>
                <input type="date" name="start_date" class="bp-input"
                       value="<?php echo $start_date; ?>">
              </div>
            </div>
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">Date de fin</label>
                <input type="date" name="end_date" class="bp-input"
                       value="<?php echo $end_date; ?>">
              </div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">Statut</label>
                <select name="status" class="bp-select">
                  <?php foreach (['En attente','En cours','Terminé','Pausé'] as $s): ?>
                    <option value="<?php echo $s; ?>"
                      <?php echo ($status === $s) ? 'selected' : ''; ?>>
                      <?php echo $s; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">
                  Progression :
                  <span id="prog-val" style="color:var(--accent);font-weight:700;">
                    <?php echo $progress; ?>%
                  </span>
                </label>
                <input type="range" name="progress"
                       min="0" max="100" step="5"
                       value="<?php echo $progress; ?>"
                       class="form-range"
                       oninput="document.getElementById('prog-val').textContent=this.value+'%'">
              </div>
            </div>
          </div>

          <div class="divider"></div>
          <div class="d-flex gap-2 justify-content-end">
            <a href="index.php" class="btn-bp-ghost">Annuler</a>
            <button type="submit" class="btn-bp-primary">
              <i class="bi bi-check-lg"></i> Créer le projet
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php include "../layout/footer.php"; ?>
