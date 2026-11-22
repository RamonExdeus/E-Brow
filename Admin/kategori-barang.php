<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8"><meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Kelola Barang - EBROW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleAdmin.css">
  </head>
  <body>
    <aside class="sidebar">
    <div class="brand"><i class="bi bi-box"></i><span>EBROW</span></div>
    <div class="menu-title">PANEL ADMINISTRATOR</div>

    <a href="./adminDashboard.php" class="menu-link"><i class="bi bi-grid"></i><span>Dashboard</span></a>
    <a href="./barang.php" class="menu-link active"><i class="bi bi-box-seam"></i><span>Data Barang</span></a>
    <a href="#peminjaman" class="menu-link"><i class="bi bi-arrow-left-right"></i><span>Data Peminjaman</span></a>

    <a href="#logout" class="menu-link logout"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
  </aside>
    <main class="main-content">
      <header class="topbar">
        <div class="page-heading">
          <h1>
            Kelola Barang
          </h1>
          <p>
            Kelola data barang dan kategori yang tersedia untuk peminjaman.
          </p>
        </div>
        <div class="admin-info">
          <i class="bi bi-bell"></i>
          <div class="avatar">
            <i class="bi bi-person-fill"></i>
          </div>
          <div class="admin-details">
            <div class="admin-name">
              Rizky Pratama
            </div>
            <div class="admin-role">
              Administrator
            </div>
          </div>
        </div>
      </header>
      <div class="content-area">
        <nav class="page-tabs">
          <a class="page-tab" href="barang.php">Data Barang</a>
          <a class="page-tab active" href="kategori-barang.php">Kategori Barang</a>
        </nav>
        <section class="stats-grid">
          <div class="summary-card">
            <div class="summary-label">
              Total Barang
            </div>
            <div class="summary-bottom">
              <span class="summary-number">18</span><span class="summary-icon"><i class="bi bi-box-seam"></i></span>
            </div>
          </div>
          <div class="summary-card">
            <div class="summary-label">
              Barang Tersedia
            </div>
            <div class="summary-bottom">
              <span class="summary-number">13</span><span class="summary-icon"><i class="bi bi-check-circle"></i></span>
            </div>
          </div>
          <div class="summary-card">
            <div class="summary-label">
              Barang Sedang Dipinjam
            </div>
            <div class="summary-bottom">
              <span class="summary-number">5</span><span class="summary-icon"><i class="bi bi-clock"></i></span>
            </div>
          </div>
        </section>
        <section class="panel">
          <div class="panel-action">
            <div>
              <h3 class="panel-title">
                Kategori Barang
              </h3>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table category-table">
              <thead>
                <tr>
                  <th>
                    Nama Kategori
                  </th>
                  <th>
                    Deskripsi Kategori
                  </th>
                  <th class="text-center">
                    Jumlah Barang
                  </th>
                  <th class="text-end">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="category-name">
                    Elektronik
                  </td>
                  <td class="category-description">
                    Perangkat elektronik untuk kuliah dan kegiatan kampus.
                  </td>
                  <td class="category-count">
                    8
                  </td>
                  <td>
                    <div class="table-actions">
                      <button class="btn-small">
                        Edit
                      </button>
                      <button class="btn-small btn-reject">
                        Hapus
                      </button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="category-name">
                    Non Elektronik
                  </td>
                  <td class="category-description">
                    Peralatan non-elektronik untuk kuliah dan kegiatan kampus.
                  </td>
                  <td class="category-count">
                    3
                  </td>
                  <td>
                    <div class="table-actions">
                      <button class="btn-small">
                        Edit
                      </button>
                      <button class="btn-small btn-reject">
                        Hapus
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </div>
      <footer>
        <span>2026 EBROW. Semua hak dilindungi.</span><span>Panel Administrator</span>
      </footer>
    </main>
  </body>
</html>
