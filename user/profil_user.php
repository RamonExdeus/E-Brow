<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EBROW • Profil Pengguna</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- External CSS User -->
  <link rel="stylesheet" href="../assets/css/user.css" />
</head>
<body class="d-flex flex-column min-vh-100">
    
  <!-- Navbar Pengguna -->
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
            <a class="nav-link dropdown-toggle active fw-semibold d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle fs-5"></i>
              <span>Akun Saya</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
              <li><a class="dropdown-item py-2 active" href="profil_user.php"><i class="bi bi-person me-2"></i>Profil Pengguna</a></li>
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
    <div class="row g-4">
      <!-- Kolom Kiri: Kartu Ringkasan Profil -->
      <div class="col-lg-4">
        <div class="bg-white rounded-4 shadow-sm p-4 text-center">
          <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 user-avatar-circle">
            <i class="bi bi-person-fill text-dark user-avatar-icon"></i>
          </div>

          <h1 class="h5 fw-bold text-dark mb-1" id="display-name">Mahasiswa Pengguna</h1>
          <p class="small text-secondary mb-2" id="display-email">mahasiswa@ebrow.ac.id</p>
          <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 small">
            Akun Terverifikasi
          </span>

          <hr class="my-4" />

          <div class="row text-center small">
            <div class="col-6 border-end">
              <div class="h5 fw-bold text-dark mb-0">8</div>
              <div class="text-secondary">Total Pinjam</div>
            </div>
            <div class="col-6">
              <div class="h5 fw-bold text-success mb-0">100%</div>
              <div class="text-secondary">Tepat Waktu</div>
            </div>
          </div>

          <div class="ebrow-info-box rounded-3 p-3 small text-secondary text-start mt-4">
            <strong class="d-block text-dark mb-1">ID Pengguna:</strong>
            <span id="display-nim">261402138</span> &bull; Pengguna Umum
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Form Edit Data & Password -->
      <div class="col-lg-8">
        <!-- Form Informasi Pribadi -->
        <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5 mb-4">
          <h2 class="h5 fw-bold text-dark mb-1">Informasi Pribadi</h2>
          <p class="small text-secondary mb-4">Perbarui data diri dan kontak aktif untuk keperluan konfirmasi peminjaman.</p>

          <form onsubmit="saveProfile(event)">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="prof-name" class="form-label small fw-semibold">Nama Lengkap</label>
                <input type="text" class="form-control ebrow-input" id="prof-name" value="Mahasiswa Pengguna" required />
              </div>
              <div class="col-md-6">
                <label for="prof-nim" class="form-label small fw-semibold">NIM / ID Anggota</label>
                <input type="text" class="form-control ebrow-input" id="prof-nim" value="261402138" required />
              </div>
              <div class="col-md-6">
                <label for="prof-email" class="form-label small fw-semibold">Email</label>
                <input type="email" class="form-control ebrow-input" id="prof-email" value="mahasiswa@ebrow.ac.id" required />
              </div>
              <div class="col-md-6">
                <label for="prof-phone" class="form-label small fw-semibold">Nomor Telepon / WhatsApp</label>
                <input type="tel" class="form-control ebrow-input" id="prof-phone" value="081234567890" required />
              </div>
              <div class="col-12">
                <label for="prof-unit" class="form-label small fw-semibold">Program Studi / Unit Organisasi</label>
                <input type="text" class="form-control ebrow-input" id="prof-unit" value="S1 Teknologi Informasi - Fasilkom-TI" />
              </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
              <button type="submit" class="btn btn-ebrow px-4 py-2 fw-semibold">Simpan Perubahan</button>
            </div>
          </form>
        </div>

        <!-- Form Keamanan / Ubah Password -->
        <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
          <h2 class="h5 fw-bold text-dark mb-1">Keamanan Akun</h2>
          <p class="small text-secondary mb-4">Ubah kata sandi secara berkala untuk menjaga keamanan akunmu.</p>

          <form onsubmit="changePassword(event)">
            <div class="mb-3">
              <label for="old-pass" class="form-label small fw-semibold">Password Saat Ini</label>
              <div class="position-relative">
                <input type="password" class="form-control ebrow-input pe-5" id="old-pass" placeholder="Masukkan password lama" required />
                <button type="button" class="btn btn-link ebrow-password-toggle text-secondary p-0" onclick="togglePassword('old-pass', this)">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="new-pass" class="form-label small fw-semibold">Password Baru</label>
                <div class="position-relative">
                  <input type="password" class="form-control ebrow-input pe-5" id="new-pass" placeholder="Minimal 8 karakter" minlength="8" required />
                  <button type="button" class="btn btn-link ebrow-password-toggle text-secondary p-0" onclick="togglePassword('new-pass', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-6">
                <label for="confirm-new-pass" class="form-label small fw-semibold">Konfirmasi Password Baru</label>
                <div class="position-relative">
                  <input type="password" class="form-control ebrow-input pe-5" id="confirm-new-pass" placeholder="Ulangi password baru" minlength="8" required />
                  <button type="button" class="btn btn-link ebrow-password-toggle text-secondary p-0" onclick="togglePassword('confirm-new-pass', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-outline-dark px-4 py-2 fw-semibold">Perbarui Password</button>
            </div>
          </form>
        </div>
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
  <script>
    function togglePassword(inputId, btn) {
      const input = document.getElementById(inputId);
      const icon = btn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
      }
    }

    function saveProfile(event) {
      event.preventDefault();
      document.getElementById('display-name').textContent = document.getElementById('prof-name').value;
      document.getElementById('display-email').textContent = document.getElementById('prof-email').value;
      document.getElementById('display-nim').textContent = document.getElementById('prof-nim').value;
      alert('Data profil berhasil diperbarui!');
    }

    function changePassword(event) {
      event.preventDefault();
      const newPass = document.getElementById('new-pass').value;
      const confirmPass = document.getElementById('confirm-new-pass').value;
      if (newPass !== confirmPass) {
        alert('Konfirmasi password baru tidak sesuai!');
        return;
      }
      alert('Password berhasil diubah!');
      event.target.reset();
    }
  </script>
</body>
</html>