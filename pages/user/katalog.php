<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
$pageTitle = 'Katalog Barang';
$q = trim($_GET['q'] ?? '');
$kategori = $_GET['kategori'] ?? '';
$cats = $pdo->query('SELECT id, nama_kategori FROM categories ORDER BY nama_kategori')->fetchAll();
$sql = 'SELECT i.*, c.nama_kategori FROM items i LEFT JOIN categories c ON i.category_id=c.id WHERE 1=1';
$params = [];
if ($q !== '') { $sql .= ' AND i.nama_barang LIKE ?'; $params[] = "%$q%"; }
if ($kategori !== '') { $sql .= ' AND i.category_id = ?'; $params[] = $kategori; }
$sql .= ' ORDER BY i.created_at DESC';
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$items = $stmt->fetchAll();
include __DIR__ . '/../../includes/header.php'; include __DIR__ . '/../../includes/navbar.php';
?>
<main class="flex-1 max-w-6xl mx-auto w-full px-4 py-6">
  <h1 class="text-xl font-semibold mb-4">Katalog Barang</h1>
  <form method="get" class="bg-white border rounded-lg p-3 mb-4 space-y-3 md:space-y-0 md:flex md:gap-3 md:items-end">
    <div class="flex-1">
      <label class="block text-sm text-gray-600 mb-1">Cari barang</label>
      <input type="text" name="q" value="<?= e($q) ?>" placeholder="Nama barang..." class="w-full border rounded-lg px-3 py-2 text-sm">
    </div>
    <div class="md:w-48">
      <label class="block text-sm text-gray-600 mb-1">Kategori</label>
      <select name="kategori" class="w-full border rounded-lg px-3 py-2 text-sm">
        <option value="">Semua Kategori</option>
        <?php foreach ($cats as $c): ?>
          <option value="<?= e($c['id']) ?>" <?= (string)$kategori===(string)$c['id']?'selected':'' ?>><?= e($c['nama_kategori']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="w-full md:w-auto bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Cari</button>
  </form>

  <?php if (!$items): ?><p class="text-sm text-gray-500">Belum ada barang.</p><?php endif; ?>
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($items as $it): $avail = (int)$it['tersedia'] > 0; ?>
    <a href="/ebrow/pages/user/detail.php?id=<?= e($it['id']) ?>" class="bg-white border rounded-lg overflow-hidden hover:shadow">
      <div class="aspect-square bg-gray-200 flex items-center justify-center text-gray-400 text-sm overflow-hidden">
        <?php if (!empty($it['foto'])): ?><img src="<?= e($it['foto']) ?>" alt="" class="w-full h-full object-cover"><?php else: ?>Foto<?php endif; ?>
      </div>
      <div class="p-3">
        <h3 class="font-medium text-sm"><?= e($it['nama_barang']) ?></h3>
        <p class="text-xs text-gray-500"><?= e($it['nama_kategori'] ?? 'Tanpa kategori') ?></p>
        <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded <?= $avail?'bg-green-100 text-green-700':'bg-red-100 text-red-700' ?>"><?= $avail?'Tersedia':'Habis' ?></span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
