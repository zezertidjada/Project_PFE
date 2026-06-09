<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$role_id = (int)($_SESSION['role_id'] ?? 4);
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";
require_once "../../config/csrf.php";

$page_title = "Utilisateurs";


// Accès : Admin + Chef de projet
if (!in_array($role_id, [1, 2])) {
    header("Location: /bproject/dashboard.php");
    exit();
}

$users = mysqli_query($conn,
    "SELECT u.*, r.name AS role_name
     FROM users u
     LEFT JOIN roles r ON u.role_id = r.id
     ORDER BY u.role_id ASC, u.name ASC"
);

// Comptage par rôle
$role_counts = [];
$tmp = mysqli_query($conn, "SELECT role_id, COUNT(*) AS c FROM users GROUP BY role_id");
while ($r = mysqli_fetch_assoc($tmp)) $role_counts[$r['role_id']] = $r['c'];

$av_colors   = [1=>'av-indigo', 2=>'av-amber', 3=>'av-green', 4=>'av-gray'];
$badge_cls   = [1=>'badge-admin', 2=>'badge-chef', 3=>'badge-dev', 4=>'badge-stagiaire'];
$role_labels = [1=>'Admin', 2=>'Chef de projet', 3=>'Développeur', 4=>'Stagiaire'];
?>

<!-- TOPBAR -->
<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-people-fill me-2" style="color:var(--accent);font-size:13px;"></i>
    Utilisateurs
  </span>
  <?php if ($role_id == 1): ?>
  <a href="create.php" class="btn-bp-primary">
    <i class="bi bi-person-plus"></i> Ajouter un utilisateur
  </a>
  <?php endif; ?>
</div>

<div class="bp-content">

  <?php if (isset($_GET['success'])): ?>
    <div class="bp-alert bp-alert-success">
      <i class="bi bi-check-circle-fill"></i>
      <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
  <?php endif; ?>

  <!-- Stats rôles -->
  <div class="row g-3 mb-4">
    <?php foreach ([1=>'Admins', 2=>'Chefs de projet', 3=>'Développeurs', 4=>'Stagiaires'] as $rid => $label): ?>
    <div class="col-6 col-xl-3">
      <div class="bp-stat-card">
        <div class="bp-stat-label"><?php echo $label; ?></div>
        <div class="bp-stat-val" style="font-size:28px;">
          <?php echo $role_counts[$rid] ?? 0; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Tableau utilisateurs -->
  <div class="bp-table-wrap mb-4">
    <table class="bp-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Utilisateur</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Créé le</th>
          <?php if ($role_id == 1): ?>
          <th>Actions</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($users) === 0): ?>
          <tr>
            <td colspan="6" style="text-align:center;padding:32px;color:var(--text-3);">
              Aucun utilisateur trouvé.
            </td>
          </tr>
        <?php else: ?>
        <?php $i = 1; while ($u = mysqli_fetch_assoc($users)):
          $av  = $av_colors[$u['role_id']]  ?? 'av-gray';
          $bdg = $badge_cls[$u['role_id']]  ?? 'badge-stagiaire';
          $words    = array_values(array_filter(explode(' ', $u['name'])));
          $initials = strtoupper(($words[0][0] ?? '') . ($words[1][0] ?? ''));
        ?>
        <tr>
          <td style="color:var(--text-3);font-size:12px;"><?php echo $i++; ?></td>
          <td>
            <div class="user-cell">
              <div class="bp-avatar bp-av-md <?php echo $av; ?>">
                <?php echo $initials; ?>
              </div>
              <div>
                <div class="cell-name">
                  <?php echo htmlspecialchars($u['name']); ?>
                  <?php if ($u['id'] == $_SESSION['user_id']): ?>
                    <span style="font-size:10px;color:var(--accent);margin-left:5px;">Vous</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </td>
          <td class="text-muted-bp"><?php echo htmlspecialchars($u['email']); ?></td>
          <td>
            <span class="bp-badge <?php echo $bdg; ?>">
              <?php echo htmlspecialchars($u['role_name']); ?>
            </span>
          </td>
          <td class="text-muted-bp">
            <?php echo date('d/m/Y', strtotime($u['created_at'])); ?>
          </td>
          <?php if ($role_id == 1): ?>
          <td>
            <div class="d-flex gap-2">
              <a href="edit.php?id=<?php echo $u['id']; ?>"
                 class="btn-bp-icon" title="Modifier">
                <i class="bi bi-pencil"></i>
              </a>
              <?php if ($u['id'] != $_SESSION['user_id']): ?>
              <form method="POST" action="delete.php" style="margin:0;">
                <input type="hidden" name="id"
                       value="<?php echo $u['id']; ?>">
                <input type="hidden" name="csrf_token"
                       value="<?php echo generateCSRFToken(); ?>">
                <button type="submit"
                        class="btn-bp-danger"
                        style="padding:5px 9px;"
                        onclick="return confirm('Supprimer <?php echo htmlspecialchars(addslashes($u['name'])); ?> ?')"
                        title="Supprimer">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
              <?php endif; ?>
            </div>
          </td>
          <?php endif; ?>
        </tr>
        <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Matrice RBAC -->
  <div class="bp-card">
    <div class="bp-card-title">Matrice des permissions (RBAC)</div>
    <div class="bp-table-wrap">
      <table class="bp-table">
        <thead>
          <tr>
            <th>Module</th>
            <th>Admin</th>
            <th>Chef de projet</th>
            <th>Développeur</th>
            <th>Stagiaire</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $matrix = [
            'Clients'      => ['CRUD',    'Lecture', 'Lecture', 'Lecture'],
            'Projets'      => ['CRUD',    'CRUD',    'Lecture', 'Lecture'],
            'Tâches'       => ['CRUD',    'CRUD',    'CRU',     'Lecture'],
            'Utilisateurs' => ['CRUD',    'Lecture', '—',       '—'],
          ];
          foreach ($matrix as $module => $perms): ?>
          <tr>
            <td style="font-weight:600;"><?php echo $module; ?></td>
            <?php foreach ($perms as $perm): ?>
            <td>
              <?php if ($perm === 'CRUD'): ?>
                <span style="color:#16a34a;font-weight:700;font-size:12px;">CRUD</span>
              <?php elseif ($perm === 'CRU'): ?>
                <span style="color:#f59e0b;font-weight:700;font-size:12px;">CRU</span>
              <?php elseif ($perm === 'Lecture'): ?>
                <span style="color:#6366f1;font-size:12px;">Lecture</span>
              <?php else: ?>
                <span style="color:#ef4444;font-weight:700;">✕</span>
              <?php endif; ?>
            </td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php include "../layout/footer.php"; ?>