<?php
/**
 * Session helpers bersama untuk EBROW.
 * File ini aman di-require berulang kali.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Apakah pengunjung sudah login sebagai pengguna/admin? */
function isLoggedIn(): bool
{
    return (int) ($_SESSION['id_user'] ?? 0) > 0;
}

/** Role akun yang sedang login: user atau admin. */
function currentRole(): string
{
    return strtolower(trim((string) ($_SESSION['role'] ?? '')));
}

/** Apakah akun yang sedang login memiliki role admin? */
function isAdmin(): bool
{
    return currentRole() === 'admin';
}

/** Arahkan ke halaman login bila belum login. */
function requireLogin(string $loginUrl = 'login_screen.php'): void
{
    if (!isLoggedIn()) {
        header('Location: ' . $loginUrl);
        exit;
    }
}

/** Arahkan ke login bila tamu, atau ke dashboard user bila bukan admin. */
function requireAdmin(string $loginUrl = 'login_screen.php', string $userUrl = 'user/dashboard_user.php'): void
{
    if (!isLoggedIn()) {
        header('Location: ' . $loginUrl);
        exit;
    }

    if (!isAdmin()) {
        header('Location: ' . $userUrl);
        exit;
    }
}
