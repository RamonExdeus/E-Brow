<?php
session_start();
require_once __DIR__ . '/../database/repository.php';

/** Path gambar di database relatif terhadap root aplikasi, bukan folder user/. */
function urlGambarBarang($path): string
{
    $path = trim((string) $path);
    if ($path === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#i', $path) || $path[0] === '/') {
        return $path;
    }

    return '../' . ltrim($path, '/');
}

/** Slug sederhana untuk menerima ?barang=nama-barang dari halaman lain. */
function slugBarang(string $nama): string
{
    $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($nama)) ?? '';

    return trim($slug, '-');
}

$waktuOptions = [
    '09:00 WIB' => '09:00 WIB (Pagi)',
    '11:00 WIB' => '11:00 WIB (Siang)',
    '13:30 WIB' => '13:30 WIB (Siang)',
    '15:00 WIB' => '15:00 WIB (Sore)',
];

$barangList = fetchBarangUntukPeminjaman();
$tidakAdaBarang = $barangList === [];

$idUser = (int) ($_SESSION['id_user'] ?? 0);
$peminjam = $idUser > 0 ? fetchUserById($idUser) : null;

$berhasil = false;
$error_msg = '';
$info_sukses = [
    'item' => '-',
    'total' => '-',
    'kode' => '-',
];

$pilihanBarang = (string) ($_POST['id_barang'] ?? $_GET['barang'] ?? '');
$selectedBarang = null;
foreach ($barangList as $barang) {
    if ((string) $barang['id_barang'] === $pilihanBarang ||
        slugBarang((string) $barang['nama_barang']) === strtolower($pilihanBarang)) {
        $selectedBarang = $barang;
        break;
    }
}
if ($selectedBarang === null && !$tidakAdaBarang) {
    $selectedBarang = $barangList[0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idBarang = (int) ($_POST['id_barang'] ?? 0);
    $barangDipilih = null;
    foreach ($barangList as $barang) {
        if ((int) $barang['id_barang'] === $idBarang) {
            $barangDipilih = $barang;
            break;
        }
    }

    if ($barangDipilih === null) {
        $error_msg = 'Barang yang dipilih tidak tersedia.';
    } else {
        $waktuPengambilan = (string) ($_POST['borrow_time'] ?? '');
        if (!array_key_exists($waktuPengambilan, $waktuOptions)) {
            $waktuPengambilan = '';
        }

        $hasil = ajukanPeminjaman(
            $idUser,
            $idBarang,
            (int) ($_POST['borrow_qty'] ?? 1),
            (string) ($_POST['borrow_start'] ?? ''),
            (string) ($_POST['borrow_end'] ?? ''),
            (string) ($_POST['borrow_purpose'] ?? ''),
            $waktuPengambilan,
            $_FILES['borrow_doc'] ?? []
        );

        if ($hasil['ok']) {
            $berhasil = true;
            $selectedBarang = $barangDipilih;
            $info_sukses = [
                'item' => $hasil['nama_barang'] . ' (' . $hasil['jumlah_unit'] . ' Unit)',
                'total' => 'Rp' . number_format($hasil['total'], 0, ',', '.'),
                'kode' => $hasil['nomor'],
            ];
        } else {
            $error_msg = $hasil['message'];
        }
    }
}

$namaPeminjam = $peminjam['nama_lengkap'] ?? trim((string) ($_POST['borrow_name'] ?? ''));
$nimPeminjam = $peminjam['nim_anggota'] ?? trim((string) ($_POST['borrow_nim'] ?? ''));
$teleponPeminjam = $peminjam['no_telepon'] ?? trim((string) ($_POST['borrow_phone'] ?? ''));
$prodiPeminjam = $peminjam['prodi_organisasi'] ?? trim((string) ($_POST['borrow_unit'] ?? ''));
$keperluan = trim((string) ($_POST['borrow_purpose'] ?? ''));
$waktuTerpilih = (string) ($_POST['borrow_time'] ?? '');
if (!array_key_exists($waktuTerpilih, $waktuOptions)) {
    $waktuTerpilih = array_key_first($waktuOptions);
}

$selectedId = $selectedBarang ? (int) $selectedBarang['id_barang'] : 0;
$hargaBarang = $selectedBarang ? (float) $selectedBarang['harga_per_hari'] : 0.0;
$stokBarang = $selectedBarang ? (int) $selectedBarang['stok_tersedia'] : 0;
$gambarBarang = $selectedBarang ? urlGambarBarang($selectedBarang['foto_barang']) : '';
$kategoriBarang = $selectedBarang['nama_kategori'] ?? '-';
$namaBarang = $selectedBarang['nama_barang'] ?? '-';
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

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser" aria-controls="navbarUser" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarUser">
        <ul class="navbar-nav align-items-md-center gap-md-3 mt-3 mt-md-0">
          <li class="nav-item"><a class="nav-link" href="dashboard_user.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../katalog.php">Katalog Barang</a></li>
          <li class="nav-item"><a class="nav-link" href="status_peminjaman.php">Status Peminjaman</a></li>
          <li class="nav-item"><a class="nav-link" href="riwayat_peminjaman.php">Riwayat</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
        <li class="breadcrumb-item active text-secondary" aria-current="page">Form Peminjaman</li>
      </ol>
    </nav>

    <?php if ($error_msg !== ''): ?>
      <div class="alert alert-danger rounded-3 small mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc($error_msg) ?>
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
            <input type="hidden" name="id_barang" id="hidden-id-barang" value="<?= esc($selectedId) ?>" />

            <div class="mb-4">
              <h2 class="h6 fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-badge me-2"></i>1. Data Peminjam
              </h2>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="borrow-name" class="form-label small fw-semibold">Nama Lengkap</label>
                  <input type="text" class="form-control ebrow-input" id="borrow-name" name="borrow_name" value="<?= esc($namaPeminjam) ?>" required />
                </div>
                <div class="col-md-6">
                  <label for="borrow-nim" class="form-label small fw-semibold">NIM / ID Anggota</label>
                  <input type="text" class="form-control ebrow-input" id="borrow-nim" name="borrow_nim" value="<?= esc($nimPeminjam) ?>" required />
                </div>
                <div class="col-md-6">
                  <label for="borrow-phone" class="form-label small fw-semibold">Nomor WhatsApp Aktif</label>
                  <input type="tel" class="form-control ebrow-input" id="borrow-phone" name="borrow_phone" value="<?= esc($teleponPeminjam) ?>" placeholder="08xxxxxxxxxx" required />
                </div>
                <div class="col-md-6">
                  <label for="borrow-unit" class="form-label small fw-semibold">Program Studi / Organisasi</label>
                  <input type="text" class="form-control ebrow-input" id="borrow-unit" name="borrow_unit" value="<?= esc($prodiPeminjam) ?>" required />
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
                  <select class="form-select ebrow-input" id="borrow-item" name="borrow_item" onchange="updateSummary()" <?= $tidakAdaBarang ? 'disabled' : '' ?> required>
                    <?php if ($tidakAdaBarang): ?>
                      <option value="">Belum ada barang tersedia</option>
                    <?php else: ?>
                      <?php foreach ($barangList as $barang): ?>
                        <?php $harga = (float) $barang['harga_per_hari']; ?>
                        <option
                          value="<?= esc($barang['id_barang']) ?>"
                          data-id="<?= esc($barang['id_barang']) ?>"
                          data-name="<?= esc($barang['nama_barang']) ?>"
                          data-category="<?= esc($barang['nama_kategori']) ?>"
                          data-price="<?= esc(number_format($harga, 0, '', '')) ?>"
                          data-stock="<?= esc($barang['stok_tersedia']) ?>"
                          data-img="<?= esc(urlGambarBarang($barang['foto_barang'])) ?>"
                          <?= (int) $barang['id_barang'] === $selectedId ? 'selected' : '' ?>
                        >
                          <?= esc($barang['nama_barang']) ?> (Rp<?= number_format($harga, 0, ',', '.') ?> / hari • Stok: <?= (int) $barang['stok_tersedia'] ?>)
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>

                <div class="col-md-4">
                  <label for="borrow-qty" class="form-label small fw-semibold">Jumlah Unit</label>
                  <input type="number" class="form-control ebrow-input" id="borrow-qty" name="borrow_qty" value="1" min="1" max="<?= esc(max(1, $stokBarang)) ?>" onchange="updateSummary()" oninput="updateSummary()" required />
                  <div class="form-text borrow-helper-text" id="stock-hint">
                    <?= $tidakAdaBarang ? 'Belum ada barang tersedia.' : 'Maksimal ' . $stokBarang . ' unit tersedia.' ?>
                  </div>
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
                    <?php foreach ($waktuOptions as $nilai => $label): ?>
                      <option value="<?= esc($nilai) ?>" <?= $nilai === $waktuTerpilih ? 'selected' : '' ?>><?= esc($label) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-12">
                  <label for="borrow-purpose" class="form-label small fw-semibold">Keperluan / Nama Kegiatan</label>
                  <textarea class="form-control ebrow-input" id="borrow-purpose" name="borrow_purpose" rows="3" placeholder="Contoh: Digunakan untuk kegiatan praktikum dan presentasi tugas besar di gedung Fasilkom-TI." required><?= esc($keperluan) ?></textarea>
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
                <input class="form-check-input ebrow-checkbox" type="checkbox" id="agree-terms" name="agree_terms" required />
                <label class="form-check-label text-secondary" for="agree-terms">
                  Saya menyatakan data di atas benar dan bersedia menjaga kondisi barang serta mengembalikannya tepat waktu sesuai jadwal yang ditentukan.
                </label>
              </div>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
              <a href="../katalog.php" class="btn btn-outline-secondary px-4 py-2 fw-semibold">Batal</a>
              <button type="submit" class="btn btn-ebrow px-4 py-2 fw-semibold" <?= $tidakAdaBarang ? 'disabled' : '' ?>>
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
            <?php if ($gambarBarang !== ''): ?>
              <img src="<?= esc($gambarBarang) ?>" alt="Preview Barang" id="summary-img" class="rounded-3 borrow-preview-img mb-2" />
              <div id="summary-icon" class="rounded-3 d-flex align-items-center justify-content-center bg-light text-secondary borrow-preview-img mb-2 d-none">
                <i class="bi bi-box fs-1"></i>
              </div>
            <?php else: ?>
              <img alt="Preview Barang" id="summary-img" class="rounded-3 borrow-preview-img mb-2 d-none" />
              <div id="summary-icon" class="rounded-3 d-flex align-items-center justify-content-center bg-light text-secondary borrow-preview-img mb-2">
                <i class="bi bi-box fs-1"></i>
              </div>
            <?php endif; ?>
            <span class="badge bg-white text-dark border rounded-pill px-3 py-1 small mb-1" id="summary-category"><?= esc($kategoriBarang) ?></span>
            <h3 class="h6 fw-bold text-dark mb-0" id="summary-name"><?= esc($namaBarang) ?></h3>
            <div class="small text-secondary mt-1" id="summary-rate">
              <?= $selectedBarang ? 'Rp' . number_format($hargaBarang, 0, ',', '.') . ' / hari' : '-' ?>
            </div>
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
              <span class="h5 fw-bold mb-0 borrow-total-price" id="summary-total">Rp<?= number_format($hargaBarang, 0, ',', '.') ?></span>
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
            Permohonan peminjaman <strong class="text-dark"><?= esc($info_sukses['item']) ?></strong> telah masuk ke sistem dengan nomor <strong class="text-dark"><?= esc($info_sukses['kode']) ?></strong>.
          </p>
          <div class="ebrow-info-box rounded-3 p-3 small text-secondary text-start mb-4">
            <div class="d-flex justify-content-between mb-1">
              <span>Status Saat Ini:</span>
              <span class="badge bg-warning text-dark rounded-pill">Menunggu Persetujuan</span>
            </div>
            <div class="d-flex justify-content-between">
              <span>Estimasi Total:</span>
              <span class="fw-bold text-dark"><?= esc($info_sukses['total']) ?></span>
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
    function formatRupiah(angka) {
      return 'Rp' + Number(angka || 0).toLocaleString('id-ID');
    }

    function tanggalYmd(date) {
      const tahun = date.getFullYear();
      const bulan = String(date.getMonth() + 1).padStart(2, '0');
      const hari = String(date.getDate()).padStart(2, '0');
      return `${tahun}-${bulan}-${hari}`;
    }

    function slugBarang(teks) {
      return String(teks || '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    }

    document.addEventListener('DOMContentLoaded', () => {
      const today = new Date();
      const tomorrow = new Date(today);
      tomorrow.setDate(tomorrow.getDate() + 1);

      const startInput = document.getElementById('borrow-start');
      const endInput = document.getElementById('borrow-end');

      const todayStr = tanggalYmd(today);
      const tomorrowStr = tanggalYmd(tomorrow);

      startInput.min = todayStr;
      if (!startInput.value) startInput.value = todayStr;
      endInput.min = startInput.value;
      if (!endInput.value) endInput.value = tomorrowStr;

      const selectEl = document.getElementById('borrow-item');
      const barangParam = new URLSearchParams(window.location.search).get('barang');
      const sedangPosting = <?= $_SERVER['REQUEST_METHOD'] === 'POST' ? 'true' : 'false' ?>;

      if (barangParam && !sedangPosting && !selectEl.disabled) {
        for (let opt of selectEl.options) {
          const cocok = opt.value === barangParam ||
            slugBarang(opt.getAttribute('data-name')) === barangParam.toLowerCase();
          if (cocok) {
            selectEl.value = opt.value;
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
      const el = (id) => document.getElementById(id);
      const selectEl = el('borrow-item');
      const selectedOpt = selectEl.options[selectEl.selectedIndex];

      if (!selectedOpt || !selectedOpt.getAttribute('data-id')) {
        el('hidden-id-barang').value = '';
        el('stock-hint').textContent = 'Belum ada barang tersedia.';
        el('summary-img').classList.add('d-none');
        el('summary-icon').classList.remove('d-none');
        el('summary-category').textContent = '-';
        el('summary-name').textContent = '-';
        el('summary-rate').textContent = '-';
        el('summary-qty').textContent = '-';
        el('summary-duration').textContent = '-';
        el('summary-total').textContent = 'Rp0';
        return;
      }

      const itemId = selectedOpt.getAttribute('data-id');
      const itemName = selectedOpt.getAttribute('data-name');
      const itemCategory = selectedOpt.getAttribute('data-category');
      const itemPrice = parseInt(selectedOpt.getAttribute('data-price'), 10) || 0;
      const itemStock = parseInt(selectedOpt.getAttribute('data-stock'), 10) || 1;
      const itemImg = selectedOpt.getAttribute('data-img');

      el('hidden-id-barang').value = itemId;

      const qtyInput = el('borrow-qty');
      qtyInput.max = itemStock;
      if (parseInt(qtyInput.value, 10) > itemStock) qtyInput.value = itemStock;
      if (parseInt(qtyInput.value, 10) < 1 || isNaN(parseInt(qtyInput.value, 10))) qtyInput.value = 1;
      const qty = parseInt(qtyInput.value, 10);
      el('stock-hint').textContent = `Maksimal ${itemStock} unit tersedia.`;

      const startInput = el('borrow-start');
      const endInput = el('borrow-end');
      endInput.min = startInput.value || tanggalYmd(new Date());

      let days = 1;
      if (startInput.value && endInput.value) {
        const diffDays = Math.ceil((new Date(endInput.value) - new Date(startInput.value)) / (1000 * 60 * 60 * 24));
        days = diffDays >= 1 ? diffDays : 1;
      }

      const totalCost = itemPrice * qty * days;

      if (itemImg) {
        el('summary-img').src = itemImg;
        el('summary-img').classList.remove('d-none');
        el('summary-icon').classList.add('d-none');
      } else {
        el('summary-img').removeAttribute('src');
        el('summary-img').classList.add('d-none');
        el('summary-icon').classList.remove('d-none');
      }
      el('summary-category').textContent = itemCategory;
      el('summary-name').textContent = itemName;
      el('summary-rate').textContent = `${formatRupiah(itemPrice)} / hari`;
      el('summary-qty').textContent = `${qty} Unit`;
      el('summary-duration').textContent = `${days} Hari`;
      el('summary-total').textContent = formatRupiah(totalCost);
    }
  </script>
</body>
</html>
