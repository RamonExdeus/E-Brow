<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'register') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($nama === '' || $email === '' || $password === '') {
        header('Location: /ebrow/pages/user/auth.php?tab=register&error=empty'); exit;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare('INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, "user")');
        $stmt->execute([$nama, $email, $hash]);
        header('Location: /ebrow/pages/user/auth.php?success=registered'); exit;
    } catch (PDOException $e) {
        header('Location: /ebrow/pages/user/auth.php?tab=register&error=exists'); exit;
    }
}

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, nama, email, password, role, no_hp, alamat FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']);
        $_SESSION['user'] = $user;
        if ($user['role'] === 'admin') {
            header('Location: /ebrow/pages/admin/dashboard.php'); exit;
        }
        header('Location: /ebrow/pages/user/dashboard.php'); exit;
    }
    header('Location: /ebrow/pages/user/auth.php?error=invalid'); exit;
}

if ($action === 'logout') {
    $_SESSION = [];
    session_destroy();
    header('Location: /ebrow/index.php'); exit;
}

header('Location: /ebrow/index.php');
