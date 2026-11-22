<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - EBROW</title>

  <!-- Bootstrap untuk layout dan komponen dasar -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons untuk ikon menu dan informasi -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styleAdmin.css">

</head>
<body>
  <!-- Menu samping -->
  <aside class="sidebar">
    <div class="brand"><i class="bi bi-box-seam"></i><span>EBROW</span></div>
    <div class="menu-title">PANEL ADMINISTRATOR</div>

    <a href="./adminDashboard.php" class="menu-link active"><i class="bi bi-grid"></i><span>Dashboard</span></a>
    <a href="./barang.php" class="menu-link"><i class="bi bi-box-seam"></i><span>Data Barang</span></a>
    <a href="#pengguna" class="menu-link"><i class="bi bi-person"></i><span>Data Pengguna</span></a>
    <a href="#peminjaman" class="menu-link"><i class="bi bi-arrow-left-right"></i><span>Data Peminjaman</span></a>

    <a href="#logout" class="menu-link logout"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
  </aside>

  <!-- Konten utama dashboard -->
  <main class="main-content" id="dashboard">
    <header class="topbar">
      <div class="page-heading">
        <h1>Dashboard Admin</h1>
        <p>Selamat Datang, Admin!</p>
      </div>

      <div class="admin-info">
        <i class="bi bi-bell"></i>
        <div class="avatar"><i class="bi bi-person-fill"></i></div>
        <div class="admin-details">
          <div class="admin-name">Rizky Pratama</div>
          <div class="admin-role">Administrator</div>
        </div>
      </div>
    </header>

    <div class="content-area">
      <!-- Ringkasan data -->
      <section class="mb-3">
        <div class="section-heading">
          <h2>Ringkasan Data</h2>
          <span class="date-text">Selasa, 17 November 2026</span>
        </div>

        <div class="row g-2">
          <div class="col-6 col-lg-3">
            <div class="summary-card">
              <div class="summary-label">Total Barang</div>
              <div class="summary-bottom"><span class="summary-number">18</span><span class="summary-icon"><i class="bi bi-box-seam"></i></span></div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="summary-card">
              <div class="summary-label">Total Pengguna</div>
              <div class="summary-bottom"><span class="summary-number">126</span><span class="summary-icon"><i class="bi bi-people"></i></span></div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="summary-card">
              <div class="summary-label">Total Peminjaman</div>
              <div class="summary-bottom"><span class="summary-number">284</span><span class="summary-icon"><i class="bi bi-arrow-left-right"></i></span></div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="summary-card pending">
              <div class="summary-label">Pengajuan Menunggu</div>
              <div class="summary-bottom"><span class="summary-number">7</span><span class="summary-icon"><i class="bi bi-clock-history"></i></span></div>
            </div>
          </div>
        </div>
      </section>

      <!-- Tabel pengajuan terbaru -->
      <section class="panel mb-3" id="peminjaman">
        <h3 class="panel-title">Pengajuan Terbaru</h3>
        <div class="panel-subtitle">4 pengajuan terakhir dari seluruh pengguna.</div>

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Nama<br>Peminjam</th>
                <th>Nama Barang</th>
                <th>Tanggal<br>Pengajuan</th>
                <th>Tanggal<br>Peminjaman</th>
                <th>Tanggal<br>Pengembalian</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="borrower">lia</td>
                <td class="item-name">Laptop ASUS VivoBook 14</td>
                <td class="date-cell">17 Nov 2026</td>
                <td class="date-cell">18 Nov 2026</td>
                <td class="date-cell">21 Nov 2026</td>
                <td><span class="status waiting">Menunggu</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-small">Lihat Detail</button>
                    <button class="btn-small btn-approve">Setujui</button>
                    <button class="btn-small btn-reject">Tolak</button>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="borrower">Andi</td>
                <td class="item-name">Kamera Canon EOS 700D</td>
                <td class="date-cell">16 Nov 2026</td>
                <td class="date-cell">18 Nov 2026</td>
                <td class="date-cell">20 Nov 2026</td>
                <td><span class="status approved">Disetujui</span></td>
                <td><button class="btn-small">Lihat Detail</button></td>
              </tr>
              <tr>
                <td class="borrower">Budi</td>
                <td class="item-name">Proyektor Epson EB-X400</td>
                <td class="date-cell">15 Nov 2026</td>
                <td class="date-cell">18 Nov 2026</td>
                <td class="date-cell">19 Nov 2026</td>
                <td><span class="status rejected">Ditolak</span></td>
                <td><button class="btn-small">Lihat Detail</button></td>
              </tr>
              <tr>
                <td class="borrower">Siti</td>
                <td class="item-name">Speaker portabel JBL</td>
                <td class="date-cell">12 Nov 2026</td>
                <td class="date-cell">14 Nov 2026</td>
                <td class="date-cell">16 Nov 2026</td>
                <td><span class="status done">Selesai</span></td>
                <td><button class="btn-small">Lihat Detail</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Aktivitas terbaru -->
      <section class="panel">
        <div class="section-heading mb-2">
          <h3 class="panel-title mb-0">Aktivitas Terbaru</h3>
          <span class="date-text">Hari Ini</span>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="activity-item">
              <div class="activity-icon"><i class="bi bi-journal-check"></i></div>
              <div>
                <div class="activity-title">Pengajuan peminjaman masuk</div>
                <div class="activity-description">lia mengajukan peminjaman Laptop ASUS.</div>
                <div class="activity-time">Hari ini, 09.42</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon"><i class="bi bi-box-seam"></i></div>
              <div>
                <div class="activity-title">Barang baru ditambahkan</div>
                <div class="activity-description">Tripod kamera ditambahkan ke inventaris.</div>
                <div class="activity-time">Hari ini, 09.15</div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="activity-item">
              <div class="activity-icon"><i class="bi bi-check-circle"></i></div>
              <div>
                <div class="activity-title">Pengajuan telah disetujui</div>
                <div class="activity-description">Kamera Canon untuk Andi telah disetujui.</div>
                <div class="activity-time">Hari ini, 09.30</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon"><i class="bi bi-person-plus"></i></div>
              <div>
                <div class="activity-title">Pengguna baru mendaftar</div>
                <div class="activity-description">Rina terdaftar sebagai pengguna EBROW.</div>
                <div class="activity-time">Hari ini, 08.50</div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <footer>
      <span>© 2026 EBROW. Semua hak dilindungi.</span>
      <span>Panel Administrator</span>
    </footer>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
