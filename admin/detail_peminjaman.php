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
                    <h1>Detail Peminjaman</h1>
                    <p>Periksa informasi peminjaman dan proses persetujuan.</p>
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
            <div class="content-area detail-area">
                <a href="kelola_peminjaman.php" class="back-link"><i class="bi bi-arrow-left"></i>Kembali ke Pengajuan</a>
                <section class="panel detail-card">
                    <div class="detail-product">
                        <div class="detail-product-image"><i class="bi bi-laptop"></i></div>
                        <div>
                            <h2>Laptop ASUS VivoBook 14</h2>
                            <span class="status waiting" id="detailStatus">Menunggu Persetujuan</span>
                            <p class="muted-text">BRG-001 · Elektronik</p>
                        </div>
                    </div>
                    <div class="detail-divider"></div>
                    <h3 class="detail-section-title">Informasi Peminjaman</h3>
                    <div class="detail-grid">
                        <div><label>Nama Peminjam</label><b>Lia Putri</b></div>
                        <div><label>Jumlah Barang</label><b>1 unit</b></div>
                        <div><label>Tanggal Pengajuan</label><b>17 Nov 2026</b></div>
                        <div><label>Tanggal Peminjaman</label><b>18 Nov 2026</b></div>
                        <div><label>Tanggal Pengembalian</label><b>21 Nov 2026</b></div>
                        <div><label>Nomor Peminjaman</label><b>PMJ-2026-001</b></div>
                    </div>
                    <div class="detail-divider"></div>
                    <h3 class="detail-section-title">Keperluan Peminjaman</h3>
                    <p class="purpose-text">Untuk menyelesaikan tugas kelompok dan presentasi mata kuliah Pengantar Teknologi Informasi.</p>
                    <div class="process-box">
                        <h3 class="detail-section-title">Proses Pengajuan</h3>
                        <p class="muted-text">Pilih tindakan untuk memproses pengajuan peminjaman barang.</p>
                        <div class="process-buttons">
                            <button class="btn-approve" onclick="openDecision('approve')"><i class="bi bi-check-circle"></i>Setujui</button>
                            <button class="btn-reject" onclick="openDecision('reject')"><i class="bi bi-x-circle"></i>Tolak</button>
                        </div>
                    </div>
                    <button class="btn-return-outline" onclick="openDecision('return')">Kembali ke Pengajuan</button>
                </section>
            </div>
            <footer><span>© 2026 EBROW. Semua Hak Dilindungi.</span><span>Panel Administrator</span></footer>
        </main>
        <div class="modal-backdrop-custom" id="approveModal" aria-hidden="true">
            <div class="dialog-card">
                <div class="dialog-icon green"><i class="bi bi-check-lg"></i></div>
                <h2>Setujui Pengajuan?</h2>
                <p class="dialog-person">Lia Putri - Laptop ASUS VivoBook 14</p>
                <p class="dialog-description">
                    Apakah kamu yakin ingin menyetujui pengajuan peminjaman ini? Pastikan barang tersedia pada tanggal yang diminta.
                </p>
                <div class="dialog-actions">
                    <button class="btn-cancel" onclick="closeDecision('approveModal')">Batal</button>
                    <button class="btn-confirm green-button" onclick="finishDecision('approve')">Ya, Setujui</button>
                </div>
            </div>
        </div>
        <div class="modal-backdrop-custom" id="rejectModal" aria-hidden="true">
            <div class="dialog-card">
                <div class="dialog-icon red"><i class="bi bi-x-lg"></i></div>
                <h2>Tolak Pengajuan?</h2>
                <p class="dialog-person">Lia Putri - Laptop ASUS VivoBook 14</p>
                <label class="dialog-label" for="rejectReason">Alasan Penolakan *</label>
                <textarea id="rejectReason" class="dialog-textarea" placeholder="Tuliskan alasan penolakan pengajuan..."></textarea>
                <p class="dialog-help">Alasan ini akan disampaikan kepada peminjam.</p>
                <div class="dialog-actions">
                    <button class="btn-cancel" onclick="closeDecision('rejectModal')">Batal</button>
                    <button class="btn-confirm red-button" onclick="finishDecision('reject')">Tolak Pengajuan</button>
                </div>
            </div>
        </div>
        <script>
                    function openDecision(type) {
                        if (type === 'return') { window.location.href = 'kelola_peminjaman.php'; return; }
                        document.getElementById(type + 'Modal').classList.add('show');
                    }
                    function closeDecision(id) { document.getElementById(id).classList.remove('show'); }
                    function finishDecision(type) {
                        if (type === 'reject' && !document.getElementById('rejectReason').value.trim()) {
                            alert('Silakan isi alasan penolakan terlebih dahulu.'); return;
                        }
                        closeDecision(type + 'Modal');
                        const status = document.getElementById('detailStatus');
                        if (type === 'approve') { status.textContent = 'Disetujui'; status.className = 'status available'; }
                        else { status.textContent = 'Ditolak'; status.className = 'status rejected'; }
                        alert(type === 'approve' ? 'Pengajuan berhasil disetujui. (Simulasi HTML)' : 'Pengajuan berhasil ditolak. (Simulasi HTML)');
                    }
                    document.querySelectorAll('.modal-backdrop-custom').forEach(modal => modal.addEventListener('click', event => { if (event.target === modal) modal.classList.remove('show'); }));
            
        </script>
    </body>
</html>
