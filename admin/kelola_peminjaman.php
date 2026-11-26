<?php
require_once __DIR__ . '/../session.php';
requireAdmin('../login_screen.php', '../user/dashboard_user.php');
require_once __DIR__ . '/../database/repository.php';
require_once __DIR__ . '/partials/ui.php';

$notifikasi = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'confirm_return') {
    $admin = fetchAdmin();
    $hasil = konfirmasiPengembalian((int) ($_POST['id'] ?? 0), (int) $admin['id_user']);
    $notifikasi = $hasil['message'];
}

$admin = fetchAdmin();
$stats = fetchDashboardStats();

$cari = trim((string) ($_GET['search'] ?? ''));
$statusDipilih = (string) ($_GET['status'] ?? '');
$sort = ($_GET['sort'] ?? 'terbaru') === 'terlama' ? 'terlama' : 'terbaru';
$batas = 8;

$halaman = max(1, (int) ($_GET['page'] ?? 1));
[$totalPengajuan, $daftarPengajuan] = fetchPeminjamanList($statusDipilih, $cari, $batas, ($halaman - 1) * $batas, $sort);
$totalHalaman = max(1, (int) ceil($totalPengajuan / $batas));
if ($halaman > $totalHalaman) {
    $halaman = $totalHalaman;
    [$totalPengajuan, $daftarPengajuan] = fetchPeminjamanList($statusDipilih, $cari, $batas, ($halaman - 1) * $batas, $sort);
}

$returnCari = trim((string) ($_GET['return_search'] ?? ''));
$halamanReturn = max(1, (int) ($_GET['return_page'] ?? 1));
[$totalPengembalian, $daftarPengembalian] = fetchReturnList($returnCari, $batas, ($halamanReturn - 1) * $batas);
$totalHalamanReturn = max(1, (int) ceil($totalPengembalian / $batas));
if ($halamanReturn > $totalHalamanReturn) {
    $halamanReturn = $totalHalamanReturn;
    [$totalPengembalian, $daftarPengembalian] = fetchReturnList($returnCari, $batas, ($halamanReturn - 1) * $batas);
}

$daftarParams = adminListParams($_GET);
$kembaliDetailParams = array_diff_key($daftarParams, array_flip(['tab', 'return_search', 'return_page', 'scroll']));
$urlDengan = static function (array $ubah = []) use ($daftarParams): string {
    $params = array_merge($daftarParams, $ubah);

    foreach ($params as $kunci => $nilai) {
        if ($nilai === '' || $nilai === null || $nilai === 0) {
            unset($params[$kunci]);
        }
    }
    if (($params['sort'] ?? '') === 'terbaru') {
        unset($params['sort']);
    }
    if ((int) ($params['page'] ?? 0) <= 1) {
        unset($params['page']);
    }
    if ((int) ($params['return_page'] ?? 0) <= 1) {
        unset($params['return_page']);
    }
    unset($params['scroll']);

    return adminUrl('kelola_peminjaman.php', $params);
};
$statusFilter = ['Semua', 'Menunggu', 'Disetujui', 'Ditolak', 'Sedang Dipinjam'];
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kelola Peminjaman - EBROW</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/admin.css">
    </head>
    <body>
    <?php renderSidebar('peminjaman'); ?>
        <main class="main-content">
            <header class="topbar">
                <div class="page-heading">
                    <h1>Kelola Peminjaman</h1>
                    <p>Kelola dan pantau seluruh aktivitas pengajuan peminjaman serta pengembalian barang.</p>
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
                <?php if ($notifikasi !== ''): ?>
                    <div class="alert alert-info py-2" role="alert"><?= esc($notifikasi) ?></div>
                <?php endif; ?>
                <section class="stats-grid stats-four">
                    <div class="summary-card">
                        <div class="summary-label">Total Pengajuan</div>
                        <div class="summary-bottom"><span class="summary-number"><?= (int) $stats['total_peminjaman'] ?></span><span class="summary-icon"><i class="bi bi-clipboard-check"></i></span></div>
                    </div>
                    <div class="summary-card highlighted">
                        <div class="summary-label">Menunggu Persetujuan</div>
                        <div class="summary-bottom">
                            <span class="summary-number" id="countPending"><?= (int) $stats['menunggu'] ?></span><span class="summary-icon"><i class="bi bi-clock"></i></span>
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-label">Sedang Dipinjam</div>
                        <div class="summary-bottom"><span class="summary-number"><?= (int) $stats['sedang_dipinjam'] ?></span><span class="summary-icon"><i class="bi bi-arrow-left-right"></i></span></div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-label">Menunggu Pengembalian</div>
                        <div class="summary-bottom">
                            <span class="summary-number" id="countReturn"><?= (int) $stats['menunggu_pengembalian'] ?></span><span class="summary-icon"><i class="bi bi-box-arrow-in-left"></i></span>
                        </div>
                    </div>
                </section>
                <div class="page-tabs">
                    <button class="page-tab active" data-tab="pengajuan">Pengajuan</button><button class="page-tab" data-tab="pengembalian">Pengembalian</button>
                </div>
                <section class="panel tab-panel" id="panelPengajuan">
                    <div class="filter-grid">
                        <form method="get" class="search-form">
                            <div class="search-wrap">
                                <i class="bi bi-search"></i>
                                <input class="search-field" id="searchPengajuan" name="search" type="search" value="<?= esc($cari) ?>" placeholder="Cari nama peminjam atau barang...">
                            </div>
                            <?php foreach ($daftarParams as $kunci => $nilai): ?>
                                <?php if (in_array($kunci, ['search', 'scroll', 'tab', 'return_search', 'return_page'], true)) continue; ?>
                                <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn-small">Cari</button>
                        </form>
                        <div class="status-filters">
                            <?php foreach ($statusFilter as $status): ?>
                                <a class="filter-chip <?= $statusDipilih === $status ? 'active' : '' ?>" href="<?= esc($urlDengan(['status' => $status === 'Semua' ? '' : $status, 'page' => 1])) ?>">
                                    <?= $status === 'Semua' ? 'Semua Status' : esc($status) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <select class="filter-field" onchange="if(this.value) window.location.href=this.value;">
                            <option value="">Urutkan</option>
                            <option value="<?= esc($urlDengan(['sort' => 'terbaru', 'page' => 1])) ?>" <?= $sort === 'terbaru' ? 'selected' : '' ?>>Terbaru</option>
                            <option value="<?= esc($urlDengan(['sort' => 'terlama', 'page' => 1])) ?>" <?= $sort === 'terlama' ? 'selected' : '' ?>>Terlama</option>
                        </select>
                    </div>
                    <div class="section-heading">
                        <div>
                            <h3 class="panel-title">Daftar Pengajuan Peminjaman</h3>
                            <p class="panel-subtitle">Periksa detail peminjaman sebelum memproses pengajuan.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table ebrow-table">
                            <thead>
                                <tr>
                                    <th>Peminjam</th>
                                    <th>Barang</th>
                                    <th>Tgl Pengajuan</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Tgl Kembali</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="applicationRows">
                                <?php if (!$daftarPengajuan): ?>
                                    <tr><td colspan="8" class="text-center py-4">Pengajuan tidak ditemukan.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($daftarPengajuan as $pengajuan): ?>
                                        <tr data-name="<?= esc(strtolower($pengajuan['nama_peminjam'] . ' ' . $pengajuan['nama_barang'])) ?>"
                                            data-status="<?= esc(statusLabel($pengajuan['status_peminjaman'])) ?>">
                                            <td><?= esc($pengajuan['nama_peminjam']) ?></td>
                                            <td>
                                                <div class="item-cell">
                                                    <div class="item-picture"><i class="bi <?= esc(barangIcon($pengajuan['nama_barang'])) ?>"></i></div>
                                                    <div><b><?= esc($pengajuan['nama_barang']) ?></b><small><?= esc($pengajuan['kode_barang']) ?> · <?= esc($pengajuan['kondisi_barang']) ?></small></div>
                                                </div>
                                            </td>
                                            <td><?= esc(formatTanggalPendek($pengajuan['tanggal_pengajuan'])) ?></td>
                                            <td><?= esc(formatTanggalPendek($pengajuan['tanggal_pinjam'])) ?></td>
                                            <td><?= esc(formatTanggalPendek($pengajuan['tanggal_kembali'])) ?></td>
                                            <td><?= (int) $pengajuan['jumlah_unit'] ?></td>
                                            <td><span class="status <?= esc(statusClass($pengajuan['status_peminjaman'])) ?>"><?= esc(statusLabel($pengajuan['status_peminjaman'])) ?></span></td>
                                            <td><a class="btn-small" href="<?= esc(adminUrl('detail_peminjaman.php', array_merge($kembaliDetailParams, ['id' => (int) $pengajuan['id_peminjaman'], 'page' => $halaman]))) ?>" data-remember-scroll>Lihat Detail</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php renderPagination((int) $totalPengajuan, $batas, $halaman, count($daftarPengajuan), 'pengajuan', static fn (int $nomor): string => $urlDengan(['page' => $nomor])); ?>
                </section>
                <section class="panel tab-panel" id="panelPengembalian" hidden="">
                    <div class="filter-grid return-filter">
                        <form method="get" class="search-form">
                            <div class="search-wrap">
                                <i class="bi bi-search"></i>
                                <input class="search-field" id="searchReturn" name="return_search" type="search" value="<?= esc($returnCari) ?>" placeholder="Cari nama peminjam atau barang...">
                            </div>
                            <input type="hidden" name="tab" value="pengembalian">
                            <?php foreach ($daftarParams as $kunci => $nilai): ?>
                                <?php if (in_array($kunci, ['return_search', 'return_page', 'page', 'scroll', 'tab'], true)) continue; ?>
                                <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn-small">Cari</button>
                        </form>
                    </div>
                    <div class="section-heading">
                        <div>
                            <h3 class="panel-title">Pengembalian Barang</h3>
                            <p class="panel-subtitle">Periksa barang yang telah dikembalikan sebelum melakukan konfirmasi.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table ebrow-table">
                            <thead>
                                <tr>
                                    <th>Peminjam</th>
                                    <th>Barang</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Rencana Kembali</th>
                                    <th>Status Pengembalian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="returnRows">
                                <?php if (!$daftarPengembalian): ?>
                                    <tr><td colspan="6" class="text-center py-4">Tidak ada pengembalian yang perlu dikonfirmasi.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($daftarPengembalian as $pengembalian): ?>
                                        <tr data-name="<?= esc(strtolower($pengembalian['nama_peminjam'] . ' ' . $pengembalian['nama_barang'])) ?>">
                                            <td><?= esc($pengembalian['nama_peminjam']) ?></td>
                                            <td>
                                                <div class="item-cell">
                                                    <div class="item-picture"><i class="bi <?= esc(barangIcon($pengembalian['nama_barang'])) ?>"></i></div>
                                                    <div><b><?= esc($pengembalian['nama_barang']) ?></b><small><?= esc($pengembalian['kode_barang']) ?> · Jumlah: <?= (int) $pengembalian['jumlah_unit'] ?> unit</small></div>
                                                </div>
                                            </td>
                                            <td><?= esc(formatTanggalPendek($pengembalian['tanggal_pinjam'])) ?></td>
                                            <td><?= esc(formatTanggalPendek($pengembalian['tanggal_kembali'])) ?></td>
                                            <td><span class="status waiting">Menunggu Konfirmasi</span></td>
                                            <td>
                                                <button type="button" class="btn-primary-soft" data-return-modal
                                                    data-person="<?= esc($pengembalian['nama_peminjam']) ?>"
                                                    data-item="<?= esc($pengembalian['nama_barang']) ?>"
                                                    data-loan-id="<?= (int) $pengembalian['id_peminjaman'] ?>">Konfirmasi Pengembalian</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php renderPagination((int) $totalPengembalian, $batas, $halamanReturn, count($daftarPengembalian), 'pengembalian', static fn (int $nomor): string => $urlDengan(['return_page' => $nomor, 'tab' => 'pengembalian'])); ?>
                </section>
            </div>
            <footer><span>© 2026 EBROW. Semua Hak Dilindungi.</span><span>Panel Administrator</span></footer>
        </main>
        <!-- Popup konfirmasi pengembalian -->
        <div class="modal-backdrop-custom" id="returnModal" aria-hidden="true">
            <div class="dialog-card">
                <div class="dialog-icon gold"><i class="bi bi-box-arrow-in-left"></i></div>
                <h2>Konfirmasi Pengembalian</h2>
                <p class="dialog-person" id="returnPerson">Siti Rahmawati - Speaker portabel JBL</p>
                <p class="dialog-description">Pastikan barang dan kelengkapannya sudah diterima dalam kondisi baik.</p>
                <div class="dialog-info"><b>Status Peminjaman: Selesai</b><br>Stok siap dipinjam bertambah sesuai jumlah unit.</div>
                <form method="post" id="returnForm">
                    <input type="hidden" name="action" value="confirm_return">
                    <input type="hidden" name="id" id="returnLoanId" value="">
                    <input type="hidden" name="tab" value="pengembalian">
                    <?php foreach ($daftarParams as $kunci => $nilai): ?>
                        <?php if ($kunci === 'tab') continue; ?>
                        <input type="hidden" name="<?= esc($kunci) ?>" value="<?= esc($nilai) ?>">
                    <?php endforeach; ?>
                    <div class="dialog-actions">
                        <button type="button" class="btn-cancel" onclick="closeModal('returnModal')">Batal</button>
                        <button type="submit" class="btn-confirm gold-button">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            const tabs = document.querySelectorAll('.page-tab');
            const panelPengajuan = document.getElementById('panelPengajuan');
            const panelPengembalian = document.getElementById('panelPengembalian');

            function aktifkanTab(nama, perbaruiUrl) {
                tabs.forEach(item => item.classList.toggle('active', item.dataset.tab === nama));
                panelPengajuan.hidden = nama !== 'pengajuan';
                panelPengembalian.hidden = nama !== 'pengembalian';

                if (perbaruiUrl) {
                    const url = new URL(window.location.href);
                    if (nama === 'pengembalian') url.searchParams.set('tab', 'pengembalian');
                    else url.searchParams.delete('tab');
                    history.replaceState(null, '', url.toString());
                }
            }

            const tabAwal = new URLSearchParams(window.location.search).get('tab') === 'pengembalian' ? 'pengembalian' : 'pengajuan';
            aktifkanTab(tabAwal, false);
            tabs.forEach(tab => tab.addEventListener('click', () => aktifkanTab(tab.dataset.tab, true)));

            function closeModal(id) { document.getElementById(id).classList.remove('show'); }
            document.querySelectorAll('[data-return-modal]').forEach(button => button.addEventListener('click', () => {
                document.getElementById('returnPerson').textContent = button.dataset.person + ' - ' + button.dataset.item;
                document.getElementById('returnLoanId').value = button.dataset.loanId;
                document.getElementById('returnModal').classList.add('show');
            }));
            document.querySelectorAll('.modal-backdrop-custom').forEach(modal => modal.addEventListener('click', event => { if (event.target === modal) modal.classList.remove('show'); }));
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') document.querySelectorAll('.modal-backdrop-custom.show').forEach(modal => modal.classList.remove('show'));
            });

            document.querySelectorAll('[data-remember-scroll]').forEach(link => {
                link.addEventListener('click', () => {
                    const url = new URL(link.href, window.location.href);
                    url.searchParams.set('scroll', String(Math.round(window.scrollY)));
                    link.href = url.toString();
                });
            });

            const params = new URLSearchParams(window.location.search);
            if (params.has('scroll')) {
                const posisi = Math.max(0, parseInt(params.get('scroll'), 10) || 0);
                window.requestAnimationFrame(() => window.scrollTo(0, posisi));
                params.delete('scroll');
                const sisa = params.toString();
                history.replaceState(null, '', window.location.pathname + (sisa ? '?' + sisa : ''));
            }
        </script>
    </body>
</html>
