<?php
require_once __DIR__ . '/session.php';

if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'admin/admin_dashboard.php' : 'user/dashboard_user.php'));
    exit;
}

$pageTitle = 'Peminjaman Barang';
$activeNav = 'beranda';
$base = '';
$pageCss = 'assets/css/index.css';
$bodyClass = '';
require __DIR__ . '/partials/header.php';
?>
<!-- ================= HERO SECTION ================= -->
    <section class="hero">
      <div class="container">
        <div class="hero-card">
          <div class="row align-items-center g-4">
            <!-- Judul dan deskripsi EBROW -->
            <div class="col-lg-6">
              <h1>
                Butuh barang?
                <span>Pinjam aja di EBROW</span>
              </h1>
              <p>Temukan barang yang kamu butuhkan tanpa harus membeli. Lebih praktis, hemat, dan mudah bersama EBROW.</p>
              <!-- Tombol menuju katalog -->
              <a href="login_screen.php" class="btn btn-hero"> Masuk untuk Jelajahi </a>
            </div>

            <!-- Carousel gambar yang berganti otomatis -->
            <div class="col-lg-6">
              <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="assets/images/laptop.jpeg" class="d-block w-100" alt="Laptop" />
                  </div>
                  <div class="carousel-item">
                    <img src="assets/images/kamera.jpeg" class="d-block w-100" alt="Kamera" />
                  </div>
                  <div class="carousel-item">
                    <img src="assets/images/speaker.jpeg" class="d-block w-100" alt="Speaker" />
                  </div>
                  <div class="carousel-item">
                    <img src="assets/images/mic.jpeg" class="d-block w-100" alt="mic" />
                  </div>
                </div>

                <!-- Tombol gambar sebelumnya -->
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon"></span>
                  <span class="visually-hidden">Sebelumnya</span>
                </button>

                <!-- Tombol gambar berikutnya -->
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                  <span class="carousel-control-next-icon"></span>
                  <span class="visually-hidden">Berikutnya</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--  CARA PEMINJAMAN  -->
    <section class="cara-peminjaman" id="cara-peminjaman">
      <div class="container">
        <div class="text-center mb-4">
          <h2>Cara Peminjaman</h2>
          <p>Hanya dengan tiga langkah mudah.</p>
        </div>
        <div class="row g-4">
          <!-- Langkah pertama -->
          <div class="col-md-4">
            <div class="step-card text-center">
              <h3>01</h3>
              <h5>Pilih Barang</h5>
              <p>Pilih barang yang tersedia di katalog.</p>
            </div>
          </div>
          <!-- Langkah kedua -->
          <div class="col-md-4">
            <div class="step-card text-center">
              <h3>02</h3>
              <h5>Isi Formulir</h5>
              <p>Isi formulir dan tentukan jadwal peminjaman.</p>
            </div>
          </div>
          <!-- Langkah ketiga -->
          <div class="col-md-4">
            <div class="step-card text-center">
              <h3>03</h3>
              <h5>Konfirmasi Admin</h5>
              <p>Admin memeriksa dan mengonfirmasi pengajuan.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--   BARANG POPULER  -->
    <section class="barang-populer">
      <div class="container">
        <div class="text-center mb-5">
          <h2>Barang Populer</h2>
          <p>Beberapa barang yang bisa kamu pinjam di EBROW.</p>
        </div>
        <div class="row g-4">
          <!-- Barang pertama -->
          <div class="col-md-4">
            <div class="barang-card">
              <img src="assets/images/laptop.jpeg" alt="Laptop" />
              <div class="barang-info">
                <h5>Laptop</h5>
                <p>Perangkat untuk mendukung kegiatan kuliah.</p>
              </div>
            </div>
          </div>
          <!-- Barang kedua -->
          <div class="col-md-4">
            <div class="barang-card">
              <img src="assets/images/kamera.jpeg" alt="Kamera" />
              <div class="barang-info">
                <h5>Kamera</h5>
                <p>Untuk dokumentasi dan kebutuhan kegiatan.</p>
              </div>
            </div>
          </div>
          <!-- Barang ketiga -->
          <div class="col-md-4">
            <div class="barang-card">
              <img src="assets/images/speaker.jpeg" alt="Speaker" />
              <div class="barang-info">
                <h5>Speaker</h5>
                <p>Mendukung kebutuhan audio berbagai kegiatan.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Tombol melihat seluruh barang -->
        <div class="text-center mt-5">
          <a href="login_screen.php" class="btn btn-warning"> Masuk untuk Lihat Barang </a>
        </div>
      </div>
    </section>

    <!--  FAQ  -->
    <section id="faq" class="py-5 bg-light">
      <div class="container">
        <div class="text-center mb-4">
          <span class="text-warning fw-bold">FAQ</span>
          <h2 class="fw-bold">Pertanyaan yang Sering Diajukan</h2>
          <p class="text-secondary">Temukan jawaban dari beberapa pertanyaan seputar layanan EBROW.</p>
        </div>

        <!-- Daftar pertanyaan dan jawaban -->
        <div class="accordion" id="accordionFAQ">
          <!-- Pertanyaan pertama -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">Apa itu EBROW?</button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionFAQ">
              <div class="accordion-body">EBROW adalah website yang menyediakan layanan informasi dan pengajuan peminjaman barang.</div>
            </div>
          </div>
          <!-- Pertanyaan kedua -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">Barang apa saja yang tersedia?</button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
              <div class="accordion-body">Tersedia berbagai barang elektronik dan non-elektronik sesuai katalog EBROW.</div>
            </div>
          </div>
          <!-- Pertanyaan ketiga -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">Bagaimana cara meminjam barang?</button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
              <div class="accordion-body">Pengguna harus login, memilih barang dari katalog, kemudian mengisi formulir pengajuan peminjaman.</div>
            </div>
          </div>
          <!-- Pertanyaan keempat -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">Apakah pengajuan langsung disetujui?</button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
              <div class="accordion-body">Tidak. Pengajuan akan diperiksa dan diproses terlebih dahulu oleh admin.</div>
            </div>
          </div>
          <!-- Pertanyaan kelima -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">Bagaimana mengetahui status peminjaman?</button>
            </h2>
            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
              <div class="accordion-body">Pengguna dapat melihat status pengajuan melalui halaman riwayat peminjaman setelah login.</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--  KONTAK  -->
    <section id="kontak" class="py-5">
      <div class="container">
        <div class="text-center mb-4">
          <span class="text-warning fw-bold">KONTAK</span>
          <h2 class="fw-bold">Hubungi Kami</h2>
          <p class="text-secondary">Ada pertanyaan seputar peminjaman barang? Silakan hubungi admin EBROW.</p>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
              <div class="card-body p-4">
                <!-- Informasi email -->
                <div class="mb-3">
                  <h6 class="fw-bold">
                    <i class="bi bi-envelope me-2"></i>
                    Email
                  </h6>
                  <p class="text-secondary mb-0">ebrow@gmail.com</p>
                </div>

                <hr />

                <!-- Informasi layanan bantuan -->
                <div class="mb-3">
                  <h6 class="fw-bold">
                    <i class="bi bi-chat-dots me-2"></i>
                    Layanan Bantuan
                  </h6>
                  <p class="text-secondary mb-0">Hubungi admin untuk mendapatkan informasi dan bantuan peminjaman.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="container py-3 text-muted small d-flex flex-column flex-sm-row justify-content-between gap-2">
      <span>&copy; 2026 EBROW. Semua hak dilindungi.</span>
      <span>Pinjam dengan mudah. Kembalikan dengan tanggung jawab.</span>
    </footer>


    <!-- Bootstrap JavaScript untuk navbar, carousel, dan FAQ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
