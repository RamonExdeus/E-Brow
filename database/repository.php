<?php
/**
 * Kumpulan query dan utilitas yang dipakai halaman admin EBROW.
 * Semua data ditarik dari database; tidak ada data contoh hardcoded.
 */
require_once __DIR__ . '/db.php';

/** Escape nilai sebelum ditampilkan di HTML. */
function esc($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Label status yang lebih pendek untuk tabel. */
function statusLabel(string $status): string
{
    return $status === 'Menunggu Persetujuan' ? 'Menunggu' : $status;
}

/** Kelas CSS status yang tersedia di assets/css/admin.css. */
function statusClass(string $status): string
{
    return match ($status) {
        'Menunggu Persetujuan' => 'waiting',
        'Disetujui', 'Siap Diambil' => 'available',
        'Ditolak' => 'rejected',
        'Sedang Dipinjam' => 'borrowed',
        'Selesai' => 'done',
        default => 'waiting',
    };
}

/** Deskripsi bawaan untuk kategori (tabel tidak memiliki kolom deskripsi). */
function kategoriDeskripsi(string $nama): string
{
    return match ($nama) {
        'Elektronik' => 'Perangkat elektronik untuk kuliah dan kegiatan kampus.',
        'Non Elektronik' => 'Peralatan non-elektronik untuk kuliah dan kegiatan kampus.',
        'Perlengkapan Acara' => 'Perlengkapan pendukung kegiatan dan acara kampus.',
        default => 'Kategori barang yang tersedia di EBROW.',
    };
}

/** Ikon Bootstrap untuk tiap jenis barang. */
function barangIcon(string $nama): string
{
    $nama = strtolower($nama);

    return match (true) {
        str_contains($nama, 'laptop'), str_contains($nama, 'macbook') => 'bi-laptop',
        str_contains($nama, 'kamera'), str_contains($nama, 'webcam') => 'bi-camera',
        str_contains($nama, 'tripod') => 'bi-camera-reels',
        str_contains($nama, 'proyektor') => 'bi-projector',
        str_contains($nama, 'speaker') => 'bi-speaker',
        str_contains($nama, 'microphone'), str_contains($nama, 'mikrofon') => 'bi-mic',
        str_contains($nama, 'tablet'), str_contains($nama, 'ipad') => 'bi-tablet',
        str_contains($nama, 'kursi') => 'bi-person-standing',
        str_contains($nama, 'meja') => 'bi-grid-3x3',
        str_contains($nama, 'banner') => 'bi-signpost',
        str_contains($nama, 'backdrop') => 'bi-image',
        str_contains($nama, 'lampu') => 'bi-lightbulb',
        str_contains($nama, 'kabel') => 'bi-plug',
        str_contains($nama, 'power bank') => 'bi-battery-full',
        str_contains($nama, 'alat') => 'bi-tools',
        default => 'bi-box',
    };
}

function formatTanggalPendek($value): string
{
    if (!$value) {
        return '-';
    }

    $bulan = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
    $tanggal = new DateTimeImmutable((string) $value);

    return $tanggal->format('d') . ' ' . $bulan[(int) $tanggal->format('n')] . ' ' . $tanggal->format('Y');
}

function formatTanggalPanjang($value): string
{
    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
    $tanggal = new DateTimeImmutable((string) $value);

    return $hari[(int) $tanggal->format('w')] . ', ' . $tanggal->format('d') . ' ' .
        $bulan[(int) $tanggal->format('n')] . ' ' . $tanggal->format('Y');
}

/** Akun admin pertama yang dipakai sebagai nama tampilan dan verifikator. */
function fetchAdmin(): array
{
    $conn = getDBConnection();
    $result = $conn->query("SELECT id_user, nama_lengkap FROM users WHERE role = 'admin' ORDER BY id_user LIMIT 1");
    $row = $result->fetch_assoc();

    return $row ?: ['id_user' => 0, 'nama_lengkap' => 'Administrator'];
}

/** Semua angka ringkasan diharian langsung dari database. */
function fetchDashboardStats(): array
{
    $conn = getDBConnection();

    return $conn->query("
        SELECT
            (SELECT COUNT(*) FROM barang) AS total_barang,
            (SELECT COUNT(*) FROM barang WHERE status_ketersediaan = 'Tersedia') AS barang_tersedia,
            (SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'Sedang Dipinjam') AS sedang_dipinjam,
            (SELECT COUNT(*) FROM users) AS total_user,
            (SELECT COUNT(*) FROM peminjaman) AS total_peminjaman,
            (SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'Menunggu Persetujuan') AS menunggu,
            (SELECT COUNT(*) FROM peminjaman
        WHERE status_peminjaman = 'Sedang Dipinjam' AND tanggal_kembali <= CURDATE()) AS menunggu_pengembalian
    ")->fetch_assoc() ?: [];
}

/** Pengajuan terbaru untuk dashboard. */
function fetchRecentPeminjaman(int $limit = 4): array
{
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        SELECT p.id_peminjaman, p.nomor_peminjaman, p.status_peminjaman, p.tanggal_pengajuan,
            p.tanggal_pinjam, p.tanggal_kembali, u.nama_lengkap AS nama_peminjam, b.nama_barang
        FROM peminjaman p
        JOIN users u ON u.id_user = p.id_user
        JOIN barang b ON b.id_barang = p.id_barang
        ORDER BY p.tanggal_pengajuan DESC, p.id_peminjaman DESC
        LIMIT ?
    ");
    $stmt->bind_param('i', $limit);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/** Daftar kategori beserta jumlah barangnya. */
function fetchKategoriList(): array
{
    $conn = getDBConnection();

    return $conn->query("
        SELECT k.id_kategori, k.nama_kategori, COUNT(b.id_barang) AS jumlah_barang
        FROM kategori_barang k
        LEFT JOIN barang b ON b.id_kategori = k.id_kategori
        GROUP BY k.id_kategori, k.nama_kategori
        ORDER BY k.id_kategori
    ")->fetch_all(MYSQLI_ASSOC);
}

/** Tambah atau ubah nama kategori barang. */
function saveKategori(string $nama, ?int $id): array
{
    $nama = trim($nama);
    if ($nama === '') {
        return ['ok' => false, 'message' => 'Nama kategori wajib diisi.'];
    }

    $conn = getDBConnection();

    try {
        if ($id === null) {
            $stmt = $conn->prepare('INSERT INTO kategori_barang (nama_kategori) VALUES (?)');
            $stmt->bind_param('s', $nama);
        } else {
            $stmt = $conn->prepare('UPDATE kategori_barang SET nama_kategori = ? WHERE id_kategori = ?');
            $stmt->bind_param('si', $nama, $id);
        }
        $stmt->execute();

        return ['ok' => true, 'message' => 'Kategori berhasil disimpan.'];
    } catch (Throwable $error) {
        error_log($error->getMessage());

        return ['ok' => false, 'message' => 'Nama kategori sudah digunakan.'];
    }
}

function deleteKategori(int $id): array
{
    $conn = getDBConnection();

    try {
        $stmt = $conn->prepare('DELETE FROM kategori_barang WHERE id_kategori = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows > 0
            ? ['ok' => true, 'message' => 'Kategori berhasil dihapus.']
            : ['ok' => false, 'message' => 'Kategori tidak ditemukan.'];
    } catch (Throwable $error) {
        error_log($error->getMessage());

        return ['ok' => false, 'message' => 'Kategori tidak dapat dihapus karena masih memiliki barang.'];
    }
}

/**
 * Daftar barang dengan pencarian/filter dan pagination.
 * Mengembalikan [total baris, baris pada halaman ini].
 */
function fetchBarangList(string $search = '', int $kategoriId = 0, string $status = '', int $limit = 5, int $offset = 0): array
{
    $conn = getDBConnection();
    $where = [];
    $types = '';
    $values = [];

    if ($search !== '') {
        $where[] = '(b.nama_barang LIKE ? OR b.kode_barang LIKE ?)';
        $like = '%' . $search . '%';
        $types .= 'ss';
        $values[] = $like;
        $values[] = $like;
    }
    if ($kategoriId > 0) {
        $where[] = 'b.id_kategori = ?';
        $types .= 'i';
        $values[] = $kategoriId;
    }
    if ($status !== '') {
        $where[] = 'b.status_ketersediaan = ?';
        $types .= 's';
        $values[] = $status;
    }

    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM barang b $whereSql");
    if ($values) {
        $countStmt->bind_param($types, ...$values);
    }
    $countStmt->execute();
    $total = (int) $countStmt->get_result()->fetch_row()[0];

    $stmt = $conn->prepare("
        SELECT b.*, k.nama_kategori
        FROM barang b
        JOIN kategori_barang k ON k.id_kategori = b.id_kategori
        $whereSql
        ORDER BY b.id_barang
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param($types . 'ii', ...array_merge($values, [$limit, $offset]));
    $stmt->execute();

    return [$total, $stmt->get_result()->fetch_all(MYSQLI_ASSOC)];
}

function fetchBarangById(int $id): ?array
{
    $conn = getDBConnection();
    $stmt = $conn->prepare('SELECT * FROM barang WHERE id_barang = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return $row ?: null;
}

/** Simpan barang baru atau perbarui barang yang sudah ada. */
function saveBarang(array $post, array $files, ?int $id): array
{
    $conn = getDBConnection();

    $nama = trim((string) ($post['nama_barang'] ?? ''));
    $kategoriId = (int) ($post['id_kategori'] ?? 0);
    $deskripsi = trim((string) ($post['deskripsi'] ?? ''));
    $kondisi = (string) ($post['kondisi_barang'] ?? 'Baik');
    $stok = max(0, (int) ($post['stok_total'] ?? 0));
    $tersedia = max(0, min($stok, (int) ($post['stok_tersedia'] ?? $stok)));
    $status = $tersedia > 0 ? 'Tersedia' : 'Tidak tersedia';
    $harga = max(0, (float) ($post['harga_per_hari'] ?? 0));

    if ($nama === '' || $kategoriId <= 0) {
        return ['ok' => false, 'message' => 'Nama barang dan kategori wajib diisi.'];
    }

    try {
        $conn->begin_transaction();

        if ($id === null) {
            $kode = 'BRG-' . str_pad((string) ((int) $conn->query('SELECT COALESCE(MAX(id_barang), 0) + 1 FROM barang')->fetch_row()[0]), 3, '0', STR_PAD_LEFT);
            $stmt = $conn->prepare("
                INSERT INTO barang
                    (kode_barang, id_kategori, nama_barang, deskripsi, foto_barang, harga_per_hari,
                     kondisi_barang, stok_total, stok_tersedia, status_ketersediaan)
                VALUES (?, ?, ?, ?, NULL, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param('sissdsiis', $kode, $kategoriId, $nama, $deskripsi, $harga,
                $kondisi, $stok, $tersedia, $status);
            $stmt->execute();
            $id = (int) $conn->insert_id;
        } else {
            $stmt = $conn->prepare("
                UPDATE barang SET id_kategori = ?, nama_barang = ?, deskripsi = ?, harga_per_hari = ?,
                    kondisi_barang = ?, stok_total = ?, stok_tersedia = ?, status_ketersediaan = ?
                WHERE id_barang = ?
            ");
            $stmt->bind_param('issdsiisi', $kategoriId, $nama, $deskripsi, $harga,
                $kondisi, $stok, $tersedia, $status, $id);
            $stmt->execute();
        }

        $foto = uploadFotoBarang($files['foto'] ?? null, (int) $id);
        if ($foto !== null) {
            $stmt = $conn->prepare('UPDATE barang SET foto_barang = ? WHERE id_barang = ?');
            $stmt->bind_param('si', $foto, $id);
            $stmt->execute();
        }

        $conn->commit();

        return ['ok' => true, 'message' => 'Barang berhasil disimpan.', 'id' => $id];
    } catch (Throwable $error) {
        $conn->rollback();
        error_log($error->getMessage());

        return ['ok' => false, 'message' => 'Barang gagal disimpan. Periksa kembali input Anda.'];
    }
}

/** Pindahkan foto barang ke assets/images dan kembalikan path relatif dari root web. */
function uploadFotoBarang($file, int $id): ?string
{
    if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 2 * 1024 * 1024) {
        return null;
    }

    $info = getimagesize($file['tmp_name']);
    $extensions = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    if (!$info || !isset($extensions[$info[2]])) {
        return null;
    }

    $namaFile = 'barang-' . $id . '-' . bin2hex(random_bytes(6)) . '.' . $extensions[$info[2]];
    $tujuan = __DIR__ . '/../assets/images/' . $namaFile;

    if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
        return null;
    }

    return 'assets/images/' . $namaFile;
}

function deleteBarang(int $id): array
{
    $conn = getDBConnection();

    try {
        $stmt = $conn->prepare('DELETE FROM barang WHERE id_barang = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows > 0
            ? ['ok' => true, 'message' => 'Barang berhasil dihapus.']
            : ['ok' => false, 'message' => 'Barang tidak ditemukan.'];
    } catch (Throwable $error) {
        error_log($error->getMessage());

        return ['ok' => false, 'message' => 'Barang tidak dapat dihapus karena masih memiliki riwayat peminjaman.'];
    }
}

/** Ringkasan jumlah akun untuk halaman Data User. */
function fetchUserStats(): array
{
    $conn = getDBConnection();
    $baris = $conn->query("
        SELECT COUNT(*) AS total_user,
            SUM(role = 'admin') AS total_admin,
            SUM(is_terverifikasi = 1) AS terverifikasi
        FROM users
    ")->fetch_assoc() ?: [];

    return [
        'total_user' => (int) ($baris['total_user'] ?? 0),
        'total_admin' => (int) ($baris['total_admin'] ?? 0),
        'terverifikasi' => (int) ($baris['terverifikasi'] ?? 0),
    ];
}

/**
 * Daftar pengguna dengan pencarian, filter, dan pagination.
 * Mengembalikan [total baris, baris pada halaman ini].
 */
function fetchUserList(
    string $search = '',
    string $role = '',
    string $verifikasi = '',
    int $limit = 8,
    int $offset = 0
): array {
    $conn = getDBConnection();
    $kondisi = [];
    $types = '';
    $values = [];

    if ($search !== '') {
        $kondisi[] = '(nama_lengkap LIKE ? OR nim_anggota LIKE ? OR email LIKE ? OR prodi_organisasi LIKE ?)';
        $like = '%' . $search . '%';
        $types .= 'ssss';
        $values = array_merge($values, [$like, $like, $like, $like]);
    }
    if (in_array($role, ['user', 'admin'], true)) {
        $kondisi[] = 'role = ?';
        $types .= 's';
        $values[] = $role;
    }
    if (in_array($verifikasi, ['0', '1'], true)) {
        $kondisi[] = 'is_terverifikasi = ?';
        $types .= 'i';
        $values[] = (int) $verifikasi;
    }

    $dari = 'FROM users' . ($kondisi ? ' WHERE ' . implode(' AND ', $kondisi) : '');

    $hitung = $conn->prepare("SELECT COUNT(*) $dari");
    if ($values) {
        $hitung->bind_param($types, ...$values);
    }
    $hitung->execute();
    $total = (int) $hitung->get_result()->fetch_row()[0];

    $stmt = $conn->prepare("
        SELECT id_user, nama_lengkap, nim_anggota, email, no_telepon, prodi_organisasi, role, is_terverifikasi, created_at
        $dari
        ORDER BY id_user DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param($types . 'ii', ...array_merge($values, [$limit, $offset]));
    $stmt->execute();

    return [$total, $stmt->get_result()->fetch_all(MYSQLI_ASSOC)];
}

function fetchUserById(int $id): ?array
{
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        SELECT id_user, nama_lengkap, nim_anggota, email, no_telepon,
               prodi_organisasi, role, is_terverifikasi, created_at
        FROM users WHERE id_user = ?
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $baris = $stmt->get_result()->fetch_assoc();

    return $baris ?: null;
}

/** Tambah pengguna baru atau perbarui data pengguna yang sudah ada. */
function saveUser(array $post, ?int $id): array
{
    $conn = getDBConnection();

    $nama = trim((string) ($post['nama_lengkap'] ?? ''));
    $nim = trim((string) ($post['nim_anggota'] ?? ''));
    $nim = $nim !== '' ? $nim : null;
    $email = strtolower(trim((string) ($post['email'] ?? '')));
    $telepon = trim((string) ($post['no_telepon'] ?? ''));
    $telepon = $telepon !== '' ? $telepon : null;
    $prodi = trim((string) ($post['prodi_organisasi'] ?? ''));
    $prodi = $prodi !== '' ? $prodi : null;
    $role = (string) ($post['role'] ?? 'user');
    $role = in_array($role, ['user', 'admin'], true) ? $role : 'user';
    $terverifikasi = isset($post['is_terverifikasi']) && (int) $post['is_terverifikasi'] === 1 ? 1 : 0;
    $password = (string) ($post['password'] ?? '');

    if ($nama === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'message' => 'Nama lengkap dan email yang valid wajib diisi.'];
    }
    if (($id === null || $password !== '') && strlen($password) < 8) {
        return ['ok' => false, 'message' => 'Password minimal 8 karakter.'];
    }

    try {
        $cek = $conn->prepare('SELECT id_user FROM users WHERE email = ?' . ($id !== null ? ' AND id_user <> ?' : ''));
        $tipeCek = 's';
        $nilaiCek = [$email];
        if ($id !== null) {
            $tipeCek .= 'i';
            $nilaiCek[] = $id;
        }
        $cek->bind_param($tipeCek, ...$nilaiCek);
        $cek->execute();
        if ($cek->get_result()->fetch_row()) {
            return ['ok' => false, 'message' => 'Email tersebut sudah digunakan pengguna lain.'];
        }

        if ($nim !== null) {
            $cekNim = $conn->prepare('SELECT id_user FROM users WHERE nim_anggota = ?' . ($id !== null ? ' AND id_user <> ?' : ''));
            $tipeNim = 's';
            $nilaiNim = [$nim];
            if ($id !== null) {
                $tipeNim .= 'i';
                $nilaiNim[] = $id;
            }
            $cekNim->bind_param($tipeNim, ...$nilaiNim);
            $cekNim->execute();
            if ($cekNim->get_result()->fetch_row()) {
                return ['ok' => false, 'message' => 'NIM anggota tersebut sudah digunakan pengguna lain.'];
            }
        }

        if ($id === null) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("
                INSERT INTO users
                    (nama_lengkap, nim_anggota, email, no_telepon, prodi_organisasi,
                     password_hash, role, is_terverifikasi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param('sssssssi', $nama, $nim, $email, $telepon, $prodi, $hash, $role, $terverifikasi);
            $pesan = 'User berhasil ditambahkan.';
        } else {
            if (!fetchUserById($id)) {
                return ['ok' => false, 'message' => 'User tidak ditemukan.'];
            }

            $sql = 'UPDATE users SET nama_lengkap = ?, nim_anggota = ?, email = ?, no_telepon = ?,
                    prodi_organisasi = ?, role = ?, is_terverifikasi = ?';
            $types = 'ssssssi';
            $values = [$nama, $nim, $email, $telepon, $prodi, $role, $terverifikasi];
            if ($password !== '') {
                $sql .= ', password_hash = ?';
                $types .= 's';
                $values[] = password_hash($password, PASSWORD_DEFAULT);
            }
            $sql .= ' WHERE id_user = ?';
            $types .= 'i';
            $values[] = $id;

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$values);
            $pesan = 'Data user berhasil diperbarui.';
        }

        $stmt->execute();

        return ['ok' => true, 'message' => $pesan];
    } catch (Throwable $error) {
        error_log($error->getMessage());

        return ['ok' => false, 'message' => 'Data user gagal disimpan. Periksa kembali input Anda.'];
    }
}

function deleteUser(int $id, int $adminId): array
{
    if ($id <= 0) {
        return ['ok' => false, 'message' => 'User tidak ditemukan.'];
    }
    if ($id === $adminId) {
        return ['ok' => false, 'message' => 'Akun administrator yang sedang digunakan tidak dapat dihapus.'];
    }

    $conn = getDBConnection();

    try {
        $stmt = $conn->prepare('DELETE FROM users WHERE id_user = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows > 0
            ? ['ok' => true, 'message' => 'User berhasil dihapus.']
            : ['ok' => false, 'message' => 'User tidak ditemukan.'];
    } catch (Throwable $error) {
        error_log($error->getMessage());

        return ['ok' => false, 'message' => 'User tidak dapat dihapus karena masih memiliki riwayat peminjaman.'];
    }
}

/** Status dari filter UI ke nilai enum di database. */
function statusPeminjamanDariFilter(string $status): string
{
    return $status === 'Menunggu' ? 'Menunggu Persetujuan' : $status;
}

/**
 * Daftar pengajuan peminjaman dengan pencarian/filter dan pagination.
 * Mengembalikan [total baris, baris pada halaman ini].
 */
function fetchPeminjamanList(string $status = '', string $search = '', int $limit = 4, int $offset = 0, string $sort = 'terbaru'): array
{
    $conn = getDBConnection();
    $where = [];
    $types = '';
    $values = [];

    if ($status !== '') {
        $where[] = 'p.status_peminjaman = ?';
        $types .= 's';
        $values[] = statusPeminjamanDariFilter($status);
    }
    if ($search !== '') {
        $where[] = '(u.nama_lengkap LIKE ? OR b.nama_barang LIKE ? OR b.kode_barang LIKE ?)';
        $like = '%' . $search . '%';
        $types .= 'sss';
        $values[] = $like;
        $values[] = $like;
        $values[] = $like;
    }

    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $arahUrut = $sort === 'terlama' ? 'ASC' : 'DESC';

    $countStmt = $conn->prepare("
        SELECT COUNT(*) FROM peminjaman p
        JOIN users u ON u.id_user = p.id_user
        JOIN barang b ON b.id_barang = p.id_barang
        $whereSql
    ");
    if ($values) {
        $countStmt->bind_param($types, ...$values);
    }
    $countStmt->execute();
    $total = (int) $countStmt->get_result()->fetch_row()[0];

    $stmt = $conn->prepare("
        SELECT p.id_peminjaman, p.nomor_peminjaman, p.jumlah_unit, p.status_peminjaman,
               p.tanggal_pengajuan, p.tanggal_pinjam, p.tanggal_kembali,
               u.nama_lengkap AS nama_peminjam, b.nama_barang, b.kode_barang, b.kondisi_barang
        FROM peminjaman p
        JOIN users u ON u.id_user = p.id_user
        JOIN barang b ON b.id_barang = p.id_barang
        $whereSql
        ORDER BY p.tanggal_pengajuan $arahUrut, p.id_peminjaman $arahUrut
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param($types . 'ii', ...array_merge($values, [$limit, $offset]));
    $stmt->execute();

    return [$total, $stmt->get_result()->fetch_all(MYSQLI_ASSOC)];
}

/**
 * Barang yang sedang dipinjam dan sudah lewat/jatuh pada batas kembali.
 * Mengembalikan [total baris, baris pada halaman ini].
 */
function fetchReturnList(string $search = '', int $limit = 8, int $offset = 0): array
{
    $conn = getDBConnection();
    $where = "p.status_peminjaman = 'Sedang Dipinjam' AND p.tanggal_kembali <= CURDATE()";
    $types = '';
    $values = [];

    if ($search !== '') {
        $where .= ' AND (u.nama_lengkap LIKE ? OR b.nama_barang LIKE ? OR b.kode_barang LIKE ?)';
        $like = '%' . $search . '%';
        $types = 'sss';
        $values = [$like, $like, $like];
    }

    $dari = "
        FROM peminjaman p
        JOIN users u ON u.id_user = p.id_user
        JOIN barang b ON b.id_barang = p.id_barang
        WHERE $where
    ";

    $countStmt = $conn->prepare("SELECT COUNT(*) $dari");
    if ($values) {
        $countStmt->bind_param($types, ...$values);
    }
    $countStmt->execute();
    $total = (int) $countStmt->get_result()->fetch_row()[0];

    $stmt = $conn->prepare("
        SELECT p.id_peminjaman, p.nomor_peminjaman, p.jumlah_unit, p.tanggal_pinjam, p.tanggal_kembali,
               u.nama_lengkap AS nama_peminjam, b.nama_barang, b.kode_barang, b.kondisi_barang
        $dari
        ORDER BY p.tanggal_kembali, p.id_peminjaman
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param($types . 'ii', ...array_merge($values, [$limit, $offset]));
    $stmt->execute();

    return [$total, $stmt->get_result()->fetch_all(MYSQLI_ASSOC)];
}

function fetchPeminjamanById(int $id): ?array
{
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        SELECT p.*, u.nama_lengkap AS nama_peminjam, u.nim_anggota, u.prodi_organisasi,
               b.nama_barang, b.kode_barang, k.nama_kategori
        FROM peminjaman p
        JOIN users u ON u.id_user = p.id_user
        JOIN barang b ON b.id_barang = p.id_barang
        JOIN kategori_barang k ON k.id_kategori = b.id_kategori
        WHERE p.id_peminjaman = ?
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return $row ?: null;
}

/** Setujui atau tolak pengajuan yang masih menunggu. */
function prosesPeminjaman(int $id, string $status, ?string $alasan, int $adminId): array
{
    $conn = getDBConnection();

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare('SELECT status_peminjaman FROM peminjaman WHERE id_peminjaman = ? FOR UPDATE');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            $conn->rollback();

            return ['ok' => false, 'message' => 'Pengajuan tidak ditemukan.'];
        }
        if ($row['status_peminjaman'] !== 'Menunggu Persetujuan') {
            $conn->rollback();

            return ['ok' => false, 'message' => 'Pengajuan ini sudah diproses.'];
        }

        $stmt = $conn->prepare("
            UPDATE peminjaman
            SET status_peminjaman = ?, alasan_penolakan = ?, id_admin_verifikator = ?,
                waktu_diverifikasi = NOW()
            WHERE id_peminjaman = ?
        ");
        $stmt->bind_param('ssii', $status, $alasan, $adminId, $id);
        $stmt->execute();
        $conn->commit();

        return ['ok' => true, 'message' => $status === 'Disetujui' ? 'Pengajuan disetujui.' : 'Pengajuan ditolak.'];
    } catch (Throwable $error) {
        $conn->rollback();
        error_log($error->getMessage());

        return ['ok' => false, 'message' => 'Pengajuan gagal diproses.'];
    }
}

/** Konfirmasi pengembalian: status selesai dan stok barang dikembalikan. */
function konfirmasiPengembalian(int $id, int $adminId): array
{
    $conn = getDBConnection();

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("
            SELECT p.status_peminjaman, p.id_barang, p.jumlah_unit, b.stok_total, b.stok_tersedia
            FROM peminjaman p JOIN barang b ON b.id_barang = p.id_barang
            WHERE p.id_peminjaman = ? FOR UPDATE
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row || $row['status_peminjaman'] !== 'Sedang Dipinjam') {
            $conn->rollback();

            return ['ok' => false, 'message' => 'Pengembalian tidak valid atau sudah dikonfirmasi.'];
        }

        $tersedia = min((int) $row['stok_total'], (int) $row['stok_tersedia'] + (int) $row['jumlah_unit']);
        $statusBarang = $tersedia > 0 ? 'Tersedia' : 'Tidak tersedia';

        $stmt = $conn->prepare("
            UPDATE peminjaman
            SET status_peminjaman = 'Selesai', waktu_kembali_aktual = NOW(),
                kondisi_verifikasi_kembali = 'Lengkap & Baik', id_admin_verifikator = ?
            WHERE id_peminjaman = ?
        ");
        $stmt->bind_param('ii', $adminId, $id);
        $stmt->execute();

        $stmt = $conn->prepare('UPDATE barang SET stok_tersedia = ?, status_ketersediaan = ? WHERE id_barang = ?');
        $stmt->bind_param('isi', $tersedia, $statusBarang, $row['id_barang']);
        $stmt->execute();

        $conn->commit();

        return ['ok' => true, 'message' => 'Pengembalian berhasil dikonfirmasi.'];
    } catch (Throwable $error) {
        $conn->rollback();
        error_log($error->getMessage());

        return ['ok' => false, 'message' => 'Pengembalian gagal dikonfirmasi.'];
    }
}
