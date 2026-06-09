<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$role_id = (int)($_SESSION['role_id'] ?? 4);
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";
require_once "../../config/csrf.php";

$page_title = "Clients";

$result = mysqli_query($conn,
    "SELECT c.*, COUNT(p.id) AS project_count
     FROM clients c
     LEFT JOIN projects p ON p.client_id = c.id
     GROUP BY c.id
     ORDER BY c.created_at DESC"
);
?>

<!-- TOPBAR -->
<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-building me-2" style="color:var(--accent);font-size:13px;"></i>
    Clients
  </span>
  <?php if ($role_id == 1): ?>
  <a href="create.php" class="btn-bp-primary">
    <i class="bi bi-plus-lg"></i> Ajouter un client
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

  <?php if (isset($_GET['error'])): ?>
    <div class="bp-alert bp-alert-error">
      <i class="bi bi-exclamation-circle-fill"></i>
      <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
  <?php endif; ?>

  <div class="bp-table-wrap">
    <table class="bp-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Client</th>
          <th>Entreprise</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Projets</th>
          <th>Créé le</th>
          <?php if ($role_id === 1): ?>
          <th>Actions</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>

        <?php if (mysqli_num_rows($result) === 0): ?>
          <tr>
            <td colspan="8" style="text-align:center;padding:32px;color:var(--text-3);">
              Aucun client enregistré.
              <?php if ($role_id === 1): ?>
                <a href="create.php" style="color:var(--accent);">Ajouter le premier</a>
              <?php endif; ?>
            </td>
          </tr>

        <?php else: ?>
          <?php $i = 1; while ($client = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td style="color:var(--text-3);font-size:12px;"><?php echo $i++; ?></td>

            <td>
              <div class="user-cell">
                <div class="bp-avatar bp-av-md av-indigo"
                     style="border-radius:9px;flex-shrink:0;">
                  <?php echo strtoupper(substr($client['name'], 0, 2)); ?>
                </div>
                <div class="cell-name">
                  <?php echo htmlspecialchars($client['name']); ?>
                </div>
              </div>
            </td>

            <td><?php echo htmlspecialchars($client['company'] ?? '—'); ?></td>

            <td class="text-muted-bp">
              <?php echo htmlspecialchars($client['email'] ?? '—'); ?>
            </td>

            <td class="text-muted-bp">
              <?php echo htmlspecialchars($client['phone'] ?? '—'); ?>
            </td>

            <td>
              <span style="font-size:13px;font-weight:700;color:var(--accent);">
                <?php echo $client['project_count']; ?>
              </span>
            </td>

            <td class="text-muted-bp">
              <?php echo date('d/m/Y', strtotime($client['created_at'])); ?>
            </td>

            <?php if ($role_id === 1): ?>
            <td>
              <div class="d-flex gap-2">
                <a href="edit.php?id=<?php echo $client['id']; ?>"
                   class="btn-bp-icon" title="Modifier">
                  <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="delete.php" style="margin:0;">
                  <input type="hidden" name="id" value="<?php echo $client['id']; ?>">
                  <input type="hidden" name="csrf_token"
                         value="<?php echo generateCSRFToken(); ?>">
                  <button type="submit"
                          class="btn-bp-danger"
                          style="padding:5px 9px;"
                          onclick="return confirm('Supprimer <?php echo htmlspecialchars(addslashes($client['name'])); ?> ?')"
                          title="Supprimer">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
            <?php endif; ?>

          </tr>
          <?php endwhile; ?>
        <?php endif; ?>

      </tbody>
    </table>
  </div>

</div>

<?php include "../layout/footer.php"; ?>
