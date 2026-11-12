<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
$action = $_POST['action'] ?? $_GET['action'] ?? '';
if (!$id) { header('Location: /ebrow/pages/admin/peminjaman.php'); exit; }

$stmt = $pdo->prepare('SELECT * FROM loans WHERE id=?');
$stmt->execute([$id]); $loan = $stmt->fetch();
if (!$loan) { header('Location: /ebrow/pages/admin/peminjaman.php'); exit; }

function adjust_stock($pdo, $item_id, $delta) {
    $pdo->prepare('UPDATE items SET tersedia = GREATEST(0, tersedia + ?) WHERE id=?')->execute([$delta, $item_id]);
}

$newStatus = $loan['status'];
$note = trim($_POST['catatan_admin'] ?? $loan['catatan_admin'] ?? '');

if ($action === 'approve') $newStatus = 'disetujui';
elseif ($action === 'reject') $newStatus = 'ditolak';
elseif ($action === 'update') $newStatus = $_POST['status'] ?? $loan['status'];

$old = $loan['status'];
if ($newStatus !== $old) {
    // reserve on approval
    if ($old === 'menunggu' && $newStatus === 'disetujui') {
        adjust_stock($pdo, $loan['item_id'], - (int)$loan['jumlah']);
    }
    // reject before taken: release if was approved (reserved)
    if ($old === 'disetujui' && $newStatus === 'ditolak') {
        adjust_stock($pdo, $loan['item_id'], (int)$loan['jumlah']);
    }
    // mark as borrowed (no stock change if already reserved)
    // return/finish: release stock
    if (in_array($old, ['disetujui','dipinjam'], true) && in_array($newStatus, ['dikembalikan','selesai'], true)) {
        adjust_stock($pdo, $loan['item_id'], (int)$loan['jumlah']);
    }
    // if moved back to disetujui from dipinjam no change
}

$up = $pdo->prepare('UPDATE loans SET status=?, catatan_admin=? WHERE id=?');
$up->execute([$newStatus, $note, $id]);
header('Location: /ebrow/pages/admin/peminjaman.php?view='.$id.'&success=1'); exit;
