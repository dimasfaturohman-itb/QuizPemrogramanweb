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


-- Dumping database structure for db_pmb
CREATE DATABASE IF NOT EXISTS `db_pmb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_pmb`;

-- Dumping structure for table db_pmb.dokumen
CREATE TABLE IF NOT EXISTS `dokumen` (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `id_pendaftar` int NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `ijazah` varchar(255) DEFAULT NULL,
  `rapor` varchar(255) DEFAULT NULL,
  `kartu_keluarga` varchar(255) DEFAULT NULL,
  `status_verifikasi` varchar(30) NOT NULL DEFAULT 'menunggu',
  `catatan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jenis_dokumen` varchar(50) DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`),
  KEY `fk_dokumen_pendaftar` (`id_pendaftar`),
  CONSTRAINT `fk_dokumen_pendaftar` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_pmb.dokumen: ~0 rows (approximately)
INSERT INTO `dokumen` (`id_dokumen`, `id_pendaftar`, `foto`, `ijazah`, `rapor`, `kartu_keluarga`, `status_verifikasi`, `catatan`, `created_at`, `jenis_dokumen`, `nama_file`, `updated_at`) VALUES
	(5, 2, NULL, NULL, NULL, NULL, 'diterima', '', '2026-05-19 02:27:29', 'KTP', '1779157649_2_Dokumen.jpg', '2026-05-19 09:30:21'),
	(6, 2, NULL, NULL, NULL, NULL, 'diterima', '', '2026-05-19 02:27:38', 'Kartu Keluarga', '1779157658_2_Dokumen.jpg', '2026-05-19 09:30:18'),
	(7, 2, NULL, NULL, NULL, NULL, 'diterima', '', '2026-05-19 02:27:52', 'Ijazah', '1779157672_2_Dokumen.jpg', '2026-05-19 09:30:15'),
	(8, 2, NULL, NULL, NULL, NULL, 'diterima', '', '2026-05-19 02:28:05', 'Pas Foto', '1779157685_2_Dokumen.jpg', '2026-05-19 09:30:11');

-- Dumping structure for table db_pmb.hasil_ujian
CREATE TABLE IF NOT EXISTS `hasil_ujian` (
  `id_hasil` int NOT NULL AUTO_INCREMENT,
  `id_pendaftar` int NOT NULL,
  `id_ujian` int NOT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `status_lulus` enum('lulus','tidak_lulus') DEFAULT 'tidak_lulus',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hasil`),
  KEY `fk_hasil_pendaftar` (`id_pendaftar`),
  KEY `fk_hasil_ujian` (`id_ujian`),
  CONSTRAINT `fk_hasil_pendaftar` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE,
  CONSTRAINT `fk_hasil_ujian` FOREIGN KEY (`id_ujian`) REFERENCES `ujian` (`id_ujian`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_pmb.hasil_ujian: ~0 rows (approximately)

-- Dumping structure for table db_pmb.ospek
CREATE TABLE IF NOT EXISTS `ospek` (
  `id_ospek` int NOT NULL AUTO_INCREMENT,
  `id_pendaftar` int NOT NULL,
  `tanggal_ospek` date DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `kelompok` varchar(50) DEFAULT NULL,
  `mentor` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ospek`),
  KEY `fk_ospek_pendaftar` (`id_pendaftar`),
  CONSTRAINT `fk_ospek_pendaftar` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_pmb.ospek: ~0 rows (approximately)
INSERT INTO `ospek` (`id_ospek`, `id_pendaftar`, `tanggal_ospek`, `lokasi`, `kelompok`, `mentor`, `created_at`) VALUES
	(2, 2, '2026-08-31', 'Lapangan FITK', 'Kelompok 31', 'Suryana', '2026-05-19 02:32:28');

-- Dumping structure for table db_pmb.pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id_pembayaran` int NOT NULL AUTO_INCREMENT,
  `id_pendaftar` int NOT NULL,
  `jumlah_bayar` decimal(12,2) DEFAULT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `status_verifikasi` varchar(30) NOT NULL DEFAULT 'belum_bayar',
  `catatan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  KEY `fk_pembayaran_pendaftar` (`id_pendaftar`),
  CONSTRAINT `fk_pembayaran_pendaftar` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_pmb.pembayaran: ~0 rows (approximately)
INSERT INTO `pembayaran` (`id_pembayaran`, `id_pendaftar`, `jumlah_bayar`, `tanggal_bayar`, `bukti_bayar`, `status_verifikasi`, `catatan`, `created_at`) VALUES
	(2, 2, 1500000.00, '2026-05-19', '1779157856_2_Dokumen.jpg', 'lunas', 'OK', '2026-05-19 02:30:56');

-- Dumping structure for table db_pmb.pendaftar
CREATE TABLE IF NOT EXISTS `pendaftar` (
  `id_pendaftar` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_prodi` int NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `agama` varchar(30) DEFAULT NULL,
  `alamat` text,
  `no_hp` varchar(15) DEFAULT NULL,
  `asal_sekolah` varchar(100) DEFAULT NULL,
  `tahun_lulus` year DEFAULT NULL,
  `status_berkas` enum('pending','lulus','ditolak') DEFAULT 'pending',
  `status_ujian` enum('belum','sudah') DEFAULT 'belum',
  `status_akhir` enum('pending','diterima','cadangan','ditolak') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jurusan_sekolah` varchar(100) DEFAULT NULL,
  `status_pendaftaran` varchar(30) NOT NULL DEFAULT 'menunggu',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pendaftar`),
  UNIQUE KEY `nik` (`nik`),
  KEY `fk_user_pendaftar` (`id_user`),
  KEY `fk_prodi_pendaftar` (`id_prodi`),
  CONSTRAINT `fk_prodi_pendaftar` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_pendaftar` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_pmb.pendaftar: ~0 rows (approximately)
INSERT INTO `pendaftar` (`id_pendaftar`, `id_user`, `id_prodi`, `nik`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `alamat`, `no_hp`, `asal_sekolah`, `tahun_lulus`, `status_berkas`, `status_ujian`, `status_akhir`, `created_at`, `jurusan_sekolah`, `status_pendaftaran`, `updated_at`) VALUES
	(2, 3, 1, '32091000000000', NULL, 'CIREBON', '2008-01-01', 'Laki-laki', 'ISLAM', 'RT 001 RW 001 Dusun 01, Sunyaragi, Kesambi, Cirebon, Jawa Barat', '0812345678910', 'SMAN 7 CIREBON', NULL, 'pending', 'belum', 'pending', '2026-05-19 02:26:39', 'Ilmu Pengetahuan Alam', 'diterima', '2026-05-19 09:30:21');

-- Dumping structure for table db_pmb.prodi
CREATE TABLE IF NOT EXISTS `prodi` (
  `id_prodi` int NOT NULL AUTO_INCREMENT,
  `nama_prodi` varchar(100) NOT NULL,
  `kuota` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prodi`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_pmb.prodi: ~5 rows (approximately)
INSERT INTO `prodi` (`id_prodi`, `nama_prodi`, `kuota`) VALUES
	(1, 'Teknik Informatika', 100),
	(2, 'Sistem Informasi', 80),
	(3, 'Manajemen', 120),
	(4, 'Akuntansi', 90),
	(5, 'Teknik Industri', 70);

-- Dumping structure for table db_pmb.ujian
CREATE TABLE IF NOT EXISTS `ujian` (
  `id_ujian` int NOT NULL AUTO_INCREMENT,
  `nama_ujian` varchar(100) NOT NULL,
  `tanggal_ujian` date NOT NULL,
  `jam_ujian` time DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_ujian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_pmb.ujian: ~0 rows (approximately)

-- Dumping structure for table db_pmb.users
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pendaftar') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pendaftar',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_pmb.users: ~1 rows (approximately)
INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
	(1, 'Administrator', 'admin@gmail.com', '$2y$10$Lioc5Z6gcXCqj/r3UehRRunrIbBquQnVQT3hORQBRsrQODAUWTOVC', 'admin', '2026-05-19 00:52:45'),
	(3, 'Pendaftar', 'pendaftar@gmail.com', '$2y$10$/u/xe0kbx1we2pvquY.V6uMd6Fn6cv0yo1Ofp89xnRmICldqo2Cs.', 'pendaftar', '2026-05-19 02:22:26');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
