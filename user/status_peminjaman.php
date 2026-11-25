<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'ebrow');
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

$id_user = (int) ($_SESSION['id_user'] ?? 1);

$sql = "SELECT p.*, b.nama_barang, b.foto_barang
        FROM `peminjaman` p
        LEFT JOIN `barang` b ON p.id_barang = b.id_barang
        WHERE p.id_user = ?
        ORDER BY p.id_peminjaman DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($r = $result->fetch_assoc()) {
    $rows[] = $r;
}
$stmt->close();

function mapStatusInfo(string $rawStatus): array
{
    $s = strtolower(trim($rawStatus));

    if (str_contains($s, 'tolak')) {
        return [
            'filter' => 'ditolak',
            'badge_label' => 'Ditolak',
            'badge_class' => 'bg-danger rounded-pill',
            'btn_class' => 'btn-outline-dark',
            'modal_status' => 'Ditolak',
            'catatan' => 'Pengajuan peminjaman ditolak oleh admin. Silakan cek ketersediaan barang atau ajukan di jadwal lain.',
        ];
    }

    if (str_contains($s, 'sedang') || str_contains($s, 'pinjam')) {
        return [
            'filter' => 'dipinjam',
            'badge_label' => 'Sedang Dipinjam',
            'badge_class' => 'bg-primary rounded-pill',
            'btn_class' => 'btn-outline-dark',
            'modal_status' => 'Sedang Dipinjam',
            'catatan' => 'Barang telah diserahkan. Gunakan sesuai jadwal dan kembalikan tepat waktu ke ruang inventaris.',
        ];
    }

    if (str_contains($s, 'setuju') || str_contains($s, 'siap') || str_contains($s, 'ambil')) {
        return [
            'filter' => 'siap',
            'badge_label' => 'Siap Diambil',
            'badge_class' => 'bg-info text-dark rounded-pill',
            'btn_class' => 'btn-ebrow',
            'modal_status' => 'Disetujui / Siap Diambil',
            'catatan' => 'Pengajuan telah disetujui admin! Silakan ambil barang di ruang inventaris dengan membawa KTM/ID.',
        ];
    }

    return [
        'filter' => 'menunggu',
        'badge_label' => 'Menunggu Persetujuan',
        'badge_class' => 'bg-warning text-dark rounded-pill',
        'btn_class' => 'btn-outline-dark',
        'modal_status' => 'Menunggu Persetujuan',
        'catatan' => 'Pengajuan sedang ditinjau oleh admin inventaris. Estimasi verifikasi maksimal 1x24 jam.',
    ];
}

function formatTglSingkat(?string $tgl): string
{
    if (empty($tgl)) return '-';
    $ts = strtotime($tgl);
    if (!$ts) return htmlspecialchars($tgl);
    $bulan = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
    ];
    return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EBROW • Informasi &amp; Status Peminjaman</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/user.css" />
</head>
<body class="d-flex flex-column min-vh-100">

  <nav class="navbar navbar-expand-md ebrow-navbar shadow-sm py-3 px-4 px-lg-5 sticky-top">
    <div class="container-fluid px-0">
      <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-6 text-dark" href="dashboard_user.php">
        <i class="bi bi-box fs-5"></i>
        <span>EBROW</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarUser">
        <ul class="navbar-nav align-items-md-center gap-md-3 mt-3 mt-md-0">
          <li class="nav-item"><a class="nav-link" href="dashboard_user.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../katalog.php">Katalog Barang</a></li>
          <li class="nav-item"><a class="nav-link active fw-semibold" href="status_peminjaman.php">Status Peminjaman</a></li>
          <li class="nav-item"><a class="nav-link" href="riwayat_peminjaman.php">Riwayat</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle fs-5"></i>
              <span>Akun Saya</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
              <li><a class="dropdown-item py-2" href="profil_user.php"><i class="bi bi-person me-2"></i>Profil Pengguna</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item py-2 text-danger" href="../login_screen.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container my-4 flex-grow-1">
    <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
          <h1 class="h4 fw-bold text-dark mb-1">Informasi &amp; Status Peminjaman</h1>
          <p class="small text-secondary mb-0">Lacak progres pengajuan peminjaman, jadwal pengambilan, dan instruksi dari admin.</p>
        </div>
        <a href="form_peminjaman.php" class="btn btn-ebrow px-4 py-2 fw-semibold">
          <i class="bi bi-plus-lg me-1"></i> Ajukan Peminjaman
        </a>
      </div>

      <div class="ebrow-info-box rounded-3 p-3 small text-secondary mb-4">
        <strong class="d-block text-dark mb-1">Alur Proses Peminjaman EBROW:</strong>
1. <strong>Menunggu Persetujuan</strong> (Admin memeriksa ketersediaan stok) &rarr;
2. <strong>Siap Diambil</strong> (Ambil barang di ruang inventaris dengan menunjukkan KTM/ID) &rarr;
3. <strong>Sedang Dipinjam</strong> (Gunakan sesuai jadwal dan kembalikan tepat waktu).
      </div>

      <div class="d-flex flex-wrap gap-2 mb-4" id="status-filter-buttons">
        <button type="button" class="btn btn-dark btn-sm rounded-pill px-3" onclick="filterStatus('all', this)">Semua (<?= count($rows) ?>)</button>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="filterStatus('menunggu', this)">Menunggu Persetujuan</button>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="filterStatus('siap', this)">Siap Diambil</button>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="filterStatus('dipinjam', this)">Sedang Dipinjam</button>
        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="filterStatus('ditolak', this)">Ditolak</button>
      </div>

      <div class="row g-3" id="status-list">
        <?php if (empty($rows)): ?>
          <div class="col-12">
            <div class="border rounded-4 p-4 text-center text-secondary small">
              Belum ada data pengajuan peminjaman. Klik <strong>Ajukan Peminjaman</strong> untuk membuat pengajuan baru.
            </div>
          </div>
        <?php else: ?>
          <?php foreach ($rows as $row): ?>
            <?php
              $idPinjam = (int) ($row['id_peminjaman'] ?? 0);
              $kode = '#BRW-' . str_pad((string) $idPinjam, 3, '0', STR_PAD_LEFT);
              $namaBarang = $row['nama_barang'] ?? ('Barang #' . ($row['id_barang'] ?? '-'));
              $jumlah = (int) ($row['jumlah_unit'] ?? 1);
              $tglMulai = formatTglSingkat($row['tanggal_pinjam'] ?? null);
              $tglSelesai = formatTglSingkat($row['tanggal_kembali'] ?? null);
              $jadwal = $tglMulai . ' – ' . $tglSelesai;

              $foto = !empty($row['foto_barang']) ? $row['foto_barang'] : '../assets/images/laptop.jpeg';
              if (!str_starts_with($foto, '../') && !str_starts_with($foto, 'http')) {
                  $foto = '../' . ltrim($foto, '/');
              }

              $statusRaw = $row['status_peminjaman'] ?? 'Menunggu Persetujuan';
              $st = mapStatusInfo($statusRaw);

              $catatan = $st['catatan'];
              if (!empty($row['keperluan_peminjaman'])) {
                  $catatan .= ' Keperluan: ' . $row['keperluan_peminjaman'] . '.';
              }
            ?>
            <div class="col-12 status-item" data-status="<?= htmlspecialchars($st['filter']) ?>">
              <div class="border rounded-4 p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 user-status-card">
                <div class="d-flex align-items-center gap-3">
                  <img src="<?= htmlspecialchars($foto) ?>" alt="<?= htmlspecialchars($namaBarang) ?>" class="rounded-3 border user-item-thumb-lg" />
                  <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                      <span class="small fw-bold text-secondary"><?= htmlspecialchars($kode) ?></span>
                      <span class="badge <?= htmlspecialchars($st['badge_class']) ?>"><?= htmlspecialchars($st['badge_label']) ?></span>
                    </div>
                    <h2 class="h6 fw-bold text-dark mb-1"><?= htmlspecialchars($namaBarang) ?> (<?= $jumlah ?> Unit)</h2>
                    <div class="small text-secondary">
                      <i class="bi bi-calendar-event me-1"></i> Jadwal: <?= htmlspecialchars($jadwal) ?>
                    </div>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <button
                    type="button"
                    class="btn <?= htmlspecialchars($st['btn_class']) ?> btn-sm px-3 py-2 fw-semibold"
                    data-kode="<?= htmlspecialchars($kode) ?>"
                    data-barang="<?= htmlspecialchars($namaBarang) ?>"
                    data-jumlah="<?= $jumlah ?> Unit"
                    data-tanggal="<?= htmlspecialchars($jadwal) ?>"
                    data-status="<?= htmlspecialchars($st['modal_status']) ?>"
                    data-catatan="<?= htmlspecialchars($catatan) ?>"
                    data-lokasi="Ruang Inventaris Gedung Utama Lt. 1"
                    onclick="openDetailFromBtn(this)"
                  >
                    <i class="bi bi-info-circle me-1"></i> Detail Info
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 border-0 shadow">
        <div class="modal-header border-bottom-0 pb-0">
          <h5 class="modal-title fw-bold" id="detailModalLabel">Detail Informasi Peminjaman</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body small">
          <div class="bg-light rounded-3 p-3 mb-3">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary">Kode Peminjaman</span>
              <span class="fw-bold text-dark" id="modal-kode">-</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary">Nama Barang</span>
              <span class="fw-semibold text-dark" id="modal-barang">-</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary">Jumlah</span>
              <span class="fw-semibold text-dark" id="modal-jumlah">-</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary">Periode Pinjam</span>
              <span class="fw-semibold text-dark" id="modal-tanggal">-</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary">Lokasi Pengambilan</span>
              <span class="fw-semibold text-dark text-end" id="modal-lokasi">-</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-secondary">Status Saat Ini</span>
              <span class="fw-bold text-primary" id="modal-status">-</span>
            </div>
          </div>

          <div class="ebrow-info-box rounded-3 p-3">
            <strong class="d-block text-dark mb-1">Catatan &amp; Instruksi Admin:</strong>
            <span class="text-secondary" id="modal-catatan">-</span>
          </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-ebrow w-100 fw-semibold" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <footer class="container py-3 text-muted small d-flex flex-column flex-sm-row justify-content-between gap-2">
    <span>&copy; 2026 EBROW. Semua hak dilindungi.</span>
    <span>Pinjam dengan mudah. Kembalikan dengan tanggung jawab.</span>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function filterStatus(status, btnElement) {
      const buttons = document.querySelectorAll('#status-filter-buttons button');
      buttons.forEach(btn => {
        btn.classList.remove('btn-dark');
        btn.classList.add('btn-outline-secondary');
      });
      btnElement.classList.remove('btn-outline-secondary');
      btnElement.classList.add('btn-dark');

      const items = document.querySelectorAll('.status-item');
      items.forEach(item => {
        if (status === 'all' || item.getAttribute('data-status') === status) {
          item.classList.remove('d-none');
        } else {
          item.classList.add('d-none');
        }
      });
    }

    function openDetailFromBtn(btn) {
      document.getElementById('modal-kode').textContent = btn.dataset.kode;
      document.getElementById('modal-barang').textContent = btn.dataset.barang;
      document.getElementById('modal-jumlah').textContent = btn.dataset.jumlah;
      document.getElementById('modal-tanggal').textContent = btn.dataset.tanggal;
      document.getElementById('modal-status').textContent = btn.dataset.status;
      document.getElementById('modal-catatan').textContent = btn.dataset.catatan;
      document.getElementById('modal-lokasi').textContent = btn.dataset.lokasi;

      new bootstrap.Modal(document.getElementById('detailModal')).show();
    }
  </script>
</body>
</html>