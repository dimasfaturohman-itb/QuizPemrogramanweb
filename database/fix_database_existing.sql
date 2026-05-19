USE db_pmb;

SET @db = DATABASE();

SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='pendaftar' AND COLUMN_NAME='jurusan_sekolah') = 0,
    'ALTER TABLE pendaftar ADD COLUMN jurusan_sekolah VARCHAR(100) NULL',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='pendaftar' AND COLUMN_NAME='status_pendaftaran') = 0,
    'ALTER TABLE pendaftar ADD COLUMN status_pendaftaran VARCHAR(30) NOT NULL DEFAULT ''menunggu''',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='pendaftar' AND COLUMN_NAME='updated_at') = 0,
    'ALTER TABLE pendaftar ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

ALTER TABLE pendaftar MODIFY nama_lengkap VARCHAR(100) NULL;
ALTER TABLE pendaftar MODIFY jenis_kelamin VARCHAR(20) NULL;

UPDATE pendaftar
SET status_pendaftaran = status_akhir
WHERE status_akhir IN ('diterima', 'ditolak');

UPDATE pendaftar
SET status_pendaftaran = 'menunggu'
WHERE status_pendaftaran IN ('pending', 'cadangan', 'terverifikasi')
   OR status_pendaftaran IS NULL
   OR status_pendaftaran = '';

SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='dokumen' AND COLUMN_NAME='jenis_dokumen') = 0,
    'ALTER TABLE dokumen ADD COLUMN jenis_dokumen VARCHAR(50) NULL',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='dokumen' AND COLUMN_NAME='nama_file') = 0,
    'ALTER TABLE dokumen ADD COLUMN nama_file VARCHAR(255) NULL',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='dokumen' AND COLUMN_NAME='updated_at') = 0,
    'ALTER TABLE dokumen ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

ALTER TABLE dokumen MODIFY status_verifikasi VARCHAR(30) NOT NULL DEFAULT 'menunggu';

UPDATE dokumen SET status_verifikasi = 'menunggu' WHERE status_verifikasi = 'pending';
UPDATE dokumen SET status_verifikasi = 'diterima' WHERE status_verifikasi = 'valid';
UPDATE dokumen SET status_verifikasi = 'ditolak' WHERE status_verifikasi = 'revisi';

CREATE TABLE IF NOT EXISTS pembayaran (
    id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
    id_pendaftar INT NOT NULL,
    jumlah_bayar DECIMAL(12,2) NULL,
    tanggal_bayar DATE NULL,
    bukti_bayar VARCHAR(255) NULL,
    status_verifikasi VARCHAR(30) NOT NULL DEFAULT 'belum_bayar',
    catatan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE pembayaran MODIFY status_verifikasi VARCHAR(30) NOT NULL DEFAULT 'belum_bayar';

CREATE TABLE IF NOT EXISTS ospek (
    id_ospek INT AUTO_INCREMENT PRIMARY KEY,
    id_pendaftar INT NOT NULL,
    tanggal_ospek DATE NULL,
    lokasi VARCHAR(100) NULL,
    kelompok VARCHAR(50) NULL,
    mentor VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
