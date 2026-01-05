<?php
session_start();
$action = $_GET['action'] ?? '';
if ($action === 'login') {
    $_SESSION['is_admin'] = true;
} elseif ($action === 'logout') {
    unset($_SESSION['is_admin']);
}
$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $redirect);
exit;
