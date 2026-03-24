<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$isAdminUser = isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@admin.com';

if (!$isAdminUser) {
    header("Location: ../home.php");
    exit;
}
?>
