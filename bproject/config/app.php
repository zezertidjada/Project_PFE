<?php
/**
 * Chemin de base de l'application (détecté automatiquement).
 * Ex. /Project_PFE/bproject ou /bproject selon l'installation XAMPP.
 */
if (!defined('BP_BASE')) {
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    $appRoot = str_replace('\\', '/', realpath(dirname(__FILE__) . '/..'));
    $relative = substr($appRoot, strlen($docRoot));
    $relative = '/' . trim($relative, '/');
    define('BP_BASE', $relative === '/' ? '' : $relative);
}

if (!function_exists('bp_url')) {
    function bp_url(string $path = ''): string
    {
        $path = ltrim($path, '/');
        return BP_BASE . ($path !== '' ? '/' . $path : '');
    }

    function bp_asset(string $path): string
    {
        return bp_url('assets/' . ltrim($path, '/'));
    }
}
