<?php
// ============ TOUTE LA LOGIQUE D'ABORD ============
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once "../../config/database.php";
require_once "../../config/csrf.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: /bproject/login.php"); exit();
}
if ($_SESSION['role_id'] != 1) {
    header("Location: /bproject/dashboard.php"); exit();
}
if (empty($_GET['id'])) {
    header("Location: index.php"); exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
if (mysqli_num_rows($result) === 0) {
    header("Location: index.php"); exit();
}
$user  = mysqli_fetch_assoc($result);
$roles = mysqli_query($conn, "SELECT * FROM roles ORDER BY id");
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verifyCSRFToken($_POST['csrf_token'] ?? '');

    $name         = trim($_POST['name']      ?? '');
    $email        = trim($_POST['email']     ?? '');
    $new_password = $_POST['new_password']   ?? '';
    $user_role_id = intval($_POST['role_id'] ?? $user['role_id']);

    if (empty($name) || empty($email)) {
        $error = "Le nom et l'email sont obligatoires.";
    } elseif (!empty($new_password) && strlen($new_password) < 6) {
        $error = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
    } else {
        $email_esc = mysqli_real_escape_string($conn, $email);
        $exists    = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT id FROM users WHERE email='$email_esc' AND id != $id")
        );
        if ($exists) {
            $error = "Cet email est déjà utilisé par un autre compte.";
        } else {
            $name_esc = mysqli_real_escape_string($conn, $name);
            if (!empty($new_password)) {
                $hashed     = mysqli_real_escape_string($conn, password_hash($new_password, PASSWORD_DEFAULT));
                $sql        = "UPDATE users SET name='$name_esc', email='$email_esc', password='$hashed', role_id=$user_role_id WHERE id=$id";
            } else {
                $sql        = "UPDATE users SET name='$name_esc', email='$email_esc', role_id=$user_role_id WHERE id=$id";
            }
            if (mysqli_query($conn, $sql)) {
                header("Location: index.php?success=Utilisateur+modifié+avec+succès"); exit();
            } else {
                $error = "Erreur : " . mysqli_error($conn);
            }
        }
    }
}

// ============ HTML ENSUITE ============
$page_title = "Modifier — " . $user['name'];
include "../layout/header.php";
include "../layout/sidebar.php";
?>

<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-pencil-fill me-2" style="color:var(--accent);font-size:13px;"></i>
    Modifier — <?php echo htmlspecialchars($user['name']); ?>
  </span>
  <a href="index.php" class="btn-bp-ghost">
    <i class="bi bi-arrow-left"></i> Retour
  </a>
</div>

<div class="bp-content">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-6">
      <div class="bp-card">
        <div class="bp-card-title">Modifier le compte</div>

        <?php if ($error): ?>
          <div class="bp-alert bp-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

          <div class="bp-form-group">
            <label class="bp-label">Nom complet <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" class="bp-input"
                   value="<?php echo htmlspecialchars($_POST['name'] ?? $user['name']); ?>"
                   required autofocus>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">Email <span style="color:#ef4444;">*</span></label>
            <input type="email" name="email" class="bp-input"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? $user['email']); ?>"
                   required>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">Nouveau mot de passe</label>
            <input type="password" name="new_password" class="bp-input"
                   placeholder="Laisser vide pour ne pas changer">
            <div style="font-size:11px;color:var(--text-3);margin-top:4px;">
              Minimum 6 caractères si vous souhaitez le changer.
            </div>
          </div>

          <div class="bp-form-group">
            <label class="bp-label">Rôle</label>
            <select name="role_id" class="bp-select">
              <?php while ($r = mysqli_fetch_assoc($roles)): ?>
                <option value="<?php echo $r['id']; ?>"
                  <?php echo (($_POST['role_id'] ?? $user['role_id']) == $r['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($r['name']); ?>
                </option>
              <?php endwhile; ?>
            </select>
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