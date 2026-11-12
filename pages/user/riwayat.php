<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();
$pageTitle = 'Riwayat Peminjaman'; $u = current_user();
$detailId = (int)($_GET['detail'] ?? 0);
$stmt = $pdo->prepare('SELECT l.*, i.nama_barang FROM loans l JOIN items i ON l.item_id=i.id WHERE l.user_id=? ORDER BY l.created_at DESC');
$stmt->execute([$u['id']]); $loans = $stmt->fetchAll();
$detail = null;
if ($detailId) { foreach ($loans as $l) { if ((int)$l['id']===$detailId) { $detail=$l; break; } } }
$labels = ['menunggu'=>'bg-yellow-100 text-yellow-700','disetujui'=>'bg-blue-100 text-blue-700','ditolak'=>'bg-red-100 text-red-700','dipinjam'=>'bg-indigo-100 text-indigo-700','dikembalikan'=>'bg-teal-100 text-teal-700','selesai'=>'bg-green-100 text-green-700'];
include __DIR__ . '/../../includes/header.php'; include __DIR__ . '/../../includes/navbar.php';
?>
<main class="flex-1 max-w-6xl mx-auto w-full px-4 py-6">
  <h1 class="text-xl font-semibold mb-4">Riwayat Peminjaman</h1>
  <?php if (isset($_GET['success'])): ?><div class="mb-3 text-sm text-green-700 bg-green-50 p-2 rounded">Pengajuan berhasil dikirim.</div><?php endif; ?>

  <?php if ($detail): ?>
  <div class="bg-white border rounded-lg p-4 mb-4">
    <h2 class="font-medium mb-2">Detail Peminjaman #<?= e($detail['id']) ?></h2>
    <dl class="text-sm grid grid-cols-1 sm:grid-cols-2 gap-2">
      <div><dt class="text-gray-500">Barang</dt><dd><?= e($detail['nama_barang']) ?></dd></div>
      <div><dt class="text-gray-500">Jumlah</dt><dd><?= e($detail['jumlah']) ?></dd></div>
      <div><dt class="text-gray-500">Tanggal Pinjam</dt><dd><?= e($detail['tanggal_pinjam']) ?></dd></div>
      <div><dt class="text-gray-500">Tanggal Kembali</dt><dd><?= e($detail['tanggal_kembali']) ?></dd></div>
      <div><dt class="text-gray-500">Status</dt><dd><span class="text-xs px-2 py-0.5 rounded <?= $labels[$detail['status']] ?? 'bg-gray-100' ?>"><?= e($detail['status']) ?></span></dd></div>
      <div class="sm:col-span-2"><dt class="text-gray-500">Keperluan</dt><dd><?= nl2br(e($detail['keperluan'] ?? '-')) ?></dd></div>
      <div class="sm:col-span-2"><dt class="text-gray-500">Catatan Admin</dt><dd><?= nl2br(e($detail['catatan_admin'] ?? '-')) ?></dd></div>
    </dl>
    <a href="/ebrow/pages/user/riwayat.php" class="inline-block mt-3 text-sm text-blue-600">Tutup detail</a>
  </div>
  <?php endif; ?>

  <div class="space-y-3">
    <?php if (!$loans): ?><p class="text-sm text-gray-500">Belum ada riwayat peminjaman.</p><?php endif; ?>
    <?php foreach ($loans as $l): ?>
    <div class="bg-white border rounded-lg p-4">
      <div class="flex items-start justify-between gap-3">
        <div>
          <h3 class="font-medium"><?= e($l['nama_barang']) ?></h3>
          <p class="text-sm text-gray-500">Pinjam: <?= e($l['tanggal_pinjam']) ?> &middot; Kembali: <?= e($l['tanggal_kembali']) ?></p>
          <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded <?= $labels[$l['status']] ?? 'bg-gray-100' ?>"><?= e($l['status']) ?></span>
        </div>
        <a href="/ebrow/pages/user/riwayat.php?detail=<?= e($l['id']) ?>" class="text-sm text-blue-600">Detail</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
