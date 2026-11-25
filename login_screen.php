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

$error_msg = '';
$success_msg = '';
$active_tab = (isset($_GET['tab']) && $_GET['tab'] === 'register') ? 'register' : 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        $active_tab = 'register';
        $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $no_telepon = trim($_POST['no_telepon'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($nama_lengkap === '' || $email === '' || $no_telepon === '' || $password === '' || $confirm_password === '') {
            $error_msg = 'Semua kolom pendaftaran wajib diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_msg = 'Format email tidak valid.';
        } elseif (strlen($password) < 8) {
            $error_msg = 'Password minimal 8 karakter.';
        } elseif ($password !== $confirm_password) {
            $error_msg = 'Konfirmasi password tidak sama!';
        } else {
            $cek = $conn->prepare('SELECT id_user FROM users WHERE email = ? LIMIT 1');
            $cek->bind_param('s', $email);
            $cek->execute();
            $cek->store_result();

            if ($cek->num_rows > 0) {
                $error_msg = 'Email sudah terdaftar. Silakan gunakan email lain atau login.';
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $role = 'user';

                $stmt = $conn->prepare(
                    'INSERT INTO users (nama_lengkap, email, no_telepon, password_hash, role) VALUES (?, ?, ?, ?, ?)'
                );
                $stmt->bind_param('sssss', $nama_lengkap, $email, $no_telepon, $password_hash, $role);

                if ($stmt->execute()) {
                    $new_user_id = $stmt->insert_id;
                    $_SESSION['id_user'] = $new_user_id;
                    $_SESSION['nama_lengkap'] = $nama_lengkap;
                    $_SESSION['email'] = $email;
                    $_SESSION['role'] = $role;

                    header('Location: user/dashboard_user.php');
                    exit;
                } else {
                    $error_msg = 'Gagal mendaftarkan akun. Silakan coba lagi.';
                }
                $stmt->close();
            }
            $cek->close();
        }
    } elseif ($action === 'login') {
        $active_tab = 'login';
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error_msg = 'Email dan password wajib diisi.';
        } else {
            $stmt = $conn->prepare(
                'SELECT id_user, nama_lengkap, email, password_hash, role, is_terverifikasi FROM users WHERE email = ? LIMIT 1'
            );
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $password_valid = password_verify($password, $row['password_hash']) || ($password === $row['password_hash']);

                if ((int) $row['is_terverifikasi'] !== 1) {
                    $error_msg = 'Akun belum terverifikasi. Hubungi admin.';
                } elseif ($password_valid) {
                    $_SESSION['id_user'] = $row['id_user'];
                    $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];

                    if ($row['role'] === 'admin') {
                        header('Location: admin/admin_dashboard.php');
                        exit;
                    } else {
                        header('Location: user/dashboard_user.php');
                        exit;
                    }
                } else {
                    $error_msg = 'Email atau password salah.';
                }
            }
        }
    }
}
?>
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
      <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-6" href="index.php">
        <i class="bi bi-box fs-5"></i>
        <span>EBROW</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-md-center gap-md-4 mt-3 mt-md-0">
          <li class="nav-item">
            <button type="button" class="btn btn-ebrow-nav btn-sm px-3 py-2 fw-semibold" onclick="window.location.href='index.php'">
            Kembali
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

            <?php if ($error_msg !== ''): ?>
              <div class="alert alert-danger py-2 px-3 small mb-3" role="alert">
                <?= htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8') ?>
              </div>
            <?php endif; ?>

            <?php if ($success_msg !== ''): ?>
              <div class="alert alert-success py-2 px-3 small mb-3" role="alert">
                <?= htmlspecialchars($success_msg, ENT_QUOTES, 'UTF-8') ?>
              </div>
            <?php endif; ?>

            <!-- Tab Switcher -->
            <div class="ebrow-switcher row g-1 p-1 rounded-3 mb-4 mx-0">
              <div class="col-6 px-0 pe-1">
                <button type="button" id="tab-login" class="btn ebrow-tab-btn <?= $active_tab === 'login' ? 'active' : '' ?> w-100 fw-semibold" onclick="switchTab('login')">
                  Login
                </button>
              </div>
              <div class="col-6 px-0 ps-1">
                <button type="button" id="tab-register" class="btn ebrow-tab-btn <?= $active_tab === 'register' ? 'active' : '' ?> w-100 fw-semibold" onclick="switchTab('register')">
                  Register
                </button>
              </div>
            </div>

            <!-- FORM LOGIN -->
            <div id="login-section" class="<?= $active_tab === 'login' ? '' : 'd-none' ?>">
              <h2 class="h4 fw-bold mb-1 text-dark">Selamat Datang Kembali!</h2>
              <p class="small text-secondary mb-4">Masuk ke akun EBROW untuk mulai melakukan peminjaman barang.</p>

              <form method="POST" action="">
                <input type="hidden" name="action" value="login" />

                <div class="mb-3">
                  <label for="login-email" class="form-label small fw-semibold">Email</label>
                  <input type="email" class="form-control ebrow-input" id="login-email" name="email" placeholder="nama@email.com" value="<?= ($active_tab === 'login' && isset($_POST['email'])) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>" required />
                </div>

                <div class="mb-4">
                  <label for="login-password" class="form-label small fw-semibold">Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control ebrow-input pe-5" id="login-password" name="password" placeholder="Masukkan password" required />
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
            <div id="register-section" class="<?= $active_tab === 'register' ? '' : 'd-none' ?>">
              <h2 class="h4 fw-bold mb-1 text-dark">Buat Akun Baru</h2>
              <p class="small text-secondary mb-4">Daftar sebagai pengguna untuk mulai meminjam barang.</p>

              <form method="POST" action="" onsubmit="return validateRegisterForm()">
                <input type="hidden" name="action" value="register" />

                <div class="mb-3">
                  <label for="reg-name" class="form-label small fw-semibold">Nama Lengkap</label>
                  <input type="text" class="form-control ebrow-input" id="reg-name" name="nama_lengkap" placeholder="Masukkan nama lengkap" value="<?= ($active_tab === 'register' && isset($_POST['nama_lengkap'])) ? htmlspecialchars($_POST['nama_lengkap'], ENT_QUOTES, 'UTF-8') : '' ?>" required />
                </div>

                <div class="mb-3">
                  <label for="reg-email" class="form-label small fw-semibold">Email</label>
                  <input type="email" class="form-control ebrow-input" id="reg-email" name="email" placeholder="nama@email.com" value="<?= ($active_tab === 'register' && isset($_POST['email'])) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>" required />
                </div>

                <div class="mb-3">
                  <label for="reg-phone" class="form-label small fw-semibold">Nomor Telepon</label>
                  <input type="tel" class="form-control ebrow-input" id="reg-phone" name="no_telepon" placeholder="08xxxxxxxxxx" value="<?= ($active_tab === 'register' && isset($_POST['no_telepon'])) ? htmlspecialchars($_POST['no_telepon'], ENT_QUOTES, 'UTF-8') : '' ?>" required />
                </div>

                <div class="mb-3">
                  <label for="reg-password" class="form-label small fw-semibold">Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control ebrow-input pe-5" id="reg-password" name="password" placeholder="Minimal 8 karakter" minlength="8" required />
                    <button type="button" class="btn btn-link ebrow-password-toggle text-secondary p-0" onclick="togglePassword('reg-password', this)" aria-label="Tampilkan password">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>

                <div class="mb-4">
                  <label for="reg-confirm" class="form-label small fw-semibold">Konfirmasi Password</label>
                  <div class="position-relative">
                    <input type="password" class="form-control ebrow-input pe-5" id="reg-confirm" name="confirm_password" placeholder="Ulangi password" minlength="8" required />
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

    function validateRegisterForm() {
      const pass = document.getElementById('reg-password').value;
      const confirm = document.getElementById('reg-confirm').value;
      if (pass !== confirm) {
        alert('Konfirmasi password tidak sama!');
        return false;
      }
      return true;
    }
  </script>
</body>
</html>