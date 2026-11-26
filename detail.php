<?php
require_once __DIR__ . '/session.php';
requireLogin('login_screen.php');
require_once __DIR__ . '/database/repository.php';

$idBarang = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1],
]);
$barang = $idBarang ? fetchBarangDetailById((int) $idBarang) : null;

$namaBarang = $barang['nama_barang'] ?? 'Barang tidak ditemukan';
$kodeBarang = $barang['kode_barang'] ?? '-';
$kategoriBarang = $barang['nama_kategori'] ?? '-';
$deskripsiBarang = $barang['deskripsi'] ?? '';
$hargaBarang = $barang ? formatHargaSewa($barang['harga_per_hari']) : '-';
$kondisiBarang = $barang['kondisi_barang'] ?? '-';
$stokTersedia = (int) ($barang['stok_tersedia'] ?? 0);
$stokTotal = (int) ($barang['stok_total'] ?? 0);
$tersedia = $barang !== null
    && $barang['status_ketersediaan'] === 'Tersedia'
    && $stokTersedia > 0;
$statusBarang = $tersedia ? 'Tersedia' : 'Tidak tersedia';

$fotoBarang = fotoBarangUrl($barang['foto_barang'] ?? '');
$pathLokal = __DIR__ . '/' . ltrim($fotoBarang, '/');
$adaFoto = $fotoBarang !== ''
    && (preg_match('#^(https?:)?//#i', $fotoBarang) || is_file($pathLokal));

$pageTitle = 'Detail Barang';
$activeNav = 'katalog';
$base = '';
$pageCss = 'assets/css/detail.css';
$bodyClass = 'd-flex flex-column min-vh-100';
require __DIR__ . '/partials/header.php';
?>
<main class="flex-grow-1">
  <!-- DETAIL BARANG -->
  <section class="detail-barang py-5">
    <div class="container">
      <?php if ($barang === null): ?>
        <div class="alert alert-warning rounded-4" role="alert">
          <h1 class="h4 fw-bold mb-2">Barang tidak ditemukan</h1>
          <p class="mb-3">Barang yang kamu cari tidak tersedia atau ID-nya tidak valid.</p>
          <a href="katalog.php" class="btn btn-warning">Kembali ke Katalog</a>
        </div>
      <?php else: ?>
        <div class="row g-4">
          <!-- Foto Barang -->
          <div class="col-md-5">
            <div class="card border-0 shadow-sm">
              <?php if ($adaFoto): ?>
                <img src="<?= esc($fotoBarang) ?>" id="gambarBarang" class="img-fluid rounded gambar-barang" alt="<?= esc($namaBarang) ?>" />
              <?php else: ?>
                <div class="gambar-placeholder d-flex flex-column align-items-center justify-content-center text-muted rounded" role="img" aria-label="Belum ada foto <?= esc($namaBarang) ?>">
                  <i class="bi bi-image fs-1 mb-2"></i>
                  <span class="small">Belum ada foto</span>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Informasi Barang -->
          <div class="col-md-7">
            <p class="text-muted small mb-1">Kode: <?= esc($kodeBarang) ?></p>
            <h2 class="fw-bold" id="namaBarang"><?= esc($namaBarang) ?></h2>
            <p class="text-muted" id="kategoriBarang">Kategori: <?= esc($kategoriBarang) ?></p>
            <span class="badge <?= $tersedia ? 'bg-success' : 'bg-danger' ?>" id="statusBarang">
              <?= esc($statusBarang) ?>
            </span>
            <p class="mt-3" id="deskripsiBarang">
              <?= esc($deskripsiBarang !== '' ? $deskripsiBarang : 'Deskripsi barang belum tersedia.') ?>
            </p>

            <h5 class="mt-4">Informasi Barang</h5>
            <p class="mb-1" id="hargaBarang">Harga: <?= esc($hargaBarang) ?></p>
            <p class="mb-1" id="kondisiBarang">Kondisi: <?= esc($kondisiBarang) ?></p>
            <p class="mb-1" id="jumlahBarang">Jumlah: <?= $stokTersedia ?> unit tersedia dari <?= $stokTotal ?> unit</p>

            <div class="d-flex flex-wrap gap-2 mt-3">
              <?php if ($tersedia): ?>
                <a href="user/form_peminjaman.php?barang=<?= (int) $barang['id_barang'] ?>" id="tombolPeminjaman" class="btn btn-warning px-4 py-2">
                  <i class="bi bi-clipboard-check me-2"></i>
                  Pinjam Barang
                </a>
              <?php else: ?>
                <button type="button" class="btn btn-secondary px-4 py-2" disabled>
                  <i class="bi bi-x-circle me-2"></i>
                  Tidak Tersedia
                </button>
              <?php endif; ?>
              <a href="katalog.php" class="btn btn-outline-secondary px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali ke Katalog
              </a>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<!-- Footer -->
<footer class="container py-3 text-muted small d-flex flex-column flex-sm-row justify-content-between gap-2">
  <span>&copy; 2026 EBROW. Semua hak dilindungi.</span>
  <span>Pinjam dengan mudah. Kembalikan dengan tanggung jawab.</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
