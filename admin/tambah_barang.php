<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - EBROW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <aside class="sidebar">
    <div class="brand"><i class="bi bi-box"></i><span>EBROW</span></div>
    <div class="menu-title">PANEL ADMINISTRATOR</div>

    <a href="admin_dashboard.php" class="menu-link"><i class="bi bi-grid"></i><span>Dashboard</span></a>
    <a href="barang.php" class="menu-link active"><i class="bi bi-box-seam"></i><span>Data Barang</span></a>
    <a href="kelola_peminjaman.php" class="menu-link"><i class="bi bi-arrow-left-right"></i><span>Data Peminjaman</span></a>

    <a href="../login_screen.html" class="menu-link logout"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
  </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="page-heading">
                <h1>Tambah Barang</h1>
                <p>Lengkapi informasi untuk menambahkan barang baru.</p>
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
            <div class="form-page">
                <a href="barang.php" class="back-link">
                    <i class="bi bi-arrow-left"></i> Kembali ke Data Barang
                </a>

                <section class="panel form-panel">
                    <h2 class="form-panel-title">Informasi Barang</h2>
                    <p class="form-panel-description">Lengkapi informasi barang dengan benar.</p>

                    <form action="tambah_barang.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                   class="form-control-ebrow" placeholder="Contoh: Laptop ASUS VivoBook 14" required>
                        </div>

                        <div class="form-group">
                            <label for="foto" class="form-label">Foto Barang</label>
                            <div class="upload-area">
                                <i class="bi bi-cloud-upload"></i>
                                <img src="" alt="Preview foto barang" id="previewFoto" class="image-preview">
                                <label for="foto" class="upload-button">Pilih Foto</label>
                                <input type="file" id="foto" name="foto"
                                       accept="image/jpeg,image/png,image/webp" hidden>
                                <span class="upload-help">JPG atau PNG, maksimal 2 MB.</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kategori" class="form-label">Kategori Barang</label>
                            <select id="kategori" name="kategori" class="form-select-ebrow" required>
                                <option value="">Pilih kategori</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Non-Elektronik">Non-Elektronik</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi" class="form-label">Deskripsi Barang</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control-ebrow"
                                      placeholder="Jelaskan spesifikasi dan kegunaan barang..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="kondisi" class="form-label">Kondisi Barang</label>
                            <select id="kondisi" name="kondisi" class="form-select-ebrow" required>
                                <option value="Baik">Baik</option>
                                <option value="Kurang Baik">Kurang Baik</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="stok" class="form-label">Jumlah Stok</label>
                            <input type="number" id="stok" name="stok" class="form-control-ebrow"
                                   min="0" value="1" required>
                            <div class="form-help">Jumlah unit barang.</div>
                        </div>

                        <div class="form-group">
                            <label for="ketersediaan" class="form-label">Status Ketersediaan</label>
                            <select id="ketersediaan" name="ketersediaan" class="form-select-ebrow" required>
                                <option value="Tersedia">Tersedia</option>
                                <option value="Tidak Tersedia">Tidak Tersedia</option>
                            </select>
                        </div>

                        <div class="form-buttons">
                            <a href="barang.php" class="btn-cancel">Batal</a>
                            <button type="submit" class="btn-save">Simpan Barang</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>

        <footer>
            <span>© 2026 EBROW. Semua hak dilindungi.</span>
            <span>Panel Administrator</span>
        </footer>
    </main>

    <script>
        // Pratinjau foto sebelum formulir disimpan.
        const inputFoto = document.getElementById("foto");
        const previewFoto = document.getElementById("previewFoto");

        inputFoto.addEventListener("change", function() {
            const file = this.files[0];

            if (!file) {
                previewFoto.style.display = "none";
                previewFoto.src = "";
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert("Ukuran foto maksimal 2 MB.");
                this.value = "";
                previewFoto.style.display = "none";
                return;
            }

            previewFoto.src = URL.createObjectURL(file);
            previewFoto.style.display = "block";
        });
    </script>
</body>
</html>
