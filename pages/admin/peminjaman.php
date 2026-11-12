<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin();
$pageTitle = 'Kelola Peminjaman';
$viewId = (int)($_GET['view'] ?? 0);
$loans = $pdo->query("SELECT l.*, u.nama AS peminjam, u.email, i.nama_barang FROM loans l JOIN users u ON l.user_id=u.id JOIN items i ON l.item_id=i.id ORDER BY l.created_at DESC")->fetchAll();
$view = null; if ($viewId) { $s=$pdo->prepare("SELECT l.*, u.nama AS peminjam, u.email, u.no_hp, i.nama_barang FROM loans l JOIN users u ON l.user_id=u.id JOIN items i ON l.item_id=i.id WHERE l.id=?"); $s->execute([$viewId]); $view=$s->fetch(); }
$labels = ['menunggu'=>'bg-yellow-100 text-yellow-700','disetujui'=>'bg-blue-100 text-blue-700','ditolak'=>'bg-red-100 text-red-700','dipinjam'=>'bg-indigo-100 text-indigo-700','dikembalikan'=>'bg-teal-100 text-teal-700','selesai'=>'bg-green-100 text-green-700'];
$statuses = ['menunggu','disetujui','ditolak','dipinjam','dikembalikan','selesai'];
include __DIR__ . '/../../includes/header.php'; include __DIR__ . '/../../includes/navbar_admin.php';
?>
<main class="flex-1 max-w-6xl mx-auto w-full px-4 py-6 space-y-6">
  <h1 class="text-xl font-semibold">Kelola Peminjaman</h1>
  <?php if (isset($_GET['success'])): ?><div class="text-sm text-green-700 bg-green-50 p-2 rounded">Status peminjaman diperbarui.</div><?php endif; ?>

  <?php if ($view): ?>
  <section class="bg-white border rounded-lg p-4">
    <h2 class="font-medium mb-3">Detail Peminjaman #<?= e($view['id']) ?></h2>
    <dl class="text-sm grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
      <div><dt class="text-gray-500">Peminjam</dt><dd><?= e($view['peminjam']) ?> (<?= e($view['email']) ?>, <?= e($view['no_hp'] ?? '-') ?>)</dd></div>
      <div><dt class="text-gray-500">Barang</dt><dd><?= e($view['nama_barang']) ?> (jumlah: <?= e($view['jumlah']) ?>)</dd></div>
      <div><dt class="text-gray-500">Tgl Pinjam</dt><dd><?= e($view['tanggal_pinjam']) ?></dd></div>
      <div><dt class="text-gray-500">Tgl Kembali</dt><dd><?= e($view['tanggal_kembali']) ?></dd></div>
      <div class="sm:col-span-2"><dt class="text-gray-500">Keperluan</dt><dd><?= nl2br(e($view['keperluan'] ?? '-')) ?></dd></div>
      <div><dt class="text-gray-500">Status Saat Ini</dt><dd><span class="text-xs px-2 py-0.5 rounded <?= $labels[$view['status']] ?? '' ?>"><?= e($view['status']) ?></span></dd></div>
    </dl>
    <form method="post" action="/ebrow/actions/admin_peminjaman_action.php" class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm border-t pt-3">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= e($view['id']) ?>">
      <div><label class="block mb-1">Perbarui Status</label>
        <select name="status" class="w-full border rounded px-3 py-2"><?php foreach ($statuses as $st): ?><option value="<?= $st ?>" <?= $view['status']===$st?'selected':'' ?>><?= $st ?></option><?php endforeach; ?></select>
      </div>
      <div class="md:col-span-2"><label class="block mb-1">Catatan Admin / Status Pengembalian</label><textarea name="catatan_admin" class="w-full border rounded px-3 py-2" rows="2"><?= e($view['catatan_admin'] ?? '') ?></textarea></div>
      <div class="flex gap-2 flex-wrap">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
        <a href="/ebrow/actions/admin_peminjaman_action.php?action=approve&id=<?= e($view['id']) ?>" class="bg-green-600 text-white px-4 py-2 rounded" onclick="return confirm('Setujui?')">Setujui</a>
        <a href="/ebrow/actions/admin_peminjaman_action.php?action=reject&id=<?= e($view['id']) ?>" class="bg-red-600 text-white px-4 py-2 rounded" onclick="return confirm('Tolak?')">Tolak</a>
      </div>
    </form>
  </section>
  <?php endif; ?>

  <section class="bg-white border rounded-lg p-4">
    <h2 class="font-medium mb-3">Daftar Pengajuan Peminjaman</h2>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-left text-gray-500 border-b"><tr><th class="py-2">Peminjam</th><th class="py-2">Barang</th><th class="py-2">Tgl Pinjam</th><th class="py-2">Tgl Kembali</th><th class="py-2">Status</th><th class="py-2">Aksi</th></tr></thead>
        <tbody>
          <?php if (!$loans): ?><tr><td class="py-2" colspan="6">Belum ada data peminjaman.</td></tr><?php endif; ?>
          <?php foreach ($loans as $l): ?>
          <tr class="border-b last:border-0">
            <td class="py-2"><?= e($l['peminjam']) ?></td><td><?= e($l['nama_barang']) ?></td><td><?= e($l['tanggal_pinjam']) ?></td><td><?= e($l['tanggal_kembali']) ?></td>
            <td><span class="text-xs px-2 py-0.5 rounded <?= $labels[$l['status']] ?? '' ?>"><?= e($l['status']) ?></span></td>
            <td class="whitespace-nowrap"><a class="text-blue-600" href="/ebrow/pages/admin/peminjaman.php?view=<?= e($l['id']) ?>">Detail/Kelola</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
