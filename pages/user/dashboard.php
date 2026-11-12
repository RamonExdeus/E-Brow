<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();
$pageTitle = 'Dashboard & Profil'; $u = current_user();
$cnt = $pdo->prepare('SELECT COUNT(*) total, SUM(status IN ("menunggu","disetujui","dipinjam")) aktif, SUM(status="selesai") selesai FROM loans WHERE user_id=?');
$cnt->execute([$u['id']]); $sum = $cnt->fetch() ?: ['total'=>0,'aktif'=>0,'selesai'=>0];
$recent = $pdo->prepare('SELECT l.*, i.nama_barang FROM loans l JOIN items i ON l.item_id=i.id WHERE l.user_id=? ORDER BY l.created_at DESC LIMIT 5');
$recent->execute([$u['id']]); $rows = $recent->fetchAll();
$labels = ['menunggu'=>'bg-yellow-100 text-yellow-700','disetujui'=>'bg-blue-100 text-blue-700','ditolak'=>'bg-red-100 text-red-700','dipinjam'=>'bg-indigo-100 text-indigo-700','dikembalikan'=>'bg-teal-100 text-teal-700','selesai'=>'bg-green-100 text-green-700'];
$edit = isset($_GET['edit']);
include __DIR__ . '/../../includes/header.php'; include __DIR__ . '/../../includes/navbar.php';
?>
<main class="flex-1 max-w-6xl mx-auto w-full px-4 py-6">
  <h1 class="text-xl font-semibold mb-4">Dashboard & Profil Pengguna</h1>
  <?php if (isset($_GET['success'])): ?><div class="mb-3 text-sm text-green-700 bg-green-50 p-2 rounded">Profil diperbarui.</div><?php endif; ?>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="md:col-span-1 bg-white border rounded-lg p-4">
      <h2 class="font-medium mb-3">Informasi Akun</h2>
      <?php if ($edit): ?>
      <form method="post" action="/ebrow/actions/profile_action.php" class="space-y-3 text-sm">
        <div><label class="block text-gray-500 mb-1">Nama</label><input name="nama" value="<?= e($u['nama']) ?>" class="w-full border rounded px-3 py-2" required></div>
        <div><label class="block text-gray-500 mb-1">Email</label><input value="<?= e($u['email']) ?>" class="w-full border rounded px-3 py-2 bg-gray-100" disabled></div>
        <div><label class="block text-gray-500 mb-1">No. HP</label><input name="no_hp" value="<?= e($u['no_hp'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
        <div><label class="block text-gray-500 mb-1">Alamat</label><textarea name="alamat" class="w-full border rounded px-3 py-2" rows="2"><?= e($u['alamat'] ?? '') ?></textarea></div>
        <div class="flex gap-2"><button class="flex-1 bg-blue-600 text-white py-2 rounded">Simpan</button><a href="/ebrow/pages/user/dashboard.php" class="flex-1 text-center border py-2 rounded">Batal</a></div>
      </form>
      <?php else: ?>
      <dl class="text-sm space-y-2">
        <div><dt class="text-gray-500">Nama</dt><dd class="font-medium"><?= e($u['nama']) ?></dd></div>
        <div><dt class="text-gray-500">Email</dt><dd><?= e($u['email']) ?></dd></div>
        <div><dt class="text-gray-500">No. HP</dt><dd><?= e($u['no_hp'] ?? '-') ?></dd></div>
        <div><dt class="text-gray-500">Alamat</dt><dd><?= e($u['alamat'] ?? '-') ?></dd></div>
      </dl>
      <a href="/ebrow/pages/user/dashboard.php?edit=1" class="block mt-4 text-center w-full border border-blue-600 text-blue-600 py-2 rounded-lg text-sm">Edit Profil</a>
      <?php endif; ?>
    </div>
    <div class="md:col-span-2 space-y-4">
      <div class="bg-white border rounded-lg p-4">
        <h2 class="font-medium mb-2">Ringkasan Peminjaman</h2>
        <div class="grid grid-cols-3 gap-3 text-center text-sm">
          <div class="bg-gray-50 rounded p-3"><div class="text-lg font-semibold"><?= (int)$sum['total'] ?></div><div class="text-gray-500">Total</div></div>
          <div class="bg-gray-50 rounded p-3"><div class="text-lg font-semibold"><?= (int)$sum['aktif'] ?></div><div class="text-gray-500">Aktif</div></div>
          <div class="bg-gray-50 rounded p-3"><div class="text-lg font-semibold"><?= (int)$sum['selesai'] ?></div><div class="text-gray-500">Selesai</div></div>
        </div>
      </div>
      <div class="bg-white border rounded-lg p-4">
        <div class="flex justify-between items-center mb-2">
          <h2 class="font-medium">Aktivitas Peminjaman Terbaru</h2>
          <a href="/ebrow/pages/user/riwayat.php" class="text-sm text-blue-600">Lihat riwayat</a>
        </div>
        <?php if (!$rows): ?><p class="text-sm text-gray-500">Belum ada aktivitas.</p><?php else: ?>
        <ul class="space-y-2 text-sm"><?php foreach ($rows as $r): ?><li class="flex justify-between"><span><?= e($r['nama_barang']) ?></span><span class="text-xs px-2 py-0.5 rounded <?= $labels[$r['status']] ?? '' ?>"><?= e($r['status']) ?></span></li><?php endforeach; ?></ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
