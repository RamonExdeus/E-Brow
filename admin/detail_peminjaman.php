<?php
require_once __DIR__ . '/../session.php';
requireAdmin('../login_screen.php', '../user/dashboard_user.php');
require_once __DIR__ . '/../database/repository.php';
require_once __DIR__ . '/partials/ui.php';

$daftarParams = adminListParams($_GET);
$kembaliUrl = adminUrl('kelola_peminjaman.php', $daftarParams);

$admin = fetchAdmin();
$pesan = '';
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['action'] ?? '';
    if ($aksi === 'approve' || $aksi === 'reject') {
        $alasan = $aksi === 'reject' ? trim((string) ($_POST['alasan'] ?? '')) : null;
        if ($aksi === 'reject' && $alasan === '') {
            $pesan = 'Alasan penolakan wajib diisi.';
        } else {
            $status = $aksi === 'approve' ? 'Disetujui' : 'Ditolak';
            $hasil = prosesPeminjaman($id, $status, $alasan, (int) $admin['id_user']);
            if ($hasil['ok']) {
                $tujuan = $daftarParams
                    ? $kembaliUrl
                    : adminUrl('detail_peminjaman.php', ['id' => $id]);
                header('Location: ' . $tujuan);
                exit;
            }
            $pesan = $hasil['message'];
        }
    }
}

$pinjaman = $id > 0 ? fetchPeminjamanById($id) : null;
$aksiAwal = in_array($_GET['action'] ?? '', ['approve', 'reject'], true) && $pinjaman && $pinjaman['status_peminjaman'] === 'Menunggu Persetujuan'
    ? $_GET['action']
    : '';
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman - EBROW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
  </head>
  <body>
    <?php renderSidebar('peminjaman'); ?>
    <main class="main-content">
      <header class="topbar">
        <div class="page-heading">
          <h1>Detail Peminjaman</h1>
          <p>Periksa pengajuan dan tentukan tindakan yang diperlukan.</p>
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
      <div class="content-area detail-area">
        <a href="<?= esc($kembaliUrl) ?>" class="back-link"><i class="bi bi-arrow-left"></i>Kembali ke Pengajuan</a>

        <?php if ($pesan !== ''): ?>
          <div class="alert alert-danger py-2" role="alert"><?= esc($pesan) ?></div>
        <?php endif; ?>

        <?php if (!$pinjaman): ?>
          <section class="panel detail-card"><p class="muted-text">Pengajuan tidak ditemukan.</p></section>
        <?php else: ?>
          <section class="panel detail-card">
            <div class="detail-product">
              <div class="detail-product-image"><i class="bi <?= esc(barangIcon($pinjaman['nama_barang'])) ?>"></i></div>
              <div>
                <h2><?= esc($pinjaman['nama_barang']) ?></h2>
                <span class="status <?= esc(statusClass($pinjaman['status_peminjaman'])) ?>" id="detailStatus"><?= esc(statusLabel($pinjaman['status_peminjaman'])) ?></span>
                <p class="muted-text"><?= esc($pinjaman['kode_barang']) ?> · <?= esc($pinjaman['nama_kategori']) ?></p>
              </div>
            </div>
            <div class="detail-divider"></div>
            <h3 class="detail-section-title">Informasi Peminjaman</h3>
            <div class="detail-grid">
              <div><label>Nama Peminjam</label><b><?= esc($pinjaman['nama_peminjam']) ?></b></div>
              <div><label>Jumlah Barang</label><b><?= (int) $pinjaman['jumlah_unit'] ?> unit</b></div>
              <div><label>Tanggal Pengajuan</label><b><?= esc(formatTanggalPendek($pinjaman['tanggal_pengajuan'])) ?></b></div>
              <div><label>Tanggal Peminjaman</label><b><?= esc(formatTanggalPendek($pinjaman['tanggal_pinjam'])) ?></b></div>
              <div><label>Tanggal Pengembalian</label><b><?= esc(formatTanggalPendek($pinjaman['tanggal_kembali'])) ?></b></div>
              <div><label>Nomor Peminjaman</label><b><?= esc($pinjaman['nomor_peminjaman']) ?></b></div>
              <div><label>NIM Anggota</label><b><?= esc($pinjaman['nim_anggota'] ?: '-') ?></b></div>
              <div><label>Program Studi</label><b><?= esc($pinjaman['prodi_organisasi'] ?: '-') ?></b></div>
            </div>
            <div class="detail-divider"></div>
            <h3 class="detail-section-title">Keperluan Peminjaman</h3>
            <p class="purpose-text"><?= esc($pinjaman['keperluan_peminjaman'] ?: '-') ?></p>
            <?php if ($pinjaman['alasan_penolakan']): ?>
              <div class="detail-divider"></div>
              <h3 class="detail-section-title">Alasan Penolakan</h3>
              <p class="purpose-text"><?= esc($pinjaman['alasan_penolakan']) ?></p>
            <?php endif; ?>
            <?php if ($pinjaman['status_peminjaman'] === 'Menunggu Persetujuan'): ?>
              <div class="process-box">
                <div class="process-box-head">
                  <h3 class="detail-section-title">Proses Pengajuan</h3>
                  <p class="muted-text">Pilih tindakan untuk memproses pengajuan peminjaman barang.</p>
                </div>
                <div class="process-buttons">
                  <button class="btn-approve" onclick="openDecision('approve')"><i class="bi bi-check-circle"></i>Setujui</button>
                  <button class="btn-reject" onclick="openDecision('reject')"><i class="bi bi-x-lg"></i>Tolak</button>
                </div>
              </div>
            <?php else: ?>
              <div class="process-box">
                <p class="muted-text mb-0">Pengajuan ini sudah diproses. Status saat ini: <b><?= esc(statusLabel($pinjaman['status_peminjaman'])) ?></b>.</p>
              </div>
            <?php endif; ?>
            <button class="btn-return-outline" onclick="window.location.href='<?= esc($kembaliUrl) ?>'">Kembali ke Pengajuan</button>
          </section>
        <?php endif; ?>
      </div>
      <footer><span>© 2026 EBROW. Semua Hak Dilindungi.</span><span>Panel Administrator</span></footer>
    </main>

    <?php if ($pinjaman && $pinjaman['status_peminjaman'] === 'Menunggu Persetujuan'): ?>
      <div class="modal-backdrop-custom" id="approveModal" aria-hidden="true">
        <div class="dialog-card">
          <div class="dialog-icon green"><i class="bi bi-check-lg"></i></div>
          <h2>Setujui Pengajuan?</h2>
          <p class="dialog-person"><?= esc($pinjaman['nama_peminjam']) ?> - <?= esc($pinjaman['nama_barang']) ?></p>
          <p class="dialog-description">
            Apakah kamu yakin ingin menyetujui pengajuan peminjaman ini? Pastikan barang tersedia pada tanggal yang diminta.
          </p>
          <form method="post">
            <input type="hidden" name="action" value="approve">
            <input type="hidden" name="id" value="<?= (int) $pinjaman['id_peminjaman'] ?>">
            <?php foreach ($daftarParams as $kunci => $nilai): ?>
              <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
            <?php endforeach; ?>
            <div class="dialog-actions">
              <button type="button" class="btn-cancel" onclick="closeDecision('approveModal')">Batal</button>
              <button type="submit" class="btn-confirm green-button">Ya, Setujui</button>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-backdrop-custom" id="rejectModal" aria-hidden="true">
        <div class="dialog-card">
          <div class="dialog-icon red"><i class="bi bi-x-lg"></i></div>
          <h2>Tolak Pengajuan?</h2>
          <p class="dialog-person"><?= esc($pinjaman['nama_peminjam']) ?> - <?= esc($pinjaman['nama_barang']) ?></p>
          <form method="post">
            <input type="hidden" name="action" value="reject">
            <input type="hidden" name="id" value="<?= (int) $pinjaman['id_peminjaman'] ?>">
            <?php foreach ($daftarParams as $kunci => $nilai): ?>
              <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
            <?php endforeach; ?>
            <label class="dialog-label" for="alasan">Alasan Penolakan *</label>
            <textarea id="alasan" name="alasan" class="dialog-textarea" placeholder="Tuliskan alasan penolakan pengajuan..." required></textarea>
            <p class="dialog-help">Alasan ini akan disampaikan kepada peminjam.</p>
            <div class="dialog-actions">
              <button type="button" class="btn-cancel" onclick="closeDecision('rejectModal')">Batal</button>
              <button type="submit" class="btn-confirm red-button">Tolak Pengajuan</button>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>
    <script>
      function openDecision(type) {
        document.getElementById(type + 'Modal').classList.add('show');
      }
      function closeDecision(id) { document.getElementById(id).classList.remove('show'); }
      document.querySelectorAll('.modal-backdrop-custom').forEach(modal => modal.addEventListener('click', event => { if (event.target === modal) modal.classList.remove('show'); }));
      document.addEventListener('keydown', event => {
        if (event.key === 'Escape') document.querySelectorAll('.modal-backdrop-custom.show').forEach(modal => modal.classList.remove('show'));
      });

      const aksiAwal = <?= json_encode($aksiAwal) ?>;
      if (aksiAwal) openDecision(aksiAwal);
    </script>
  </body>
</html>
