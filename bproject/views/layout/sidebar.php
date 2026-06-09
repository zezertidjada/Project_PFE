<?php
if (!defined('BP_BASE')) {
    require_once dirname(__DIR__, 2) . '/config/app.php';
}

$current_dir  = basename(dirname($_SERVER['PHP_SELF']));
$current_file = basename($_SERVER['PHP_SELF'], '.php');

function bp_active($dir) {
    global $current_dir;
    return $current_dir === $dir ? 'active' : '';
}
function bp_active_file($file) {
    global $current_file;
    return $current_file === $file ? 'active' : '';
}

$user_name = $_SESSION['user_name'] ?? 'Utilisateur';
$role_id   = (int)($_SESSION['role_id'] ?? 4);

$words    = array_values(array_filter(explode(' ', $user_name)));
$initials = strtoupper(($words[0][0] ?? '') . ($words[1][0] ?? ''));

$role_labels = [1=>'Admin', 2=>'Chef de projet', 3=>'Développeur', 4=>'Stagiaire'];
$av_colors   = [1=>'av-indigo', 2=>'av-amber', 3=>'av-green', 4=>'av-gray'];

$role_label = $role_labels[$role_id] ?? 'Utilisateur';
$av_class   = $av_colors[$role_id]   ?? 'av-gray';
?>

<aside class="bp-sidebar">

  <div class="bp-sidebar-logo">
    <div class="bp-logo-icon">BP</div>
    <div>
      <div class="bp-logo-name">B-Project Manager</div>
      <div class="bp-logo-company">B-NETWORK</div>
    </div>
  </div>

  <!-- Toggle Dark / Light (visible en haut de la sidebar) -->
  <div class="bp-theme-row">
    <i class="bi bi-sun-fill"  style="font-size:13px;color:var(--text-3);"></i>
    <button class="bp-theme-toggle" data-theme-toggle title="Changer le thème" type="button" aria-label="Changer le thème"></button>
    <i class="bi bi-moon-fill" style="font-size:13px;color:var(--text-3);"></i>
    <span style="font-size:11px;color:var(--text-3);margin-left:2px;" data-theme-label>Clair</span>
  </div>

  <div class="bp-nav-section">
    <span class="bp-nav-label">Menu</span>

    <a href="<?php echo bp_url('dashboard.php'); ?>"
       class="bp-nav-item <?php echo bp_active_file('dashboard'); ?>">
      <svg class="bp-nav-icon" viewBox="0 0 16 16" fill="none">
        <rect x="1" y="1" width="6" height="6" rx="1.5" fill="currentColor"/>
        <rect x="9" y="1" width="6" height="6" rx="1.5" fill="currentColor" opacity=".4"/>
        <rect x="1" y="9" width="6" height="6" rx="1.5" fill="currentColor" opacity=".4"/>
        <rect x="9" y="9" width="6" height="6" rx="1.5" fill="currentColor" opacity=".4"/>
      </svg>
      Dashboard
    </a>

    <a href="<?php echo bp_url('views/clients/index.php'); ?>"
       class="bp-nav-item <?php echo bp_active('clients'); ?>">
      <svg class="bp-nav-icon" viewBox="0 0 16 16" fill="none">
        <rect x="2" y="2" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.4"/>
        <rect x="9" y="2" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.4"/>
        <rect x="2" y="9" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.4"/>
        <rect x="9" y="9" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.4"/>
      </svg>
      Clients
    </a>

    <a href="<?php echo bp_url('views/projects/index.php'); ?>"
       class="bp-nav-item <?php echo bp_active('projects'); ?>">
      <svg class="bp-nav-icon" viewBox="0 0 16 16" fill="none">
        <path d="M2 5a1 1 0 011-1h3l1.5 2H13a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1V5z"
              stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
      </svg>
      Projets
    </a>

    <a href="<?php echo bp_url('views/tasks/index.php'); ?>"
       class="bp-nav-item <?php echo bp_active('tasks'); ?>">
      <svg class="bp-nav-icon" viewBox="0 0 16 16" fill="none">
        <path d="M2.5 8l3.5 3.5L13.5 4" stroke="currentColor" stroke-width="1.5"
              stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      Tâches
    </a>

    <?php if (in_array($role_id, [1, 2])): ?>
    <a href="<?php echo bp_url('views/users/index.php'); ?>"
       class="bp-nav-item <?php echo bp_active('users'); ?>">
      <svg class="bp-nav-icon" viewBox="0 0 16 16" fill="none">
        <circle cx="5.5" cy="5" r="2.5" stroke="currentColor" stroke-width="1.4"/>
        <circle cx="10.5" cy="5" r="2.5" stroke="currentColor" stroke-width="1.4"/>
        <path d="M1 13c0-2.5 2-4 4.5-4M15 13c0-2.5-2-4-4.5-4"
              stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
      </svg>
      Utilisateurs
    </a>
    <?php endif; ?>
  </div>

  <!-- Utilisateur connecté -->
  <div class="bp-sidebar-user">
    <div class="bp-avatar bp-av-md <?php echo $av_class; ?>">
      <?php echo $initials; ?>
    </div>
    <div style="flex:1; min-width:0;">
      <div class="name"><?php echo htmlspecialchars($user_name); ?></div>
      <div class="role"><?php echo $role_label; ?></div>
    </div>
    <a href="<?php echo bp_url('logout.php'); ?>" title="Déconnexion"
       style="color:var(--text-3); font-size:16px; flex-shrink:0;">
      <i class="bi bi-box-arrow-right"></i>
    </a>
  </div>

</aside>

<div class="bp-main">
