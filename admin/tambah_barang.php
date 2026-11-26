<?php
require_once __DIR__ . '/../session.php';
requireAdmin('../login_screen.php', '../user/dashboard_user.php');
require_once __DIR__ . '/../database/repository.php';
require_once __DIR__ . '/partials/ui.php';

$sumberParams = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
$daftarParams = adminListParams($sumberParams);
$kembaliUrl = adminUrl('barang.php', $daftarParams);

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editId = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
    $hasil = saveBarang($_POST, $_FILES, $editId);
    if ($hasil['ok']) {
        header('Location: ' . $kembaliUrl);
        exit;
    }
    $pesan = $hasil['message'];
}

$editId = isset($_GET['edit'])
    ? (int) $_GET['edit']
    : (isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null);
$barang = $editId ? fetchBarangById($editId) : null;
if ($editId && !$barang) {
    header('Location: ' . $kembaliUrl);
    exit;
}

$admin = fetchAdmin();
$kategoriList = fetchKategoriList();
$fotoAda = !empty($barang['foto_barang']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $barang ? 'Edit Barang' : 'Tambah Barang' ?> - EBROW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <?php renderSidebar('barang'); ?>

    <main class="main-content">
        <header class="topbar">
            <div class="page-heading">
                <h1><?= $barang ? 'Edit Barang' : 'Tambah Barang' ?></h1>
                <p><?= $barang ? 'Perbarui informasi barang yang sudah terdaftar.' : 'Lengkapi informasi untuk menambahkan barang baru.' ?></p>
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
            <?php if ($pesan !== ''): ?>
                <div class="alert alert-danger py-2" role="alert"><?= esc($pesan) ?></div>
            <?php endif; ?>
            <div class="form-page">
                <a href="<?= esc($kembaliUrl) ?>" class="back-link">
                    <i class="bi bi-arrow-left"></i> Kembali ke Data Barang
                </a>

                <section class="panel form-panel">
                    <h2 class="form-panel-title">Informasi Barang</h2>
                    <p class="form-panel-description">Lengkapi informasi barang dengan benar.</p>

                    <form action="tambah_barang.php" method="POST" enctype="multipart/form-data">
                        <?php if ($barang): ?>
                            <input type="hidden" name="id" value="<?= (int) $barang['id_barang'] ?>">
                        <?php endif; ?>
                        <?php foreach ($daftarParams as $kunci => $nilai): ?>
                            <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
                        <?php endforeach; ?>

                        <div class="form-group">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang" class="form-control-ebrow" placeholder="Contoh: Laptop ASUS VivoBook 14" value="<?= esc($barang['nama_barang'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="foto" class="form-label">Foto Barang</label>
                            <div class="upload-area">
                                <i class="bi bi-cloud-upload"></i>
                                <img src="<?= $fotoAda ? '../' . esc($barang['foto_barang']) : '' ?>" alt="Preview foto barang" id="previewFoto" class="image-preview" style="<?= $fotoAda ? '' : 'display:none;' ?>">
                                <label for="foto" class="upload-button">Pilih Foto</label>
                                <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" hidden>
                                <span class="upload-help">JPG atau PNG, maksimal 2 MB.</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kategori" class="form-label">Kategori Barang</label>
                            <select id="kategori" name="id_kategori" class="form-select-ebrow" required>
                                <option value="">Pilih kategori</option>
                                <?php foreach ($kategoriList as $kategori): ?>
                                    <option value="<?= (int) $kategori['id_kategori'] ?>" <?= ($barang && (int) $barang['id_kategori'] === (int) $kategori['id_kategori']) ? 'selected' : '' ?>>
                                        <?= esc($kategori['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi" class="form-label">Deskripsi Barang</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control-ebrow" placeholder="Jelaskan spesifikasi dan kegunaan barang..."><?= esc($barang['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="kondisi" class="form-label">Kondisi Barang</label>
                            <select id="kondisi" name="kondisi_barang" class="form-select-ebrow" required>
                                <option value="Baik" <?= (!$barang || $barang['kondisi_barang'] === 'Baik') ? 'selected' : '' ?>>Baik</option>
                                <option value="Kurang Baik" <?= ($barang && $barang['kondisi_barang'] === 'Kurang Baik') ? 'selected' : '' ?>>Kurang Baik</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="harga" class="form-label">Harga per Hari</label>
                            <input type="number" id="harga" name="harga_per_hari" class="form-control-ebrow" min="0" step="1000" value="<?= esc($barang['harga_per_hari'] ?? 0) ?>" required>
                            <div class="form-help">Biaya sewa per hari dalam rupiah.</div>
                        </div>

                        <div class="form-group">
                            <label for="stok" class="form-label">Jumlah Stok</label>
                            <input type="number" id="stok" name="stok_total" class="form-control-ebrow" min="0" value="<?= esc($barang['stok_total'] ?? 1) ?>" required>
                            <div class="form-help">Jumlah unit barang.</div>
                        </div>

                        <div class="form-group">
                            <label for="tersedia" class="form-label">Stok Tersedia</label>
                            <input type="number" id="tersedia" name="stok_tersedia" class="form-control-ebrow" min="0" value="<?= esc($barang['stok_tersedia'] ?? 1) ?>" required>
                            <div class="form-help">Status ketersediaan ditentukan otomatis dari stok ini.</div>
                        </div>

                        <div class="form-buttons">
                            <a href="<?= esc($kembaliUrl) ?>" class="btn-cancel">Batal</a>
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
