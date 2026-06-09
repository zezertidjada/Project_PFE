<?php
session_start();
require_once __DIR__ . '/config/app.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . bp_url('dashboard.php'));
} else {
    header('Location: ' . bp_url('landing.php'));
}
exit();
