<?php
require_once __DIR__ . '/config/app.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>B-Project Manager — Gérez vos projets avec clarté</title>
  <script>
    (function(){
      if (localStorage.getItem('bp_theme') === 'dark')
        document.documentElement.setAttribute('data-theme','dark');
    })();
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo bp_asset('css/landing.css?v=2'); ?>" rel="stylesheet">
</head>
<body>

<!-- ══════════════════════════════════
     NAVBAR
══════════════════════════════════ -->
<nav class="lp-nav" id="navbar">
  <a href="#" class="lp-logo">
    <div class="lp-logo-icon">BP</div>
    <div>
      <div class="lp-logo-text">B-Project Manager</div>
      <div class="lp-logo-sub">B-NETWORK</div>
    </div>
  </a>
  <div class="lp-nav-actions">
    <div class="lp-theme-row">
      <i class="bi bi-sun-fill"></i>
      <button class="bp-theme-toggle" data-theme-toggle title="Changer le thème" type="button"></button>
      <i class="bi bi-moon-fill"></i>
      <span data-theme-label>Clair</span>
    </div>
    <a href="<?php echo bp_url('login.php'); ?>" class="btn-nav-cta">
      <i class="bi bi-box-arrow-in-right"></i> Se connecter
    </a>
  </div>
</nav>

<!-- ══════════════════════════════════
     HERO
══════════════════════════════════ -->
<section class="hero" id="home">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>

  <div class="hero-content">
    <div class="hero-badge">
      <div class="badge-pulse"></div>
      Plateforme B-NETWORK · PHP + MySQL · v1.0
    </div>

    <h1 class="hero-title">
      Gérez vos projets<br>
      <span class="grad-text">avec clarté et efficacité</span>
    </h1>

    <p class="hero-sub">
      B-Project Manager centralise vos projets, tâches et équipes dans
      une interface Kanban intuitive avec gestion des droits par rôle.
    </p>

    <div class="hero-btns">
      <a href="<?php echo bp_url('login.php'); ?>" class="btn-primary-lp">
        <i class="bi bi-rocket-takeoff-fill"></i>
        Accéder au tableau de bord
      </a>
      <a href="#features" class="btn-ghost-lp">
        <i class="bi bi-play-circle"></i>
        Découvrir les fonctionnalités
      </a>
    </div>
  </div>

  <!-- Mockup navigateur -->
  <div class="hero-mockup w-100" style="max-width:900px;">
    <div class="mockup-glow"></div>
    <div class="mockup-frame">
      <div class="mockup-bar">
        <div class="win-dot" style="background:#ef4444;"></div>
        <div class="win-dot" style="background:#f59e0b;"></div>
        <div class="win-dot" style="background:#16a34a;"></div>
        <div class="mockup-url-bar">localhost<?php echo bp_url('views/tasks/index.php'); ?></div>
      </div>
      <div class="mockup-body">
        <!-- Mini sidebar -->
        <div class="mock-sidebar">
          <div class="mock-logo">
            <div class="mock-logo-icon">BP</div>
            <div class="mock-logo-text">B-Project</div>
          </div>
          <div class="mock-nav-item"><div class="mock-nav-dot" style="color:#6366f1;background:#6366f1;"></div> Dashboard</div>
          <div class="mock-nav-item"><div class="mock-nav-dot"></div> Clients</div>
          <div class="mock-nav-item"><div class="mock-nav-dot"></div> Projets</div>
          <div class="mock-nav-item active"><div class="mock-nav-dot"></div> Tâches</div>
          <div class="mock-nav-item"><div class="mock-nav-dot"></div> Utilisateurs</div>
        </div>
        <!-- Mini kanban -->
        <div class="mock-content">
          <div class="mock-topbar">
            <span class="mock-title">✅ Tâches</span>
            <button class="mock-btn">+ Nouvelle tâche</button>
          </div>
          <div class="mock-kanban">
            <div class="mk-col">
              <div class="mk-h"><div class="mk-d" style="background:#94a3b8;"></div><span class="mk-t">À FAIRE</span><span class="mk-c">2</span></div>
              <div class="mk-card"><div class="mk-card-t">Configurer TypeScript v5</div><span class="mk-badge mk-h-badge">Haute</span> <span class="mock-avatar" style="background:#6366f1;">DS</span></div>
              <div class="mk-card"><div class="mk-card-t">Page d'accueil</div><span class="mk-badge mk-m-badge">Moyenne</span> <span class="mock-avatar" style="background:#16a34a;">DJ</span></div>
            </div>
            <div class="mk-col" style="border-top:2px solid #6366f1;">
              <div class="mk-h"><div class="mk-d" style="background:#6366f1;"></div><span class="mk-t">EN COURS</span><span class="mk-c">2</span></div>
              <div class="mk-card" style="border-left:2px solid #6366f1;"><div class="mk-card-t">Tableau de bord admin</div><span class="mk-badge mk-m-badge">Moyenne</span> <span class="mock-avatar" style="background:#f59e0b;">KZ</span></div>
              <div class="mk-card" style="border-left:2px solid #6366f1;"><div class="mk-card-t">Interface login</div><span class="mk-badge mk-l-badge">Basse</span></div>
            </div>
            <div class="mk-col">
              <div class="mk-h"><div class="mk-d" style="background:#16a34a;"></div><span class="mk-t">TERMINÉ</span><span class="mk-c">2</span></div>
              <div class="mk-card" style="opacity:.7;"><div class="mk-card-t">Création BDD</div><span class="mk-badge mk-h-badge">Haute</span></div>
              <div class="mk-card" style="opacity:.7;"><div class="mk-card-t">Init monorepo</div><span class="mk-badge mk-m-badge">Moyenne</span></div>
            </div>
            <div class="mk-col">
              <div class="mk-h"><div class="mk-d" style="background:#ef4444;"></div><span class="mk-t">BLOQUÉE</span><span class="mk-c">0</span></div>
              <div style="font-size:8px;color:#334155;text-align:center;padding:14px 0;">Aucune tâche</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="sep"></div>

<!-- ══════════════════════════════════
     STATS
══════════════════════════════════ -->
<section class="stats-s">
  <div class="container">
    <div class="row g-4 justify-content-center text-center">
      <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-num" data-target="4" data-suffix="">0</div><div class="stat-label">Rôles RBAC distincts</div></div></div>
      <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-num" data-target="100" data-suffix="%">0</div><div class="stat-label">Sécurisé (CSRF + bcrypt)</div></div></div>
      <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-num" data-target="4" data-suffix="">0</div><div class="stat-label">Statuts Kanban</div></div></div>
      <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-num" data-target="5" data-suffix="">0</div><div class="stat-label">Modules intégrés</div></div></div>
    </div>
  </div>
</section>

<div class="sep"></div>

<!-- ══════════════════════════════════
     FEATURES
══════════════════════════════════ -->
<section class="section s-dark" id="features">
  <div class="container">
    <div class="text-center mb-5 fade-up">
      <div class="sec-badge">Fonctionnalités</div>
      <h2 class="sec-title">Tout ce dont votre équipe<br>a besoin</h2>
      <p class="sec-sub mx-auto">Une suite complète conçue pour les agences et équipes digitales africaines.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4 fade-up">
        <div class="feat-card">
          <div class="feat-icon fi-ind"><i class="bi bi-kanban-fill"></i></div>
          <div class="feat-title">Kanban drag & drop</div>
          <div class="feat-desc">Déplacez vos tâches entre les colonnes par glisser-déposer. Le statut est mis à jour instantanément en base de données.</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 fade-up d1">
        <div class="feat-card">
          <div class="feat-icon fi-green"><i class="bi bi-folder2-open"></i></div>
          <div class="feat-title">Gestion de projets</div>
          <div class="feat-desc">Créez des projets avec suivi de progression, dates limites, statuts colorés et client associé en quelques clics.</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 fade-up d2">
        <div class="feat-card">
          <div class="feat-icon fi-amber"><i class="bi bi-building"></i></div>
          <div class="feat-title">Gestion des clients</div>
          <div class="feat-desc">Répertoire complet : coordonnées, entreprise, projets liés et historique en un seul endroit.</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 fade-up d1">
        <div class="feat-card">
          <div class="feat-icon fi-purple"><i class="bi bi-shield-check"></i></div>
          <div class="feat-title">RBAC 4 niveaux</div>
          <div class="feat-desc">Admin, Chef de projet, Développeur et Stagiaire. Chaque rôle accède uniquement à ce qu'il peut faire.</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 fade-up d2">
        <div class="feat-card">
          <div class="feat-icon fi-teal"><i class="bi bi-graph-up-arrow"></i></div>
          <div class="feat-title">Dashboard analytique</div>
          <div class="feat-desc">KPIs, graphique donut SVG des statuts de tâches, projets récents et activité de l'équipe en temps réel.</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 fade-up d3">
        <div class="feat-card">
          <div class="feat-icon fi-red"><i class="bi bi-lock-fill"></i></div>
          <div class="feat-title">Sécurité complète</div>
          <div class="feat-desc">Tokens CSRF sur toutes les actions, mots de passe hashés bcrypt, requêtes paramétrées contre les injections SQL.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="sep"></div>

<!-- ══════════════════════════════════
     HOW IT WORKS
══════════════════════════════════ -->
<section class="section s-mid" id="how">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 fade-up">
        <div class="sec-badge">Comment ça marche</div>
        <h2 class="sec-title">Opérationnel en 3 étapes</h2>
        <p class="sec-sub">Déployez B-Project Manager sur votre serveur local ou distant et commencez immédiatement.</p>
      </div>
      <div class="col-lg-7">
        <div class="timeline-item fade-up d1">
          <div class="timeline-num">1</div>
          <div class="timeline-content">
            <div class="timeline-title">Importez la base de données</div>
            <div class="timeline-desc">Chargez <code style="color:#818cf8;">bproject_db.sql</code> dans phpMyAdmin. Toutes les tables (users, clients, projects, tasks, roles) sont créées avec des données de démonstration.</div>
          </div>
        </div>
        <div class="timeline-item fade-up d2">
          <div class="timeline-num">2</div>
          <div class="timeline-content">
            <div class="timeline-title">Connectez-vous avec le compte Admin</div>
            <div class="timeline-desc">Utilisez <code style="color:#818cf8;">admin@test.com</code> pour découvrir la plateforme avec accès complet. Créez ensuite vos utilisateurs avec les rôles appropriés.</div>
          </div>
        </div>
        <div class="timeline-item fade-up d3">
          <div class="timeline-num">3</div>
          <div class="timeline-content">
            <div class="timeline-title">Gérez vos projets et tâches</div>
            <div class="timeline-desc">Créez des clients, lancez des projets, assignez des tâches à votre équipe et suivez l'avancement via le tableau Kanban interactif.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="sep"></div>

<!-- ══════════════════════════════════
     RÔLES
══════════════════════════════════ -->
<section class="section s-dark" id="roles">
  <div class="container">
    <div class="text-center mb-5 fade-up">
      <div class="sec-badge">Rôles & Permissions</div>
      <h2 class="sec-title">Un accès adapté<br>à chaque profil</h2>
      <p class="sec-sub mx-auto">Le système RBAC garantit que chaque membre dispose exactement des droits nécessaires.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3 fade-up">
        <div class="role-card">
          <div class="role-avatar" style="background:#6366f1;box-shadow:0 0 20px rgba(99,102,241,.4);">AD</div>
          <div class="role-name">Admin</div>
          <div class="role-desc">Contrôle total sur la plateforme, les utilisateurs et toutes les données.</div>
          <div class="perm-line"><i class="bi bi-check-circle-fill pyes"></i> Gérer les utilisateurs</div>
          <div class="perm-line"><i class="bi bi-check-circle-fill pyes"></i> CRUD clients</div>
          <div class="perm-line"><i class="bi bi-check-circle-fill pyes"></i> CRUD projets</div>
          <div class="perm-line"><i class="bi bi-check-circle-fill pyes"></i> CRUD tâches</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 fade-up d1">
        <div class="role-card">
          <div class="role-avatar" style="background:#f59e0b;box-shadow:0 0 20px rgba(245,158,11,.3);">CP</div>
          <div class="role-name">Chef de projet</div>
          <div class="role-desc">Pilote les projets et coordonne l'équipe de développement.</div>
          <div class="perm-line"><i class="bi bi-x-circle pno"></i> Gestion utilisateurs</div>
          <div class="perm-line"><i class="bi bi-eye-fill pread"></i> Lecture clients</div>
          <div class="perm-line"><i class="bi bi-check-circle-fill pyes"></i> CRUD projets</div>
          <div class="perm-line"><i class="bi bi-check-circle-fill pyes"></i> CRUD tâches</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 fade-up d2">
        <div class="role-card">
          <div class="role-avatar" style="background:#16a34a;box-shadow:0 0 20px rgba(22,163,74,.3);">DV</div>
          <div class="role-name">Développeur</div>
          <div class="role-desc">Travaille sur ses tâches et peut mettre à jour leur statut via le Kanban.</div>
          <div class="perm-line"><i class="bi bi-x-circle pno"></i> Gestion utilisateurs</div>
          <div class="perm-line"><i class="bi bi-eye-fill pread"></i> Lecture clients</div>
          <div class="perm-line"><i class="bi bi-eye-fill pread"></i> Lecture projets</div>
          <div class="perm-line"><i class="bi bi-check-circle-fill pyes"></i> CRU tâches</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 fade-up d3">
        <div class="role-card">
          <div class="role-avatar" style="background:#94a3b8;box-shadow:0 0 20px rgba(148,163,184,.2);">ST</div>
          <div class="role-name">Stagiaire</div>
          <div class="role-desc">Consultation du tableau de bord et visualisation des données uniquement.</div>
          <div class="perm-line"><i class="bi bi-x-circle pno"></i> Gestion utilisateurs</div>
          <div class="perm-line"><i class="bi bi-eye-fill pread"></i> Lecture clients</div>
          <div class="perm-line"><i class="bi bi-eye-fill pread"></i> Lecture projets</div>
          <div class="perm-line"><i class="bi bi-eye-fill pread"></i> Lecture tâches</div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="sep"></div>

<!-- ══════════════════════════════════
     CTA
══════════════════════════════════ -->
<section class="cta-section">
  <div style="position:relative;z-index:2;">
    <div class="sec-badge">Prêt à commencer ?</div>
    <h2 class="cta-title">
      Votre équipe mérite<br>
      <span class="grad-text">un meilleur outil</span>
    </h2>
    <p class="cta-sub">
      Connectez-vous dès maintenant et prenez le contrôle de vos projets avec B-Project Manager.
    </p>
    <a href="<?php echo bp_url('login.php'); ?>" class="btn-primary-lp" style="font-size:16px;padding:17px 44px;">
      <i class="bi bi-rocket-takeoff-fill"></i>
      Accéder à l'application
    </a>
  </div>
</section>

<!-- ══════════════════════════════════
     FOOTER
══════════════════════════════════ -->
<footer class="lp-footer">
  <div style="display:flex;align-items:center;gap:9px;">
    <div style="width:28px;height:28px;background:var(--ind);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;color:#fff;">BP</div>
    <span class="lp-footer-brand">B-Project Manager</span>
  </div>
  <div class="lp-footer-text">© <?php echo date('Y'); ?> B-NETWORK. Tous droits réservés.</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo bp_asset('js/theme.js'); ?>"></script>
<script>
// ── Navbar scroll effect ──────────────────────────────
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// ── IntersectionObserver pour fade-up ────────────────
const fadeObserver = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); fadeObserver.unobserve(e.target); } });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-up').forEach(el => fadeObserver.observe(el));

// ── Compteurs animés ──────────────────────────────────
function animCounter(el) {
  const target = parseInt(el.dataset.target);
  const suffix = el.dataset.suffix || '';
  const dur    = 1800;
  const fps    = 60;
  const step   = target / (dur / (1000 / fps));
  let cur = 0;
  const iv = setInterval(() => {
    cur = Math.min(cur + step, target);
    el.textContent = Math.floor(cur) + suffix;
    if (cur >= target) clearInterval(iv);
  }, 1000 / fps);
}
const countObs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.querySelectorAll('[data-target]').forEach(animCounter);
      countObs.unobserve(e.target);
    }
  });
}, { threshold: 0.4 });
document.querySelectorAll('.stats-s').forEach(s => countObs.observe(s));

// ── Smooth scroll pour ancres internes ───────────────
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const target = document.querySelector(a.getAttribute('href'));
    if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
  });
});
</script>
</body>
</html>