<?php
require_once __DIR__ . '/../database/repository.php';
require_once __DIR__ . '/partials/ui.php';

$sumberParams = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
$daftarParams = adminListParams($sumberParams);

$admin = fetchAdmin();
$notifikasi = '';
$notifikasiTipe = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $hasil = deleteUser((int) ($_POST['id'] ?? 0), (int) $admin['id_user']);
    $notifikasi = $hasil['message'];
    $notifikasiTipe = $hasil['ok'] ? 'success' : 'danger';
}

$stats = fetchUserStats();

$cari = trim((string) ($sumberParams['search'] ?? ''));
$roleDipilih = in_array($sumberParams['role'] ?? '', ['user', 'admin'], true)
    ? (string) $sumberParams['role']
    : '';
$verifikasiDipilih = in_array((string) ($sumberParams['verifikasi'] ?? ''), ['0', '1'], true)
    ? (string) $sumberParams['verifikasi']
    : '';

$batas = 8;
$halaman = max(1, (int) ($daftarParams['page'] ?? 1));
[$totalUser, $daftarUser] = fetchUserList($cari, $roleDipilih, $verifikasiDipilih, $batas, ($halaman - 1) * $batas);
$totalHalaman = max(1, (int) ceil($totalUser / $batas));
if ($halaman > $totalHalaman) {
    $halaman = $totalHalaman;
    [$totalUser, $daftarUser] = fetchUserList($cari, $roleDipilih, $verifikasiDipilih, $batas, ($halaman - 1) * $batas);
}

$filterParams = $daftarParams;
unset($filterParams['page'], $filterParams['scroll']);
$urlHalaman = static fn (int $nomor): string => adminUrl('data_user.php', array_merge($filterParams, ['page' => $nomor]));

$paramsDaftar = array_merge($filterParams, ['page' => $halaman]);
if (isset($daftarParams['scroll'])) {
    $paramsDaftar['scroll'] = $daftarParams['scroll'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Data Pengguna - EBROW</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <?php renderSidebar('user'); ?>
  <main class="main-content">
    <header class="topbar">
      <div class="page-heading">
        <h1>Data Pengguna</h1>
        <p>Kelola akun pengguna dan administrator EBROW.</p>
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
      <?php if ($notifikasi !== ''): ?>
        <div class="alert alert-<?= esc($notifikasiTipe) ?> py-2" role="alert"><?= esc($notifikasi) ?></div>
      <?php endif; ?>

      <section class="stats-grid">
        <div class="summary-card">
          <div class="summary-label">Total User</div>
          <div class="summary-bottom">
            <span class="summary-number"><?= (int) $stats['total_user'] ?></span><span class="summary-icon"><i class="bi bi-people"></i></span>
          </div>
        </div>
        <div class="summary-card">
          <div class="summary-label">Administrator</div>
          <div class="summary-bottom">
            <span class="summary-number"><?= (int) $stats['total_admin'] ?></span><span class="summary-icon"><i class="bi bi-shield-check"></i></span>
          </div>
        </div>
        <div class="summary-card">
          <div class="summary-label">Akun Terverifikasi</div>
          <div class="summary-bottom">
            <span class="summary-number"><?= (int) $stats['terverifikasi'] ?></span><span class="summary-icon"><i class="bi bi-check-circle"></i></span>
          </div>
        </div>
      </section>

      <section class="panel">
        <div class="panel-action">
          <div>
            <h3 class="panel-title">Daftar User</h3>
            <div class="panel-subtitle mb-0">Tambah, ubah, atau hapus akun yang dapat mengakses EBROW.</div>
          </div>
          <a class="btn-primary-soft" href="<?= esc(adminUrl('tambah_user.php', $paramsDaftar)) ?>" data-remember-scroll>
            <i class="bi bi-plus"></i>Tambah User
          </a>
        </div>

        <form class="inventory-toolbar" method="get">
          <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input class="search-field" name="search" type="search" value="<?= esc($cari) ?>" placeholder="Cari nama, NIM, atau email...">
          </div>
          <select class="filter-field" name="role" onchange="this.form.submit()">
            <option value="">Semua role</option>
            <option value="user" <?= $roleDipilih === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $roleDipilih === 'admin' ? 'selected' : '' ?>>Admin</option>
          </select>
          <select class="filter-field" name="verifikasi" onchange="this.form.submit()">
            <option value="">Semua status</option>
            <option value="1" <?= $verifikasiDipilih === '1' ? 'selected' : '' ?>>Terverifikasi</option>
            <option value="0" <?= $verifikasiDipilih === '0' ? 'selected' : '' ?>>Belum terverifikasi</option>
          </select>
          <button type="submit" class="btn-small">Cari</button>
        </form>

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Nama User</th>
                <th>NIM Anggota</th>
                <th>Kontak</th>
                <th>Role</th>
                <th>Verifikasi</th>
                <th>Bergabung</th>
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$daftarUser): ?>
                <tr><td colspan="7" class="text-center py-4">Belum ada user yang cocok.</td></tr>
              <?php else: ?>
                <?php foreach ($daftarUser as $user): ?>
                  <tr>
                    <td class="user-cell">
                      <b><?= esc($user['nama_lengkap']) ?></b>
                      <small><?= esc($user['email']) ?></small>
                    </td>
                    <td><?= esc($user['nim_anggota'] ?: '-') ?></td>
                    <td class="user-cell">
                      <b><?= esc($user['no_telepon'] ?: '-') ?></b>
                      <small><?= esc($user['prodi_organisasi'] ?: '-') ?></small>
                    </td>
                    <td>
                      <span class="status <?= $user['role'] === 'admin' ? 'borrowed' : 'done' ?>">
                        <?= $user['role'] === 'admin' ? 'Admin' : 'User' ?>
                      </span>
                    </td>
                    <td>
                      <span class="status <?= (int) $user['is_terverifikasi'] === 1 ? 'available' : 'waiting' ?>">
                        <?= (int) $user['is_terverifikasi'] === 1 ? 'Terverifikasi' : 'Belum verifikasi' ?>
                      </span>
                    </td>
                    <td><?= esc(formatTanggalPendek($user['created_at'])) ?></td>
                    <td>
                      <div class="table-actions justify-content-end">
                        <a class="btn-small" href="<?= esc(adminUrl('tambah_user.php', array_merge($paramsDaftar, ['edit' => (int) $user['id_user']]))) ?>" data-remember-scroll>Edit</a>
                        <?php if ((int) $user['id_user'] === (int) $admin['id_user']): ?>
                          <span class="status waiting" title="Akun yang sedang digunakan">Akun saat ini</span>
                        <?php else: ?>
                          <form method="post" onsubmit="return confirm('Hapus user ini?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $user['id_user'] ?>">
                            <?php foreach ($paramsDaftar as $kunci => $nilai): ?>
                              <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn-small btn-reject">Hapus</button>
                          </form>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php renderPagination((int) $totalUser, $batas, $halaman, count($daftarUser), 'user', $urlHalaman); ?>
      </section>
    </div>

    <footer>
      <span>© 2026 EBROW. Semua hak dilindungi.</span><span>Panel Administrator</span>
    </footer>
  </main>

  <script>
    (function () {
      document.querySelectorAll('[data-remember-scroll]').forEach(link => {
        link.addEventListener('click', () => {
          const url = new URL(link.href, window.location.href);
          url.searchParams.set('scroll', String(Math.round(window.scrollY)));
          link.href = url.toString();
        });
      });

      const params = new URLSearchParams(window.location.search);
      if (!params.has('scroll')) return;

      const posisi = Math.max(0, parseInt(params.get('scroll'), 10) || 0);
      window.requestAnimationFrame(() => window.scrollTo(0, posisi));
      params.delete('scroll');
      const sisa = params.toString();
      history.replaceState(null, '', window.location.pathname + (sisa ? '?' + sisa : ''));
    })();
  </script>
</body>
</html>
