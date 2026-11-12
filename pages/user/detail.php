<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT i.*, c.nama_kategori FROM items i LEFT JOIN categories c ON i.category_id=c.id WHERE i.id=?');
$stmt->execute([$id]); $item = $stmt->fetch();
if (!$item) { http_response_code(404); $pageTitle='Barang tidak ditemukan'; include __DIR__.'/../../includes/header.php'; include __DIR__.'/../../includes/navbar.php'; echo '<main class="p-6">Barang tidak ditemukan.</main>'; include __DIR__.'/../../includes/footer.php'; exit; }
$pageTitle = 'Detail Barang'; $avail = (int)$item['tersedia'] > 0;
include __DIR__ . '/../../includes/header.php'; include __DIR__ . '/../../includes/navbar.php';
?>
<main class="flex-1 max-w-6xl mx-auto w-full px-4 py-6">
  <a href="/ebrow/pages/user/katalog.php" class="text-sm text-blue-600">&larr; Kembali ke katalog</a>
  <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 overflow-hidden">
      <?php if (!empty($item['foto'])): ?><img src="<?= e($item['foto']) ?>" alt="" class="w-full h-full object-cover"><?php else: ?>Foto Barang<?php endif; ?>
    </div>
    <div class="bg-white border rounded-lg p-4">
      <h1 class="text-xl font-semibold"><?= e($item['nama_barang']) ?></h1>
      <p class="text-sm text-gray-500 mb-2">Kategori: <?= e($item['nama_kategori'] ?? '-') ?></p>
      <span class="inline-block text-xs px-2 py-1 rounded <?= $avail?'bg-green-100 text-green-700':'bg-red-100 text-red-700' ?> mb-4"><?= $avail?'Tersedia':'Habis' ?> (Stok: <?= e($item['tersedia']) ?>)</span>
      <h2 class="font-medium mb-1">Deskripsi</h2>
      <p class="text-sm text-gray-600 mb-3"><?= nl2br(e($item['deskripsi'] ?? '-')) ?></p>
      <h2 class="font-medium mb-1">Kondisi</h2>
      <p class="text-sm text-gray-600 mb-4"><?= e($item['kondisi'] ?? '-') ?></p>
      <?php if ($avail): ?>
        <a href="/ebrow/pages/user/peminjaman.php?id=<?= e($item['id']) ?>" class="block text-center w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg font-medium">Ajukan Peminjaman</a>
      <?php else: ?>
        <button disabled class="block w-full bg-gray-300 text-gray-600 px-4 py-2.5 rounded-lg font-medium">Tidak Tersedia</button>
      <?php endif; ?>
    </div>
  </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
