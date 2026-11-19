<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
$action = $_POST['action'] ?? $_GET['action'] ?? '';
if (!$id) { header('Location: /ebrow/pages/admin/peminjaman.php'); exit; }

$loan = db_one('SELECT * FROM loans WHERE id=?', 'i', [$id]);
if (!$loan) { header('Location: /ebrow/pages/admin/peminjaman.php'); exit; }

function adjust_stock($item_id, $delta) {
    db_exec('UPDATE items SET tersedia = GREATEST(0, tersedia + ?) WHERE id=?', 'ii', [$delta, $item_id]);
}

$newStatus = $loan['status'];
$note = trim($_POST['catatan_admin'] ?? ($loan['catatan_admin'] ?? ''));

if ($action === 'approve') $newStatus = 'disetujui';
elseif ($action === 'reject') $newStatus = 'ditolak';
elseif ($action === 'update') $newStatus = $_POST['status'] ?? $loan['status'];

$old = $loan['status'];
if ($newStatus !== $old) {
    if ($old === 'menunggu' && $newStatus === 'disetujui') {
        adjust_stock($loan['item_id'], - (int)$loan['jumlah']);
    }
    if ($old === 'disetujui' && $newStatus === 'ditolak') {
        adjust_stock($loan['item_id'], (int)$loan['jumlah']);
    }
    if (in_array($old, ['disetujui','dipinjam'], true) && in_array($newStatus, ['dikembalikan','selesai'], true)) {
        adjust_stock($loan['item_id'], (int)$loan['jumlah']);
    }
}

db_exec('UPDATE loans SET status=?, catatan_admin=? WHERE id=?', 'ssi', [$newStatus, $note, $id]);
header('Location: /ebrow/pages/admin/peminjaman.php?view='.$id.'&success=1'); exit;
