<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Modifier le client";
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";

// Sécurité : Admin seulement
if ($_SESSION['role_id'] != 1) {
    header('Location: ' . bp_url('dashboard.php'));
    exit();
}

// ID obligatoire
if (empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Récupérer le client
$stmt = mysqli_prepare($conn, "SELECT * FROM clients WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit();
}

$client = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Pré-remplir avec données existantes
$name    = $client['name'];
$email   = $client['email'];
$phone   = $client['phone']   ?? '';
$company = $client['company'] ?? '';
$error   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $company = trim($_POST['company'] ?? '');

    if (empty($name) || empty($email)) {
        $error = "Le nom et l'email sont obligatoires.";
    } else {
        $update = mysqli_prepare($conn,
            "UPDATE clients SET name=?, email=?, phone=?, company=? WHERE id=?"
        );
        mysqli_stmt_bind_param($update, "ssssi", $name, $email, $phone, $company, $id);

        if (mysqli_stmt_execute($update)) {
            mysqli_stmt_close($update);
            header("Location: index.php?success=Client+modifié+avec+succès");
            exit();
        } else {
            $error = "Erreur lors de la modification.";
            mysqli_stmt_close($update);
        }
    }
}
?>

<!-- TOPBAR -->
<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-pencil-fill me-2" style="color:var(--accent);font-size:13px;"></i>
    Modifier — <?php echo htmlspecialchars($client['name']); ?>
  </span>
  <a href="index.php" class="btn-bp-ghost">
    <i class="bi bi-arrow-left"></i> Retour
  </a>
</div>

<div class="bp-content">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-6">
      <div class="bp-card">
        <div class="bp-card-title">Modifier les informations</div>

        <?php if ($error): ?>
          <div class="bp-alert bp-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form method="POST">

          <div class="bp-form-group">
            <label class="bp-label">
              Nom complet <span style="color:#ef4444;">*</span>
            </label>
            <input type="text" name="name" class="bp-input"
                   value="<?php echo htmlspecialchars($name); ?>"
                   required autofocus>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">Entreprise</label>
            <input type="text" name="company" class="bp-input"
                   value="<?php echo htmlspecialchars($company); ?>">
          </div>

          <div class="row g-3">
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">
                  Email <span style="color:#ef4444;">*</span>
                </label>
                <input type="email" name="email" class="bp-input"
                       value="<?php echo htmlspecialchars($email); ?>"
                       required>
              </div>
            </div>
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">Téléphone</label>
                <input type="text" name="phone" class="bp-input"
                       value="<?php echo htmlspecialchars($phone); ?>">
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
