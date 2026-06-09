<?php
if (!defined('BP_BASE')) {
    require_once dirname(__DIR__, 2) . '/config/app.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? $page_title . ' — B-Project Manager' : 'B-Project Manager'; ?></title>
  <!-- Anti-FOUC : applique le thème AVANT le rendu CSS -->
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
<div class="bp-wrapper">