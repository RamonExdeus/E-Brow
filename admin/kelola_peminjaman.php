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
    <aside class="sidebar">
    <div class="brand"><i class="bi bi-box"></i><span>EBROW</span></div>
    <div class="menu-title">PANEL ADMINISTRATOR</div>

    <a href="admin_dashboard.php" class="menu-link"><i class="bi bi-grid"></i><span>Dashboard</span></a>
    <a href="barang.php" class="menu-link"><i class="bi bi-box-seam"></i><span>Data Barang</span></a>
    <a href="kelola_peminjaman.php" class="menu-link active"><i class="bi bi-arrow-left-right"></i><span>Data Peminjaman</span></a>

    <a href="../login_screen.html" class="menu-link logout"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
  </aside>
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
                        <div class="admin-name">Rizky Pratama</div>
                        <div class="admin-role">Administrator</div>
                    </div>
                </div>
            </header>
            <div class="content-area">
                <section class="stats-grid stats-four">
                    <div class="summary-card">
                        <div class="summary-label">Total Pengajuan</div>
                        <div class="summary-bottom"><span class="summary-number">284</span><span class="summary-icon"><i class="bi bi-clipboard-check"></i></span></div>
                    </div>
                    <div class="summary-card highlighted">
                        <div class="summary-label">Menunggu Persetujuan</div>
                        <div class="summary-bottom">
                            <span class="summary-number" id="countPending">7</span><span class="summary-icon"><i class="bi bi-clock"></i></span>
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-label">Sedang Dipinjam</div>
                        <div class="summary-bottom"><span class="summary-number">5</span><span class="summary-icon"><i class="bi bi-arrow-left-right"></i></span></div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-label">Menunggu Pengembalian</div>
                        <div class="summary-bottom">
                            <span class="summary-number" id="countReturn">2</span><span class="summary-icon"><i class="bi bi-box-arrow-in-left"></i></span>
                        </div>
                    </div>
                </section>
                <div class="page-tabs">
                    <button class="page-tab active" data-tab="pengajuan">Pengajuan</button><button class="page-tab" data-tab="pengembalian">Pengembalian</button>
                </div>
                <section class="panel tab-panel" id="panelPengajuan">
                    <div class="filter-grid">
                        <div class="search-wrap">
                            <i class="bi bi-search"></i><input class="search-field" id="searchPengajuan" type="search" placeholder="Cari nama peminjam atau barang...">
                        </div>
                        <div class="input-icon">
                            <i class="bi bi-calendar3"></i>
                            <input class="filter-field" type="text" placeholder="Semua tanggal peminjaman" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
                        </div>
                        <div class="status-filters">
                            <button class="filter-chip active" data-status="Semua">Semua Status</button>
                            <button class="filter-chip" data-status="Menunggu">Menunggu</button>
                            <button class="filter-chip" data-status="Disetujui">Disetujui</button>
                            <button class="filter-chip" data-status="Ditolak">Ditolak</button>
                            <button class="filter-chip" data-status="Sedang Dipinjam">Sedang Dipinjam</button>
                        </div>
                        <select class="filter-field"><option>Semua status peminjaman</option><option>Terbaru</option><option>Terlama</option></select>
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
                                <tr data-name="lia laptop asus vivobook 14" data-status="Menunggu">
                                    <td>Lia</td>
                                    <td>
                                        <div class="item-cell">
                                            <div class="item-picture"><i class="bi bi-laptop"></i></div>
                                            <div><b>Laptop ASUS VivoBook 14</b><small>BRG-001 · Elektronik</small></div>
                                        </div>
                                    </td>
                                    <td>17 Nov 2026</td>
                                    <td>18 Nov 2026</td>
                                    <td>21 Nov 2026</td>
                                    <td>1</td>
                                    <td><span class="status waiting">Menunggu</span></td>
                                    <td><a class="btn-small" href="detail_peminjaman.php?id=1">Lihat Detail</a></td>
                                </tr>
                                <tr data-name="andi kamera canon eos 700d" data-status="Disetujui">
                                    <td>Andi</td>
                                    <td>
                                        <div class="item-cell">
                                            <div class="item-picture"><i class="bi bi-camera"></i></div>
                                            <div><b>Kamera Canon EOS 700D</b><small>BRG-002 · Elektronik</small></div>
                                        </div>
                                    </td>
                                    <td>16 Nov 2026</td>
                                    <td>18 Nov 2026</td>
                                    <td>20 Nov 2026</td>
                                    <td>1</td>
                                    <td><span class="status available">Disetujui</span></td>
                                    <td><a class="btn-small" href="detail_peminjaman.php?id=2">Lihat Detail</a></td>
                                </tr>
                                <tr data-name="budi proyektor epson eb x400" data-status="Ditolak">
                                    <td>Budi</td>
                                    <td>
                                        <div class="item-cell">
                                            <div class="item-picture"><i class="bi bi-projector"></i></div>
                                            <div><b>Proyektor Epson EB-X400</b><small>BRG-003 · Elektronik</small></div>
                                        </div>
                                    </td>
                                    <td>15 Nov 2026</td>
                                    <td>18 Nov 2026</td>
                                    <td>21 Nov 2026</td>
                                    <td>1</td>
                                    <td><span class="status rejected">Ditolak</span></td>
                                    <td><a class="btn-small" href="detail_peminjaman.php?id=3">Lihat Detail</a></td>
                                </tr>
                                <tr data-name="siti speaker portabel jbl" data-status="Sedang Dipinjam">
                                    <td>Siti</td>
                                    <td>
                                        <div class="item-cell">
                                            <div class="item-picture"><i class="bi bi-speaker"></i></div>
                                            <div><b>Speaker portabel JBL</b><small>BRG-004 · Elektronik</small></div>
                                        </div>
                                    </td>
                                    <td>14 Nov 2026</td>
                                    <td>16 Nov 2026</td>
                                    <td>19 Nov 2026</td>
                                    <td>1</td>
                                    <td><span class="status borrowed">Sedang Dipinjam</span></td>
                                    <td><a class="btn-small" href="detail_peminjaman.php?id=4">Lihat Detail</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-footer">
                        Menampilkan<b id="showApplications">4</b>dari 284 pengajuan
                        <div class="pagination-lite">
                            <button>‹</button><button class="current">1</button><button>2</button><button>3</button><span>...</span><button>71</button><button>›</button>
                        </div>
                    </div>
                </section>
                <section class="panel tab-panel" id="panelPengembalian" hidden="">
                    <div class="filter-grid return-filter">
                        <div class="search-wrap">
                            <i class="bi bi-search"></i><input class="search-field" id="searchReturn" type="search" placeholder="Cari nama peminjam atau barang...">
                        </div>
                        <div class="input-icon">
                            <i class="bi bi-calendar3"></i>
                            <input class="filter-field" type="text" placeholder="Semua tanggal pengembalian" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
                        </div>
                        <select class="filter-field"><option>Semua status pengembalian</option><option>Menunggu Konfirmasi</option><option>Selesai</option></select>
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
                                <tr data-name="siti speaker portabel jbl">
                                    <td>Siti</td>
                                    <td>
                                        <div class="item-cell">
                                            <div class="item-picture"><i class="bi bi-speaker"></i></div>
                                            <div><b>Speaker portabel JBL</b><small>BRG-004 · Jumlah: 1 unit</small></div>
                                        </div>
                                    </td>
                                    <td>16 Nov 2026</td>
                                    <td>19 Nov 2026</td>
                                    <td><span class="status waiting">Menunggu Konfirmasi</span></td>
                                    <td>
                                        <button class="btn-primary-soft" onclick="openModal('returnModal','Siti Rahmawati','Speaker portabel JBL')">Konfirmasi Pengembalian</button>
                                    </td>
                                </tr>
                                <tr data-name="roa tripod kamera">
                                    <td>Roa</td>
                                    <td>
                                        <div class="item-cell">
                                            <div class="item-picture"><i class="bi bi-camera-video"></i></div>
                                            <div><b>Tripod kamera</b><small>BRG-005 · Jumlah: 1 unit</small></div>
                                        </div>
                                    </td>
                                    <td>15 Nov 2026</td>
                                    <td>18 Nov 2026</td>
                                    <td><span class="status waiting">Menunggu Konfirmasi</span></td>
                                    <td><button class="btn-primary-soft" onclick="openModal('returnModal','Roa','Tripod kamera')">Konfirmasi Pengembalian</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-footer">
                        Menampilkan 2 pengembalian
                        <div class="pagination-lite"><button class="current">1</button></div>
                    </div>
                </section>
            </div>
            <footer><span>© 2026 EBROW. Semua Hak Dilindungi.</span><span>Panel Administrator</span></footer>
        </main>
        Popup konfirmasi pengembalian
        <div class="modal-backdrop-custom" id="returnModal" aria-hidden="true">
            <div class="dialog-card">
                <div class="dialog-icon gold"><i class="bi bi-box-arrow-in-left"></i></div>
                <h2>Konfirmasi Pengembalian</h2>
                <p class="dialog-person" id="returnPerson">Siti Rahmawati - Speaker portabel JBL</p>
                <p class="dialog-description">Pastikan barang dan kelengkapannya sudah diterima dalam kondisi baik.</p>
                <div class="dialog-info"><b>Status Peminjaman: Selesai</b><br>Stok siap dipinjam + 1 unit</div>
                <div class="dialog-actions">
                    <button class="btn-cancel" onclick="closeModal('returnModal')">Batal</button>
                    <button class="btn-confirm gold-button" onclick="confirmReturn()">Konfirmasi</button>
                </div>
            </div>
        </div>
        <script>
                    const tabs = document.querySelectorAll('.page-tab');
                    tabs.forEach(tab => tab.addEventListener('click', () => {
                        tabs.forEach(item => item.classList.toggle('active', item === tab));
                        document.getElementById('panelPengajuan').hidden = tab.dataset.tab !== 'pengajuan';
                        document.getElementById('panelPengembalian').hidden = tab.dataset.tab !== 'pengembalian';
                    }));
                    function searchRows(inputId, rowsId) {
                        const term = document.getElementById(inputId).value.toLowerCase();
                        document.querySelectorAll('#' + rowsId + ' tr').forEach(row => row.hidden = !row.dataset.name.includes(term));
                    }
                    document.getElementById('searchPengajuan').addEventListener('input', () => searchRows('searchPengajuan', 'applicationRows'));
                    document.getElementById('searchReturn').addEventListener('input', () => searchRows('searchReturn', 'returnRows'));
                    document.querySelectorAll('.filter-chip').forEach(button => button.addEventListener('click', () => {
                        document.querySelectorAll('.filter-chip').forEach(item => item.classList.toggle('active', item === button));
                        document.querySelectorAll('#applicationRows tr').forEach(row => row.hidden = button.dataset.status !== 'Semua' && row.dataset.status !== button.dataset.status);
                    }));
                    function openModal(id, person, item) {
                        document.getElementById('returnPerson').textContent = person + ' - ' + item;
                        document.getElementById(id).classList.add('show');
                    }
                    function closeModal(id) { document.getElementById(id).classList.remove('show'); }
                    function confirmReturn() {
                        closeModal('returnModal');
                        alert('Pengembalian berhasil dikonfirmasi. (Simulasi tampilan HTML)');
                    }
                    document.querySelectorAll('.modal-backdrop-custom').forEach(modal => modal.addEventListener('click', event => { if (event.target === modal) modal.classList.remove('show'); }));
            
        </script>
    </body>
</html>
