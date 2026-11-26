<?php
require_once __DIR__ . '/../session.php';
requireLogin('../login_screen.php');
require_once __DIR__ . '/../database/repository.php';

$idUser = (int) ($_SESSION['id_user'] ?? 0);
if (!fetchUserById($idUser)) {
    session_destroy();
    header('Location: ../login_screen.php');
    exit;
}

$riwayatList = fetchPeminjamanUser($idUser, ['Selesai', 'Dibatalkan', 'Ditolak'], 100);

$pageTitle = 'Riwayat Peminjaman';
$activeNav = 'riwayat';
$base = '../';
$pageCss = 'assets/css/user.css';
require __DIR__ . '/../partials/header.php';
?>
<!-- Main Content -->
  <main class="container my-4 flex-grow-1">
    <div class="bg-white rounded-4 shadow-sm p-4 p-lg-5">
      <div class="mb-4">
        <h1 class="h4 fw-bold text-dark mb-1">Riwayat Peminjaman</h1>
        <p class="small text-secondary mb-0">Catatan seluruh barang yang pernah kamu pinjam dan kembalikan di EBROW.</p>
      </div>

      <!-- Bar Pencarian & Filter -->
      <div class="row g-2 mb-4">
        <div class="col-md-8">
          <div class="position-relative">
            <input type="text" id="searchHistory" class="form-control ebrow-input ps-5" placeholder="Cari nama barang atau kode transaksi..." />
            <i class="bi bi-search text-secondary position-absolute top-50 start-0 translate-middle-y ms-3"></i>
          </div>
        </div>
        <div class="col-md-4">
          <select id="filterStatusHistory" class="form-select ebrow-input">
            <option value="all">Semua Status Riwayat</option>
            <option value="Selesai">Selesai Dikembalikan</option>
            <option value="Dibatalkan">Dibatalkan</option>
            <option value="Ditolak">Ditolak</option>
          </select>
        </div>
      </div>

      <!-- Tabel Riwayat -->
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="historyTable">
          <thead class="table-light small text-secondary">
            <tr>
              <th>Kode</th>
              <th>Barang</th>
              <th>Periode Pinjam</th>
              <th>Tgl Kembali</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody class="small">
            <?php if (!$riwayatList): ?>
              <tr>
                <td colspan="6" class="text-center text-secondary py-4">Belum ada riwayat peminjaman.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($riwayatList as $pinjam): ?>
                <?php
                $status = (string) $pinjam['status_peminjaman'];
                $periode = formatTanggalPendek($pinjam['tanggal_pinjam']) . ' – ' . formatTanggalPendek($pinjam['tanggal_kembali']);
                $waktuKembali = $pinjam['waktu_kembali_aktual']
                    ? formatTanggalPendek($pinjam['waktu_kembali_aktual'])
                    : '-';
                $waktuKembaliPanjang = $pinjam['waktu_kembali_aktual']
                    ? $waktuKembali . ', ' . date('H.i', strtotime((string) $pinjam['waktu_kembali_aktual'])) . ' WIB'
                    : '-';
                $kondisiVerifikasi = (string) ($pinjam['kondisi_verifikasi_kembali'] ?? '');
                $alasan = trim((string) ($pinjam['alasan_penolakan'] ?? ''));
                $subtext = (int) $pinjam['jumlah_unit'] . ' Unit • '
                    . ($alasan !== '' && in_array($status, ['Dibatalkan', 'Ditolak'], true)
                        ? $status . ': ' . $alasan
                        : 'Kondisi: ' . $pinjam['kondisi_barang']);
                ?>
                <tr data-status="<?= esc($status) ?>" data-kode="<?= esc(strtolower($pinjam['nomor_peminjaman'])) ?>" data-barang="<?= esc(strtolower($pinjam['nama_barang'])) ?>">
                  <td class="fw-semibold text-dark"><?= esc($pinjam['nomor_peminjaman']) ?></td>
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <?php if (fotoBarangTersedia($pinjam['foto_barang'])): ?>
                        <img src="<?= esc(fotoBarangUrlUser($pinjam['foto_barang'])) ?>" alt="<?= esc($pinjam['nama_barang']) ?>" class="rounded-3 border user-item-thumb" />
                      <?php else: ?>
                        <span class="rounded-3 border user-item-thumb bg-light d-flex align-items-center justify-content-center">
                          <i class="bi bi-image text-secondary"></i>
                        </span>
                      <?php endif; ?>
                      <div>
                        <div class="fw-semibold text-dark item-name"><?= esc($pinjam['nama_barang']) ?></div>
                        <div class="text-secondary user-item-subtext"><?= esc($subtext) ?></div>
                      </div>
                    </div>
                  </td>
                  <td><?= esc($periode) ?></td>
                  <td><?= esc($waktuKembali) ?></td>
                  <td>
                    <span class="badge <?= esc(statusBootstrapClass($status)) ?> rounded-pill px-3 py-2">
                      <?= esc($status) ?>
                    </span>
                  </td>
                  <td class="text-end">
                    <?php if ($status === 'Selesai'): ?>
                      <button type="button" class="btn btn-outline-secondary btn-sm me-1"
                        data-bs-toggle="modal" data-bs-target="#proofModal"
                        data-kode="<?= esc($pinjam['nomor_peminjaman']) ?>"
                        data-barang="<?= esc($pinjam['nama_barang'] . ' (' . (int) $pinjam['jumlah_unit'] . ' Unit)') ?>"
                        data-periode="<?= esc($periode) ?>"
                        data-waktu="<?= esc($waktuKembaliPanjang) ?>"
                        data-kondisi="<?= esc($kondisiVerifikasi !== '' ? $kondisiVerifikasi : '-') ?>">
                        Bukti
                      </button>
                    <?php endif; ?>
                    <a href="../detail.php?id=<?= (int) $pinjam['id_barang'] ?>" class="btn btn-ebrow btn-sm fw-semibold">Pinjam Lagi</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Modal Bukti Pengembalian -->
  <div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 border-0 shadow">
        <div class="modal-header border-bottom-0 pb-0">
          <h5 class="modal-title fw-bold">Bukti Pengembalian Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body small">
          <div class="text-center py-3">
            <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-2">
              <i class="bi bi-patch-check-fill fs-2"></i>
            </div>
            <h6 class="fw-bold mb-0">Pengembalian Terverifikasi</h6>
            <span class="text-secondary" id="proof-kode">-</span>
          </div>

          <div class="bg-light rounded-3 p-3">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary">Barang</span>
              <span class="fw-semibold text-dark" id="proof-barang">-</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary">Periode Pinjam</span>
              <span class="fw-semibold text-dark" id="proof-periode">-</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary">Waktu Kembali</span>
              <span class="fw-semibold text-dark" id="proof-waktu">-</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-secondary">Kondisi Verifikasi</span>
              <span class="fw-bold text-success" id="proof-kondisi">-</span>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top-0 pt-0">
          <button type="button" class="btn btn-ebrow w-100 fw-semibold" data-bs-dismiss="modal">Selesai</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="container py-3 text-muted small d-flex flex-column flex-sm-row justify-content-between gap-2">
    <span>&copy; 2026 EBROW. Semua hak dilindungi.</span>
    <span>Pinjam dengan mudah. Kembalikan dengan tanggung jawab.</span>
  </footer>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const searchHistory = document.getElementById('searchHistory');
    const filterStatusHistory = document.getElementById('filterStatusHistory');

    function filterHistory() {
      const keyword = searchHistory.value.toLowerCase();
      const statusFilter = filterStatusHistory.value;
      const rows = document.querySelectorAll('#historyTable tbody tr[data-status]');

      rows.forEach(row => {
        const teks = (row.dataset.kode + ' ' + row.dataset.barang + ' ' + row.textContent).toLowerCase();
        const cocok = teks.includes(keyword) && (statusFilter === 'all' || row.dataset.status === statusFilter);
        row.classList.toggle('d-none', !cocok);
      });
    }

    searchHistory.addEventListener('input', filterHistory);
    filterStatusHistory.addEventListener('change', filterHistory);

    const proofModal = document.getElementById('proofModal');
    proofModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      if (!button || !button.dataset.kode) return;

      document.getElementById('proof-kode').textContent = button.dataset.kode;
      document.getElementById('proof-barang').textContent = button.dataset.barang;
      document.getElementById('proof-periode').textContent = button.dataset.periode;
      document.getElementById('proof-waktu').textContent = button.dataset.waktu;
      document.getElementById('proof-kondisi').textContent = button.dataset.kondisi;
    });
  </script>
</body>
</html>
