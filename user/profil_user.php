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
$persentaseTepat = $ringkasan['selesai'] > 0
    ? (int) round(($ringkasan['tepat_waktu'] / $ringkasan['selesai']) * 100)
    : 0;

$notifikasi = '';
$notifikasiTipe = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = (string) ($_POST['form'] ?? '');

    if ($form === 'profil') {
        $hasil = simpanProfilUser($idUser, $_POST);
        if ($hasil['ok']) {
            $penggunaBaru = fetchUserById($idUser);
            if ($penggunaBaru) {
                $_SESSION['nama_lengkap'] = $penggunaBaru['nama_lengkap'];
            }
        }
    } elseif ($form === 'password') {
        $hasil = ubahPasswordUser(
            $idUser,
            (string) ($_POST['password_lama'] ?? ''),
            (string) ($_POST['password_baru'] ?? ''),
            (string) ($_POST['konfirmasi_password'] ?? '')
        );
    } else {
        $hasil = ['ok' => false, 'message' => 'Permintaan tidak dikenali.'];
    }

    header('Location: profil_user.php?notif=' . rawurlencode($hasil['message'])
        . '&tipe=' . ($hasil['ok'] ? 'success' : 'danger'));
    exit;
}

if (isset($_GET['notif'])) {
    $notifikasi = (string) $_GET['notif'];
    $notifikasiTipe = (string) ($_GET['tipe'] ?? '') === 'danger' ? 'danger' : 'success';
}

$nimTampil = trim((string) ($pengguna['nim_anggota'] ?? '')) !== ''
    ? $pengguna['nim_anggota']
    : '-';
$roleLabel = $pengguna['role'] === 'admin' ? 'Administrator' : 'Pengguna Umum';

$pageTitle = 'Profil Pengguna';
$activeNav = 'profil';
$base = '../';
$pageCss = 'assets/css/user.css';
require __DIR__ . '/../partials/header.php';
?>
<!-- Main Content -->
  <main class="container my-4 flex-grow-1">
    <?php if ($notifikasi !== ''): ?>
      <div class="alert alert-<?= esc($notifikasiTipe) ?> alert-dismissible fade show" role="alert">
        <?= esc($notifikasi) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="row g-4">
      <!-- Kolom Kiri: Kartu Ringkasan Profil -->
      <div class="col-lg-4">
        <div class="bg-white rounded-4 shadow-sm p-4 text-center">
          <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 user-avatar-circle">
            <i class="bi bi-person-fill text-dark user-avatar-icon"></i>
          </div>

          <h1 class="h5 fw-bold text-dark mb-1" id="display-name"><?= esc($pengguna['nama_lengkap']) ?></h1>
          <p class="small text-secondary mb-2" id="display-email"><?= esc($pengguna['email']) ?></p>
          <span class="badge <?= (int) $pengguna['is_terverifikasi'] === 1 ? 'bg-success' : 'bg-warning text-dark' ?> bg-opacity-10 border rounded-pill px-3 py-1 small">
            <?= (int) $pengguna['is_terverifikasi'] === 1 ? 'Akun Terverifikasi' : 'Belum Terverifikasi' ?>
          </span>

          <hr class="my-4" />

          <div class="row text-center small">
            <div class="col-6 border-end">
              <div class="h5 fw-bold text-dark mb-0"><?= $ringkasan['total'] ?></div>
              <div class="text-secondary">Total Pinjam</div>
            </div>
          </div>

          <div class="ebrow-info-box rounded-3 p-3 small text-secondary text-start mt-4">
            <strong class="d-block text-dark mb-1">ID Pengguna:</strong>
            <span id="display-nim"><?= esc($nimTampil) ?></span> &bull; <?= esc($roleLabel) ?>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Form Edit Data & Password -->
      <div class="col-lg-8">
        <!-- Form Informasi Pribadi -->
        <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5 mb-4">
          <h2 class="h5 fw-bold text-dark mb-1">Informasi Pribadi</h2>
          <p class="small text-secondary mb-4">Perbarui data diri dan kontak aktif untuk keperluan konfirmasi peminjaman.</p>

          <form method="post" action="">
            <input type="hidden" name="form" value="profil" />
            <div class="row g-3">
              <div class="col-md-6">
                <label for="prof-name" class="form-label small fw-semibold">Nama Lengkap</label>
                <input type="text" class="form-control ebrow-input" id="prof-name" name="nama_lengkap" value="<?= esc($pengguna['nama_lengkap']) ?>" required />
              </div>
              <div class="col-md-6">
                <label for="prof-nim" class="form-label small fw-semibold">NIM / ID Anggota</label>
                <input type="text" class="form-control ebrow-input" id="prof-nim" name="nim_anggota" value="<?= esc($pengguna['nim_anggota'] ?? '') ?>" />
              </div>
              <div class="col-md-6">
                <label for="prof-email" class="form-label small fw-semibold">Email</label>
                <input type="email" class="form-control ebrow-input" id="prof-email" name="email" value="<?= esc($pengguna['email']) ?>" required />
              </div>
              <div class="col-md-6">
                <label for="prof-phone" class="form-label small fw-semibold">Nomor Telepon / WhatsApp</label>
                <input type="tel" class="form-control ebrow-input" id="prof-phone" name="no_telepon" value="<?= esc($pengguna['no_telepon'] ?? '') ?>" />
              </div>
              <div class="col-12">
                <label for="prof-unit" class="form-label small fw-semibold">Program Studi / Unit Organisasi</label>
                <input type="text" class="form-control ebrow-input" id="prof-unit" name="prodi_organisasi" value="<?= esc($pengguna['prodi_organisasi'] ?? '') ?>" />
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

          <form method="post" action="">
            <input type="hidden" name="form" value="password" />
            <div class="mb-3">
              <label for="old-pass" class="form-label small fw-semibold">Password Saat Ini</label>
              <div class="position-relative">
                <input type="password" class="form-control ebrow-input pe-5" id="old-pass" name="password_lama" placeholder="Masukkan password lama" required />
                <button type="button" class="btn btn-link ebrow-password-toggle text-secondary p-0" onclick="togglePassword('old-pass', this)">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="new-pass" class="form-label small fw-semibold">Password Baru</label>
                <div class="position-relative">
                  <input type="password" class="form-control ebrow-input pe-5" id="new-pass" name="password_baru" placeholder="Minimal 8 karakter" minlength="8" required />
                  <button type="button" class="btn btn-link ebrow-password-toggle text-secondary p-0" onclick="togglePassword('new-pass', this)">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
              <div class="col-md-6">
                <label for="confirm-new-pass" class="form-label small fw-semibold">Konfirmasi Password Baru</label>
                <div class="position-relative">
                  <input type="password" class="form-control ebrow-input pe-5" id="confirm-new-pass" name="konfirmasi_password" placeholder="Ulangi password baru" minlength="8" required />
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
  </script>
</body>
</html>
