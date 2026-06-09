<?php
session_start();
require_once "config/database.php";

// Déjà connecté → dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ' . bp_url('dashboard.php'));
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']    ?? ''));
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' LIMIT 1");

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // Support mot de passe hashé ET en clair (transition)
            $valid = password_verify($password, $user['password'])
                  || $password === $user['password'];

            if ($valid) {
                // Si encore en clair → on le hashe maintenant automatiquement
                if ($password === $user['password']) {
                    $hashed = mysqli_real_escape_string($conn, password_hash($password, PASSWORD_DEFAULT));
                    mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE id=" . (int)$user['id']);
                }

                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role_id']   = $user['role_id'];

                header('Location: ' . bp_url('dashboard.php'));
                exit();
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Aucun compte trouvé avec cet email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — B-Project Manager</title>
  <script>
    (function(){
      if (localStorage.getItem('bp_theme') === 'dark')
        document.documentElement.setAttribute('data-theme','dark');
    })();
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo bp_asset('css/bproject.css?v=5'); ?>" rel="stylesheet">
</head>
<body>

<div class="bp-theme-fab">
  <i class="bi bi-sun-fill"></i>
  <button class="bp-theme-toggle" data-theme-toggle title="Changer le thème" type="button"></button>
  <i class="bi bi-moon-fill"></i>
  <span data-theme-label>Clair</span>
</div>

<div class="bp-login-wrap">
  <div class="bp-login-card">

    <!-- En-tête logo -->
    <div class="bp-login-header">
      <div class="bp-login-logo-icon">BP</div>
      <div>
        <div style="font-size:15px; font-weight:700; color:var(--text-1); line-height:1.2;">
          B-Project Manager
        </div>
        <div style="font-size:11px; color:var(--text-3);">B-NETWORK</div>
      </div>
    </div>

    <div class="bp-login-title">Connexion</div>
    <div class="bp-login-sub">Bienvenue. Connectez-vous pour accéder à votre espace.</div>

    <!-- Message d'erreur -->
    <?php if ($error): ?>
      <div class="bp-alert bp-alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <!-- Formulaire -->
    <form method="POST" novalidate>

      <div class="bp-form-group">
        <label class="bp-label" for="email">
          <i class="bi bi-envelope me-1"></i> Adresse email
        </label>
        <input
          type="email"
          id="email"
          name="email"
          class="bp-input"
          placeholder="vous@exemple.com"
          value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
          required
          autofocus
        >
      </div>

      <div class="bp-form-group">
        <label class="bp-label" for="password">
          <i class="bi bi-lock me-1"></i> Mot de passe
        </label>
        <div style="position:relative;">
          <input
            type="password"
            id="password"
            name="password"
            class="bp-input"
            placeholder="••••••••"
            style="padding-right:42px;"
            required
          >
          <button
            type="button"
            onclick="togglePassword()"
            style="position:absolute; right:11px; top:50%; transform:translateY(-50%);
                   background:none; border:none; color:var(--text-3); cursor:pointer; font-size:15px; padding:0;"
          >
            <i class="bi bi-eye" id="pwd-icon"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-bp-primary w-100" style="justify-content:center; padding:11px; margin-top:4px;">
        <i class="bi bi-box-arrow-in-right"></i>
        Se connecter
      </button>

    </form>

    <!-- Pied de page -->
    <div style="margin-top:28px; padding-top:16px; border-top:1px solid var(--border-light);
                text-align:center; font-size:11px; color:var(--text-3);">
      B-Project Manager &copy; <?php echo date('Y'); ?> &mdash; B-NETWORK
    </div>

  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('pwd-icon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'bi bi-eye-slash';
  } else {
    input.type = 'password';
    icon.className = 'bi bi-eye';
  }
}
</script>
<script src="<?php echo bp_asset('js/theme.js'); ?>"></script>

</body>
</html>