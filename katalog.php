<?php
require_once __DIR__ . '/session.php';
requireLogin('login_screen.php');
require_once __DIR__ . '/database/repository.php';

$pageTitle = 'Katalog Barang';
$activeNav = 'katalog';
$base = '';
$pageCss = 'assets/css/katalog.css';
$bodyClass = 'd-flex flex-column min-vh-100';

$kategoriList = fetchKategoriList();
[$totalBarang, $barangList] = fetchBarangList('', 0, '', 1000, 0);

require __DIR__ . '/partials/header.php';
?>
<main class="flex-grow-1">
  <!--   JUDUL KATALOG    -->
  <section class="katalog py-5">
    <div class="container">
      <h2>Katalog Barang</h2>
      <p>Temukan berbagai barang yang dapat kamu pinjam untuk mendukung kegiatan perkuliahan.</p>
      <div class="row g-3 mt-3">
        <div class="col-12 col-md-8">
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" id="searchBarang" placeholder="Cari nama atau kode barang..." />
          </div>
        </div>
        <div class="col-12 col-md-4">
          <select class="form-select" id="kategoriFilter">
            <option value="semua" selected>Semua Kategori</option>
            <?php foreach ($kategoriList as $kategori): ?>
              <option value="<?= (int) $kategori['id_kategori'] ?>">
                <?= esc($kategori['nama_kategori']) ?> (<?= (int) $kategori['jumlah_barang'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
  </section>

  <!--   DAFTAR BARANG    -->
  <section class="daftar-barang py-4">
    <div class="container">
      <h3>Daftar Barang</h3>
      <p>
        Pilih barang yang ingin kamu lihat
        <?php if ($barangList): ?>(<?= count($barangList) ?> barang)<?php endif; ?>
      </p>

      <?php if (!$barangList): ?>
        <p id="pesanKosong" class="text-center mt-4">Belum ada barang di katalog.</p>
      <?php else: ?>
        <div class="row g-4 mt-2">
          <?php foreach ($barangList as $barang): ?>
            <?php
            $fotoBarang = fotoBarangUrl($barang['foto_barang'] ?? '');
            $pathLokal = __DIR__ . '/' . ltrim($fotoBarang, '/');
            $adaFoto = $fotoBarang !== ''
                && (preg_match('#^(https?:)?//#i', $fotoBarang) || is_file($pathLokal));
            $tersedia = $barang['status_ketersediaan'] === 'Tersedia'
                && (int) $barang['stok_tersedia'] > 0;
            ?>
            <div
              class="col-6 col-md-4 col-lg-3"
              data-kategori="<?= (int) $barang['id_kategori'] ?>"
              data-nama="<?= esc(strtolower((string) $barang['nama_barang'])) ?>"
              data-kode="<?= esc(strtolower((string) $barang['kode_barang'])) ?>"
            >
              <div class="card h-100">
                <?php if ($adaFoto): ?>
                  <img src="<?= esc($fotoBarang) ?>" class="card-img-top barang-img" alt="<?= esc($barang['nama_barang']) ?>" />
                <?php else: ?>
                  <div class="barang-placeholder d-flex align-items-center justify-content-center text-muted" role="img" aria-label="Belum ada foto <?= esc($barang['nama_barang']) ?>">
                    <i class="bi bi-image fs-1"></i>
                  </div>
                <?php endif; ?>
                <div class="card-body">
                  <p class="text-muted small mb-1"><?= esc($barang['kode_barang']) ?></p>
                  <h5 class="card-title"><?= esc($barang['nama_barang']) ?></h5>
                  <p class="fw-semibold mb-2"><?= esc(formatHargaSewa($barang['harga_per_hari'])) ?></p>
                  <p class="card-text">Kategori: <?= esc($barang['nama_kategori']) ?></p>
                  <span class="badge <?= $tersedia ? 'bg-success' : 'bg-danger' ?>">
                    <?= $tersedia ? 'Tersedia' : 'Tidak tersedia' ?>
                  </span>
                  <div class="mt-3">
                    <a href="detail.php?id=<?= (int) $barang['id_barang'] ?>" class="btn btn-warning">Lihat Detail</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <p id="pesanKosong" class="text-center mt-4" style="display: none">Barang tidak ditemukan.</p>
      <?php endif; ?>
    </div>
  </section>
</main>

<!-- Footer -->
<footer class="container py-3 text-muted small d-flex flex-column flex-sm-row justify-content-between gap-2">
  <span>&copy; 2026 EBROW. Semua hak dilindungi.</span>
  <span>Pinjam dengan mudah. Kembalikan dengan tanggung jawab.</span>
</footer>

<script src="assets/js/katalog.js"></script>
<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
