<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin();
$pageTitle = 'Kelola Barang';
$cats = $pdo->query('SELECT * FROM categories ORDER BY nama_kategori')->fetchAll();
$editId = (int)($_GET['edit'] ?? 0);
$editItem = null;
if ($editId) { $s=$pdo->prepare('SELECT * FROM items WHERE id=?'); $s->execute([$editId]); $editItem=$s->fetch(); }
$items = $pdo->query('SELECT i.*, c.nama_kategori FROM items i LEFT JOIN categories c ON i.category_id=c.id ORDER BY i.created_at DESC')->fetchAll();
include __DIR__ . '/../../includes/header.php'; include __DIR__ . '/../../includes/navbar_admin.php';
?>
<main class="flex-1 max-w-6xl mx-auto w-full px-4 py-6 space-y-6">
  <h1 class="text-xl font-semibold">Kelola Barang (CRUD Barang dan Kategori)</h1>
  <?php if (isset($_GET['success'])): ?><div class="text-sm text-green-700 bg-green-50 p-2 rounded">Perubahan berhasil disimpan.</div><?php endif; ?>
  <?php if (isset($_GET['error'])): ?><div class="text-sm text-red-700 bg-red-50 p-2 rounded">Data tidak valid.</div><?php endif; ?>

  <section class="bg-white border rounded-lg p-4">
    <h2 class="font-medium mb-3"><?= $editItem?'Edit Barang':'Tambah Barang' ?></h2>
    <form method="post" action="/ebrow/actions/admin_barang_action.php" class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
      <input type="hidden" name="action" value="save_item">
      <input type="hidden" name="id" value="<?= e($editItem['id'] ?? 0) ?>">
      <div><label class="block mb-1">Nama Barang</label><input name="nama_barang" value="<?= e($editItem['nama_barang'] ?? '') ?>" class="w-full border rounded px-3 py-2" required></div>
      <div><label class="block mb-1">Kategori</label>
        <select name="category_id" class="w-full border rounded px-3 py-2">
          <option value="">Pilih kategori</option>
          <?php foreach ($cats as $c): ?><option value="<?= e($c['id']) ?>" <?= isset($editItem['category_id']) && (string)$editItem['category_id']===(string)$c['id']?'selected':'' ?>><?= e($c['nama_kategori']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="md:col-span-2"><label class="block mb-1">Deskripsi</label><textarea name="deskripsi" class="w-full border rounded px-3 py-2" rows="2"><?= e($editItem['deskripsi'] ?? '') ?></textarea></div>
      <div><label class="block mb-1">Kondisi</label><input name="kondisi" value="<?= e($editItem['kondisi'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
      <div><label class="block mb-1">Stok</label><input type="number" name="stok" value="<?= e($editItem['stok'] ?? 0) ?>" class="w-full border rounded px-3 py-2"></div>
      <div><label class="block mb-1">Tersedia</label><input type="number" name="tersedia" value="<?= e($editItem['tersedia'] ?? 0) ?>" class="w-full border rounded px-3 py-2"></div>
      <div><label class="block mb-1">Foto (URL/Path)</label><input name="foto" value="<?= e($editItem['foto'] ?? '') ?>" class="w-full border rounded px-3 py-2"></div>
      <div class="md:col-span-2 flex gap-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Barang</button>
        <?php if ($editItem): ?><a href="/ebrow/pages/admin/barang.php" class="border px-4 py-2 rounded">Batal</a><?php endif; ?>
      </div>
    </form>
  </section>

  <section class="bg-white border rounded-lg p-4">
    <h2 class="font-medium mb-3">Kelola Kategori</h2>
    <form method="post" action="/ebrow/actions/admin_barang_action.php" class="flex flex-col sm:flex-row gap-2 mb-3 text-sm">
      <input type="hidden" name="action" value="save_category">
      <input name="nama_kategori" placeholder="Nama kategori" class="flex-1 border rounded px-3 py-2" required>
      <button class="bg-slate-800 text-white px-4 py-2 rounded">Tambah Kategori</button>
    </form>
    <ul class="text-sm text-gray-700 space-y-1">
      <?php if (!$cats): ?><li class="text-gray-500">Belum ada kategori.</li><?php endif; ?>
      <?php foreach ($cats as $c): ?><li class="flex justify-between items-center border-b py-1"><span><?= e($c['nama_kategori']) ?></span><a class="text-red-600 text-xs" href="/ebrow/actions/admin_barang_action.php?action=delete_category&id=<?= e($c['id']) ?>" onclick="return confirm('Hapus kategori?')">Hapus</a></li><?php endforeach; ?>
    </ul>
  </section>

  <section class="bg-white border rounded-lg p-4">
    <h2 class="font-medium mb-3">Daftar Barang</h2>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-left text-gray-500 border-b"><tr><th class="py-2">Nama</th><th class="py-2">Kategori</th><th class="py-2">Stok</th><th class="py-2">Tersedia</th><th class="py-2">Aksi</th></tr></thead>
        <tbody>
          <?php if (!$items): ?><tr><td class="py-2" colspan="5">Belum ada data barang.</td></tr><?php endif; ?>
          <?php foreach ($items as $it): ?>
          <tr class="border-b last:border-0">
            <td class="py-2"><?= e($it['nama_barang']) ?></td>
            <td><?= e($it['nama_kategori'] ?? '-') ?></td>
            <td><?= e($it['stok']) ?></td>
            <td><?= e($it['tersedia']) ?></td>
            <td class="space-x-2 whitespace-nowrap"><a class="text-blue-600" href="/ebrow/pages/admin/barang.php?edit=<?= e($it['id']) ?>">Edit</a><a class="text-red-600" href="/ebrow/actions/admin_barang_action.php?action=delete_item&id=<?= e($it['id']) ?>" onclick="return confirm('Hapus barang?')">Hapus</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
