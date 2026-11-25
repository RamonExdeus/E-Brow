<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EBROW - Peminjaman Barang</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet" />
    <!-- CSS tambahan -->
    <link rel="stylesheet" href="assets/css/katalog.css" />
  </head>
  <body>
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
            <a class="nav-link" href="user/dashboard_user.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user/katalog.php">Katalog Barang</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user/status_peminjaman.php">Status Peminjaman</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user/riwayat_peminjaman.php">Riwayat</a>
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

    <!--   JUDUL KATALOG    -->
    <section class="katalog py-5">
      <div class="container">
        <h2>Katalog Barang</h2>
        <p>Temukan berbagai barang yang dapat kamu pinjam untuk mendukung kegiatan perkuliahan.</p>
        <div class="row g-3 mt-3">
          <div class="col-12 col-md-8">
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" class="form-control" id="searchBarang" placeholder="Cari nama barang..." />
            </div>
          </div>
          <div class="col-12 col-md-4">
            <select class="form-select" id="kategoriFilter">
              <option value="semua" selected>Semua Kategori</option>
              <option value="elektronik">Elektronik</option>
              <option value="non-elektronik">Non-Elektronik</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <!--   DAFTAR BARANG    -->
    <section class="daftar-barang py-4">
      <div class="container">
        <h3>Daftar Barang</h3>
        <p>Pilih barang yang ingin kamu lihat</p>
        <div class="row g-4 mt-2">
          <!-- card pertama -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="elektronik">
            <div class="card h-100">
              <img src="assets/images/laptop.jpeg" class="card-img-top barang-img" alt="laptop" />
              <div class="card-body">
                <h5 class="card-title">Laptop</h5>
                <p class="fw-semibold mb-2">Rp30.000 / hari</p>
                <p class="card-text">Kategori: Elektronik</p>
                <span class="badge bg-success">Tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=laptop" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
          <!-- card kedua -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="elektronik">
            <div class="card h-100">
              <img src="assets/images/kamera.jpeg" class="card-img-top barang-img" alt="kamera" />
              <div class="card-body">
                <h5 class="card-title">Kamera</h5>
                <p class="fw-semibold mb-2">Rp20.000 / hari</p>
                <p class="card-text">Kategori: Elektronik</p>
                <span class="badge bg-success">Tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=kamera" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
          <!-- card ketiga -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="elektronik">
            <div class="card h-100">
              <img src="assets/images/speaker.jpeg" class="card-img-top barang-img" alt="speaker" />
              <div class="card-body">
                <h5 class="card-title">Speaker</h5>
                <p class="fw-semibold mb-2">Rp15.000 / hari</p>
                <p class="card-text">Kategori: Elektronik</p>
                <span class="badge bg-success">Tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=speaker" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
          <!-- card keempat -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="elektronik">
            <div class="card h-100">
              <img src="assets/images/mic.jpeg" class="card-img-top barang-img" alt="mic" />
              <div class="card-body">
                <h5 class="card-title">Microphone</h5>
                <p class="fw-semibold mb-2">Rp15.000 / hari</p>
                <p class="card-text">Kategori: Elektronik</p>
                <span class="badge bg-danger">Tidak tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=mic" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
          <!-- card kelima -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="elektronik">
            <div class="card h-100">
              <img src="assets/images/proyektor.jpeg" class="card-img-top barang-img" alt="proyektor" />
              <div class="card-body">
                <h5 class="card-title">Proyektor</h5>
                <p class="fw-semibold mb-2">Rp30.000 / hari</p>
                <p class="card-text">Kategori: Elektronik</p>
                <span class="badge bg-success">Tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=proyektor" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
          <!-- card keenam -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="non-elektronik">
            <div class="card h-100">
              <img src="assets/images/tripod.jpeg" class="card-img-top barang-img" alt="tripod" />
              <div class="card-body">
                <h5 class="card-title">Tripod</h5>
                <p class="fw-semibold mb-2">Rp10.000 / hari</p>
                <p class="card-text">Kategori: Non Elektronik</p>
                <span class="badge bg-danger">Tidak tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=tripod" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
          <!-- card ketujuh -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="non-elektronik">
            <div class="card h-100">
              <img src="assets/images/kursi.jpeg" class="card-img-top barang-img" alt="kursi" />
              <div class="card-body">
                <h5 class="card-title">Kursi Lipat</h5>
                <p class="fw-semibold mb-2">Rp15.000 / hari</p>
                <p class="card-text">Kategori: Non Elektronik</p>
                <span class="badge bg-success">Tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=kursi" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
          <!-- card kedelapan -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="non-elektronik">
            <div class="card h-100">
              <img src="assets/images/meja.jpeg" class="card-img-top barang-img" alt="meja" />
              <div class="card-body">
                <h5 class="card-title">Meja Lipat</h5>
                <p class="fw-semibold mb-2">Rp20.000 / hari</p>
                <p class="card-text">Kategori: Non Elektronik</p>
                <span class="badge bg-success">Tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=meja" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
          <!-- card kesembilan -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="non-elektronik">
            <div class="card h-100">
              <img src="assets/images/papan-tulis.jpeg" class="card-img-top barang-img" alt="papan-tulis" />
              <div class="card-body">
                <h5 class="card-title">Papan Tulis</h5>
                <p class="fw-semibold mb-2">Rp25.000 / hari</p>
                <p class="card-text">Kategori: Non Elektronik</p>
                <span class="badge bg-danger">Tidak tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=papan-tulis" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
          <!-- card kesembilan -->
          <div class="col-6 col-md-4 col-lg-3" data-kategori="non-elektronik">
            <div class="card h-100">
              <img src="assets/images/stand-poster.jpeg" class="card-img-top barang-img" alt="stand-poster" />
              <div class="card-body">
                <h5 class="card-title">Stand Poster</h5>
                <p class="fw-semibold mb-2">Rp20.000 / hari</p>
                <p class="card-text">Kategori: Non Elektronik</p>
                <span class="badge bg-success">Tersedia</span>
                <div class="mt-3">
                  <a href="detail.php?id=stand-poster" class="btn btn-warning"> Lihat Detail </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <p id="pesanKosong" class="text-center mt-4" style="display: none">Barang tidak ditemukan.</p>
      </div>
    </section>

    <!--  FOOTER ================= -->
    <footer class="footer text-center">
      <div class="container">
        <h4>EBR<span>O</span>W</h4>
        <p>Platform peminjaman barang yang praktis dan mudah.</p>
        <p class="mb-0">&copy; 2026 EBROW. All Rights Reserved.</p>
      </div>
    </footer>

    <script src="assets/js/katalog.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
