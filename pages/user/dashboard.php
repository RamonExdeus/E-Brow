<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard & Profil Pengguna - EBROW</title>
  <link rel="stylesheet" href="../assets/css/ebrow-user.css">
  <style>
    .avatar-circle {
      width: 68px;
      height: 68px;
      border-radius: 50%;
      background: var(--primary);
      color: var(--dark);
      font-size: 1.5rem;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 14px;
    }
    .profile-item {
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
    }
    .profile-item:last-child { border-bottom: none; }
    .profile-label { font-size: 0.78rem; color: var(--text-muted); }
    .profile-val { font-weight: 600; font-size: 0.92rem; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <a href="../index.php" class="brand">EBROW<span>.</span></a>
  <div class="nav-links">
    <a href="../katalog.php">Katalog Barang</a>
    <a href="dashboard.php" class="active">Dashboard & Profil</a>
    <a href="riwayat.php">Riwayat Peminjaman</a>
    <a href="../auth.php" class="btn btn-outline btn-sm">Keluar</a>
  </div>
</nav>

<div class="container">
  <div class="page-header">
    <div>
      <h1 style="font-size:1.5rem; font-weight:800;">Halo, Sabam Nainggolan</h1>
      <p style="color:var(--text-muted); font-size:0.9rem;">Kelola profil akun dan pantau ringkasan peminjamanmu di sini.</p>
    </div>
    <a href="form_peminjaman.php" class="btn btn-primary">+ Ajukan Peminjaman</a>
  </div>

  <!-- Ringkasan Jumlah Peminjaman -->
  <div class="stats-grid">
    <div class="stat-card highlight">
      <div class="stat-label">Total Peminjaman</div>
      <div class="stat-value">12</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Menunggu Konfirmasi</div>
      <div class="stat-value">1</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Sedang Dipinjam</div>
      <div class="stat-value">2</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Peminjaman Selesai</div>
      <div class="stat-value">9</div>
    </div>
  </div>

  <div class="dashboard-grid">
    <!-- KOLOM KIRI: PROFIL PENGGUNA (LIHAT & EDIT) -->
    <div class="card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="font-size:1.05rem;">Profil Pengguna</h3>
        <button type="button" class="btn btn-outline btn-sm" id="btnEditProfil" onclick="toggleEditProfil()">Edit Profil</button>
      </div>

      <!-- Mode Lihat Profil -->
      <div id="viewProfil">
        <div class="avatar-circle">SN</div>
        <div class="profile-item">
          <div class="profile-label">Nama Lengkap</div>
          <div class="profile-val">Sabam A. J. Nainggolan</div>
        </div>
        <div class="profile-item">
          <div class="profile-label">Email Akun</div>
          <div class="profile-val">sabam.user@ebrow.id</div>
        </div>
        <div class="profile-item">
          <div class="profile-label">Nomor WhatsApp</div>
          <div class="profile-val">0812-3456-7890</div>
        </div>
        <div class="profile-item">
          <div class="profile-label">Alamat / Domisili</div>
          <div class="profile-val">Jl. Dr. Mansyur, Medan</div>
        </div>
      </div>

      <!-- Mode Edit Profil -->
      <form id="formEditProfil" class="hidden" action="update_profil.php" method="POST">
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" value="Sabam A. J. Nainggolan" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="sabam.user@ebrow.id" required>
        </div>
        <div class="form-group">
          <label>Nomor WhatsApp</label>
          <input type="text" name="no_hp" class="form-control" value="0812-3456-7890" required>
        </div>
        <div class="form-group">
          <label>Alamat / Domisili</label>
          <textarea name="alamat" class="form-control" rows="2">Jl. Dr. Mansyur, Medan</textarea>
        </div>
        <div style="display:flex; gap:8px; margin-top:12px;">
          <button type="submit" class="btn btn-primary btn-sm" style="flex:1;">Simpan Perubahan</button>
          <button type="button" class="btn btn-outline btn-sm" onclick="toggleEditProfil()">Batal</button>
        </div>
      </form>
    </div>

    <!-- KOLOM KANAN: AKTIVITAS PEMINJAMAN TERBARU -->
    <div class="card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="font-size:1.05rem;">Aktivitas Peminjaman Terbaru</h3>
        <a href="riwayat.php" style="font-size:0.85rem; font-weight:700; color:var(--dark);">Lihat Semua →</a>
      </div>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Barang</th>
              <th>Tanggal Pinjam</th>
              <th>Tgl Kembali</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Kamera Mirrorless Sony A6000</strong></td>
              <td>20 Nov 2026</td>
              <td>23 Nov 2026</td>
              <td><span class="badge badge-menunggu">Menunggu</span></td>
            </tr>
            <tr>
              <td><strong>Proyektor Portable Mini</strong></td>
              <td>15 Nov 2026</td>
              <td>18 Nov 2026</td>
              <td><span class="badge badge-disetujui">Disetujui</span></td>
            </tr>
            <tr>
              <td><strong>Tenda Dome 4 Orang</strong></td>
              <td>02 Nov 2026</td>
              <td>05 Nov 2026</td>
              <td><span class="badge badge-selesai">Selesai</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleEditProfil() {
    const view = document.getElementById('viewProfil');
    const form = document.getElementById('formEditProfil');
    const btn = document.getElementById('btnEditProfil');

    view.classList.toggle('hidden');
    form.classList.toggle('hidden');
    btn.classList.toggle('hidden');
  }
</script>
</body>
</html>