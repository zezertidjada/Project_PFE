<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$role_id = (int)($_SESSION['role_id'] ?? 4);
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";
require_once "../../config/csrf.php";

$page_title = "Projets";


$result = mysqli_query($conn,
    "SELECT p.*, c.name AS client_name,
            COUNT(t.id) AS task_count
     FROM projects p
     LEFT JOIN clients c ON p.client_id = c.id
     LEFT JOIN tasks t   ON t.project_id = p.id
     GROUP BY p.id
     ORDER BY p.created_at DESC"
);
?>

<!-- TOPBAR -->
<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-folder-fill me-2" style="color:var(--accent);font-size:13px;"></i>
    Projets
  </span>
  <?php if (in_array($role_id, [1, 2])): ?>
  <a href="create.php" class="btn-bp-primary">
    <i class="bi bi-plus-lg"></i> Nouveau projet
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

  <?php if (mysqli_num_rows($result) === 0): ?>
    <div class="bp-card" style="text-align:center;padding:48px 24px;">
      <div style="font-size:36px;margin-bottom:12px;">📂</div>
      <div style="font-size:14px;color:var(--text-2);margin-bottom:18px;">
        Aucun projet pour l'instant.
      </div>
      <?php if (in_array($role_id, [1, 2])): ?>
        <a href="create.php" class="btn-bp-primary">Créer le premier projet</a>
      <?php endif; ?>
    </div>

  <?php else: ?>
    <?php while ($proj = mysqli_fetch_assoc($result)):
      $progress  = $proj['progress'] ?? 0;
      $bar_color = match($proj['status']) {
        'Terminé'    => 'fill-green',
        'En attente' => 'fill-amber',
        default      => ''
      };
      $badge = match($proj['status']) {
        'En cours'   => 'badge-active',
        'Terminé'    => 'badge-done',
        'En attente' => 'badge-pending',
        default      => 'badge-paused'
      };
      $stripe_color = match($proj['status']) {
        'En cours'   => '#6366f1',
        'Terminé'    => '#16a34a',
        'En attente' => '#f59e0b',
        default      => '#94a3b8'
      };
    ?>
    <div class="bp-card mb-3">
      <div class="d-flex gap-3">

        <!-- Barre colorée statut -->
        <div style="width:4px;border-radius:4px;background:<?php echo $stripe_color; ?>;
                    flex-shrink:0;align-self:stretch;"></div>

        <div style="flex:1;">
          <!-- Titre + badge + actions -->
          <div class="d-flex align-items-start justify-content-between mb-1">
            <div>
              <span style="font-size:15px;font-weight:700;color:var(--text-1);">
                <?php echo htmlspecialchars($proj['title']); ?>
              </span>
              <span class="bp-badge <?php echo $badge; ?> ms-2">
                <?php echo htmlspecialchars($proj['status']); ?>
              </span>
            </div>

            <?php if (in_array($role_id, [1, 2])): ?>
            <div class="d-flex gap-2">
              <a href="edit.php?id=<?php echo $proj['id']; ?>"
                 class="btn-bp-icon" title="Modifier">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="delete.php" style="margin:0;">
                <input type="hidden" name="id"
                       value="<?php echo $proj['id']; ?>">
                <input type="hidden" name="csrf_token"
                       value="<?php echo generateCSRFToken(); ?>">
                <button type="submit"
                        class="btn-bp-danger"
                        style="padding:5px 9px;"
                        onclick="return confirm('Supprimer <?php echo htmlspecialchars(addslashes($proj['title'])); ?> et toutes ses tâches ?')"
                        title="Supprimer">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
            <?php endif; ?>
          </div>

          <!-- Infos -->
          <div style="font-size:12px;color:var(--text-2);margin-bottom:10px;">
            Client : <strong><?php echo htmlspecialchars($proj['client_name'] ?? '—'); ?></strong>
            &nbsp;·&nbsp;
            <i class="bi bi-check2-square"></i>
            <?php echo $proj['task_count']; ?> tâche(s)
            &nbsp;·&nbsp;
            <i class="bi bi-calendar3"></i>
            <?php echo $proj['start_date'] ? date('d/m/Y', strtotime($proj['start_date'])) : '—'; ?>
            →
            <?php echo $proj['end_date'] ? date('d/m/Y', strtotime($proj['end_date'])) : '—'; ?>
          </div>

          <!-- Description courte -->
          <?php if (!empty($proj['description'])): ?>
          <div style="font-size:12px;color:var(--text-3);margin-bottom:10px;">
            <?php echo htmlspecialchars(substr($proj['description'], 0, 120))
                     . (strlen($proj['description']) > 120 ? '…' : ''); ?>
          </div>
          <?php endif; ?>

          <!-- Barre progression -->
          <div class="bp-progress-wrap">
            <div class="bp-progress-label">
              <span>Progression</span>
              <span><?php echo $progress; ?>%</span>
            </div>
            <div class="bp-progress-bar">
              <div class="bp-progress-fill <?php echo $bar_color; ?>"
                   style="width:<?php echo $progress; ?>%;"></div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <?php endwhile; ?>
  <?php endif; ?>

</div>

<?php include "../layout/footer.php"; ?>
