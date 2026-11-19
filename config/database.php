<?php
// Konfigurasi koneksi database MariaDB (MySQLi)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$dbname = 'ebrow';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $mysqli = new mysqli($host, $user, $pass, $dbname);
    $mysqli->set_charset($charset);
} catch (mysqli_sql_exception $e) {
    // Pada produksi, jangan tampilkan detail error
    die('Koneksi database gagal.');
}

function db_all(string $sql, string $types = '', array $params = []): array {
    global $mysqli;
    if ($types === '' || empty($params)) {
        $res = $mysqli->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
    $st = $mysqli->prepare($sql);
    $st->bind_param($types, ...$params);
    $st->execute();
    $res = $st->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function db_one(string $sql, string $types = '', array $params = []): ?array {
    global $mysqli;
    if ($types === '' || empty($params)) {
        $res = $mysqli->query($sql);
        $row = $res ? $res->fetch_assoc() : null;
        return $row ?: null;
    }
    $st = $mysqli->prepare($sql);
    $st->bind_param($types, ...$params);
    $st->execute();
    $res = $st->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    return $row ?: null;
}

function db_exec(string $sql, string $types = '', array $params = []): int {
    global $mysqli;
    if ($types === '' || empty($params)) {
        $mysqli->query($sql);
        return $mysqli->affected_rows;
    }
    $st = $mysqli->prepare($sql);
    $st->bind_param($types, ...$params);
    $st->execute();
    return $st->affected_rows;
}

function db_scalar(string $sql, string $types = '', array $params = []) {
    global $mysqli;
    if ($types === '' || empty($params)) {
        $res = $mysqli->query($sql);
        $row = $res ? $res->fetch_row() : null;
        return $row ? $row[0] : null;
    }
    $st = $mysqli->prepare($sql);
    $st->bind_param($types, ...$params);
    $st->execute();
    $res = $st->get_result();
    $row = $res ? $res->fetch_row() : null;
    return $row ? $row[0] : null;
}
