<?php
/**
 * Header tunggal untuk semua halaman EBROW (root dan user).
 *
 * Variabel yang bisa diset sebelum include:
 * - $pageTitle  : judul halaman tanpa prefix "EBROW •"
 * - $activeNav  : beranda | dashboard | katalog | status | riwayat | profil | login | admin
 * - $base       : '' untuk halaman root, '../' untuk halaman user/
 * - $pageCss    : path CSS relatif terhadap root (contoh: assets/css/user.css)
 * - $bodyClass  : class body, default memakai flex min-vh-100
 *
 * Menu pengguna, tautan Admin, FAQ/Kontak, dan dropdown akun
 * ditentukan otomatis dari status login/role di session.php.
 * Beranda hanya untuk tamu; pengguna yang login langsung ke dashboard.
 */
require_once __DIR__ . '/../session.php';

$pageTitle = $pageTitle ?? 'EBROW';
$activeNav = $activeNav ?? '';
$base = $base ?? '';
$pageCss = $pageCss ?? 'assets/css/user.css';
$bodyClass = $bodyClass ?? 'd-flex flex-column min-vh-100';

$e = static function ($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$href = static function (string $path) use ($base): string {
    return $base . ltrim($path, '/');
};

$masuk = isLoggedIn();
$admin = isAdmin();
$userPath = $href('user/');

$navItems = [];
if ($masuk) {
    if ($admin) {
        $navItems['admin'] = [$href('admin/admin_dashboard.php'), 'Admin'];
    } else {
        $navItems['dashboard'] = [$userPath . 'dashboard_user.php', 'Dashboard'];
    }
    $navItems['katalog'] = [$href('katalog.php'), 'Katalog Barang'];
    $navItems['status']  = [$userPath . 'status_peminjaman.php', 'Status Peminjaman'];
    $navItems['riwayat'] = [$userPath . 'riwayat_peminjaman.php', 'Riwayat'];
} else {
    $navItems['beranda'] = [$href('index.php'), 'Beranda'];
}

$brandUrl = $masuk
    ? ($admin ? $href('admin/admin_dashboard.php') : $userPath . 'dashboard_user.php')
    : $href('index.php');

$profilAktif = $activeNav === 'profil';
$halamanTamu = !$masuk;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EBROW • <?= $e($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= $e($href('assets/css/header.css')) ?>" />
  <link rel="stylesheet" href="<?= $e($href($pageCss)) ?>" />
</head>
<body class="<?= $e($bodyClass) ?>">

  <nav class="navbar navbar-expand-md ebrow-navbar shadow-sm py-3 px-4 px-lg-5 sticky-top">
    <div class="container-fluid px-0">
      <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-6 text-dark" href="<?= $e($brandUrl) ?>">
        <i class="bi bi-box fs-5"></i>
        <span>EBROW</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarMain">
        <ul class="navbar-nav align-items-md-center gap-md-3 mt-3 mt-md-0">
          <?php foreach ($navItems as $key => [$link, $label]): ?>
            <li class="nav-item">
              <a class="nav-link <?= $key === $activeNav ? 'active fw-semibold' : '' ?>" href="<?= $e($link) ?>"><?= $e($label) ?></a>
            </li>
          <?php endforeach; ?>

          <?php if ($halamanTamu && $activeNav === 'beranda'): ?>
            <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
            <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
          <?php endif; ?>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle fw-semibold d-flex align-items-center gap-2 <?= $profilAktif ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle fs-5"></i>
              <span>Akun Saya</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
              <?php if ($halamanTamu): ?>
                <?php if ($activeNav === 'login'): ?>
                  <li><a class="dropdown-item py-2" href="<?= $e($href('index.php')) ?>"><i class="bi bi-house me-2"></i>Kembali ke Beranda</a></li>
                <?php endif; ?>
                <li><a class="dropdown-item py-2" href="<?= $e($href('login_screen.php')) ?>"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk / Daftar</a></li>
              <?php else: ?>
                <li><a class="dropdown-item py-2 <?= $profilAktif ? 'active' : '' ?>" href="<?= $e($userPath . 'profil_user.php') ?>"><i class="bi bi-person me-2"></i>Profil Pengguna</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2 text-danger" href="<?= $e($href('logout.php')) ?>"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
              <?php endif; ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
