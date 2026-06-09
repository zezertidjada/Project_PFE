<?php /* bproject/landing.php */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>B-Project Manager — Gérez vos projets avec clarté</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root { --ind: #6366f1; --ind-d: #4f46e5; --ind-l: #eef2ff; }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Segoe UI', system-ui, sans-serif; background: #080c18; color: #f1f5f9; overflow-x: hidden; }

    /* ── NAVBAR ── */
    .lp-nav {
      position: fixed; top: 0; left: 0; right: 0; z-index: 200;
      padding: 16px 48px;
      display: flex; align-items: center; justify-content: space-between;
      transition: background 0.4s, backdrop-filter 0.4s, border 0.4s;
    }
    .lp-nav.scrolled {
      background: rgba(8,12,24,0.82);
      backdrop-filter: blur(18px) saturate(1.5);
      border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    .lp-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
    .lp-logo-icon {
      width: 36px; height: 36px; background: var(--ind); border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 14px; color: #fff;
      box-shadow: 0 0 20px rgba(99,102,241,0.5);
    }
    .lp-logo-text { font-size: 15px; font-weight: 700; color: #f1f5f9; }
    .lp-logo-sub  { font-size: 10px; color: #475569; font-weight: 400; }
    .btn-nav-cta {
      background: var(--ind); color: #fff; border: none;
      padding: 9px 22px; border-radius: 8px; font-size: 13px; font-weight: 600;
      cursor: pointer; text-decoration: none;
      display: inline-flex; align-items: center; gap: 7px;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 2px 12px rgba(99,102,241,0.4);
    }
    .btn-nav-cta:hover { background: var(--ind-d); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 20px rgba(99,102,241,0.5); }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      text-align: center; position: relative; overflow: hidden;
      padding: 120px 24px 60px;
    }
    .hero-bg {
      position: absolute; inset: 0; z-index: 0;
      background:
        radial-gradient(ellipse 80% 60% at 50% -10%, rgba(99,102,241,.45), transparent),
        radial-gradient(ellipse 50% 40% at 80% 90%,  rgba(79,70,229,.2),   transparent),
        #080c18;
    }
    .hero-grid {
      position: absolute; inset: 0; z-index: 0;
      background-image: radial-gradient(circle, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size: 32px 32px;
      mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
    }

    /* Orbes flottantes */
    .orb { position: absolute; border-radius: 50%; filter: blur(70px); animation: orbFloat 10s ease-in-out infinite; }
    .orb-1 { width:500px;height:500px; background:rgba(99,102,241,.18); top:-120px;left:-80px; animation-delay:0s; }
    .orb-2 { width:350px;height:350px; background:rgba(139,92,246,.14); top:30%;right:-60px; animation-delay:3s; }
    .orb-3 { width:280px;height:280px; background:rgba(99,102,241,.1);  bottom:5%;left:15%;  animation-delay:6s; }
    @keyframes orbFloat {
      0%,100%{ transform:translateY(0) scale(1); }
      50%    { transform:translateY(-28px) scale(1.04); }
    }

    .hero-content { position:relative; z-index:2; max-width:800px; }

    .hero-badge {
      display:inline-flex; align-items:center; gap:8px;
      background:rgba(99,102,241,.12); border:1px solid rgba(99,102,241,.3);
      color:#a5b4fc; font-size:12px; font-weight:600;
      padding:6px 16px; border-radius:20px; margin-bottom:28px;
      letter-spacing:.4px;
    }
    .badge-pulse { width:6px;height:6px;border-radius:50%;background:#818cf8; animation:pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.4;transform:scale(1.4);} }

    .hero-title {
      font-size: clamp(36px,6vw,76px);
      font-weight: 900;
      line-height: 1.06;
      margin-bottom: 22px;
      letter-spacing: -2px;
    }
    .grad-text {
      background: linear-gradient(135deg, #c7d2fe 0%, #818cf8 45%, #6366f1 100%);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }

    .hero-sub {
      font-size: clamp(15px,2vw,19px);
      color: #64748b; line-height: 1.75;
      margin-bottom: 40px; max-width: 580px; margin-left:auto; margin-right:auto;
    }

    .hero-btns { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }
    .btn-primary-lp {
      background:var(--ind); color:#fff; border:none;
      padding:15px 36px; border-radius:10px; font-size:15px; font-weight:700;
      display:inline-flex; align-items:center; gap:9px; text-decoration:none;
      transition:background .2s, transform .15s, box-shadow .2s;
      box-shadow: 0 4px 28px rgba(99,102,241,.45);
    }
    .btn-primary-lp:hover { background:var(--ind-d); color:#fff; transform:translateY(-3px); box-shadow:0 10px 36px rgba(99,102,241,.55); }
    .btn-ghost-lp {
      background:transparent; color:#94a3b8;
      border:1px solid rgba(255,255,255,.1);
      padding:15px 36px; border-radius:10px; font-size:15px; font-weight:600;
      display:inline-flex; align-items:center; gap:9px; text-decoration:none;
      transition:border-color .2s, color .2s, background .2s;
    }
    .btn-ghost-lp:hover { border-color:rgba(99,102,241,.5); color:#a5b4fc; background:rgba(99,102,241,.08); }

    /* ── MOCKUP ── */
    .hero-mockup { position:relative; z-index:2; margin-top:64px; }
    .mockup-glow {
      position:absolute; top:50%; left:50%;
      transform:translate(-50%,-50%);
      width:80%; height:60%; border-radius:50%;
      background:rgba(99,102,241,.15); filter:blur(50px);
      pointer-events:none;
    }
    .mockup-frame {
      background:#1a2540; border:1px solid rgba(255,255,255,.08);
      border-radius:14px; overflow:hidden; position:relative;
      box-shadow: 0 50px 100px rgba(0,0,0,.7), 0 0 0 1px rgba(255,255,255,.04);
      max-width:880px; margin:0 auto;
    }
    .mockup-bar {
      background:#111d35; padding:10px 16px;
      display:flex; align-items:center; gap:8px;
      border-bottom:1px solid rgba(255,255,255,.05);
    }
    .win-dot { width:11px;height:11px;border-radius:50%; }
    .mockup-url-bar {
      flex:1; background:rgba(255,255,255,.05); border-radius:5px;
      padding:3px 14px; font-size:11px; color:#475569; text-align:center; margin:0 20px;
    }
    .mockup-body { display:grid; grid-template-columns:200px 1fr; background:#0c1628; }
    .mock-sidebar { border-right:1px solid rgba(255,255,255,.05); padding:16px 10px; }
    .mock-logo  { display:flex;align-items:center;gap:8px;padding:8px 6px;margin-bottom:12px; }
    .mock-logo-icon { width:26px;height:26px;border-radius:7px;background:var(--ind);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;color:#fff; }
    .mock-logo-text { font-size:11px;font-weight:700;color:#94a3b8; }
    .mock-nav-item {
      display:flex;align-items:center;gap:7px;
      padding:7px 8px;border-radius:6px;
      font-size:10px;color:#475569;margin-bottom:2px;
    }
    .mock-nav-item.active { background:rgba(99,102,241,.15);color:#818cf8; }
    .mock-nav-dot { width:5px;height:5px;border-radius:50%;background:currentColor; }
    .mock-content { padding:14px; }
    .mock-topbar { display:flex;align-items:center;justify-content:space-between;margin-bottom:12px; }
    .mock-title  { font-size:12px;font-weight:700;color:#94a3b8; }
    .mock-btn    { background:var(--ind);color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:9px;font-weight:600; }
    .mock-kanban { display:grid;grid-template-columns:repeat(4,1fr);gap:8px; }
    .mk-col      { background:#111d35;border-radius:7px;padding:8px; }
    .mk-h        { display:flex;align-items:center;gap:5px;margin-bottom:7px; }
    .mk-d        { width:5px;height:5px;border-radius:50%; }
    .mk-t        { font-size:8px;font-weight:700;color:#64748b;flex:1; }
    .mk-c        { font-size:8px;color:#334155;background:#0c1628;padding:1px 5px;border-radius:6px; }
    .mk-card     { background:#1a2540;border:1px solid #1e3a5f;border-radius:5px;padding:6px 7px;margin-bottom:5px; }
    .mk-card-t   { font-size:8px;font-weight:600;color:#cbd5e1;margin-bottom:3px; }
    .mk-badge    { display:inline-block;font-size:7px;padding:1px 5px;border-radius:8px;font-weight:700; }
    .mk-h-badge  { background:#450a0a;color:#f87171; }
    .mk-m-badge  { background:#451a03;color:#fbbf24; }
    .mk-l-badge  { background:#14532d;color:#4ade80; }
    .mock-avatar { width:14px;height:14px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:6px;font-weight:700;color:#fff; }

    /* ── SÉPARATEUR ── */
    .sep { height:1px; background:linear-gradient(90deg,transparent,rgba(99,102,241,.25),transparent); }

    /* ── STATS ── */
    .stats-s {
      padding:72px 24px; position:relative; overflow:hidden;
      background:linear-gradient(135deg,#0a0e1a 0%,#12174e 50%,#0a0e1a 100%);
    }
    .stats-s::before {
      content:''; position:absolute; inset:0;
      background:radial-gradient(ellipse 80% 60% at 50% 50%,rgba(99,102,241,.12),transparent);
    }
    .stat-item { text-align:center; position:relative; z-index:2; }
    .stat-num {
      font-size:clamp(44px,6vw,68px); font-weight:900; line-height:1;
      background:linear-gradient(135deg,#f1f5f9,#a5b4fc);
      -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
      margin-bottom:8px;
    }
    .stat-suf  { font-size:clamp(28px,4vw,40px); }
    .stat-label{ font-size:13px; color:#64748b; font-weight:500; }

    /* ── SECTION COMMUNE ── */
    .section { padding:96px 24px; }
    .s-dark { background:#080c18; }
    .s-mid  { background:#0c1123; }
    .sec-badge {
      display:inline-block; background:rgba(99,102,241,.1); color:#818cf8;
      font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase;
      padding:5px 14px; border-radius:20px; margin-bottom:14px;
    }
    .sec-title {
      font-size:clamp(28px,4vw,46px); font-weight:900; color:#f1f5f9;
      line-height:1.12; margin-bottom:14px; letter-spacing:-.5px;
    }
    .sec-sub { font-size:17px; color:#475569; max-width:500px; line-height:1.7; margin-bottom:50px; }

    /* ── FEAT CARDS ── */
    .feat-card {
      background:#111d35; border:1px solid rgba(255,255,255,.06);
      border-radius:16px; padding:28px 24px; height:100%;
      position:relative; overflow:hidden;
      transition:transform .25s, border-color .25s, box-shadow .25s;
    }
    .feat-card::after {
      content:''; position:absolute; inset:0;
      background:linear-gradient(135deg,rgba(99,102,241,.06),transparent);
      opacity:0; transition:opacity .3s;
    }
    .feat-card:hover { transform:translateY(-7px); border-color:rgba(99,102,241,.35); box-shadow:0 24px 48px rgba(0,0,0,.4), 0 0 0 1px rgba(99,102,241,.18); }
    .feat-card:hover::after { opacity:1; }
    .feat-icon {
      width:50px;height:50px;border-radius:13px;
      display:flex;align-items:center;justify-content:center;
      font-size:22px;margin-bottom:18px;
    }
    .fi-ind    { background:rgba(99,102,241,.15); color:#818cf8; }
    .fi-green  { background:rgba(22,163,74,.15);  color:#4ade80; }
    .fi-amber  { background:rgba(245,158,11,.15); color:#fbbf24; }
    .fi-red    { background:rgba(239,68,68,.15);  color:#f87171; }
    .fi-teal   { background:rgba(13,148,136,.15); color:#2dd4bf; }
    .fi-purple { background:rgba(139,92,246,.15); color:#c084fc; }
    .feat-title { font-size:17px; font-weight:700; color:#f1f5f9; margin-bottom:8px; }
    .feat-desc  { font-size:14px; color:#475569; line-height:1.65; }

    /* ── TIMELINE HOW IT WORKS ── */
    .timeline-item { display:flex; gap:24px; align-items:flex-start; margin-bottom:40px; }
    .timeline-num  {
      width:44px;height:44px;border-radius:50%;background:var(--ind);
      display:flex;align-items:center;justify-content:center;
      font-size:16px;font-weight:800;color:#fff;flex-shrink:0;
      box-shadow:0 0 20px rgba(99,102,241,.4);
    }
    .timeline-content { flex:1; padding-top:4px; }
    .timeline-title   { font-size:17px;font-weight:700;color:#f1f5f9;margin-bottom:6px; }
    .timeline-desc    { font-size:14px;color:#475569;line-height:1.65; }

    /* ── ROLES ── */
    .role-card {
      background:#0f1a2e; border:1px solid rgba(255,255,255,.06);
      border-radius:14px; padding:24px; height:100%;
      transition:transform .2s, border-color .2s, box-shadow .2s;
    }
    .role-card:hover { transform:translateY(-5px); border-color:rgba(99,102,241,.3); box-shadow:0 20px 40px rgba(0,0,0,.4); }
    .role-avatar { width:46px;height:46px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:#fff;margin-bottom:14px; }
    .role-name   { font-size:16px;font-weight:700;color:#f1f5f9;margin-bottom:4px; }
    .role-desc   { font-size:12px;color:#475569;margin-bottom:14px;line-height:1.5; }
    .perm-line   { font-size:11px;color:#94a3b8;display:flex;align-items:center;gap:7px;margin-bottom:5px; }
    .pyes        { color:#4ade80; }
    .pread       { color:#818cf8; }
    .pno         { color:#334155; }

    /* ── CTA ── */
    .cta-section {
      padding:110px 24px; text-align:center; background:#080c18;
      position:relative; overflow:hidden;
    }
    .cta-section::before {
      content:''; position:absolute; top:50%;left:50%;
      transform:translate(-50%,-50%);
      width:700px;height:700px;border-radius:50%;
      background:radial-gradient(circle,rgba(99,102,241,.12),transparent 70%);
      pointer-events:none;
    }
    .cta-title {
      font-size:clamp(32px,5vw,58px); font-weight:900; color:#f1f5f9;
      line-height:1.1; margin-bottom:18px; letter-spacing:-.5px;
      position:relative; z-index:2;
    }
    .cta-sub {
      font-size:18px; color:#475569; max-width:460px;
      margin:0 auto 40px; line-height:1.65; position:relative; z-index:2;
    }

    /* ── FOOTER ── */
    .lp-footer {
      border-top:1px solid rgba(255,255,255,.06);
      padding:28px 48px;
      display:flex;align-items:center;justify-content:space-between;
      background:#060a14;
    }
    .lp-footer-text { font-size:12px; color:#334155; }

    /* ── SCROLL ANIMATIONS ── */
    .fade-up { opacity:0; transform:translateY(36px); transition:opacity .65s ease, transform .65s ease; }
    .fade-up.visible { opacity:1; transform:translateY(0); }
    .d1 { transition-delay:.1s; } .d2 { transition-delay:.2s; }
    .d3 { transition-delay:.3s; } .d4 { transition-delay:.4s; }

    /* ── MOCKUP SHIMMER ── */
    @keyframes shimmer { 0%{opacity:.6;transform:translateY(0);} 50%{opacity:1;transform:translateY(-3px);} 100%{opacity:.6;transform:translateY(0);} }
    .mk-card { animation:shimmer 3s ease-in-out infinite; }
    .mk-col:nth-child(2) .mk-card { animation-delay:.4s; }
    .mk-col:nth-child(3) .mk-card { animation-delay:.8s; }
    .mk-col:nth-child(4) .mk-card { animation-delay:1.2s; }

    @media(max-width:768px){
      .lp-nav { padding:14px 20px; }
      .hero   { padding:100px 20px 40px; }
      .mockup-body { grid-template-columns:1fr; }
      .mock-sidebar { display:none; }
      .mock-kanban  { grid-template-columns:repeat(2,1fr); }
      .lp-footer    { flex-direction:column; gap:10px; text-align:center; }
    }
  </style>
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
  <a href="/bproject/login.php" class="btn-nav-cta">
    <i class="bi bi-box-arrow-in-right"></i> Se connecter
  </a>
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
      <a href="/bproject/login.php" class="btn-primary-lp">
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
        <div class="mockup-url-bar">localhost/bproject/views/tasks/index.php</div>
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
    <a href="/bproject/login.php" class="btn-primary-lp" style="font-size:16px;padding:17px 44px;">
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
    <span style="font-size:13px;color:#334155;font-weight:600;">B-Project Manager</span>
  </div>
  <div class="lp-footer-text">© <?php echo date('Y'); ?> B-NETWORK. Tous droits réservés.</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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