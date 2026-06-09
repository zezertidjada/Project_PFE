<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$role_id = (int)($_SESSION['role_id'] ?? 4);
include "../layout/header.php";
include "../layout/sidebar.php";
require_once "../../config/database.php";
require_once "../../config/csrf.php";

$page_title = "Tâches";

$project_filter = intval($_GET['project_id'] ?? 0);
$all_projects   = mysqli_query($conn, "SELECT id, title FROM projects ORDER BY title");

$statuts    = ['À faire', 'En cours', 'Terminé', 'Bloquée'];
$dot_colors = [
    'À faire'  => '#94a3b8',
    'En cours' => '#6366f1',
    'Terminé'  => '#16a34a',
    'Bloquée'  => '#ef4444',
];
$border_colors = ['En cours' => '#6366f1', 'Bloquée' => '#ef4444'];

$tasks_by_status = [];
foreach ($statuts as $s) {
    $s_esc      = mysqli_real_escape_string($conn, $s);
    $proj_where = $project_filter ? "AND t.project_id = $project_filter" : "";
    $tasks_by_status[$s] = mysqli_query($conn,
        "SELECT t.*, u.name AS user_name, p.title AS project_title
         FROM tasks t
         LEFT JOIN users u    ON t.assigned_to = u.id
         LEFT JOIN projects p ON t.project_id  = p.id
         WHERE t.status = '$s_esc' $proj_where
         ORDER BY t.created_at DESC"
    );
}

$priority_badge = ['Haute' => 'badge-high', 'Moyenne' => 'badge-medium', 'Basse' => 'badge-low'];
?>

<!-- TOPBAR -->
<div class="bp-topbar">
  <span class="bp-page-title">
    <i class="bi bi-check2-square me-2" style="color:var(--accent);font-size:13px;"></i>
    Tâches
  </span>
  <div class="d-flex align-items-center gap-2">
    <form method="GET" style="margin:0;">
      <select name="project_id" class="bp-select"
              style="width:200px;padding:7px 12px;"
              onchange="this.form.submit()">
        <option value="0">Tous les projets</option>
        <?php while ($pr = mysqli_fetch_assoc($all_projects)): ?>
          <option value="<?php echo $pr['id']; ?>"
            <?php echo $project_filter == $pr['id'] ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($pr['title']); ?>
          </option>
        <?php endwhile; ?>
      </select>
    </form>
    <?php if (in_array($role_id, [1, 2, 3])): ?>
    <a href="create.php" class="btn-bp-primary">
      <i class="bi bi-plus-lg"></i> Nouvelle tâche
    </a>
    <?php endif; ?>
  </div>
</div>

<div class="bp-content">

  <?php if (isset($_GET['success'])): ?>
    <div class="bp-alert bp-alert-success">
      <i class="bi bi-check-circle-fill"></i>
      <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
  <?php endif; ?>

  <!-- KANBAN -->
  <div class="bp-kanban">
    <?php foreach ($statuts as $s):
      $tasks  = $tasks_by_status[$s];
      $count  = mysqli_num_rows($tasks);
      $dot    = $dot_colors[$s];
      $border = $border_colors[$s] ?? 'transparent';
    ?>
    <div class="bp-kboard" data-status="<?php echo htmlspecialchars($s); ?>">

      <div class="bp-kboard-header">
        <div class="bp-kboard-dot" style="background:<?php echo $dot; ?>;"></div>
        <div class="bp-kboard-title"><?php echo $s; ?></div>
        <div class="bp-kboard-count"><?php echo $count; ?></div>
      </div>

      <?php if ($count === 0): ?>
        <div class="bp-ktask-empty">Aucune tâche</div>
      <?php else: ?>
        <?php while ($task = mysqli_fetch_assoc($tasks)):
          $p_badge  = $priority_badge[$task['priority'] ?? 'Moyenne'] ?? 'badge-medium';
          $initials = strtoupper(substr($task['user_name'] ?? '?', 0, 2));
        ?>
        <div class="bp-ktask"
             data-task-id="<?php echo $task['id']; ?>"
             style="border-left:3px solid <?php echo $border; ?>;">

          <div class="bp-ktask-title">
            <?php echo htmlspecialchars($task['title']); ?>
          </div>

          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="bp-badge <?php echo $p_badge; ?>">
              <?php echo htmlspecialchars($task['priority'] ?? 'Moyenne'); ?>
            </span>
            <span style="font-size:10px;color:var(--text-3);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
              <?php echo htmlspecialchars($task['project_title'] ?? '—'); ?>
            </span>
          </div>

          <?php if ($s === 'Bloquée'): ?>
          <div class="bp-blocked-msg" style="font-size:11px;color:#ef4444;margin-bottom:6px;">
            <i class="bi bi-exclamation-triangle-fill"></i> Tâche bloquée
          </div>
          <?php endif; ?>

          <div class="bp-ktask-footer">
            <div class="d-flex align-items-center gap-6">
              <?php if ($task['user_name']): ?>
              <div class="bp-avatar bp-av-sm av-indigo" style="border-radius:50%;">
                <?php echo $initials; ?>
              </div>
              <span style="font-size:11px;color:var(--text-2);margin-left:5px;">
                <?php echo htmlspecialchars($task['user_name']); ?>
              </span>
              <?php else: ?>
              <span style="font-size:11px;color:var(--text-3);">Non assigné</span>
              <?php endif; ?>
            </div>

            <div class="bp-ktask-actions">
              <?php if (in_array($role_id, [1, 2, 3])): ?>
              <a href="edit.php?id=<?php echo $task['id']; ?>"
                 class="btn-bp-icon" title="Modifier"
                 style="padding:4px 8px;font-size:12px;">
                <i class="bi bi-pencil"></i>
              </a>
              <?php endif; ?>
              <?php if (in_array($role_id, [1, 2])): ?>
              <form method="POST" action="delete.php" style="margin:0;">
                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <button type="submit" class="btn-bp-danger"
                        style="padding:4px 8px;font-size:12px;"
                        onclick="return confirm('Supprimer cette tâche ?')"
                        title="Supprimer">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
              <?php endif; ?>
            </div>
          </div>

          <?php if ($task['due_date']): ?>
          <div style="font-size:10px;color:var(--text-3);margin-top:6px;">
            <i class="bi bi-calendar3"></i>
            Échéance : <?php echo date('d/m/Y', strtotime($task['due_date'])); ?>
          </div>
          <?php endif; ?>

        </div>
        <?php endwhile; ?>
      <?php endif; ?>

    </div>
    <?php endforeach; ?>
  </div>

</div>

<!-- DRAG & DROP JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const CSRF = '<?php echo generateCSRFToken(); ?>';
  const ROLE = <?php echo $role_id; ?>;

  const columns = document.querySelectorAll('.bp-kboard');
  const cards   = document.querySelectorAll('.bp-ktask');

  if (ROLE < 4) cards.forEach(c => c.setAttribute('draggable', 'true'));

  let draggedCard = null, sourceColumn = null, placeholder = null;

  document.addEventListener('dragstart', function (e) {
    if (!e.target.classList.contains('bp-ktask')) return;
    draggedCard  = e.target;
    sourceColumn = draggedCard.closest('.bp-kboard');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/plain', draggedCard.dataset.taskId);
    requestAnimationFrame(() => draggedCard.classList.add('dragging'));
    placeholder = document.createElement('div');
    placeholder.className = 'bp-ktask-placeholder';
  });

  document.addEventListener('dragend', function () {
    if (!draggedCard) return;
    draggedCard.classList.remove('dragging');
    placeholder && placeholder.remove();
    placeholder = null;
    columns.forEach(c => c.classList.remove('drag-over'));
    draggedCard = null; sourceColumn = null;
  });

  columns.forEach(col => {
    col.addEventListener('dragover', function (e) {
      e.preventDefault();
      if (!draggedCard) return;
      e.dataTransfer.dropEffect = 'move';
      const after = getAfterElement(this, e.clientY);
      after ? this.insertBefore(placeholder, after) : this.appendChild(placeholder);
    });

    col.addEventListener('dragenter', function (e) {
      e.preventDefault();
      if (draggedCard && this !== sourceColumn) this.classList.add('drag-over');
    });

    col.addEventListener('dragleave', function (e) {
      if (!this.contains(e.relatedTarget)) this.classList.remove('drag-over');
    });

    col.addEventListener('drop', function (e) {
      e.preventDefault();
      this.classList.remove('drag-over');
      if (!draggedCard || this === sourceColumn) return;

      const newStatus = this.dataset.status;
      const taskId    = draggedCard.dataset.taskId;
      const prevCol   = sourceColumn;

      placeholder && placeholder.remove();
      const after = getAfterElement(this, e.clientY);
      after ? this.insertBefore(draggedCard, after) : this.appendChild(draggedCard);

      updateCount(prevCol, -1);
      updateCount(this,    +1);
      checkEmpty(prevCol);
      checkEmpty(this);
      updateCardVisual(draggedCard, newStatus);

      draggedCard.classList.add('card-drop');
      draggedCard.addEventListener('animationend', () => draggedCard.classList.remove('card-drop'), { once: true });

      fetch('update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ task_id: parseInt(taskId), status: newStatus, csrf_token: CSRF })
      })
      .then(r => r.json())
      .then(res => {
        if (!res.success) {
          prevCol.appendChild(draggedCard);
          updateCount(this, -1); updateCount(prevCol, +1);
          checkEmpty(this); checkEmpty(prevCol);
          updateCardVisual(draggedCard, prevCol.dataset.status);
          toast('Erreur : ' + (res.message || 'inconnue'), 'error');
        } else {
          toast('Statut mis à jour ✓', 'success');
        }
      })
      .catch(() => toast('Erreur réseau', 'error'));
    });
  });

  function getAfterElement(container, mouseY) {
    const siblings = [...container.querySelectorAll('.bp-ktask:not(.dragging)')];
    return siblings.reduce((closest, el) => {
      const { top, height } = el.getBoundingClientRect();
      const offset = mouseY - top - height / 2;
      if (offset < 0 && offset > closest.offset) return { offset, element: el };
      return closest;
    }, { offset: Number.NEGATIVE_INFINITY }).element;
  }

  function updateCount(col, delta) {
    const badge = col.querySelector('.bp-kboard-count');
    if (!badge) return;
    badge.textContent = Math.max(0, (parseInt(badge.textContent) || 0) + delta);
  }

  function checkEmpty(col) {
    const hasCards = col.querySelector('.bp-ktask');
    const emptyEl  = col.querySelector('.bp-ktask-empty');
    if (!hasCards && !emptyEl) {
      const el = document.createElement('div');
      el.className = 'bp-ktask-empty'; el.textContent = 'Aucune tâche';
      col.appendChild(el);
    } else if (hasCards && emptyEl) emptyEl.remove();
  }

  function updateCardVisual(card, status) {
    const borders = { 'En cours': '#6366f1', 'Bloquée': '#ef4444' };
    card.style.borderLeft = borders[status] ? `3px solid ${borders[status]}` : '3px solid transparent';
    let blockedEl = card.querySelector('.bp-blocked-msg');
    if (status === 'Bloquée') {
      if (!blockedEl) {
        blockedEl = document.createElement('div');
        blockedEl.className = 'bp-blocked-msg';
        blockedEl.style.cssText = 'font-size:11px;color:#ef4444;margin-bottom:6px;';
        blockedEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Tâche bloquée';
        const badgeRow = card.querySelector('.d-flex.align-items-center.gap-2.mb-2');
        badgeRow && badgeRow.insertAdjacentElement('afterend', blockedEl);
      }
    } else { blockedEl && blockedEl.remove(); }
  }

  function toast(msg, type) {
    const el = document.createElement('div');
    el.className = `bp-toast bp-toast-${type}`;
    el.textContent = msg;
    document.body.appendChild(el);
    requestAnimationFrame(() => el.classList.add('show'));
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 300); }, 2500);
  }
});
</script>

<?php include "../layout/footer.php"; ?>