<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'save_item') {
    $id = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama_barang'] ?? '');
    $cat = ($_POST['category_id'] ?? '') !== '' ? (int)$_POST['category_id'] : null;
    $desk = trim($_POST['deskripsi'] ?? '');
    $kond = trim($_POST['kondisi'] ?? '');
    $stok = max(0, (int)($_POST['stok'] ?? 0));
    $ters = max(0, (int)($_POST['tersedia'] ?? 0));
    $foto = trim($_POST['foto'] ?? '');
    if ($nama === '') { header('Location: /ebrow/pages/admin/barang.php?error=empty'); exit; }
    if ($id > 0) {
        db_exec('UPDATE items SET category_id=?, nama_barang=?, deskripsi=?, kondisi=?, stok=?, tersedia=?, foto=? WHERE id=?', 'isssiisi', [$cat, $nama, $desk, $kond, $stok, $ters, $foto, $id]);
    } else {
        db_exec('INSERT INTO items (category_id,nama_barang,deskripsi,kondisi,stok,tersedia,foto) VALUES (?,?,?,?,?,?,?)', 'isssiis', [$cat, $nama, $desk, $kond, $stok, $ters, $foto]);
    }
    header('Location: /ebrow/pages/admin/barang.php?success=item'); exit;
}
if ($action === 'delete_item') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id) { db_exec('DELETE FROM items WHERE id=?', 'i', [$id]); }
    header('Location: /ebrow/pages/admin/barang.php?success=deleted'); exit;
}
if ($action === 'save_category') {
    $nama = trim($_POST['nama_kategori'] ?? '');
    if ($nama !== '') { db_exec('INSERT INTO categories (nama_kategori) VALUES (?)', 's', [$nama]); }
    header('Location: /ebrow/pages/admin/barang.php?success=cat'); exit;
}
if ($action === 'delete_category') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id) { db_exec('DELETE FROM categories WHERE id=?', 'i', [$id]); }
    header('Location: /ebrow/pages/admin/barang.php?success=catdel'); exit;
}
header('Location: /ebrow/pages/admin/barang.php');
