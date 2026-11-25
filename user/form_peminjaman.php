<?php
session_start();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ebrow';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die('Koneksi database gagal: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

$berhasil = false;
$error_msg = '';
$kode_transaksi = '#BRW-' . rand(100, 999);
$info_sukses = ['item' => '-', 'total' => '-', 'kode' => $kode_transaksi];

// Ambil barang yang didaftarkan admin di tabel `barang`
$items = [];
$sqlBarang = "SELECT b.*, k.nama_kategori
              FROM `barang` b
              LEFT JOIN `kategori_barang` k ON b.id_kategori = k.id_kategori
              ORDER BY b.id_barang ASC";
$resBarang = $conn->query($sqlBarang);
if ($resBarang) {
    while ($row = $resBarang->fetch_assoc()) {
        $img = !empty($row['foto_barang']) ? $row['foto_barang'] : '../assets/images/laptop.jpeg';
        if (!str_starts_with($img, '../') && !str_starts_with($img, 'http')) {
            $img = '../' . ltrim($img, '/');
        }
        $items[] = [
            'id' => (int) $row['id_barang'],
            'name' => (string) $row['nama_barang'],
            'category' => (string) ($row['nama_kategori'] ?? 'Barang'),
            'price' => (int) ($row['harga_per_hari'] ?? 0),
            'stock' => max(0, (int) ($row['stok_tersedia'] ?? 0)),
            'img' => $img,
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user     = (int) ($_SESSION['id_user'] ?? 1);
    $id_barang   = (int) ($_POST['id_barang'] ?? 0);
    $nama        = trim($_POST['borrow_name'] ?? '');
    $nim         = trim($_POST['borrow_nim'] ?? '');
    $no_wa       = trim($_POST['borrow_phone'] ?? '');
    $prodi_org   = trim($_POST['borrow_unit'] ?? '');
    $jumlah      = max(1, (int) ($_POST['borrow_qty'] ?? 1));
    $tgl_mulai   = $_POST['borrow_start'] ?? date('Y-m-d');
    $tgl_selesai = $_POST['borrow_end'] ?? date('Y-m-d', strtotime('+1 day'));
    $waktu_ambil = trim($_POST['borrow_time'] ?? '09:00 WIB');
    $keperluan   = trim($_POST['borrow_purpose'] ?? '');

    $barangDipilih = null;
    foreach ($items as $it) {
        if ($it['id'] === $id_barang) {
            $barangDipilih = $it;
            break;
        }
    }

    if (!$barangDipilih) {
        $error_msg = 'Barang tidak ditemukan.';
    } elseif ($jumlah > $barangDipilih['stock']) {
        $error_msg = 'Jumlah melebihi stok tersedia.';
    } else {
        $hari = max(1, (int) (new DateTime($tgl_mulai))->diff(new DateTime($tgl_selesai))->format('%r%a'));
        $total = $barangDipilih['price'] * $jumlah * $hari;

        $lampiran = null;
        if (!empty($_FILES['borrow_doc']['name']) && $_FILES['borrow_doc']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['borrow_doc']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'], true) && $_FILES['borrow_doc']['size'] <= 2 * 1024 * 1024) {
                $dir = __DIR__ . '/../uploads/peminjaman';
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                $namaFile = 'doc_' . time() . '_' . rand(100, 999) . '.' . $ext;
                if (move_uploaded_file($_FILES['borrow_doc']['tmp_name'], $dir . '/' . $namaFile)) {
                    $lampiran = 'uploads/peminjaman/' . $namaFile;
                }
            }
        }

        $stmt = $conn->prepare("
            INSERT INTO `peminjaman` (
                kode_peminjaman, id_user, id_barang, nama_peminjam, nim_anggota,
                no_telepon, prodi_organisasi, jumlah_unit, tanggal_mulai, tanggal_selesai,
                waktu_pengambilan, keperluan, lampiran, total_biaya, status_peminjaman
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Menunggu Persetujuan')
        ");
        $stmt->bind_param(
            'siissssisssssi',
            $kode_transaksi, $id_user, $id_barang, $nama, $nim,
            $no_wa, $prodi_org, $jumlah, $tgl_mulai, $tgl_selesai,
            $waktu_ambil, $keperluan, $lampiran, $total
        );

        if ($stmt->execute()) {
            $berhasil = true;
            $info_sukses['item'] = $barangDipilih['name'] . " ({$jumlah} Unit)";
            $info_sukses['total'] = 'Rp' . number_format($total, 0, ',', '.');
        } else {
            $error_msg = 'Gagal menyimpan: ' . $stmt->error;
        }
        $stmt->close();
    }
}

$firstItem = $items[0] ?? [
    'id' => 0, 'name' => 'Belum ada barang', 'category' => '-',
    'price' => 0, 'stock' => 0, 'img' => '../assets/images/laptop.jpeg',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EBROW • Form Peminjaman Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/user.css" />
</head>
<body class="d-flex flex-column min-vh-100">

  <nav class="navbar navbar-expand-md ebrow-navbar shadow-sm py-3 px-4 px-lg-5 sticky-top">
    <div class="container-fluid px-0">
      <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-6" href="dashboard_user.php">
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
          <li class="nav-item"><a class="nav-link" href="status_peminjaman.php">Status Peminjaman</a></li>
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
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb small mb-0">
        <li class="breadcrumb-item"><a href="dashboard_user.php" class="ebrow-link text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="../katalog.php" class="ebrow-link text-decoration-none">Katalog Barang</a></li>
        <li class="breadcrumb-item active text-secondary">Form Peminjaman</li>
      </ol>
    </nav>

    <?php if ($error_msg !== ''): ?>
      <div class="alert alert-danger rounded-3 small mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error_msg) ?>
      </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
      <div class="alert alert-warning rounded-3 small mb-4">
        <i class="bi bi-info-circle-fill me-2"></i>Belum ada barang yang didaftarkan admin.
      </div>
    <?php endif; ?>

    <div class="row g-4 align-items-start">
      <div class="col-lg-8">
        <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
          <div class="mb-4">
            <span class="badge bg-light text-dark border mb-2 px-3 py-2 rounded-pill">Pengajuan Baru</span>
            <h1 class="h4 fw-bold text-dark mb-1">Form Peminjaman Barang</h1>
            <p class="small text-secondary mb-0">Lengkapi data peminjam, pilihan barang, serta jadwal penggunaan di bawah ini.</p>
          </div>

          <form id="borrowForm" method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id_barang" id="hidden-id-barang" value="<?= $firstItem['id'] ?>" />

            <div class="mb-4">
              <h2 class="h6 fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-badge me-2"></i>1. Data Peminjam
              </h2>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="borrow-name" class="form-label small fw-semibold">Nama Lengkap</label>
                  <input type="text" class="form-control ebrow-input" id="borrow-name" name="borrow_name" required />
                </div>
                <div class="col-md-6">
                  <label for="borrow-nim" class="form-label small fw-semibold">NIM / ID Anggota</label>
                  <input type="text" class="form-control ebrow-input" id="borrow-nim" name="borrow_nim" required />
                </div>
                <div class="col-md-6">
                  <label for="borrow-phone" class="form-label small fw-semibold">Nomor WhatsApp Aktif</label>
                  <input type="tel" class="form-control ebrow-input" id="borrow-phone" name="borrow_phone" placeholder="08xxxxxxxxxx" required />
                </div>
                <div class="col-md-6">
                  <label for="borrow-unit" class="form-label small fw-semibold">Program Studi / Organisasi</label>
                  <input type="text" class="form-control ebrow-input" id="borrow-unit" name="borrow_unit" required />
                </div>
              </div>
            </div>

            <div class="mb-4">
              <h2 class="h6 fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-box-seam me-2"></i>2. Detail Barang &amp; Jadwal Peminjaman
              </h2>
              <div class="row g-3">
                <div class="col-md-8">
                  <label for="borrow-item" class="form-label small fw-semibold">Pilih Barang</label>
                  <select class="form-select ebrow-input" id="borrow-item" onchange="updateSummary()" <?= empty($items) ? 'disabled' : 'required' ?>>
                    <?php if (empty($items)): ?>
                      <option value="">Belum ada barang terdaftar</option>
                    <?php else: ?>
                      <?php foreach ($items as $it): ?>
                        <option
                          value="<?= $it['id'] ?>"
                          data-id="<?= $it['id'] ?>"
                          data-name="<?= htmlspecialchars($it['name']) ?>"
                          data-category="<?= htmlspecialchars($it['category']) ?>"
                          data-price="<?= $it['price'] ?>"
                          data-stock="<?= $it['stock'] ?>"
                          data-img="<?= htmlspecialchars($it['img']) ?>"
                        >
                          <?= htmlspecialchars($it['name'] . ' (Rp' . number_format($it['price'], 0, ',', '.') . ' / hari • Stok: ' . $it['stock'] . ')') ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>

                <div class="col-md-4">
                  <label for="borrow-qty" class="form-label small fw-semibold">Jumlah Unit</label>
                  <input type="number" class="form-control ebrow-input" id="borrow-qty" name="borrow_qty" value="1" min="1" max="<?= max(1, $firstItem['stock']) ?>" onchange="updateSummary()" oninput="updateSummary()" <?= empty($items) ? 'disabled' : 'required' ?> />
                  <div class="form-text borrow-helper-text" id="stock-hint">Maksimal <?= $firstItem['stock'] ?> unit tersedia.</div>
                </div>

                <div class="col-md-4">
                  <label for="borrow-start" class="form-label small fw-semibold">Tanggal Mulai Pinjam</label>
                  <input type="date" class="form-control ebrow-input" id="borrow-start" name="borrow_start" onchange="updateSummary()" required />
                </div>

                <div class="col-md-4">
                  <label for="borrow-end" class="form-label small fw-semibold">Tanggal Pengembalian</label>
                  <input type="date" class="form-control ebrow-input" id="borrow-end" name="borrow_end" onchange="updateSummary()" required />
                </div>

                <div class="col-md-4">
                  <label for="borrow-time" class="form-label small fw-semibold">Waktu Pengambilan</label>
                  <select class="form-select ebrow-input" id="borrow-time" name="borrow_time" required>
                    <option value="09:00 WIB">09:00 WIB (Pagi)</option>
                    <option value="11:00 WIB">11:00 WIB (Siang)</option>
                    <option value="13:30 WIB">13:30 WIB (Siang)</option>
                    <option value="15:00 WIB">15:00 WIB (Sore)</option>
                  </select>
                </div>

                <div class="col-12">
                  <label for="borrow-purpose" class="form-label small fw-semibold">Keperluan / Nama Kegiatan</label>
                  <textarea class="form-control ebrow-input" id="borrow-purpose" name="borrow_purpose" rows="3" placeholder="Contoh: Digunakan untuk kegiatan praktikum dan presentasi tugas besar di gedung Fasilkom-TI." required></textarea>
                </div>

                <div class="col-12">
                  <label for="borrow-doc" class="form-label small fw-semibold">
                    Lampiran Surat Peminjaman / KTM <span class="text-secondary fw-normal">(Opsional)</span>
                  </label>
                  <input type="file" class="form-control ebrow-input" id="borrow-doc" name="borrow_doc" accept=".pdf,.jpg,.jpeg,.png" />
                  <div class="form-text borrow-helper-text">Format PDF, JPG, atau PNG (maks. 2 MB).</div>
                </div>
              </div>
            </div>

            <div class="ebrow-info-box rounded-3 p-3 mb-4">
              <div class="form-check small mb-0">
                <input class="form-check-input ebrow-checkbox" type="checkbox" id="agree-terms" required />
                <label class="form-check-label text-secondary" for="agree-terms">
                  Saya menyatakan data di atas benar dan bersedia menjaga kondisi barang serta mengembalikannya tepat waktu sesuai jadwal yang ditentukan.
                </label>
              </div>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
              <a href="../katalog.php" class="btn btn-outline-secondary px-4 py-2 fw-semibold">Batal</a>
              <button type="submit" class="btn btn-ebrow px-4 py-2 fw-semibold" <?= empty($items) ? 'disabled' : '' ?>>
                <i class="bi bi-send me-1"></i> Ajukan Peminjaman
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="bg-white rounded-4 shadow-sm p-4 borrow-summary-sticky">
          <h2 class="h6 fw-bold text-dark mb-3">Ringkasan Peminjaman</h2>

          <div class="borrow-preview-box rounded-4 p-3 text-center mb-3">
            <img src="<?= htmlspecialchars($firstItem['img']) ?>" alt="Preview Barang" id="summary-img" class="rounded-3 borrow-preview-img mb-2" />
            <span class="badge bg-white text-dark border rounded-pill px-3 py-1 small mb-1" id="summary-category"><?= htmlspecialchars($firstItem['category']) ?></span>
            <h3 class="h6 fw-bold text-dark mb-0" id="summary-name"><?= htmlspecialchars($firstItem['name']) ?></h3>
            <div class="small text-secondary mt-1" id="summary-rate">Rp<?= number_format($firstItem['price'], 0, ',', '.') ?> / hari</div>
          </div>

          <div class="small">
            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-secondary">Jumlah Unit</span>
              <span class="fw-semibold text-dark" id="summary-qty">1 Unit</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-secondary">Durasi Pinjam</span>
              <span class="fw-semibold text-dark" id="summary-duration">1 Hari</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-secondary">Lokasi Ambil</span>
              <span class="fw-semibold text-dark">Ruang Inventaris Lt. 1</span>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-3">
              <span class="fw-bold text-dark">Estimasi Total</span>
              <span class="h5 fw-bold mb-0 borrow-total-price" id="summary-total">Rp<?= number_format($firstItem['price'], 0, ',', '.') ?></span>
            </div>
          </div>

          <div class="ebrow-info-box rounded-3 p-3 small text-secondary mt-3">
            <strong class="d-block text-dark mb-1"><i class="bi bi-shield-check me-1"></i> Catatan Verifikasi:</strong>
            Pengajuan akan diproses admin maksimal 1x24 jam. Pantau persetujuan pada menu <strong>Status Peminjaman</strong>.
          </div>
        </div>
      </div>
    </div>
  </main>

  <div class="modal fade" id="successBorrowModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 border-0 shadow">
        <div class="modal-body text-center p-4 p-md-5">
          <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
            <i class="bi bi-check-circle-fill fs-1"></i>
          </div>
          <h4 class="fw-bold text-dark mb-1">Pengajuan Terkirim!</h4>
          <p class="small text-secondary mb-3">
            Permohonan peminjaman <strong class="text-dark"><?= htmlspecialchars($info_sukses['item']) ?></strong> telah masuk ke sistem dengan kode transaksi <strong class="text-dark"><?= htmlspecialchars($info_sukses['kode']) ?></strong>.
          </p>
          <div class="ebrow-info-box rounded-3 p-3 small text-secondary text-start mb-4">
            <div class="d-flex justify-content-between mb-1">
              <span>Status Saat Ini:</span>
              <span class="badge bg-warning text-dark rounded-pill">Menunggu Persetujuan</span>
            </div>
            <div class="d-flex justify-content-between">
              <span>Estimasi Total:</span>
              <span class="fw-bold text-dark"><?= htmlspecialchars($info_sukses['total']) ?></span>
            </div>
          </div>
          <div class="d-grid gap-2">
            <a href="status_peminjaman.php" class="btn btn-ebrow py-2 fw-semibold">Lihat Status Peminjaman</a>
            <a href="dashboard_user.php" class="btn btn-outline-secondary py-2 fw-semibold">Kembali ke Dashboard</a>
          </div>
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
    const fmt = n => 'Rp' + n.toLocaleString('id-ID');

    document.addEventListener('DOMContentLoaded', () => {
      const today = new Date();
      const tomorrow = new Date(today);
      tomorrow.setDate(tomorrow.getDate() + 1);
      const tStr = today.toISOString().split('T')[0];
      const nStr = tomorrow.toISOString().split('T')[0];

      const s = document.getElementById('borrow-start');
      const e = document.getElementById('borrow-end');
      s.min = tStr; s.value = tStr;
      e.min = tStr; e.value = nStr;

      const barangParam = new URLSearchParams(location.search).get('barang');
      if (barangParam) {
        const sel = document.getElementById('borrow-item');
        for (const opt of sel.options) {
          if (opt.textContent.toLowerCase().includes(barangParam.toLowerCase())) {
            sel.value = opt.value;
            break;
          }
        }
      }
      updateSummary();

      <?php if ($berhasil): ?>
        new bootstrap.Modal(document.getElementById('successBorrowModal')).show();
      <?php endif; ?>
    });

    function updateSummary() {
      const sel = document.getElementById('borrow-item');
      if (!sel || sel.disabled || !sel.options.length) return;
      const opt = sel.options[sel.selectedIndex];

      const price = parseInt(opt.dataset.price, 10) || 0;
      const stock = Math.max(1, parseInt(opt.dataset.stock, 10) || 1);
      document.getElementById('hidden-id-barang').value = opt.dataset.id;

      const qtyEl = document.getElementById('borrow-qty');
      qtyEl.max = stock;
      let qty = parseInt(qtyEl.value, 10) || 1;
      if (qty > stock) qty = stock;
      if (qty < 1) qty = 1;
      qtyEl.value = qty;
      document.getElementById('stock-hint').textContent = `Maksimal ${stock} unit tersedia.`;

      const s = document.getElementById('borrow-start');
      const e = document.getElementById('borrow-end');
      e.min = s.value;

      let days = 1;
      if (s.value && e.value) {
        days = Math.max(1, Math.ceil((new Date(e.value) - new Date(s.value)) / 86400000));
      }

      document.getElementById('summary-img').src = opt.dataset.img;
      document.getElementById('summary-category').textContent = opt.dataset.category;
      document.getElementById('summary-name').textContent = opt.dataset.name;
      document.getElementById('summary-rate').textContent = `${fmt(price)} / hari`;
      document.getElementById('summary-qty').textContent = `${qty} Unit`;
      document.getElementById('summary-duration').textContent = `${days} Hari`;
      document.getElementById('summary-total').textContent = fmt(price * qty * days);
    }
  </script>
</body>
</html>