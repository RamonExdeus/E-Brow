<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
$u = current_user();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    if ($nama === '') { header('Location: /ebrow/pages/user/dashboard.php?error=empty'); exit; }
    $stmt = $pdo->prepare('UPDATE users SET nama=?, no_hp=?, alamat=? WHERE id=?');
    $stmt->execute([$nama,$no_hp,$alamat,$u['id']]);
    $_SESSION['user']['nama'] = $nama;
    $_SESSION['user']['no_hp'] = $no_hp;
    $_SESSION['user']['alamat'] = $alamat;
    header('Location: /ebrow/pages/user/dashboard.php?success=updated'); exit;
}
header('Location: /ebrow/pages/user/dashboard.php');
