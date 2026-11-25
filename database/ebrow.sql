-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for ebrow
CREATE DATABASE IF NOT EXISTS `ebrow` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ebrow`;

-- Dumping structure for table ebrow.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `id_barang` int unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori` int unsigned NOT NULL,
  `nama_barang` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `foto_barang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga_per_hari` decimal(12,2) NOT NULL DEFAULT '0.00',
  `kondisi_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baik',
  `stok_total` int unsigned NOT NULL DEFAULT '0',
  `stok_tersedia` int unsigned NOT NULL DEFAULT '0',
  `status_ketersediaan` enum('Tersedia','Tidak tersedia') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tersedia',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_barang`),
  UNIQUE KEY `kode_barang` (`kode_barang`),
  KEY `fk_barang_kategori` (`id_kategori`),
  CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_barang` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ebrow.barang: ~0 rows (approximately)

-- Dumping structure for table ebrow.kategori_barang
CREATE TABLE IF NOT EXISTS `kategori_barang` (
  `id_kategori` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ebrow.kategori_barang: ~0 rows (approximately)

-- Dumping structure for table ebrow.peminjaman
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id_peminjaman` int unsigned NOT NULL AUTO_INCREMENT,
  `nomor_peminjaman` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` int unsigned NOT NULL,
  `id_barang` int unsigned NOT NULL,
  `id_admin_verifikator` int unsigned DEFAULT NULL,
  `jumlah_unit` int unsigned NOT NULL DEFAULT '1',
  `tanggal_pengajuan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `batas_kembali_jam` time NOT NULL DEFAULT '16:00:00',
  `keperluan_peminjaman` text COLLATE utf8mb4_unicode_ci,
  `lokasi_pengambilan` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT 'Ruang Inventaris Lt. 1',
  `status_peminjaman` enum('Menunggu Persetujuan','Disetujui','Siap Diambil','Sedang Dipinjam','Ditolak','Selesai','Dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Persetujuan',
  `alasan_penolakan` text COLLATE utf8mb4_unicode_ci,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `waktu_diverifikasi` datetime DEFAULT NULL,
  `waktu_kembali_aktual` datetime DEFAULT NULL,
  `kondisi_verifikasi_kembali` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_peminjaman`),
  UNIQUE KEY `nomor_peminjaman` (`nomor_peminjaman`),
  KEY `fk_peminjaman_user` (`id_user`),
  KEY `fk_peminjaman_barang` (`id_barang`),
  KEY `fk_peminjaman_admin` (`id_admin_verifikator`),
  CONSTRAINT `fk_peminjaman_admin` FOREIGN KEY (`id_admin_verifikator`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_peminjaman_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ebrow.peminjaman: ~0 rows (approximately)

-- Dumping structure for table ebrow.users
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim_anggota` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telepon` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prodi_organisasi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_terverifikasi` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nim_anggota` (`nim_anggota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ebrow.users: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
