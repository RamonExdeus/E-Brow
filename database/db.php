<?php
/**
 * Fungsi untuk mendapatkan koneksi database MySQL menggunakan MySQLi.
 * Mengaktifkan exception handling untuk error reporting yang bersih.
 * Kredensial bisa ditimpa lewat EBROW_DB_HOST/NAME/USER/PASS.
 *
 * @return mysqli
 */
function getDBConnection() {
    // Aktifkan MySQLi exceptions
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Kredensial dapat ditimpa lewat environment variable.
    $host = getenv('EBROW_DB_HOST') ?: '127.0.0.1';
    $db   = getenv('EBROW_DB_NAME') ?: 'ebrow';
    $user = getenv('EBROW_DB_USER') ?: 'root';
    $pass = getenv('EBROW_DB_PASS') ?: '';

    try {
        $conn = new mysqli($host, $user, $pass, $db);
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (mysqli_sql_exception $e) {
        // Log error secara internal, tampilkan pesan generik ke user
        error_log($e->getMessage());
        exit('Database connection failed.');
    }
}
