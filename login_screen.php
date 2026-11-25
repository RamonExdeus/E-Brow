<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EBROW • Login & Register</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Custom CSS (Terpisah) -->
  <link rel="stylesheet" href="assets/css/login_screen.css" />
</head>
<body class="d-flex flex-column min-vh-100">

 <!-- Navbar -->
  <nav class="navbar navbar-expand-md ebrow-navbar shadow-sm py-3 px-4 px-lg-5">
    <div class="container-fluid px-0">
      <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-6" href="index.html">
        <i class="bi bi-box fs-5"></i>
        <span>EBROW</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-md-center gap-md-4 mt-3 mt-md-0">
          <li class="nav-item">
            <button type="button" class="btn btn-ebrow-nav btn-sm px-3 py-2 fw-semibold" onclick="switchTab('login')">
              Login / Register
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="container my-4 flex-grow-1">
    <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
      <div class="row g-4 g-lg-5 align-items-start">

        <!-- Kolom Kiri: Promo Card -->
        <div class="col-lg-5">
          <div class="ebrow-promo-card rounded-4 p-4 p-md-5 d-flex flex-column justify-content-between">
            <div>
              <div class="ebrow-promo-tag text-uppercase fw-bold mb-3">
                BARANG TERSEDIA. KEGIATAN TERJAGA.
              </div>

              <h1 class="ebrow-promo-title fw-bold mb-3">
                Butuh barang?<br />Pinjam saja.
              </h1>

              <p class="text-secondary small lh-base mb-4">
                Dari tugas kuliah sampai acara kampus, temukan barang yang kamu butuhkan di EBROW.
              </p>

              <div class="ebrow-steps-box rounded-3 p-3 p-md-4 mb-4">
                <h2 class="h6 fw-bold mb-3 text-dark">Meminjam jadi lebih sederhana</h2>

                <div class="d-flex align-items-center gap-3 mb-3">
                  <span class="ebrow-step-badge">1</span>
                  <span class="small text-secondary">Temukan barang di katalog</span>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                  <span class="ebrow-step-badge">2</span>
                  <span class="small text-secondary">Ajukan peminjaman sesuai kebutuhan</span>
                </div>

                <div class="d-flex align-items-center gap-3">
                  <span class="ebrow-step-badge">3</span>
                  <span class="small text-secondary">Gunakan, lalu kembalikan tepat waktu</span>
                </div>
              </div>
            </div>

            <p class="small text-secondary mb-0">Satu tempat untuk kebutuhan kegiatanmu.</p>
          </div>
        </div>

        <!-- Kolom Kanan: Form Login / Register -->
        <div class="col-lg-7">
          <div class="ebrow-auth-wrapper mx-auto pt-lg-2">

            <!-- Tab Switcher -->
            <div class="ebrow-switcher row g-1 p-1 rounded-3 mb-4 mx-0">
              <div class="col-6 px-0 pe-1">
                <button type="button" id="tab-login" class="btn ebrow-tab-btn active w-100 fw-semibold" onclick="switchTab('login')">
                  Login
                </button>
              </div>
              <div class="col-6 px-0 ps-1">
                <button type="button" id="tab-register" class="btn ebrow-tab-btn w-100 fw-semibold" onclick="switchTab('register')">
                  Register
                </button>
              </div>
            </div>

            <!-- FORM LOGIN -->
            <div id="login-section">
              <h2 class="h4 fw-bold mb-1 text-dark">Selamat Datang Kembali!</h2>
              <p class="small text-secondary mb-4">Masuk ke akun EBROW untuk mulai melakukan peminjaman barang.</p>

              <form onsubmit="handleAuthSubmit(event, 'Login')">
                <div class="mb-3">
                  <label for="login-email" class="form-label small fw-semibold">Email</label>
                  <input type="email" class="form-control ebrow-input" id="login-email" placeholder="nama@email.com" required />
                </div>

                <div class="mb-4">
                  <label for="login-password" class="form-label small fw-semibold">Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control ebrow-input pe-5" id="login-password" placeholder="Masukkan password" required />
                    <button type="button" class="btn btn-link ebrow-password-toggle text-secondary p-0" onclick="togglePassword('login-password', this)" aria-label="Tampilkan password">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>

                <button type="submit" class="btn btn-ebrow w-100 py-2 fw-semibold mb-3">Masuk</button>
              </form>

              <p class="text-center small text-secondary mb-4">
                Belum punya akun?
                <a href="javascript:void(0)" class="fw-semibold ebrow-link text-decoration-none" onclick="switchTab('register')">
                  Daftar Sekarang
                </a>
              </p>

              <div class="ebrow-info-box rounded-3 p-3 small text-secondary">
                <strong class="d-block text-dark mb-1">Satu pintu masuk untuk Pengguna &amp; Admin</strong>
                Pengguna &rarr; Halaman pengguna<br />
                Admin &rarr; Dashboard admin, sesuai hak akses akun.
              </div>
            </div>

            <!-- FORM REGISTER -->
            <div id="register-section" class="d-none">
              <h2 class="h4 fw-bold mb-1 text-dark">Buat Akun Baru</h2>
              <p class="small text-secondary mb-4">Daftar sebagai pengguna untuk mulai meminjam barang.</p>

              <form onsubmit="handleAuthSubmit(event, 'Register')">
                <div class="mb-3">
                  <label for="reg-name" class="form-label small fw-semibold">Nama Lengkap</label>
                  <input type="text" class="form-control ebrow-input" id="reg-name" placeholder="Masukkan nama lengkap" required />
                </div>

                <div class="mb-3">
                  <label for="reg-email" class="form-label small fw-semibold">Email</label>
                  <input type="email" class="form-control ebrow-input" id="reg-email" placeholder="nama@email.com" required />
                </div>

                <div class="mb-3">
                  <label for="reg-phone" class="form-label small fw-semibold">Nomor Telepon</label>
                  <input type="tel" class="form-control ebrow-input" id="reg-phone" placeholder="08xxxxxxxxxx" required />
                </div>

                <div class="mb-3">
                  <label for="reg-password" class="form-label small fw-semibold">Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control ebrow-input pe-5" id="reg-password" placeholder="Minimal 8 karakter" minlength="8" required />
                    <button type="button" class="btn btn-link ebrow-password-toggle text-secondary p-0" onclick="togglePassword('reg-password', this)" aria-label="Tampilkan password">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>

                <div class="mb-4">
                  <label for="reg-confirm" class="form-label small fw-semibold">Konfirmasi Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control ebrow-input pe-5" id="reg-confirm" placeholder="Ulangi password" minlength="8" required />
                    <button type="button" class="btn btn-link ebrow-password-toggle text-secondary p-0" onclick="togglePassword('reg-confirm', this)" aria-label="Tampilkan password">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>

                <button type="submit" class="btn btn-ebrow w-100 py-2 fw-semibold mb-3">Daftar</button>
              </form>

              <p class="text-center small text-secondary mb-0">
                Sudah punya akun?
                <a href="javascript:void(0)" class="fw-semibold ebrow-link text-decoration-none" onclick="switchTab('login')">
                  Masuk
                </a>
              </p>
            </div>

          </div>
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
    function switchTab(mode) {
      const loginSection = document.getElementById('login-section');
      const registerSection = document.getElementById('register-section');
      const tabLogin = document.getElementById('tab-login');
      const tabRegister = document.getElementById('tab-register');

      if (mode === 'login') {
        loginSection.classList.remove('d-none');
        registerSection.classList.add('d-none');
        tabLogin.classList.add('active');
        tabRegister.classList.remove('active');
      } else {
        loginSection.classList.add('d-none');
        registerSection.classList.remove('d-none');
        tabLogin.classList.remove('active');
        tabRegister.classList.add('active');
      }
    }

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

    function handleAuthSubmit(event, type) {
      event.preventDefault();

      if (type === 'Register') {
        const pass = document.getElementById('reg-password').value;
        const confirm = document.getElementById('reg-confirm').value;
        if (pass !== confirm) {
          alert('Konfirmasi password tidak sama!');
          return;
        }
        alert('Registrasi berhasil! Selamat datang di EBROW.');
        window.location.href = 'user/dashboard_user.html';
        return;
      }

      const email = document.getElementById('login-email').value.toLowerCase();
      if (email.includes('admin')) {
        window.location.href = 'admin/admin_dashboard.php';
      } else {
        window.location.href = 'user/dashboard_user.html';
      }
    }

    const initialTab = new URLSearchParams(window.location.search).get('tab');
    if (initialTab === 'register') {
      switchTab('register');
    }
  </script>
</body>
</html>