<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";
require_once "../../config/csrf.php";

$page_title = "Ajouter un utilisateur";
$role_id    = $_SESSION['role_id'];

// Admin seulement
if ($role_id != 1) {
    header("Location: /bproject/dashboard.php");
    exit();
}

$name = $email = $error = "";
$user_role_id = 4; // Stagiaire par défaut

$roles = mysqli_query($conn, "SELECT * FROM roles ORDER BY id");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verifyCSRFToken($_POST['csrf_token'] ?? '');

    $name         = trim($_POST['name']     ?? '');
    $email        = trim($_POST['email']    ?? '');
    $password     = $_POST['password']      ?? '';
    $user_role_id = intval($_POST['role_id'] ?? 4);

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Vérifier email unique
        $check_email = mysqli_real_escape_string($conn, $email);
        $exists = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT id FROM users WHERE email = '$check_email'")
        );
        if ($exists) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Hasher le mot de passe
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $name_esc  = mysqli_real_escape_string($conn, $name);
            $hashed_esc = mysqli_real_escape_string($conn, $hashed);

            $sql = "INSERT INTO users (name, email, password, role_id)
                    VALUES ('$name_esc', '$check_email', '$hashed_esc', $user_role_id)";

            if (mysqli_query($conn, $sql)) {
                header("Location: index.php?success=Utilisateur+créé+avec+succès");
                exit();
            } else {
                $error = "Erreur lors de la création : " . mysqli_error($conn);
            }
        }
    }
}
?>

<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-person-plus-fill me-2" style="color:var(--accent);font-size:13px;"></i>
    Ajouter un utilisateur
  </span>
  <a href="index.php" class="btn-bp-ghost">
    <i class="bi bi-arrow-left"></i> Retour
  </a>
</div>

<div class="bp-content">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-6">
      <div class="bp-card">
        <div class="bp-card-title">Informations du compte</div>

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
              Nom complet <span style="color:#ef4444;">*</span>
            </label>
            <input type="text" name="name" class="bp-input"
                   placeholder="Ex : Moussa Ibrahim"
                   value="<?php echo htmlspecialchars($name); ?>"
                   required autofocus>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">
              Email <span style="color:#ef4444;">*</span>
            </label>
            <input type="email" name="email" class="bp-input"
                   placeholder="utilisateur@bnetwork.td"
                   value="<?php echo htmlspecialchars($email); ?>"
                   required>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">
              Mot de passe <span style="color:#ef4444;">*</span>
            </label>
            <input type="password" name="password" class="bp-input"
                   placeholder="Minimum 6 caractères" required>
            <div style="font-size:11px;color:var(--text-3);margin-top:4px;">
              Le mot de passe sera sécurisé automatiquement.
            </div>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">
              Rôle <span style="color:#ef4444;">*</span>
            </label>
            <select name="role_id" class="bp-select">
              <?php while ($r = mysqli_fetch_assoc($roles)): ?>
                <option value="<?php echo $r['id']; ?>"
                  <?php echo $user_role_id == $r['id'] ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($r['name']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="divider"></div>
          <div class="d-flex gap-2 justify-content-end">
            <a href="index.php" class="btn-bp-ghost">Annuler</a>
            <button type="submit" class="btn-bp-primary">
              <i class="bi bi-check-lg"></i> Créer le compte
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "../layout/footer.php"; ?>