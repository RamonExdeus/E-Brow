<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();
$pageTitle = 'Form Peminjaman';
$selected = (int)($_GET['id'] ?? 0);
$items = $pdo->query('SELECT id, nama_barang, tersedia FROM items WHERE tersedia > 0 ORDER BY nama_barang')->fetchAll();
$error = $_GET['error'] ?? '';
include __DIR__ . '/../../includes/header.php'; include __DIR__ . '/../../includes/navbar.php';
?>
<main class="flex-1 max-w-xl mx-auto w-full px-4 py-6">
  <h1 class="text-xl font-semibold mb-4">Form Peminjaman</h1>
  <?php if ($error==='empty'): ?><div class="mb-3 text-sm text-red-700 bg-red-50 p-2 rounded">Lengkapi semua field wajib.</div><?php endif; ?>
  <?php if ($error==='date'): ?><div class="mb-3 text-sm text-red-700 bg-red-50 p-2 rounded">Tanggal kembali harus setelah tanggal pinjam.</div><?php endif; ?>
  <?php if ($error==='stok'): ?><div class="mb-3 text-sm text-red-700 bg-red-50 p-2 rounded">Stok tidak mencukupi.</div><?php endif; ?>
  <form method="post" action="/ebrow/actions/loan_action.php" class="bg-white border rounded-lg p-4 space-y-4">
    <input type="hidden" name="action" value="create">
    <div>
      <label class="block text-sm mb-1">Barang</label>
      <select name="item_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
        <option value="">Pilih barang...</option>
        <?php foreach ($items as $it): ?>
          <option value="<?= e($it['id']) ?>" <?= $selected===(int)$it['id']?'selected':'' ?>><?= e($it['nama_barang']) ?> (tersedia: <?= e($it['tersedia']) ?>)</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div><label class="block text-sm mb-1">Tanggal Peminjaman</label><input type="date" name="tanggal_pinjam" class="w-full border rounded-lg px-3 py-2 text-sm" required></div>
      <div><label class="block text-sm mb-1">Tanggal Pengembalian</label><input type="date" name="tanggal_kembali" class="w-full border rounded-lg px-3 py-2 text-sm" required></div>
    </div>
    <div><label class="block text-sm mb-1">Jumlah Barang</label><input type="number" name="jumlah" min="1" value="1" class="w-full border rounded-lg px-3 py-2 text-sm" required></div>
    <div><label class="block text-sm mb-1">Keperluan Peminjaman</label><textarea name="keperluan" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Jelaskan keperluan..."></textarea></div>
    <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium">Kirim Pengajuan</button>
  </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
