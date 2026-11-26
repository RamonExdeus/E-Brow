<?php
require_once __DIR__ . '/../session.php';
requireLogin('../login_screen.php');
require_once __DIR__ . '/../database/repository.php';

$idUser = (int) ($_SESSION['id_user'] ?? 0);
$pengguna = fetchUserById($idUser);
if (!$pengguna) {
    session_destroy();
    header('Location: ../login_screen.php');
    exit;
}

$ringkasan = fetchUserRingkasan($idUser);
$peminjamanAktif = fetchPeminjamanUser(
    $idUser,
    ['Menunggu Persetujuan', 'Disetujui', 'Siap Diambil', 'Sedang Dipinjam'],
    5
);
$namaSapaan = explode(' ', trim($pengguna['nama_lengkap']))[0] ?: 'Pengguna';

$pageTitle = 'Dashboard Pengguna';
$activeNav = 'dashboard';
$base = '../';
$pageCss = 'assets/css/user.css';
require __DIR__ . '/../partials/header.php';
?>
<!-- Main Content -->
  <main class="container my-4 flex-grow-1">
    <!-- Banner Sapaan -->
    <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5 mb-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
          <span class="badge bg-light text-dark border mb-2 px-3 py-2 rounded-pill">Dashboard Pengguna</span>
          <h1 class="h4 fw-bold text-dark mb-1">Selamat Datang, <?= esc($namaSapaan) ?>!</h1>
          <p class="small text-secondary mb-0">Kelola pengajuan peminjaman barang dan pantau jadwal pengembalianmu di satu tempat.</p>
        </div>
      </div>

      <!-- Kartu Statistik Ringkas -->
      <div class="row g-3 mt-3">
        <div class="col-md-4">
          <div class="border rounded-4 p-3 h-100 d-flex align-items-center gap-3 user-stat-card">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
              <i class="bi bi-box-seam fs-4"></i>
            </div>
            <div>
              <div class="small text-secondary">Sedang Dipinjam</div>
              <div class="h4 fw-bold text-dark mb-0"><?= $ringkasan['sedang_dipinjam'] ?> Barang</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="border rounded-4 p-3 h-100 d-flex align-items-center gap-3 user-stat-card">
            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
              <i class="bi bi-hourglass-split fs-4"></i>
            </div>
            <div>
              <div class="small text-secondary">Menunggu Persetujuan</div>
              <div class="h4 fw-bold text-dark mb-0"><?= $ringkasan['menunggu'] ?> Pengajuan</div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="border rounded-4 p-3 h-100 d-flex align-items-center gap-3 user-stat-card">
            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
              <i class="bi bi-check2-circle fs-4"></i>
            </div>
            <div>
              <div class="small text-secondary">Peminjaman Selesai</div>
              <div class="h4 fw-bold text-dark mb-0"><?= $ringkasan['selesai'] ?> Transaksi</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Peminjaman Aktif -->
    <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h2 class="h5 fw-bold text-dark mb-1">Peminjaman Berjalan</h2>
          <p class="small text-secondary mb-0">Daftar barang yang sedang kamu gunakan atau menunggu konfirmasi admin.</p>
        </div>
        <a href="status_peminjaman.php" class="small fw-semibold ebrow-link text-decoration-none">
          Lihat Detail Status &rarr;
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light small text-secondary">
            <tr>
              <th>Kode</th>
              <th>Barang</th>
              <th>Jumlah</th>
              <th>Tanggal Pinjam</th>
              <th>Batas Kembali</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody class="small">
            <?php if (!$peminjamanAktif): ?>
              <tr>
                <td colspan="6" class="text-center text-secondary py-4">Belum ada peminjaman berjalan.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($peminjamanAktif as $pinjam): ?>
                <tr>
                  <td class="fw-semibold text-dark"><?= esc($pinjam['nomor_peminjaman']) ?></td>
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <?php if (fotoBarangTersedia($pinjam['foto_barang'])): ?>
                        <img src="<?= esc(fotoBarangUrlUser($pinjam['foto_barang'])) ?>" alt="<?= esc($pinjam['nama_barang']) ?>" class="rounded-3 border user-item-thumb" />
                      <?php else: ?>
                        <span class="rounded-3 border user-item-thumb bg-light d-flex align-items-center justify-content-center">
                          <i class="bi bi-image text-secondary"></i>
                        </span>
                      <?php endif; ?>
                      <div>
                        <div class="fw-semibold text-dark"><?= esc($pinjam['nama_barang']) ?></div>
                        <div class="text-secondary user-item-subtext">Kategori: <?= esc($pinjam['nama_kategori']) ?></div>
                      </div>
                    </div>
                  </td>
                  <td><?= (int) $pinjam['jumlah_unit'] ?> Unit</td>
                  <td><?= esc(formatTanggalPendek($pinjam['tanggal_pinjam'])) ?></td>
                  <td class="fw-semibold text-danger"><?= esc(formatTanggalPendek($pinjam['tanggal_kembali'])) ?></td>
                  <td>
                    <span class="badge <?= esc(statusBootstrapClass($pinjam['status_peminjaman'])) ?> rounded-pill px-3 py-2">
                      <?= esc($pinjam['status_peminjaman']) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="ebrow-info-box rounded-3 p-3 small text-secondary mt-3">
        <strong class="text-dark"><i class="bi bi-info-circle me-1"></i> Pengingat Pengembalian:</strong>
        Harap kembalikan barang sebelum pukul 16.00 WIB pada tanggal batas kembali agar akun tetap aktif untuk peminjaman berikutnya.
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="container py-3 text-muted small d-flex flex-column flex-sm-row justify-content-between gap-2">
    <span>&copy; 2026 EBROW. Semua hak dilindungi.</span>
    <span>Pinjam dengan mudah. Kembalikan dengan tanggung jawab.</span>
  </footer>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
