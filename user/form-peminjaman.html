<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EBROW • Form Peminjaman Barang</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- External CSS User (Sesuai struktur folder assets/css/user.css) -->
  <link rel="stylesheet" href="../assets/css/user.css" />
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- Navbar Pengguna -->
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
          <li class="nav-item">
            <a class="nav-link" href="dashboard_user.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../katalog.php">Katalog Barang</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="status_peminjaman.php">Status Peminjaman</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="riwayat_peminjaman.php">Riwayat</a>
          </li>
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

  <!-- Main Content -->
  <main class="container my-4 flex-grow-1">
    <!-- Breadcrumb Navigasi -->
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb small mb-0">
        <li class="breadcrumb-item"><a href="dashboard_user.php" class="ebrow-link text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="../katalog.php" class="ebrow-link text-decoration-none">Katalog Barang</a></li>
        <li class="breadcrumb-item active text-secondary" aria-current="page">Form Peminjaman</li>
      </ol>
    </nav>

    <div class="row g-4 align-items-start">
      <!-- Kolom Kiri: Form Pengajuan Peminjaman -->
      <div class="col-lg-8">
        <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
          <div class="mb-4">
            <span class="badge bg-light text-dark border mb-2 px-3 py-2 rounded-pill">Pengajuan Baru</span>
            <h1 class="h4 fw-bold text-dark mb-1">Form Peminjaman Barang</h1>
            <p class="small text-secondary mb-0">Lengkapi data peminjam, pilihan barang, serta jadwal penggunaan di bawah ini.</p>
          </div>

          <form id="borrowForm" onsubmit="submitBorrowForm(event)">
            <!-- Bagian 1: Identitas Peminjam -->
            <div class="mb-4">
              <h2 class="h6 fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-badge me-2"></i>1. Data Peminjam
              </h2>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="borrow-name" class="form-label small fw-semibold">Nama Lengkap</label>
                  <input type="text" class="form-control ebrow-input" id="borrow-name" value="Mahasiswa Pengguna" required />
                </div>
                <div class="col-md-6">
                  <label for="borrow-nim" class="form-label small fw-semibold">NIM / ID Anggota</label>
                  <input type="text" class="form-control ebrow-input" id="borrow-nim" value="261402138" required />
                </div>
                <div class="col-md-6">
                  <label for="borrow-phone" class="form-label small fw-semibold">Nomor WhatsApp Aktif</label>
                  <input type="tel" class="form-control ebrow-input" id="borrow-phone" value="081234567890" placeholder="08xxxxxxxxxx" required />
                </div>
                <div class="col-md-6">
                  <label for="borrow-unit" class="form-label small fw-semibold">Program Studi / Organisasi</label>
                  <input type="text" class="form-control ebrow-input" id="borrow-unit" value="S1 Teknologi Informasi - Fasilkom-TI" required />
                </div>
              </div>
            </div>

            <!-- Bagian 2: Detail Barang & Jadwal -->
            <div class="mb-4">
              <h2 class="h6 fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-box-seam me-2"></i>2. Detail Barang &amp; Jadwal Peminjaman
              </h2>
              <div class="row g-3">
                <div class="col-md-8">
                  <label for="borrow-item" class="form-label small fw-semibold">Pilih Barang</label>
                  <select class="form-select ebrow-input" id="borrow-item" onchange="updateSummary()" required>
                    <option value="laptop" data-name="Laptop" data-category="Elektronik" data-price="30000" data-stock="5" data-img="../assets/images/laptop.jpeg">
                      Laptop Asus VivoBook (Rp30.000 / hari • Stok: 5)
                    </option>
                    <option value="kamera" data-name="Kamera DSLR Canon" data-category="Elektronik" data-price="20000" data-stock="3" data-img="../assets/images/kamera.jpeg">
                      Kamera DSLR Canon (Rp20.000 / hari • Stok: 3)
                    </option>
                    <option value="speaker" data-name="Speaker Portable Aktif" data-category="Elektronik" data-price="15000" data-stock="4" data-img="../assets/images/speaker.jpeg">
                      Speaker Portable Aktif (Rp15.000 / hari • Stok: 4)
                    </option>
                    <option value="mic" data-name="Microphone Wireless" data-category="Elektronik" data-price="15000" data-stock="6" data-img="../assets/images/mic.jpeg">
                      Microphone Wireless (Rp15.000 / hari • Stok: 6)
                    </option>
                    <option value="proyektor" data-name="Proyektor Epson EB-X500" data-category="Presentasi" data-price="25000" data-stock="3" data-img="../assets/images/proyektor.jpeg">
                      Proyektor Epson EB-X500 (Rp25.000 / hari • Stok: 3)
                    </option>
                    <option value="tripod" data-name="Tripod Takara Heavy Duty" data-category="Dokumentasi" data-price="10000" data-stock="4" data-img="../assets/images/tripod.jpeg">
                      Tripod Takara Heavy Duty (Rp10.000 / hari • Stok: 4)
                    </option>
                    <option value="meja" data-name="Meja Lipat Portable" data-category="Perlengkapan Acara" data-price="10000" data-stock="10" data-img="../assets/images/meja.jpeg">
                      Meja Lipat Portable (Rp10.000 / hari • Stok: 10)
                    </option>
                    <option value="kursi" data-name="Kursi Lipat Acara" data-category="Perlengkapan Acara" data-price="5000" data-stock="25" data-img="../assets/images/kursi.jpeg">
                      Kursi Lipat Acara (Rp5.000 / hari • Stok: 25)
                    </option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label for="borrow-qty" class="form-label small fw-semibold">Jumlah Unit</label>
                  <input type="number" class="form-control ebrow-input" id="borrow-qty" value="1" min="1" max="5" onchange="updateSummary()" oninput="updateSummary()" required />
                  <div class="form-text borrow-helper-text" id="stock-hint">Maksimal 5 unit tersedia.</div>
                </div>

                <div class="col-md-4">
                  <label for="borrow-start" class="form-label small fw-semibold">Tanggal Mulai Pinjam</label>
                  <input type="date" class="form-control ebrow-input" id="borrow-start" onchange="updateSummary()" required />
                </div>

                <div class="col-md-4">
                  <label for="borrow-end" class="form-label small fw-semibold">Tanggal Pengembalian</label>
                  <input type="date" class="form-control ebrow-input" id="borrow-end" onchange="updateSummary()" required />
                </div>

                <div class="col-md-4">
                  <label for="borrow-time" class="form-label small fw-semibold">Waktu Pengambilan</label>
                  <select class="form-select ebrow-input" id="borrow-time" required>
                    <option value="09:00 WIB">09:00 WIB (Pagi)</option>
                    <option value="11:00 WIB">11:00 WIB (Siang)</option>
                    <option value="13:30 WIB">13:30 WIB (Siang)</option>
                    <option value="15:00 WIB">15:00 WIB (Sore)</option>
                  </select>
                </div>

                <div class="col-12">
                  <label for="borrow-purpose" class="form-label small fw-semibold">Keperluan / Nama Kegiatan</label>
                  <textarea class="form-control ebrow-input" id="borrow-purpose" rows="3" placeholder="Contoh: Digunakan untuk kegiatan praktikum dan presentasi tugas besar di gedung Fasilkom-TI." required></textarea>
                </div>

                <div class="col-12">
                  <label for="borrow-doc" class="form-label small fw-semibold">
                    Lampiran Surat Peminjaman / KTM <span class="text-secondary fw-normal">(Opsional)</span>
                  </label>
                  <input type="file" class="form-control ebrow-input" id="borrow-doc" accept=".pdf,.jpg,.jpeg,.png" />
                  <div class="form-text borrow-helper-text">Format PDF, JPG, atau PNG (maks. 2 MB).</div>
                </div>
              </div>
            </div>

            <!-- Persetujuan Syarat -->
            <div class="ebrow-info-box rounded-3 p-3 mb-4">
              <div class="form-check small mb-0">
                <input class="form-check-input ebrow-checkbox" type="checkbox" id="agree-terms" required />
                <label class="form-check-label text-secondary" for="agree-terms">
                  Saya menyatakan data di atas benar dan bersedia menjaga kondisi barang serta mengembalikannya tepat waktu sesuai jadwal yang ditentukan.
                </label>
              </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
              <a href="../katalog.php" class="btn btn-outline-secondary px-4 py-2 fw-semibold">Batal</a>
              <button type="submit" class="btn btn-ebrow px-4 py-2 fw-semibold">
                <i class="bi bi-send me-1"></i> Ajukan Peminjaman
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Kolom Kanan: Ringkasan & Preview Barang -->
      <div class="col-lg-4">
        <div class="bg-white rounded-4 shadow-sm p-4 borrow-summary-sticky">
          <h2 class="h6 fw-bold text-dark mb-3">Ringkasan Peminjaman</h2>

          <!-- Preview Card Barang -->
          <div class="borrow-preview-box rounded-4 p-3 text-center mb-3">
            <img src="../assets/images/laptop.jpeg" alt="Preview Barang" id="summary-img" class="rounded-3 borrow-preview-img mb-2" />
            <span class="badge bg-white text-dark border rounded-pill px-3 py-1 small mb-1" id="summary-category">Elektronik</span>
            <h3 class="h6 fw-bold text-dark mb-0" id="summary-name">Laptop Asus VivoBook</h3>
            <div class="small text-secondary mt-1" id="summary-rate">Rp30.000 / hari</div>
          </div>

          <!-- Rincian Perhitungan -->
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
              <span class="h5 fw-bold mb-0 borrow-total-price" id="summary-total">Rp30.000</span>
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

  <!-- Modal Konfirmasi Pengajuan Berhasil -->
  <div class="modal fade" id="successBorrowModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 border-0 shadow">
        <div class="modal-body text-center p-4 p-md-5">
          <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
            <i class="bi bi-check-circle-fill fs-1"></i>
          </div>
          <h4 class="fw-bold text-dark mb-1">Pengajuan Terkirim!</h4>
          <p class="small text-secondary mb-3">
            Permohonan peminjaman <strong class="text-dark" id="modal-success-item">-</strong> telah masuk ke sistem dengan kode transaksi <strong class="text-dark">#BRW-017</strong>.
          </p>
          <div class="ebrow-info-box rounded-3 p-3 small text-secondary text-start mb-4">
            <div class="d-flex justify-content-between mb-1">
              <span>Status Saat Ini:</span>
              <span class="badge bg-warning text-dark rounded-pill">Menunggu Persetujuan</span>
            </div>
            <div class="d-flex justify-content-between">
              <span>Estimasi Total:</span>
              <span class="fw-bold text-dark" id="modal-success-total">-</span>
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

  <!-- Footer -->
  <footer class="container py-3 text-muted small d-flex flex-column flex-sm-row justify-content-between gap-2">
    <span>&copy; 2026 EBROW. Semua hak dilindungi.</span>
    <span>Pinjam dengan mudah. Kembalikan dengan tanggung jawab.</span>
  </footer>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function formatRupiah(angka) {
      return 'Rp' + angka.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', () => {
      const today = new Date();
      const tomorrow = new Date(today);
      tomorrow.setDate(tomorrow.getDate() + 1);

      const startInput = document.getElementById('borrow-start');
      const endInput = document.getElementById('borrow-end');

      const todayStr = today.toISOString().split('T')[0];
      const tomorrowStr = tomorrow.toISOString().split('T')[0];

      startInput.min = todayStr;
      startInput.value = todayStr;
      endInput.min = todayStr;
      endInput.value = tomorrowStr;

      const params = new URLSearchParams(window.location.search);
      const barangParam = params.get('barang');
      if (barangParam) {
        const selectEl = document.getElementById('borrow-item');
        for (let opt of selectEl.options) {
          if (opt.value === barangParam.toLowerCase()) {
            selectEl.value = opt.value;
            break;
          }
        }
      }

      updateSummary();
    });

    function updateSummary() {
      const selectEl = document.getElementById('borrow-item');
      const selectedOpt = selectEl.options[selectEl.selectedIndex];

      const itemName = selectedOpt.getAttribute('data-name');
      const itemCategory = selectedOpt.getAttribute('data-category');
      const itemPrice = parseInt(selectedOpt.getAttribute('data-price'), 10);
      const itemStock = parseInt(selectedOpt.getAttribute('data-stock'), 10);
      const itemImg = selectedOpt.getAttribute('data-img');

      const qtyInput = document.getElementById('borrow-qty');
      qtyInput.max = itemStock;
      if (parseInt(qtyInput.value, 10) > itemStock) {
        qtyInput.value = itemStock;
      }
      if (parseInt(qtyInput.value, 10) < 1 || isNaN(parseInt(qtyInput.value, 10))) {
        qtyInput.value = 1;
      }
      const qty = parseInt(qtyInput.value, 10);
      document.getElementById('stock-hint').textContent = `Maksimal ${itemStock} unit tersedia.`;

      const startInput = document.getElementById('borrow-start');
      const endInput = document.getElementById('borrow-end');
      endInput.min = startInput.value;

      let days = 1;
      if (startInput.value && endInput.value) {
        const startDate = new Date(startInput.value);
        const endDate = new Date(endInput.value);
        const diffTime = endDate - startDate;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        days = diffDays >= 1 ? diffDays : 1;
      }

      const totalCost = itemPrice * qty * days;

      document.getElementById('summary-img').src = itemImg;
      document.getElementById('summary-category').textContent = itemCategory;
      document.getElementById('summary-name').textContent = itemName;
      document.getElementById('summary-rate').textContent = `${formatRupiah(itemPrice)} / hari`;
      document.getElementById('summary-qty').textContent = `${qty} Unit`;
      document.getElementById('summary-duration').textContent = `${days} Hari`;
      document.getElementById('summary-total').textContent = formatRupiah(totalCost);
    }

    function submitBorrowForm(event) {
      event.preventDefault();
      const itemName = document.getElementById('summary-name').textContent;
      const qtyText = document.getElementById('summary-qty').textContent;
      const totalText = document.getElementById('summary-total').textContent;

      document.getElementById('modal-success-item').textContent = `${itemName} (${qtyText})`;
      document.getElementById('modal-success-total').textContent = totalText;

      const modal = new bootstrap.Modal(document.getElementById('successBorrowModal'));
      modal.show();
    }
  </script>
</body>
</html>