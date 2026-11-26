-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: ebrow
-- ------------------------------------------------------
-- Server version   8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `barang`
--

DROP TABLE IF EXISTS `barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barang` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang`
--

LOCK TABLES `barang` WRITE;
INSERT INTO `barang` (`id_barang`, `kode_barang`, `id_kategori`, `nama_barang`, `deskripsi`, `foto_barang`, `harga_per_hari`, `kondisi_barang`, `stok_total`, `stok_tersedia`, `status_ketersediaan`, `created_at`, `updated_at`) VALUES
  ('1', 'BRG-001', '1', 'Laptop ASUS VivoBook 14', 'Laptop untuk tugas dan presentasi perkuliahan.', 'assets/images/laptop.jpeg', '50000.00', 'Baik', '3', '3', 'Tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('2', 'BRG-002', '1', 'Kamera Canon EOS 700D', 'Kamera DSLR untuk dokumentasi kegiatan.', 'assets/images/kamera.jpeg', '40000.00', 'Baik', '2', '2', 'Tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('3', 'BRG-003', '1', 'Proyektor Epson EB-X400', 'Proyektor untuk presentasi di ruang kelas.', NULL, '75000.00', 'Baik', '1', '0', 'Tidak tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('4', 'BRG-004', '1', 'Speaker portabel JBL', 'Speaker untuk kebutuhan audio acara.', 'assets/images/speaker.jpeg', '25000.00', 'Baik', '2', '1', 'Tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('5', 'BRG-005', '3', 'Tripod kamera', 'Tripod untuk menopang kamera saat merekam.', NULL, '15000.00', 'Baik', '1', '0', 'Tidak tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('6', 'BRG-006', '2', 'Meja Lipat', 'Meja lipat untuk bazaar dan seminar.', NULL, '20000.00', 'Baik', '6', '4', 'Tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('7', 'BRG-007', '2', 'Kursi Lipat', 'Kursi lipat untuk menambah kapasitas tempat duduk.', NULL, '10000.00', 'Baik', '20', '16', 'Tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('8', 'BRG-008', '2', 'Papan Tulis Portable', 'Papan tulis berdiri untuk diskusi kelompok.', NULL, '15000.00', 'Baik', '3', '3', 'Tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('9', 'BRG-009', '3', 'Panel Backdrop', 'Panel backdrop untuk dekorasi acara.', NULL, '40000.00', 'Baik', '4', '4', 'Tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('10', 'BRG-010', '3', 'Standing Banner', 'Standing banner untuk promosi kegiatan kampus.', NULL, '20000.00', 'Kurang Baik', '5', '5', 'Tersedia', '2026-01-01 00:00:00', '2026-01-01 00:00:00');
UNLOCK TABLES;

--
-- Table structure for table `kategori_barang`
--

DROP TABLE IF EXISTS `kategori_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_barang` (
  `id_kategori` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_barang`
--

LOCK TABLES `kategori_barang` WRITE;
INSERT INTO `kategori_barang` (`id_kategori`, `nama_kategori`, `created_at`) VALUES
  ('1', 'Elektronik', '2026-01-01 00:00:00'),
  ('2', 'Non Elektronik', '2026-01-01 00:00:00'),
  ('3', 'Perlengkapan Acara', '2026-01-01 00:00:00');
UNLOCK TABLES;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peminjaman` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

LOCK TABLES `peminjaman` WRITE;
INSERT INTO `peminjaman` (`id_peminjaman`, `nomor_peminjaman`, `id_user`, `id_barang`, `id_admin_verifikator`, `jumlah_unit`, `tanggal_pengajuan`, `tanggal_pinjam`, `tanggal_kembali`, `batas_kembali_jam`, `keperluan_peminjaman`, `lokasi_pengambilan`, `status_peminjaman`, `alasan_penolakan`, `catatan_admin`, `waktu_diverifikasi`, `waktu_kembali_aktual`, `kondisi_verifikasi_kembali`, `created_at`, `updated_at`) VALUES
  ('1', 'PMJ-2026-001', '2', '1', NULL, '1', '2026-11-24 09:15:00', '2026-11-27', '2026-11-30', '16:00:00', 'Tugas kelompok mata kuliah Pengantar Teknologi Informasi.', 'Ruang Inventaris Lt. 1', 'Menunggu Persetujuan', NULL, NULL, NULL, NULL, NULL, '2026-11-24 09:15:00', '2026-11-24 09:15:00'),
  ('2', 'PMJ-2026-002', '3', '2', NULL, '1', '2026-11-25 10:20:00', '2026-11-28', '2026-11-29', '16:00:00', 'Dokumentasi seminar fakultas.\nWaktu pengambilan: 09:00 WIB\nLampiran: surat-kegiatan.pdf', 'Ruang Inventaris Lt. 1', 'Menunggu Persetujuan', NULL, NULL, NULL, NULL, NULL, '2026-11-25 10:20:00', '2026-11-25 10:20:00'),
  ('3', 'PMJ-2026-003', '4', '3', '1', '1', '2026-11-20 08:30:00', '2026-11-22', '2026-11-24', '16:00:00', 'Presentasi materi perkuliahan.', 'Ruang Inventaris Lt. 1', 'Ditolak', 'Barang sedang dalam perawatan pada tanggal yang diminta.', NULL, '2026-11-20 11:00:00', NULL, NULL, '2026-11-20 08:30:00', '2026-11-20 11:00:00'),
  ('4', 'PMJ-2026-004', '5', '4', '1', '1', '2026-11-21 13:05:00', '2026-11-27', '2026-11-28', '16:00:00', 'Kebutuhan audio untuk acara konser mahasiswa.', 'Ruang Inventaris Lt. 1', 'Disetujui', NULL, 'Diambil di ruang inventaris.', '2026-11-22 09:00:00', NULL, NULL, '2026-11-21 13:05:00', '2026-11-22 09:00:00'),
  ('5', 'PMJ-2026-005', '6', '5', '1', '1', '2026-11-22 15:40:00', '2026-11-26', '2026-11-27', '16:00:00', 'Dokumentasi kegiatan olahraga kampus.', 'Ruang Inventaris Lt. 1', 'Siap Diambil', NULL, 'Silakan ambil dengan membawa KTM.', '2026-11-23 08:30:00', NULL, NULL, '2026-11-22 15:40:00', '2026-11-23 08:30:00'),
  ('6', 'PMJ-2026-006', '7', '6', '1', '2', '2026-11-18 09:00:00', '2026-11-20', '2026-11-24', '16:00:00', 'Kegiatan bazaar fakultas.', 'Ruang Inventaris Lt. 1', 'Sedang Dipinjam', NULL, 'Harus segera dikembalikan.', '2026-11-19 10:00:00', NULL, NULL, '2026-11-18 09:00:00', '2026-11-19 10:00:00'),
  ('7', 'PMJ-2026-007', '8', '7', '1', '4', '2026-11-19 11:00:00', '2026-11-21', '2026-11-29', '16:00:00', 'Menambah kursi untuk seminar kampus.', 'Ruang Inventaris Lt. 1', 'Sedang Dipinjam', NULL, NULL, '2026-11-20 10:00:00', NULL, NULL, '2026-11-19 11:00:00', '2026-11-20 10:00:00'),
  ('8', 'PMJ-2026-008', '9', '8', '1', '1', '2026-11-10 09:30:00', '2026-11-12', '2026-11-15', '16:00:00', 'Diskusi kelompok mata kuliah.', 'Ruang Inventaris Lt. 1', 'Selesai', NULL, 'Barang kembali dengan baik.', '2026-11-11 10:00:00', '2026-11-15 15:30:00', 'Lengkap & Baik', '2026-11-10 09:30:00', '2026-11-15 15:30:00'),
  ('9', 'PMJ-2026-009', '10', '9', '1', '2', '2026-11-08 08:00:00', '2026-11-10', '2026-11-13', '16:00:00', 'Dekorasi acara jurusan.', 'Ruang Inventaris Lt. 1', 'Selesai', NULL, 'Barang kembali dengan baik.', '2026-11-09 09:00:00', '2026-11-13 14:00:00', 'Lengkap & Baik', '2026-11-08 08:00:00', '2026-11-13 14:00:00'),
  ('10', 'PMJ-2026-010', '2', '10', '1', '1', '2026-11-05 14:00:00', '2026-11-07', '2026-11-08', '16:00:00', 'Promosi kegiatan kampus.', 'Ruang Inventaris Lt. 1', 'Dibatalkan', 'Kegiatan dibatalkan panitia.', NULL, '2026-11-05 16:00:00', NULL, NULL, '2026-11-05 14:00:00', '2026-11-05 16:00:00'),
  ('11', 'PMJ-2026-011', '3', '1', NULL, '1', '2026-11-26 09:00:00', '2026-11-28', '2026-12-01', '16:00:00', 'Mengerjakan tugas akhir.', 'Ruang Inventaris Lt. 1', 'Menunggu Persetujuan', NULL, NULL, NULL, NULL, NULL, '2026-11-26 09:00:00', '2026-11-26 09:00:00'),
  ('12', 'PMJ-2026-012', '4', '2', '1', '1', '2026-11-23 11:00:00', '2026-11-29', '2026-11-30', '16:00:00', 'Dokumentasi wisuda fakultas.', 'Ruang Inventaris Lt. 1', 'Disetujui', NULL, NULL, '2026-11-24 10:00:00', NULL, NULL, '2026-11-23 11:00:00', '2026-11-24 10:00:00');
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--
-- Semua akun contoh memakai password: password123
--

LOCK TABLES `users` WRITE;
INSERT INTO `users` (`id_user`, `nama_lengkap`, `nim_anggota`, `email`, `no_telepon`, `prodi_organisasi`, `password_hash`, `role`, `is_terverifikasi`, `created_at`, `updated_at`) VALUES
  ('1', 'Rizky Pratama', NULL, 'admin@ebrow.test', '08120000000', 'Administrator', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'admin', '1', '2026-01-01 00:00:00', '2026-01-01 00:00:00'),
  ('2', 'Lia Putri', 2026001, 'lia.putri@student.ebrow.test', '08120000001', 'Teknik Informatika', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'user', '1', '2026-01-05 09:00:00', '2026-01-05 09:00:00'),
  ('3', 'Andi Saputra', 2026002, 'andi.saputra@student.ebrow.test', '08120000002', 'Sistem Informasi', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'user', '1', '2026-01-06 09:00:00', '2026-01-06 09:00:00'),
  ('4', 'Budi Santoso', 2026003, 'budi.santoso@student.ebrow.test', '08120000003', 'Manajemen', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'user', '1', '2026-01-07 09:00:00', '2026-01-07 09:00:00'),
  ('5', 'Siti Rahmawati', 2026004, 'siti.rahmawati@student.ebrow.test', '08120000004', 'Akuntansi', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'user', '1', '2026-01-08 09:00:00', '2026-01-08 09:00:00'),
  ('6', 'Roa Pratama', 2026005, 'roa.pratama@student.ebrow.test', '08120000005', 'Ilmu Komunikasi', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'user', '1', '2026-01-09 09:00:00', '2026-01-09 09:00:00'),
  ('7', 'Pengguna Ebrow 7', 20260007, 'pengguna7@student.ebrow.test', '08121000007', 'Teknik Informatika', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'user', '1', '2026-01-10 09:00:00', '2026-01-10 09:00:00'),
  ('8', 'Pengguna Ebrow 8', 20260008, 'pengguna8@student.ebrow.test', '08121000008', 'Sistem Informasi', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'user', '1', '2026-01-10 09:00:00', '2026-01-10 09:00:00'),
  ('9', 'Pengguna Ebrow 9', 20260009, 'pengguna9@student.ebrow.test', '08121000009', 'Manajemen', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'user', '0', '2026-01-10 09:00:00', '2026-01-10 09:00:00'),
  ('10', 'Dewi Admin', NULL, 'dewi@ebrow.test', '08121000010', 'Administrator', '$2y$12$sVBISVaBgriumJ26x9jC9uIELgvzh2EXyvi8EdqSqRgjN12FNDohS', 'admin', '1', '2026-01-10 09:00:00', '2026-01-10 09:00:00');
UNLOCK TABLES;

-- Dumping routines for database 'ebrow'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-25 00:00:00
