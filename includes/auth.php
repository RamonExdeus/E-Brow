<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function current_user() {
    return $_SESSION['user'] ?? null;
}
function is_logged_in() {
    return isset($_SESSION['user']);
}
function is_admin() {
    return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
}
function require_login() {
    if (!is_logged_in()) {
        header('Location: /ebrow/pages/user/auth.php?msg=login_required');
        exit;
    }
}
function require_admin() {
    if (!is_admin()) {
        header('Location: /ebrow/pages/user/auth.php?msg=forbidden');
        exit;
    }
}
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}
