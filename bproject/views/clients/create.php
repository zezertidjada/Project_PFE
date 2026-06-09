<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : Admin seulement
$page_title = "Ajouter un client";
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";

if ($_SESSION['role_id'] != 1) {
    header("Location: /bproject/dashboard.php");
    exit();
}

$name = $email = $phone = $company = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $company = trim($_POST['company'] ?? '');

    if (empty($name) || empty($email)) {
        $error = "Le nom et l'email sont obligatoires.";
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO clients (name, email, phone, company, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $phone, $company);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php?success=Client+ajouté+avec+succès");
            exit();
        } else {
            $error = "Erreur lors de l'ajout du client.";
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!-- TOPBAR -->
<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-person-plus-fill me-2" style="color:var(--accent);font-size:13px;"></i>
    Ajouter un client
  </span>
  <a href="index.php" class="btn-bp-ghost">
    <i class="bi bi-arrow-left"></i> Retour
  </a>
</div>

<div class="bp-content">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-6">
      <div class="bp-card">
        <div class="bp-card-title">Informations du client</div>

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
                   placeholder="Ex : Obi Tchad"
                   value="<?php echo htmlspecialchars($name); ?>"
                   required autofocus>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">Entreprise</label>
            <input type="text" name="company" class="bp-input"
                   placeholder="Ex : Almanna Company"
                   value="<?php echo htmlspecialchars($company); ?>">
          </div>

          <div class="row g-3">
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">
                  Email <span style="color:#ef4444;">*</span>
                </label>
                <input type="email" name="email" class="bp-input"
                       placeholder="email@exemple.com"
                       value="<?php echo htmlspecialchars($email); ?>"
                       required>
              </div>
            </div>
            <div class="col-6">
              <div class="bp-form-group">
                <label class="bp-label">Téléphone</label>
                <input type="text" name="phone" class="bp-input"
                       placeholder="Ex : 66000000"
                       value="<?php echo htmlspecialchars($phone); ?>">
              </div>
            </div>
          </div>

          <div class="divider"></div>
          <div class="d-flex gap-2 justify-content-end">
            <a href="index.php" class="btn-bp-ghost">Annuler</a>
            <button type="submit" class="btn-bp-primary">
              <i class="bi bi-check-lg"></i> Enregistrer
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php include "../layout/footer.php"; ?>
