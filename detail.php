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
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg shadow-sm sticky-top">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.html"> EBR<span>O</span>W </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
            <li class="nav-item">
              <a class="nav-link" href="index.html"> <i class="bi bi-house-door-fill"></i> Beranda </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.html#faq"> <i class="bi bi-question-circle-fill"></i> FAQ </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.html#kontak"> <i class="bi bi-envelope-fill"></i> Kontak </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="katalog.html"> <i class="bi bi-grid-fill"></i> Katalog </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-inline-flex align-items-center gap-1 account-trigger" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu Akun">
                <i class="bi bi-person-circle"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="accountDropdown">
                <li>
                  <a class="dropdown-item py-2" href="login_screen.html"> <i class="bi bi-box-arrow-in-right me-2"></i> Masuk </a>
                </li>
                <li>
                  <a class="dropdown-item py-2" href="login_screen.html?tab=register"> <i class="bi bi-person-plus me-2"></i> Daftar </a>
                </li>
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
