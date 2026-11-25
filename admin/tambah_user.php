<?php
require_once __DIR__ . '/../database/repository.php';
require_once __DIR__ . '/partials/ui.php';

$sumberParams = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
$daftarParams = adminListParams($sumberParams);
$kembaliUrl = adminUrl('data_user.php', $daftarParams);

$editId = isset($_GET['edit'])
    ? (int) $_GET['edit']
    : (isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null);
if ($editId === 0) {
    $editId = null;
}
$user = $editId ? fetchUserById($editId) : null;

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hasil = saveUser($_POST, $editId);
    if ($hasil['ok']) {
        header('Location: ' . $kembaliUrl);
        exit;
    }
    $pesan = $hasil['message'];
}

if ($editId && !$user) {
    header('Location: ' . $kembaliUrl);
    exit;
}

$admin = fetchAdmin();
$formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : ($user ?? []);
$roleForm = (string) ($formData['role'] ?? 'user');
$roleForm = in_array($roleForm, ['user', 'admin'], true) ? $roleForm : 'user';
$verifikasiForm = (string) ($formData['is_terverifikasi'] ?? '1') === '1' ? '1' : '0';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= $user ? 'Edit User' : 'Tambah User' ?> - EBROW</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <?php renderSidebar('user'); ?>
  <main class="main-content">
    <header class="topbar">
      <div class="page-heading">
        <h1><?= $user ? 'Edit User' : 'Tambah User' ?></h1>
        <p><?= $user ? 'Perbarui data akun pengguna EBROW.' : 'Buat akun baru untuk pengguna EBROW.' ?></p>
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
      <?php if ($pesan !== ''): ?>
        <div class="alert alert-danger py-2" role="alert"><?= esc($pesan) ?></div>
      <?php endif; ?>

      <div class="form-page">
        <a href="<?= esc($kembaliUrl) ?>" class="back-link">
          <i class="bi bi-arrow-left"></i> Kembali ke Data Pengguna
        </a>

        <section class="panel form-panel">
          <h2 class="form-panel-title">Informasi Akun</h2>
          <p class="form-panel-description">Lengkapi data akun dengan benar sebelum disimpan.</p>

          <form action="tambah_user.php" method="POST">
            <?php if ($user): ?>
              <input type="hidden" name="id" value="<?= (int) $user['id_user'] ?>">
            <?php endif; ?>
            <?php foreach ($daftarParams as $kunci => $nilai): ?>
              <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
            <?php endforeach; ?>

            <div class="form-grid">
              <div class="form-group">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control-ebrow"
                       placeholder="Contoh: Lia Putri" value="<?= esc($formData['nama_lengkap'] ?? '') ?>" required>
              </div>

              <div class="form-group">
                <label for="nim_anggota" class="form-label">NIM Anggota</label>
                <input type="text" id="nim_anggota" name="nim_anggota" class="form-control-ebrow"
                       placeholder="Contoh: 2026001" value="<?= esc($formData['nim_anggota'] ?? '') ?>">
                <div class="form-help">Opsional. Harus unik jika diisi.</div>
              </div>

              <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control-ebrow"
                       placeholder="nama@email.com" value="<?= esc($formData['email'] ?? '') ?>" required>
              </div>

              <div class="form-group">
                <label for="no_telepon" class="form-label">No. Telepon</label>
                <input type="tel" id="no_telepon" name="no_telepon" class="form-control-ebrow"
                       placeholder="Contoh: 081234567890" value="<?= esc($formData['no_telepon'] ?? '') ?>">
              </div>

              <div class="form-group">
                <label for="prodi_organisasi" class="form-label">Prodi / Organisasi</label>
                <input type="text" id="prodi_organisasi" name="prodi_organisasi" class="form-control-ebrow"
                       placeholder="Contoh: Teknik Informatika" value="<?= esc($formData['prodi_organisasi'] ?? '') ?>">
              </div>

              <div class="form-group">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select-ebrow" required>
                  <option value="user" <?= $roleForm === 'user' ? 'selected' : '' ?>>User</option>
                  <option value="admin" <?= $roleForm === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
              </div>

              <div class="form-group">
                <label for="is_terverifikasi" class="form-label">Status Verifikasi</label>
                <select id="is_terverifikasi" name="is_terverifikasi" class="form-select-ebrow" required>
                  <option value="1" <?= $verifikasiForm === '1' ? 'selected' : '' ?>>Terverifikasi</option>
                  <option value="0" <?= $verifikasiForm === '0' ? 'selected' : '' ?>>Belum terverifikasi</option>
                </select>
              </div>

              <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control-ebrow"
                       autocomplete="new-password" minlength="8" placeholder="Minimal 8 karakter" <?= $user ? '' : 'required' ?>>
                <div class="form-help">
                  <?= $user ? 'Kosongkan untuk mempertahankan password saat ini.' : 'Password akan disimpan dalam bentuk hash.' ?>
                </div>
              </div>
            </div>

            <div class="form-buttons">
              <a href="<?= esc($kembaliUrl) ?>" class="btn-cancel">Batal</a>
              <button type="submit" class="btn-save"><?= $user ? 'Simpan Perubahan' : 'Simpan User' ?></button>
            </div>
          </form>
        </section>
      </div>
    </div>

    <footer>
      <span>© 2026 EBROW. Semua hak dilindungi.</span><span>Panel Administrator</span>
    </footer>
  </main>
</body>
</html>
