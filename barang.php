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
    <div class="brand"><i class="bi bi-box-seam"></i><span>EBROW</span></div>
    <div class="menu-title">PANEL ADMINISTRATOR</div>

    <a href="./index.php" class="menu-link"><i class="bi bi-grid"></i><span>Dashboard</span></a>
    <a href="./barang.php" class="menu-link active"><i class="bi bi-box-seam"></i><span>Data Barang</span></a>
    <a href="#pengguna" class="menu-link"><i class="bi bi-person"></i><span>Data Pengguna</span></a>
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
          <a class="page-tab active" href="barang.php">Data Barang (18)</a>
          <a class="page-tab" href="kategori-barang.php">Kategori Barang (5)</a>
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
                Data Barang
              </h3>
              <div class="panel-subtitle mb-0">
                Perbarui stok, kondisi, dan ketersediaan barang.
              </div>
            </div>
            <button class="btn-primary-soft">
              <i class="bi bi-plus"></i>Tambah Barang
            </button>
          </div>
          <div class="inventory-toolbar">
            <div class="search-wrap">
              <i class="bi bi-search"></i><input class="search-field" placeholder="Cari nama barang...">
            </div>
            <select class="filter-field">
              <option>
                Semua kategori
              </option>
              <option>
                Elektronik
              </option>
              <option>
                Perlengkapan Acara
              </option>
            </select>
            <select class="filter-field">
              <option>
                Semua status
              </option>
              <option>
                Tersedia
              </option>
              <option>
                Tidak Tersedia
              </option>
            </select>
          </div>
          <div class="table-responsive">
            <table class="table inventory-table">
              <thead>
                <tr>
                  <th>
                    Barang
                  </th>
                  <th>
                    Kategori
                  </th>
                  <th>
                    Kondisi
                  </th>
                  <th>
                    Stok
                  </th>
                  <th>
                    Status
                  </th>
                  <th class="text-center">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="product-cell">
                      <div class="product-thumb">
                        <i class="bi bi-laptop"></i>
                      </div>
                      <div>
                        <div class="product-title">
                          Laptop ASUS VivoBook 14
                        </div>
                        <div class="product-code">
                          BRG-001
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    Elektronik
                  </td>
                  <td>
                    Baik
                  </td>
                  <td>
                    1 unit
                  </td>
                  <td>
                    <span class="status available">Tersedia</span>
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
                  <td>
                    <div class="product-cell">
                      <div class="product-thumb">
                        <i class="bi bi-camera"></i>
                      </div>
                      <div>
                        <div class="product-title">
                          Kamera Canon EOS 700D
                        </div>
                        <div class="product-code">
                          BRG-002
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    Elektronik
                  </td>
                  <td>
                    Baik
                  </td>
                  <td>
                    2 unit
                  </td>
                  <td>
                    <span class="status available">Tersedia</span>
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
                  <td>
                    <div class="product-cell">
                      <div class="product-thumb">
                        <i class="bi bi-projector"></i>
                      </div>
                      <div>
                        <div class="product-title">
                          Proyektor Epson EB-X400
                        </div>
                        <div class="product-code">
                          BRG-003
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    Elektronik
                  </td>
                  <td>
                    Baik
                  </td>
                  <td>
                    0 unit
                  </td>
                  <td>
                    <span class="status unavailable">Tidak Tersedia</span>
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
                  <td>
                    <div class="product-cell">
                      <div class="product-thumb">
                        <i class="bi bi-speaker"></i>
                      </div>
                      <div>
                        <div class="product-title">
                          Speaker portabel JBL
                        </div>
                        <div class="product-code">
                          BRG-004
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    Elektronik
                  </td>
                  <td>
                    Baik
                  </td>
                  <td>
                    1 unit
                  </td>
                  <td>
                    <span class="status available">Tersedia</span>
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
                  <td>
                    <div class="product-cell">
                      <div class="product-thumb">
                        <i class="bi bi-camera-reels"></i>
                      </div>
                      <div>
                        <div class="product-title">
                          Tripod kamera
                        </div>
                        <div class="product-code">
                          BRG-005
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    Perlengkapan Acara
                  </td>
                  <td>
                    Baik
                  </td>
                  <td>
                    0 unit
                  </td>
                  <td>
                    <span class="status unavailable">Tidak Tersedia</span>
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
          <div class="table-note">
            Menampilkan 5 dari 18 barang<br>Stok menunjukkan unit yang siap dipinjam.
          </div>
        </section>
      </div>
      <footer>
        <span>2026 EBROW. Semua hak dilindungi.</span><span>Panel Administrator</span>
      </footer>
    </main>
  </body>
</html>
