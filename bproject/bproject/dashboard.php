<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /bproject/login.php");
    exit();
}

require_once "config/database.php";

$page_title = "Dashboard";
$role_id    = (int)$_SESSION['role_id']; // cast une fois, utilisé partout

// --- KPIs ---
$users_count     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM users"))['t'];
$clients_count   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM clients"))['t'];
$projects_count  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM projects"))['t'];
$tasks_count     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM tasks"))['t'];

$tasks_todo      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM tasks WHERE status = 'À faire'"))['t'];
$tasks_inprog    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM tasks WHERE status = 'En cours'"))['t'];
$tasks_done      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM tasks WHERE status = 'Terminé'"))['t'];
$projects_active = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM projects WHERE status = 'En cours'"))['t'];

$recent_projects = mysqli_query($conn,
    "SELECT p.*, c.name AS client_name
     FROM projects p
     LEFT JOIN clients c ON p.client_id = c.id
     ORDER BY p.created_at DESC LIMIT 5"
);

$recent_tasks = mysqli_query($conn,
    "SELECT t.*, u.name AS assignee_name, p.title AS project_title
     FROM tasks t
     LEFT JOIN users u    ON t.assigned_to = u.id
     LEFT JOIN projects p ON t.project_id  = p.id
     ORDER BY t.created_at DESC LIMIT 5"
);
?>
<?php include "views/layout/header.php"; ?>
<?php include "views/layout/sidebar.php"; ?>

<!-- TOPBAR -->
<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-grid-1x2-fill me-2" style="color:var(--accent);font-size:13px;"></i>
    Tableau de bord
  </span>
  <div class="bp-topbar-right">
    <span style="font-size:12px;color:var(--text-2);">
      <?php
      $jours = ['Sunday'=>'Dimanche','Monday'=>'Lundi','Tuesday'=>'Mardi',
                'Wednesday'=>'Mercredi','Thursday'=>'Jeudi','Friday'=>'Vendredi','Saturday'=>'Samedi'];
      $mois  = ['January'=>'Janvier','February'=>'Février','March'=>'Mars','April'=>'Avril',
                'May'=>'Mai','June'=>'Juin','July'=>'Juillet','August'=>'Août',
                'September'=>'Septembre','October'=>'Octobre','November'=>'Novembre','December'=>'Décembre'];
      echo $jours[date('l')] . ' ' . date('d') . ' ' . $mois[date('F')] . ' ' . date('Y');
      ?>
    </span>
    <?php if ($role_id <= 2): ?>
    <a href="/bproject/views/projects/create.php" class="btn-bp-primary">
      <i class="bi bi-plus-lg"></i> Nouveau projet
    </a>
    <?php endif; ?>
  </div>
</div>

<div class="bp-content">

  <!-- KPI CARDS -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="bp-stat-card">
        <div class="bp-stat-label">Utilisateurs <span class="bp-stat-icon">👥</span></div>
        <div class="bp-stat-val"><?php echo $users_count; ?></div>
        <div class="bp-stat-sub">4 rôles actifs</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="bp-stat-card">
        <div class="bp-stat-label">Clients <span class="bp-stat-icon">🏢</span></div>
        <div class="bp-stat-val" style="color:var(--accent);"><?php echo $clients_count; ?></div>
        <div class="bp-stat-sub">Entreprises partenaires</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="bp-stat-card">
        <div class="bp-stat-label">Projets <span class="bp-stat-icon">📂</span></div>
        <div class="bp-stat-val" style="color:#16a34a;"><?php echo $projects_count; ?></div>
        <div class="bp-stat-sub"><?php echo $projects_active; ?> en cours</div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="bp-stat-card">
        <div class="bp-stat-label">Tâches <span class="bp-stat-icon">✅</span></div>
        <div class="bp-stat-val" style="color:#f59e0b;"><?php echo $tasks_count; ?></div>
        <div class="bp-stat-sub"><?php echo $tasks_inprog; ?> en cours · <?php echo $tasks_todo; ?> à faire</div>
      </div>
    </div>
  </div>

  <div class="row g-3">

    <!-- Colonne gauche -->
    <div class="col-12 col-xl-8">

      <!-- Projets récents -->
      <div class="bp-card mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="bp-card-title mb-0">Projets récents</div>
          <a href="/bproject/views/projects/index.php" class="btn-bp-ghost"
             style="font-size:12px;padding:5px 12px;">
            Voir tout <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        <?php if (mysqli_num_rows($recent_projects) === 0): ?>
          <div style="text-align:center;padding:24px;color:var(--text-3);font-size:13px;">
            Aucun projet pour l'instant.
          </div>
        <?php else: ?>
          <?php while ($proj = mysqli_fetch_assoc($recent_projects)):
            $progress    = $proj['progress'] ?? 0;
            $bar_color   = match($proj['status']) {
              'Terminé'    => 'fill-green',
              'En attente' => 'fill-amber',
              default      => ''
            };
            $badge_class = match($proj['status']) {
              'En cours'   => 'badge-active',
              'Terminé'    => 'badge-done',
              'En attente' => 'badge-pending',
              default      => 'badge-paused'
            };
          ?>
          <div style="padding:12px 0;border-bottom:1px solid var(--border-light);">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div>
                <span style="font-size:13px;font-weight:600;color:var(--text-1);">
                  <?php echo htmlspecialchars($proj['title']); ?>
                </span>
                <span style="font-size:11px;color:var(--text-3);margin-left:8px;">
                  <?php echo htmlspecialchars($proj['client_name'] ?? '—'); ?>
                </span>
              </div>
              <span class="bp-badge <?php echo $badge_class; ?>">
                <?php echo htmlspecialchars($proj['status']); ?>
              </span>
            </div>
            <div class="bp-progress-wrap">
              <div class="bp-progress-label">
                <span>Progression</span><span><?php echo $progress; ?>%</span>
              </div>
              <div class="bp-progress-bar">
                <div class="bp-progress-fill <?php echo $bar_color; ?>"
                     style="width:<?php echo $progress; ?>%;"></div>
              </div>
            </div>
          </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>

      <!-- Dernières tâches -->
      <div class="bp-card">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="bp-card-title mb-0">Dernières tâches</div>
          <a href="/bproject/views/tasks/index.php" class="btn-bp-ghost"
             style="font-size:12px;padding:5px 12px;">
            Voir tout <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        <?php if (mysqli_num_rows($recent_tasks) === 0): ?>
          <div style="text-align:center;padding:24px;color:var(--text-3);font-size:13px;">
            Aucune tâche pour l'instant.
          </div>
        <?php else: ?>
          <div class="bp-table-wrap">
            <table class="bp-table">
              <thead>
                <tr>
                  <th>Tâche</th>
                  <th>Projet</th>
                  <th>Assigné à</th>
                  <th>Statut</th>
                  <th>Priorité</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($task = mysqli_fetch_assoc($recent_tasks)):
                  $t_badge = match($task['status']) {
                    'En cours' => 'badge-active',
                    'Terminé'  => 'badge-done',
                    'Bloquée'  => 'badge-blocked',
                    default    => 'badge-pending'
                  };
                  $p_badge = match($task['priority'] ?? 'Moyenne') {
                    'Haute' => 'badge-high',
                    'Basse' => 'badge-low',
                    default => 'badge-medium'
                  };
                ?>
                <tr>
                  <td style="font-weight:500;"><?php echo htmlspecialchars($task['title']); ?></td>
                  <td class="text-muted-bp"><?php echo htmlspecialchars($task['project_title'] ?? '—'); ?></td>
                  <td class="text-muted-bp"><?php echo htmlspecialchars($task['assignee_name'] ?? 'Non assigné'); ?></td>
                  <td><span class="bp-badge <?php echo $t_badge; ?>"><?php echo htmlspecialchars($task['status']); ?></span></td>
                  <td><span class="bp-badge <?php echo $p_badge; ?>"><?php echo htmlspecialchars($task['priority'] ?? 'Moyenne'); ?></span></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

    </div>

    <!-- Colonne droite -->
    <div class="col-12 col-xl-4">

      <!-- Statut des tâches -->
<div class="bp-card mb-3">
  <div class="bp-card-title">Tâches par statut</div>

  <?php
  $total   = max((int)$tasks_count, 1);
  $t_todo  = (int)$tasks_todo;
  $t_prog  = (int)$tasks_inprog;
  $t_done  = (int)$tasks_done;

  // Calcul des arcs SVG (cercle r=45, circonférence=282.7)
  $circ    = 282.7;
  $pct_todo = round($t_todo / $total * 100);
  $pct_prog = round($t_prog / $total * 100);
  $pct_done = round($t_done / $total * 100);

  $arc_todo = round($t_todo / $total * $circ, 1);
  $arc_prog = round($t_prog / $total * $circ, 1);
  $arc_done = round($t_done / $total * $circ, 1);

  $offset_todo = 0;
  $offset_prog = round($circ - $arc_todo, 1);
  $offset_done = round($circ - $arc_todo - $arc_prog, 1);
  ?>

  <!-- Donut SVG -->
  <div style="display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
    <svg viewBox="0 0 120 120" width="130" height="130" style="flex-shrink:0;">
      <!-- Fond gris -->
      <circle cx="60" cy="60" r="45"
              fill="none" stroke="#f0f0f5" stroke-width="14"/>
      <!-- À faire -->
      <circle cx="60" cy="60" r="45"
              fill="none" stroke="#94a3b8" stroke-width="14"
              stroke-dasharray="<?php echo $arc_todo; ?> <?php echo $circ; ?>"
              stroke-dashoffset="<?php echo $offset_todo; ?>"
              transform="rotate(-90 60 60)"
              stroke-linecap="butt"/>
      <!-- En cours -->
      <circle cx="60" cy="60" r="45"
              fill="none" stroke="#6366f1" stroke-width="14"
              stroke-dasharray="<?php echo $arc_prog; ?> <?php echo $circ; ?>"
              stroke-dashoffset="-<?php echo $arc_todo; ?>"
              transform="rotate(-90 60 60)"
              stroke-linecap="butt"/>
      <!-- Terminées -->
      <circle cx="60" cy="60" r="45"
              fill="none" stroke="#16a34a" stroke-width="14"
              stroke-dasharray="<?php echo $arc_done; ?> <?php echo $circ; ?>"
              stroke-dashoffset="-<?php echo round($arc_todo + $arc_prog, 1); ?>"
              transform="rotate(-90 60 60)"
              stroke-linecap="butt"/>
      <!-- Total au centre -->
      <text x="60" y="55" text-anchor="middle"
            font-size="22" font-weight="700"
            fill="#111827"><?php echo $tasks_count; ?></text>
      <text x="60" y="70" text-anchor="middle"
            font-size="9" fill="#9ca3af">tâches</text>
    </svg>
  </div>

  <!-- Légende avec barres -->
  <?php
  $statuts = [
    ['label'=>'À faire',   'val'=>$t_todo, 'pct'=>$pct_todo, 'color'=>'#94a3b8'],
    ['label'=>'En cours',  'val'=>$t_prog, 'pct'=>$pct_prog, 'color'=>'#6366f1'],
    ['label'=>'Terminées', 'val'=>$t_done, 'pct'=>$pct_done, 'color'=>'#16a34a'],
  ];
  foreach ($statuts as $s):
  ?>
  <div style="margin-bottom:12px;">
    <div class="bp-progress-label">
      <span style="display:flex;align-items:center;gap:7px;">
        <span style="width:8px;height:8px;border-radius:50%;
                     background:<?php echo $s['color']; ?>;
                     display:inline-block;flex-shrink:0;"></span>
        <?php echo $s['label']; ?>
      </span>
      <span style="font-weight:600;color:var(--text-1);">
        <?php echo $s['val']; ?>
        <span style="font-weight:400;color:var(--text-3);font-size:11px;">
          (<?php echo $s['pct']; ?>%)
        </span>
      </span>
    </div>
    <div class="bp-progress-bar">
      <div class="bp-progress-fill"
           style="width:<?php echo $s['pct']; ?>%;
                  background:<?php echo $s['color']; ?>;"></div>
    </div>
  </div>
  <?php endforeach; ?>

</div>

      <!-- Actions rapides -->
      <div class="bp-card">
        <div class="bp-card-title">Actions rapides</div>
        <div class="d-flex flex-column gap-2">

          <?php if ($role_id === 1): ?>
          <a href="/bproject/views/clients/create.php" class="btn-bp-ghost w-100"
             style="justify-content:flex-start;">
            <i class="bi bi-person-plus"></i> Ajouter un client
          </a>
          <?php endif; ?>

          <?php if ($role_id <= 2): ?>
          <a href="/bproject/views/projects/create.php" class="btn-bp-ghost w-100"
             style="justify-content:flex-start;">
            <i class="bi bi-folder-plus"></i> Nouveau projet
          </a>
          <?php endif; ?>

          <?php if ($role_id <= 3): ?>
          <a href="/bproject/views/tasks/create.php" class="btn-bp-ghost w-100"
             style="justify-content:flex-start;">
            <i class="bi bi-check2-square"></i> Créer une tâche
          </a>
          <?php endif; ?>

          <?php if ($role_id === 1): ?>
          <a href="/bproject/views/users/create.php" class="btn-bp-ghost w-100"
             style="justify-content:flex-start;">
            <i class="bi bi-person-badge"></i> Ajouter un utilisateur
          </a>
          <?php endif; ?>

        </div>
      </div>

    </div>
  </div>

</div>

<?php include "views/layout/footer.php"; ?>