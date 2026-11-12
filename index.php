<?php
$pageTitle = 'Beranda';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
$popular = [];
try { $popular = $pdo->query('SELECT i.*, c.nama_kategori FROM items i LEFT JOIN categories c ON i.category_id=c.id ORDER BY i.created_at DESC LIMIT 4')->fetchAll(); } catch (Exception $e) { $popular = []; }
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>
<main class="flex-1">
  <section class="bg-blue-600 text-white">
    <div class="max-w-6xl mx-auto px-4 py-12 md:py-16">
      <h1 class="text-2xl md:text-4xl font-bold mb-3">Selamat Datang di EBROW</h1>
      <p class="text-blue-100 mb-6 max-w-xl">Platform peminjaman barang yang mudah, cepat, dan transparan. Temukan barang yang Anda butuhkan dan ajukan peminjaman secara online.</p>
      <a href="/ebrow/pages/user/katalog.php" class="inline-block bg-white text-blue-600 font-semibold px-5 py-2.5 rounded-lg">Lihat Katalog</a>
    </div>
  </section>

  <section class="max-w-6xl mx-auto px-4 py-10">
    <h2 class="text-xl font-semibold mb-4">Cara Kerja Peminjaman</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="bg-white p-4 rounded-lg shadow-sm border"><div class="text-blue-600 font-bold mb-1">1. Pilih Barang</div><p class="text-sm text-gray-600">Cari dan pilih barang dari katalog yang tersedia.</p></div>
      <div class="bg-white p-4 rounded-lg shadow-sm border"><div class="text-blue-600 font-bold mb-1">2. Ajukan Peminjaman</div><p class="text-sm text-gray-600">Isi formulir peminjaman dan kirim pengajuan.</p></div>
      <div class="bg-white p-4 rounded-lg shadow-sm border"><div class="text-blue-600 font-bold mb-1">3. Ambil & Kembalikan</div><p class="text-sm text-gray-600">Tunggu persetujuan admin, lalu ambil dan kembalikan tepat waktu.</p></div>
    </div>
  </section>

  <section class="bg-white border-y">
    <div class="max-w-6xl mx-auto px-4 py-10">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Barang Populer</h2>
        <a href="/ebrow/pages/user/katalog.php" class="text-sm text-blue-600">Lihat semua</a>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php if (!$popular): for ($i = 1; $i <= 4; $i++): ?>
          <div class="border rounded-lg overflow-hidden bg-gray-50"><div class="aspect-square bg-gray-200 flex items-center justify-center text-gray-400 text-sm">Foto</div><div class="p-3"><h3 class="font-medium text-sm">Contoh Barang</h3><p class="text-xs text-gray-500">-</p><span class="inline-block mt-2 text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-600">-</span></div></div>
        <?php endfor; else: foreach ($popular as $it): $avail=(int)$it['tersedia']>0; ?>
          <a href="/ebrow/pages/user/detail.php?id=<?= e($it['id']) ?>" class="border rounded-lg overflow-hidden bg-gray-50 hover:shadow">
            <div class="aspect-square bg-gray-200 flex items-center justify-center text-gray-400 text-sm overflow-hidden"><?php if(!empty($it['foto'])): ?><img src="<?= e($it['foto']) ?>" class="w-full h-full object-cover"><?php else: ?>Foto<?php endif; ?></div>
            <div class="p-3"><h3 class="font-medium text-sm"><?= e($it['nama_barang']) ?></h3><p class="text-xs text-gray-500"><?= e($it['nama_kategori'] ?? '-') ?></p><span class="inline-block mt-2 text-xs px-2 py-0.5 rounded <?= $avail?'bg-green-100 text-green-700':'bg-red-100 text-red-700' ?>"><?= $avail?'Tersedia':'Habis' ?></span></div>
          </a>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </section>

  <section class="max-w-6xl mx-auto px-4 py-10">
    <h2 class="text-xl font-semibold mb-4">FAQ</h2>
    <div class="space-y-3">
      <details class="bg-white border rounded-lg p-4"><summary class="font-medium cursor-pointer">Bagaimana cara meminjam barang?</summary><p class="mt-2 text-sm text-gray-600">Login, pilih barang di katalog, lalu isi form peminjaman.</p></details>
      <details class="bg-white border rounded-lg p-4"><summary class="font-medium cursor-pointer">Berapa lama proses persetujuan?</summary><p class="mt-2 text-sm text-gray-600">Pengajuan akan diproses oleh admin sesuai ketersediaan.</p></details>
    </div>
  </section>

  <section id="kontak" class="bg-gray-100">
    <div class="max-w-6xl mx-auto px-4 py-10">
      <h2 class="text-xl font-semibold mb-2">Kontak</h2>
      <p class="text-sm text-gray-600">Email: info@ebrow.example &middot; Telepon: 08xx-xxxx-xxxx</p>
    </div>
  </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
