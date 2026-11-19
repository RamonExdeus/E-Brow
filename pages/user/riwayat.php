<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat & Status Peminjaman - EBROW</title>
  <link rel="stylesheet" href="../assets/css/ebrow-user.css">
  <style>
    .filter-bar {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 18px;
    }
    .filter-pill {
      padding: 7px 16px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: var(--bg-surface);
      font-size: 0.82rem;
      font-weight: 700;
      cursor: pointer;
    }
    .filter-pill.active {
      background: var(--dark);
      color: #FFF;
      border-color: var(--dark);
    }
    .detail-row {
      display: flex;
      justify-content: space-between;
      padding: 9px 0;
      border-bottom: 1px solid var(--border);
      font-size: 0.88rem;
    }
    .admin-note-box {
      margin-top: 14px;
      padding: 12px;
      border-radius: var(--radius-sm);
      background: var(--bg-main);
      border: 1px solid var(--border);
      font-size: 0.84rem;
    }
  </style>
</head>
<body>

<nav class="navbar">
  <a href="../index.php" class="brand">EBROW<span>.</span></a>
  <div class="nav-links">
    <a href="../katalog.php">Katalog Barang</a>
    <a href="dashboard.php">Dashboard & Profil</a>
    <a href="riwayat.php" class="active">Riwayat Peminjaman</a>
    <a href="../auth.php" class="btn btn-outline btn-sm">Keluar</a>
  </div>
</nav>

<div class="container">
  <div class="page-header">
    <div>
      <h1 style="font-size:1.5rem; font-weight:800;">Riwayat & Status Peminjaman</h1>
      <p style="color:var(--text-muted); font-size:0.9rem;">Pantau perkembangan persetujuan dan informasi detail barang yang kamu pinjam.</p>
    </div>
  </div>

  <div class="card">
    <!-- Filter Status -->
    <div class="filter-bar">
      <button class="filter-pill active" onclick="filterStatus('semua', this)">Semua</button>
      <button class="filter-pill" onclick="filterStatus('menunggu', this)">Menunggu</button>
      <button class="filter-pill" onclick="filterStatus('disetujui', this)">Disetujui</button>
      <button class="filter-pill" onclick="filterStatus('ditolak', this)">Ditolak</button>
      <button class="filter-pill" onclick="filterStatus('selesai', this)">Selesai</button>
    </div>

    <div class="table-responsive">
      <table id="tabelRiwayat">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Tgl Pinjam</th>
            <th>Tgl Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr data-status="menunggu">
            <td>#BRW-014</td>
            <td><strong>Kamera Mirrorless Sony A6000</strong></td>
            <td>1 Unit</td>
            <td>20 Nov 2026</td>
            <td>23 Nov 2026</td>
            <td><span class="badge badge-menunggu">Menunggu</span></td>
            <td>
              <button class="btn btn-outline btn-sm" onclick="bukaDetail(
                '#BRW-014',
                'Kamera Mirrorless Sony A6000',
                '1 Unit',
                '20 Nov 2026 - 23 Nov 2026',
                'Dokumentasi kegiatan kepanitiaan kampus',
                'Menunggu',
                'Pengajuan sedang diperiksa oleh admin inventaris.'
              )">Info & Status</button>
            </td>
          </tr>

          <tr data-status="disetujui">
            <td>#BRW-011</td>
            <td><strong>Proyektor Portable Mini</strong></td>
            <td>1 Unit</td>
            <td>15 Nov 2026</td>
            <td>18 Nov 2026</td>
            <td><span class="badge badge-disetujui">Disetujui</span></td>
            <td>
              <button class="btn btn-outline btn-sm" onclick="bukaDetail(
                '#BRW-011',
                'Proyektor Portable Mini',
                '1 Unit',
                '15 Nov 2026 - 18 Nov 2026',
                'Presentasi tugas besar pemrograman web',
                'Disetujui',
                'Silakan ambil barang di sekretariat maksimal pukul 16.00 WIB dengan membawa KTM.'
              )">Info & Status</button>
            </td>
          </tr>

          <tr data-status="ditolak">
            <td>#BRW-009</td>
            <td><strong>Bor Listrik Cordless</strong></td>
            <td>2 Unit</td>
            <td>10 Nov 2026</td>
            <td>11 Nov 2026</td>
            <td><span class="badge badge-ditolak">Ditolak</span></td>
            <td>
              <button class="btn btn-outline btn-sm" onclick="bukaDetail(
                '#BRW-009',
                'Bor Listrik Cordless',
                '2 Unit',
                '10 Nov 2026 - 11 Nov 2026',
                'Perbaikan properti dekorasi',
                'Ditolak',
                'Stok unit sedang dalam perawatan berkala pada tanggal tersebut.'
              )">Info & Status</button>
            </td>
          </tr>

          <tr data-status="selesai">
            <td>#BRW-005</td>
            <td><strong>Tenda Dome 4 Orang</strong></td>
            <td>1 Unit</td>
            <td>02 Nov 2026</td>
            <td>05 Nov 2026</td>
            <td><span class="badge badge-selesai">Selesai</span></td>
            <td>
              <button class="btn btn-outline btn-sm" onclick="bukaDetail(
                '#BRW-005',
                'Tenda Dome 4 Orang',
                '1 Unit',
                '02 Nov 2026 - 05 Nov 2026',
                'Kegiatan makrab himpunan',
                'Selesai',
                'Barang telah dikembalikan dalam kondisi lengkap dan bersih.'
              )">Info & Status</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- MODAL INFORMASI & STATUS PEMINJAMAN -->
<div class="modal-backdrop" id="modalDetail" onclick="tutupModal(event)">
  <div class="modal-box" onclick="event.stopPropagation()">
    <div style="display:flex; justify-content:space-between; align-items:center;">
      <h3 style="font-size:1.15rem;">Informasi & Status Peminjaman</h3>
      <button onclick="document.getElementById('modalDetail').classList.remove('show')" style="border:none; background:none; font-size:1.3rem; cursor:pointer;">×</button>
    </div>

    <!-- Stepper Visual Status -->
    <div class="status-timeline">
      <div class="step active" id="step1">
        <div class="step-dot"></div>
        Diajukan
      </div>
      <div class="step" id="step2">
        <div class="step-dot"></div>
        Verifikasi Admin
      </div>
      <div class="step" id="step3">
        <div class="step-dot"></div>
        Selesai
      </div>
    </div>

    <div class="detail-row">
      <span style="color:var(--text-muted);">Kode Peminjaman</span>
      <strong id="mKode">-</strong>
    </div>
    <div class="detail-row">
      <span style="color:var(--text-muted);">Barang</span>
      <strong id="mBarang">-</strong>
    </div>
    <div class="detail-row">
      <span style="color:var(--text-muted);">Jumlah</span>
      <span id="mJumlah">-</span>
    </div>
    <div class="detail-row">
      <span style="color:var(--text-muted);">Durasi Peminjaman</span>
      <span id="mTanggal">-</span>
    </div>
    <div class="detail-row">
      <span style="color:var(--text-muted);">Keperluan</span>
      <span id="mKeperluan" style="text-align:right; max-width:60%;">-</span>
    </div>
    <div class="detail-row" style="border-bottom:none;">
      <span style="color:var(--text-muted);">Status Terkini</span>
      <strong id="mStatus">-</strong>
    </div>

    <div class="admin-note-box">
      <strong>Catatan / Informasi Admin:</strong>
      <p id="mCatatan" style="margin-top:4px; color:var(--text-muted);">-</p>
    </div>

    <button class="btn btn-dark" style="width:100%; margin-top:18px;" onclick="document.getElementById('modalDetail').classList.remove('show')">
      Tutup Informasi
    </button>
  </div>
</div>

<script>
  function filterStatus(status, btn) {
    document.querySelectorAll('.filter-pill').forEach(el => el.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('#tabelRiwayat tbody tr').forEach(row => {
      if (status === 'semua' || row.dataset.status === status) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  function bukaDetail(kode, barang, jumlah, tanggal, keperluan, status, catatan) {
    document.getElementById('mKode').textContent = kode;
    document.getElementById('mBarang').textContent = barang;
    document.getElementById('mJumlah').textContent = jumlah;
    document.getElementById('mTanggal').textContent = tanggal;
    document.getElementById('mKeperluan').textContent = keperluan;
    document.getElementById('mStatus').textContent = status;
    document.getElementById('mCatatan').textContent = catatan;

    // Update stepper progres
    const s2 = document.getElementById('step2');
    const s3 = document.getElementById('step3');
    s2.classList.remove('active');
    s3.classList.remove('active');

    if (status === 'Disetujui' || status === 'Ditolak') {
      s2.classList.add('active');
    } else if (status === 'Selesai') {
      s2.classList.add('active');
      s3.classList.add('active');
    }

    document.getElementById('modalDetail').classList.add('show');
  }

  function tutupModal(e) {
    if (e.target.id === 'modalDetail') {
      document.getElementById('modalDetail').classList.remove('show');
    }
  }
</script>
</body>
</html>