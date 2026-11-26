<?php
require_once __DIR__ . '/../session.php';
requireAdmin('../login_screen.php', '../user/dashboard_user.php');
require_once __DIR__ . '/../database/repository.php';
require_once __DIR__ . '/partials/ui.php';

$notifikasi = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $hasil = deleteBarang((int) ($_POST['id'] ?? 0));
    $notifikasi = $hasil['message'];
}

$admin = fetchAdmin();
$stats = fetchDashboardStats();
$kategoriList = fetchKategoriList();

$cari = trim((string) ($_GET['search'] ?? ''));
$kategoriDipilih = (int) ($_GET['kategori'] ?? 0);
$statusDipilih = (string) ($_GET['status'] ?? '');
$halaman = max(1, (int) ($_GET['page'] ?? 1));
$batas = 8;
$offset = ($halaman - 1) * $batas;

[$totalBarang, $daftarBarang] = fetchBarangList($cari, $kategoriDipilih, $statusDipilih, $batas, $offset);
$totalHalaman = max(1, (int) ceil($totalBarang / $batas));
if ($halaman > $totalHalaman) {
    $halaman = $totalHalaman;
    $offset = ($halaman - 1) * $batas;
    [$totalBarang, $daftarBarang] = fetchBarangList($cari, $kategoriDipilih, $statusDipilih, $batas, $offset);
}

$daftarParams = adminListParams($_GET);
$filterParams = $daftarParams;
unset($filterParams['page'], $filterParams['scroll']);
$urlHalaman = static fn (int $nomor): string => adminUrl('barang.php', array_merge($filterParams, ['page' => $nomor]));
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8"><meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Kelola Barang - EBROW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
  </head>
  <body>
    <?php renderSidebar('barang'); ?>
    <main class="main-content">
      <header class="topbar">
        <div class="page-heading">
          <h1>
            Kelola Barang
          </h1>
          <p>
            Kelola data barang dan kategori yang tersedia untuk peminjaman.
          </p>
        </div>
        <div class="admin-info">
          <i class="bi bi-bell"></i>
          <div class="avatar">
            <i class="bi bi-person-fill"></i>
          </div>
          <div class="admin-details">
            <div class="admin-name">
              <?= esc($admin['nama_lengkap']) ?>
            </div>
            <div class="admin-role">
              Administrator
            </div>
          </div>
        </div>
      </header>
      <div class="content-area">
        <?php if ($notifikasi !== ''): ?>
          <div class="alert alert-info py-2" role="alert"><?= esc($notifikasi) ?></div>
        <?php endif; ?>
        <nav class="page-tabs">
          <a class="page-tab active" href="barang.php">Data Barang</a>
          <a class="page-tab" href="kategori_barang.php">Kategori Barang</a>
        </nav>
        <section class="stats-grid">
          <div class="summary-card">
            <div class="summary-label">
              Total Barang
            </div>
            <div class="summary-bottom">
              <span class="summary-number"><?= (int) $stats['total_barang'] ?></span><span class="summary-icon"><i class="bi bi-box-seam"></i></span>
            </div>
          </div>
          <div class="summary-card">
            <div class="summary-label">
              Barang Tersedia
            </div>
            <div class="summary-bottom">
              <span class="summary-number"><?= (int) $stats['barang_tersedia'] ?></span><span class="summary-icon"><i class="bi bi-check-circle"></i></span>
            </div>
          </div>
          <div class="summary-card">
            <div class="summary-label">
              Barang Sedang Dipinjam
            </div>
            <div class="summary-bottom">
              <span class="summary-number"><?= (int) $stats['sedang_dipinjam'] ?></span><span class="summary-icon"><i class="bi bi-clock"></i></span>
            </div>
          </div>
        </section>
        <section class="panel">
          <div class="panel-action">
            <div>
              <h3 class="panel-title">
                Data Barang
              </h3>
              <div class="panel-subtitle mb-0">
                Perbarui stok, kondisi, dan ketersediaan barang.
              </div>
            </div>
            <a class="btn-primary-soft" href="<?= esc(adminUrl('tambah_barang.php', $daftarParams)) ?>" data-remember-scroll>
              <i class="bi bi-plus"></i>Tambah Barang
            </a>
          </div>
          <form class="inventory-toolbar" method="get">
            <div class="search-wrap">
              <i class="bi bi-search"></i><input class="search-field" name="search" value="<?= esc($cari) ?>" placeholder="Cari nama barang...">
            </div>
            <select class="filter-field" name="kategori" onchange="this.form.submit()">
              <option value="0">Semua kategori</option>
              <?php foreach ($kategoriList as $kategori): ?>
                <option value="<?= (int) $kategori['id_kategori'] ?>" <?= $kategoriDipilih === (int) $kategori['id_kategori'] ? 'selected' : '' ?>>
                  <?= esc($kategori['nama_kategori']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <select class="filter-field" name="status" onchange="this.form.submit()">
              <option value="">Semua status</option>
              <option value="Tersedia" <?= $statusDipilih === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
              <option value="Tidak tersedia" <?= $statusDipilih === 'Tidak tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
            </select>
            <button type="submit" class="btn-small">Cari</button>
          </form>
          <div class="table-responsive">
            <table class="table inventory-table">
              <thead>
                <tr>
                  <th>
                    Barang
                  </th>
                  <th>
                    Kategori
                  </th>
                  <th>
                    Kondisi
                  </th>
                  <th>
                    Stok
                  </th>
                  <th>
                    Status
                  </th>
                  <th class="text-center">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$daftarBarang): ?>
                  <tr><td colspan="6" class="text-center py-4">Barang tidak ditemukan.</td></tr>
                <?php else: ?>
                  <?php foreach ($daftarBarang as $barang): ?>
                    <tr>
                      <td>
                        <div class="product-cell">
                          <div class="product-thumb">
                            <?php if (!empty($barang['foto_barang'])): ?>
                              <img src="../<?= esc($barang['foto_barang']) ?>" alt="">
                            <?php else: ?>
                              <i class="bi <?= esc(barangIcon($barang['nama_barang'])) ?>"></i>
                            <?php endif; ?>
                          </div>
                          <div>
                            <div class="product-title">
                              <?= esc($barang['nama_barang']) ?>
                            </div>
                            <div class="product-code">
                              <?= esc($barang['kode_barang']) ?>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <?= esc($barang['nama_kategori']) ?>
                      </td>
                      <td>
                        <?= esc($barang['kondisi_barang']) ?>
                      </td>
                      <td>
                        <?= (int) $barang['stok_tersedia'] ?> unit
                      </td>
                      <td>
                        <span class="status <?= $barang['status_ketersediaan'] === 'Tersedia' ? 'available' : 'unavailable' ?>">
                          <?= esc($barang['status_ketersediaan']) ?>
                        </span>
                      </td>
                      <td>
                        <div class="table-actions">
                          <a class="btn-small" href="<?= esc(adminUrl('tambah_barang.php', array_merge($daftarParams, ['edit' => (int) $barang['id_barang']]))) ?>" data-remember-scroll>Edit</a>
                          <form method="post" class="d-inline" onsubmit="return confirm('Hapus barang ini?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $barang['id_barang'] ?>">
                            <?php foreach ($daftarParams as $kunci => $nilai): ?>
                              <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn-small btn-reject">Hapus</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="table-note">Stok menunjukkan unit yang siap dipinjam.</div>
          <?php renderPagination((int) $totalBarang, $batas, $halaman, count($daftarBarang), 'barang', $urlHalaman); ?>
        </section>
      </div>
      <footer>
        <span>2026 EBROW. Semua hak dilindungi.</span><span>Panel Administrator</span>
      </footer>
    </main>
    <script>
      (function () {
        document.querySelectorAll('[data-remember-scroll]').forEach(link => {
          link.addEventListener('click', () => {
            const url = new URL(link.href, window.location.href);
            url.searchParams.set('scroll', String(Math.round(window.scrollY)));
            link.href = url.toString();
          });
        });

        const params = new URLSearchParams(window.location.search);
        if (!params.has('scroll')) return;

        const posisi = Math.max(0, parseInt(params.get('scroll'), 10) || 0);
        window.requestAnimationFrame(() => window.scrollTo(0, posisi));
        params.delete('scroll');
        const sisa = params.toString();
        history.replaceState(null, '', window.location.pathname + (sisa ? '?' + sisa : ''));
      })();
    </script>
  </body>
</html>
