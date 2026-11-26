<?php
require_once __DIR__ . '/../session.php';
requireAdmin('../login_screen.php', '../user/dashboard_user.php');
require_once __DIR__ . '/../database/repository.php';
require_once __DIR__ . '/partials/ui.php';

$notifikasi = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['action'] ?? '';
    if ($aksi === 'add') {
        $hasil = saveKategori((string) ($_POST['nama_kategori'] ?? ''), null);
        $notifikasi = $hasil['message'];
    } elseif ($aksi === 'update') {
        $hasil = saveKategori((string) ($_POST['nama_kategori'] ?? ''), (int) ($_POST['id'] ?? 0));
        $notifikasi = $hasil['message'];
    } elseif ($aksi === 'delete') {
        $hasil = deleteKategori((int) ($_POST['id'] ?? 0));
        $notifikasi = $hasil['message'];
    }
}

$admin = fetchAdmin();
$stats = fetchDashboardStats();

$semuaKategori = fetchKategoriList();
$batas = 8;
$halaman = max(1, (int) ($_GET['page'] ?? 1));
$totalKategori = count($semuaKategori);
$totalHalaman = max(1, (int) ceil($totalKategori / $batas));
$halaman = min($halaman, $totalHalaman);
$kategoriList = array_slice($semuaKategori, ($halaman - 1) * $batas, $batas);
$daftarParams = adminListParams($_GET);
$paramsDaftar = array_merge($daftarParams, ['page' => $halaman]);
$urlHalaman = static fn (int $nomor): string => adminUrl('kategori_barang.php', ['page' => $nomor]);
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8"><meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Kategori Barang - EBROW</title>
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
          <a class="page-tab" href="barang.php">Data Barang</a>
          <a class="page-tab active" href="kategori_barang.php">Kategori Barang</a>
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
                Kategori Barang
              </h3>
            </div>
            <form method="post" class="d-flex gap-2 align-items-center">
              <input type="hidden" name="action" value="add">
              <?php foreach ($paramsDaftar as $kunci => $nilai): ?>
                <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
              <?php endforeach; ?>
              <input type="text" name="nama_kategori" class="form-control-ebrow" placeholder="Nama kategori baru" required>
              <button type="submit" class="btn-primary-soft"><i class="bi bi-plus"></i>Tambah Kategori</button>
            </form>
          </div>
          <div class="table-responsive">
            <table class="table category-table">
              <thead>
                <tr>
                  <th>
                    Nama Kategori
                  </th>
                  <th>
                    Deskripsi Kategori
                  </th>
                  <th class="text-center">
                    Jumlah Barang
                  </th>
                  <th class="text-end">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$kategoriList): ?>
                  <tr><td colspan="4" class="text-center py-4">Belum ada kategori.</td></tr>
                <?php else: ?>
                  <?php foreach ($kategoriList as $kategori): ?>
                    <tr>
                      <td class="category-name">
                        <form method="post" class="d-flex gap-1 align-items-center">
                          <input type="hidden" name="action" value="update">
                          <input type="hidden" name="id" value="<?= (int) $kategori['id_kategori'] ?>">
                          <?php foreach ($paramsDaftar as $kunci => $nilai): ?>
                            <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
                          <?php endforeach; ?>
                          <input type="text" name="nama_kategori" class="form-control-ebrow" value="<?= esc($kategori['nama_kategori']) ?>" required>
                          <button type="submit" class="btn-small">Simpan</button>
                        </form>
                      </td>
                      <td class="category-description">
                        <?= esc(kategoriDeskripsi($kategori['nama_kategori'])) ?>
                      </td>
                      <td class="category-count text-center">
                        <?= (int) $kategori['jumlah_barang'] ?>
                      </td>
                      <td>
                        <form method="post" class="table-actions justify-content-end" onsubmit="return confirm('Hapus kategori ini?');">
                          <input type="hidden" name="action" value="delete">
                          <input type="hidden" name="id" value="<?= (int) $kategori['id_kategori'] ?>">
                          <?php foreach ($paramsDaftar as $kunci => $nilai): ?>
                            <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
                          <?php endforeach; ?>
                          <button type="submit" class="btn-small btn-reject">Hapus</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <?php renderPagination($totalKategori, $batas, $halaman, count($kategoriList), 'kategori', $urlHalaman); ?>
        </section>
      </div>
      <footer>
        <span>2026 EBROW. Semua hak dilindungi.</span><span>Panel Administrator</span>
      </footer>
    </main>
  </body>
</html>
