<?php
/**
 * Utilitas UI bersama untuk halaman admin EBROW.
 * Dipakai untuk menjaga pagination, filter, dan posisi kembali konsisten.
 */

if (!function_exists('adminListParams')) {
    /** Ambil parameter daftar yang boleh dibawa lintas halaman. */
    function adminListParams(array $source): array
    {
        $params = [];

        if (($nilai = trim((string) ($source['search'] ?? ''))) !== '') {
            $params['search'] = $nilai;
        }
        if (($nilai = (int) ($source['kategori'] ?? 0)) > 0) {
            $params['kategori'] = $nilai;
        }
        if (($nilai = trim((string) ($source['status'] ?? ''))) !== '') {
            $params['status'] = $nilai;
        }
        if (($nilai = trim((string) ($source['role'] ?? ''))) !== '') {
            $params['role'] = $nilai;
        }
        $verifikasi = $source['verifikasi'] ?? $source['is_terverifikasi'] ?? '';
        if (($nilai = trim((string) $verifikasi)) !== '') {
            $params['verifikasi'] = $nilai;
        }
        if (($nilai = trim((string) ($source['sort'] ?? ''))) !== '' && $nilai !== 'terbaru') {
            $params['sort'] = $nilai;
        }
        if (($nilai = trim((string) ($source['return_search'] ?? ''))) !== '') {
            $params['return_search'] = $nilai;
        }
        if (($nilai = (int) ($source['page'] ?? 0)) > 1) {
            $params['page'] = $nilai;
        }
        if (($nilai = (int) ($source['return_page'] ?? 0)) > 1) {
            $params['return_page'] = $nilai;
        }
        if (($nilai = (string) ($source['tab'] ?? '')) === 'pengembalian') {
            $params['tab'] = $nilai;
        }
        if (($nilai = (int) ($source['scroll'] ?? 0)) > 0) {
            $params['scroll'] = $nilai;
        }

        return $params;
    }
}

if (!function_exists('adminListQuery')) {
    /** Query string dari parameter daftar yang sudah disaring. */
    function adminListQuery(array $params): string
    {
        return http_build_query($params);
    }
}

if (!function_exists('adminUrl')) {
    /** Bangun URL admin dengan parameter yang tidak kosong. */
    function adminUrl(string $path, array $params = []): string
    {
        $params = array_filter($params, static fn ($nilai) => $nilai !== '' && $nilai !== null && $nilai !== 0);

        return $path . ($params ? '?' . http_build_query($params) : '');
    }
}

if (!function_exists('renderPagination')) {
    /**
     * Tampilkan ringkasan dan navigasi halaman yang sama di semua daftar admin.
     *
     * @param callable(int):string $urlUntukHalaman
     */
    function renderPagination(
        int $totalItem,
        int $perHalaman,
        int $halaman,
        int $ditampilkan,
        string $label,
        callable $urlUntukHalaman
    ): void {
        $totalHalaman = max(1, (int) ceil($totalItem / $perHalaman));
        $halaman = min(max(1, $halaman), $totalHalaman);

        $nomor = [];
        for ($i = 1; $i <= $totalHalaman; $i++) {
            if ($i === 1 || $i === $totalHalaman || abs($i - $halaman) <= 2) {
                $nomor[] = $i;
            }
        }

        $itemHalaman = [];
        $sebelumnya = null;
        foreach ($nomor as $i) {
            if ($sebelumnya !== null && $i - $sebelumnya > 1) {
                $itemHalaman[] = null;
            }
            $itemHalaman[] = $i;
            $sebelumnya = $i;
        }
        ?>
        <div class="pagination-row">
            <p class="pagination-summary">
                Menampilkan <b><?= $ditampilkan ?></b> dari <?= $totalItem ?> <?= esc($label) ?>
            </p>

            <?php if ($totalHalaman > 1): ?>
                <nav class="pagination" aria-label="Navigasi halaman">
                    <?php if ($halaman > 1): ?>
                        <a class="page-nav" href="<?= esc($urlUntukHalaman($halaman - 1)) ?>" aria-label="Halaman sebelumnya">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    <?php else: ?>
                        <span class="page-nav disabled" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                    <?php endif; ?>

                    <?php foreach ($itemHalaman as $i): ?>
                        <?php if ($i === null): ?>
                            <span class="page-ellipsis" aria-hidden="true">…</span>
                        <?php elseif ($i === $halaman): ?>
                            <span class="page-number current" aria-current="page"><?= $i ?></span>
                        <?php else: ?>
                            <a class="page-number" href="<?= esc($urlUntukHalaman($i)) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($halaman < $totalHalaman): ?>
                        <a class="page-nav" href="<?= esc($urlUntukHalaman($halaman + 1)) ?>" aria-label="Halaman berikutnya">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <span class="page-nav disabled" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </div>
        <?php
    }
}

if (!function_exists('renderSidebar')) {
    /** Sidebar admin yang sama di seluruh halaman */
    function renderSidebar(string $active): void
    {
        $menu = [
            ['dashboard', 'admin_dashboard.php', 'bi-grid', 'Dashboard'],
            ['barang', 'barang.php', 'bi-box-seam', 'Data Barang'],
            ['user', 'data_user.php', 'bi-people', 'Data Pengguna'],
            ['peminjaman', 'kelola_peminjaman.php', 'bi-arrow-left-right', 'Data Peminjaman']
        ];
        ?>
        <aside class="sidebar">
            <div class="brand">
                <i class="bi bi-box"></i><span>EBROW</span>
            </div> 
            <div class="menu-title">PANEL ADMINISTRATOR</div>

            <?php foreach ($menu as [$kunci, $href, $ikon, $label]): ?>
                <a href="<?= esc($href) ?>" class="menu-link <?= $active === $kunci ? 'active' : '' ?>">
                    <i class="bi <?= esc($ikon) ?>"></i><span><?= esc($label) ?></span>
                </a>
            <?php endforeach; ?>

            <a href="../login_screen.php" class="menu-link logout">
                <i class="bi bi-box-arrow-right"></i><span>Logout</span>
            </a>
        </aside>
        <?php
    }
}
