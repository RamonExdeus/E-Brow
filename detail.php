<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Barang - EBROW</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet" />

    <!-- CSS tambahan -->
    <link rel="stylesheet" href="assets/css/detail.css" />
  </head>

  <body>
     <!--   Navbar   -->
  <nav class="navbar navbar-expand-md ebrow-navbar shadow-sm py-3 px-4 px-lg-5 sticky-top">
    <div class="container-fluid px-0">
      <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-6 text-dark" href="dashboard_user.php">
        <i class="bi bi-box fs-5"></i> EBROW
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarUser">
        <ul class="navbar-nav align-items-md-center gap-md-3 mt-3 mt-md-0">
          <li class="nav-item">
            <a class="nav-link" href="user/dashboard_user.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="katalog.php">Katalog Barang</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user/status_peminjaman.php">Status Peminjaman</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user/riwayat_peminjaman.php">Riwayat</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle fw-semibold d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
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

    <!-- DETAIL BARANG -->
    <section class="detail-barang py-5">
      <div class="container">
        <div class="row g-4">
          <!-- Foto Barang -->
          <div class="col-md-5">
            <div class="card border-0 shadow-sm">
              <img src="assets/images/laptop.jpeg" id="gambarBarang" class="img-fluid rounded" alt="Laptop" />
            </div>
          </div>

          <!-- Informasi Barang -->
          <div class="col-md-7">
            <h2 class="fw-bold" id="namaBarang">Laptop</h2>
            <p class="text-muted" id="kategoriBarang">Kategori: Elektronik</p>
            <span class="badge bg-success" id="statusBarang"> Tersedia </span>
            <p class="mt-3" id="deskripsiBarang">Laptop yang dapat digunakan untuk membantu kegiatan perkuliahan, mengerjakan tugas, dan presentasi.</p>
            <h5 class="mt-4">Informasi Barang</h5>
            <p class="mb-1" id="hargaBarang">Harga: Rp30.000 / hari</p>
            <p class="mb-1" id="kondisiBarang">Kondisi: Baik</p>
            <p class="mb-1" id="jumlahBarang">Jumlah: 2 unit</p>
            <a href="form-peminjaman.html" id="tombolPeminjaman" class="btn btn-warning mt-3 px-4 py-2">
              <i class="bi bi-clipboard-check me-2"></i>
              Pinjam Barang
            </a>
          </div>
        </div>
      </div>
    </section>
   
    <!-- JavaScript -->
    <script src="assets/js/detail.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
