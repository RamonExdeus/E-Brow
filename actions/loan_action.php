<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
$u = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $item_id = (int)($_POST['item_id'] ?? 0);
    $tgl_pinjam = $_POST['tanggal_pinjam'] ?? '';
    $tgl_kembali = $_POST['tanggal_kembali'] ?? '';
    $jumlah = max(1, (int)($_POST['jumlah'] ?? 1));
    $keperluan = trim($_POST['keperluan'] ?? '');
    if (!$item_id || !$tgl_pinjam || !$tgl_kembali) {
        header('Location: /ebrow/pages/user/peminjaman.php?id='.$item_id.'&error=empty'); exit;
    }
    if ($tgl_kembali < $tgl_pinjam) {
        header('Location: /ebrow/pages/user/peminjaman.php?id='.$item_id.'&error=date'); exit;
    }
    $stmt = $pdo->prepare('SELECT tersedia FROM items WHERE id=?');
    $stmt->execute([$item_id]); $item = $stmt->fetch();
    if (!$item || (int)$item['tersedia'] < $jumlah) {
        header('Location: /ebrow/pages/user/peminjaman.php?id='.$item_id.'&error=stok'); exit;
    }
    $ins = $pdo->prepare('INSERT INTO loans (user_id,item_id,tanggal_pinjam,tanggal_kembali,jumlah,keperluan,status) VALUES (?,?,?,?,?,?,"menunggu")');
    $ins->execute([$u['id'],$item_id,$tgl_pinjam,$tgl_kembali,$jumlah,$keperluan]);
    header('Location: /ebrow/pages/user/riwayat.php?success=created'); exit;
}
header('Location: /ebrow/pages/user/riwayat.php');
