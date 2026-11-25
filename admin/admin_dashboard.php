<?php
require_once __DIR__ . '/../database/repository.php';
require_once __DIR__ . '/partials/ui.php';

$admin = fetchAdmin();
$stats = fetchDashboardStats();
$pengajuanTerbaru = fetchRecentPeminjaman(4);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - EBROW</title>

  <!-- Bootstrap untuk layout dan komponen dasar -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons untuk ikon menu dan informasi -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin.css">

</head>
<body>
  <!-- Menu samping -->
  <?php renderSidebar('dashboard'); ?>

  <!-- Konten utama dashboard -->
  <main class="main-content" id="dashboard">
    <header class="topbar">
      <div class="page-heading">
        <h1>Dashboard Admin</h1>
        <p>Selamat Datang, <?= esc($admin['nama_lengkap']) ?>!</p>
      </div>

      <div class="admin-info">
        <i class="bi bi-bell"></i>
        <div class="avatar"><i class="bi bi-person-fill"></i></div>
        <div class="admin-details">
          <div class="admin-name"><?= esc($admin['nama_lengkap']) ?></div>
          <div class="admin-role">Administrator</div>
        </div>
      </div>
    </header>

    <div class="content-area">
      <!-- Ringkasan data -->
      <section class="mb-3">
        <div class="section-heading">
          <h2>Ringkasan Data</h2>
          <span class="date-text"><?= esc(formatTanggalPanjang('now')) ?></span>
        </div>

        <div class="row g-2">
          <div class="col-6 col-lg-3">
            <div class="summary-card">
              <div class="summary-label">Total Barang</div>
              <div class="summary-bottom"><span class="summary-number"><?= (int) $stats['total_barang'] ?></span><span class="summary-icon"><i class="bi bi-box-seam"></i></span></div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="summary-card">
              <div class="summary-label">Total Pengguna</div>
              <div class="summary-bottom"><span class="summary-number"><?= (int) $stats['total_user'] ?></span><span class="summary-icon"><i class="bi bi-people"></i></span></div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="summary-card">
              <div class="summary-label">Total Peminjaman</div>
              <div class="summary-bottom"><span class="summary-number"><?= (int) $stats['total_peminjaman'] ?></span><span class="summary-icon"><i class="bi bi-arrow-left-right"></i></span></div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="summary-card pending">
              <div class="summary-label">Pengajuan Menunggu</div>
              <div class="summary-bottom"><span class="summary-number"><?= (int) $stats['menunggu'] ?></span><span class="summary-icon"><i class="bi bi-clock-history"></i></span></div>
            </div>
          </div>
        </div>
      </section>

      <!-- Tabel pengajuan terbaru -->
      <section class="panel mb-3" id="peminjaman">
        <h3 class="panel-title">Pengajuan Terbaru</h3>
        <div class="panel-subtitle"><?= count($pengajuanTerbaru) ?> pengajuan terakhir dari seluruh pengguna.</div>

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Nama<br>Peminjam</th>
                <th>Nama Barang</th>
                <th>Tanggal<br>Pengajuan</th>
                <th>Tanggal<br>Peminjaman</th>
                <th>Tanggal<br>Pengembalian</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$pengajuanTerbaru): ?>
                <tr><td colspan="7" class="text-center py-4">Belum ada pengajuan.</td></tr>
              <?php else: ?>
                <?php foreach ($pengajuanTerbaru as $pengajuan): ?>
                  <tr>
                    <td class="borrower"><?= esc($pengajuan['nama_peminjam']) ?></td>
                    <td class="item-name"><?= esc($pengajuan['nama_barang']) ?></td>
                    <td class="date-cell"><?= esc(formatTanggalPendek($pengajuan['tanggal_pengajuan'])) ?></td>
                    <td class="date-cell"><?= esc(formatTanggalPendek($pengajuan['tanggal_pinjam'])) ?></td>
                    <td class="date-cell"><?= esc(formatTanggalPendek($pengajuan['tanggal_kembali'])) ?></td>
                    <td><span class="status <?= esc(statusClass($pengajuan['status_peminjaman'])) ?>"><?= esc(statusLabel($pengajuan['status_peminjaman'])) ?></span></td>
                    <td>
                      <div class="action-buttons">
                        <a class="btn-small" href="detail_peminjaman.php?id=<?= (int) $pengajuan['id_peminjaman'] ?>">Lihat Detail</a>
                        <?php if ($pengajuan['status_peminjaman'] === 'Menunggu Persetujuan'): ?>
                          <a class="btn-small btn-approve" href="detail_peminjaman.php?id=<?= (int) $pengajuan['id_peminjaman'] ?>&action=approve">Setujui</a>
                          <a class="btn-small btn-reject" href="detail_peminjaman.php?id=<?= (int) $pengajuan['id_peminjaman'] ?>&action=reject">Tolak</a>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>

    <footer>
      <span>© 2026 EBROW. Semua hak dilindungi.</span>
      <span>Panel Administrator</span>
    </footer>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
