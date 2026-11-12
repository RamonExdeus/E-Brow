<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin();
$pageTitle = 'Dashboard Admin';
$items = (int)$pdo->query('SELECT COUNT(*) FROM items')->fetchColumn();
$users = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$loans = (int)$pdo->query('SELECT COUNT(*) FROM loans')->fetchColumn();
$recent = $pdo->query("SELECT l.*, u.nama AS peminjam, i.nama_barang FROM loans l JOIN users u ON l.user_id=u.id JOIN items i ON l.item_id=i.id ORDER BY l.created_at DESC LIMIT 8")->fetchAll();
$labels = ['menunggu'=>'bg-yellow-100 text-yellow-700','disetujui'=>'bg-blue-100 text-blue-700','ditolak'=>'bg-red-100 text-red-700','dipinjam'=>'bg-indigo-100 text-indigo-700','dikembalikan'=>'bg-teal-100 text-teal-700','selesai'=>'bg-green-100 text-green-700'];
include __DIR__ . '/../../includes/header.php'; include __DIR__ . '/../../includes/navbar_admin.php';
?>
<main class="flex-1 max-w-6xl mx-auto w-full px-4 py-6">
  <h1 class="text-xl font-semibold mb-4">Dashboard Admin</h1>
  <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border rounded-lg p-4"><p class="text-sm text-gray-500">Jumlah Barang</p><p class="text-2xl font-semibold"><?= $items ?></p></div>
    <div class="bg-white border rounded-lg p-4"><p class="text-sm text-gray-500">Pengguna Terdaftar</p><p class="text-2xl font-semibold"><?= $users ?></p></div>
    <div class="bg-white border rounded-lg p-4 col-span-2 md:col-span-1"><p class="text-sm text-gray-500">Jumlah Peminjaman</p><p class="text-2xl font-semibold"><?= $loans ?></p></div>
  </div>
  <section class="bg-white border rounded-lg p-4">
    <h2 class="font-medium mb-3">Pengajuan Terbaru</h2>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-left text-gray-500 border-b"><tr><th class="py-2">Peminjam</th><th class="py-2">Barang</th><th class="py-2">Tanggal</th><th class="py-2">Status</th></tr></thead>
        <tbody>
          <?php if (!$recent): ?><tr><td class="py-2" colspan="4">Belum ada pengajuan.</td></tr><?php endif; ?>
          <?php foreach ($recent as $r): ?>
          <tr class="border-b last:border-0"><td class="py-2"><?= e($r['peminjam']) ?></td><td><?= e($r['nama_barang']) ?></td><td><?= e($r['tanggal_pinjam']) ?></td><td><span class="text-xs px-2 py-0.5 rounded <?= $labels[$r['status']] ?? '' ?>"><?= e($r['status']) ?></span></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
