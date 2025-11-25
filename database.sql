-- Create Database
CREATE DATABASE IF NOT EXISTS `zenergy` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use Database
USE `zenergy`;

-- ============================================
-- Table: users
-- Deskripsi: Menyimpan data pengguna (penghuni)
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL COMMENT 'Nama lengkap user',
  `email` VARCHAR(255) NOT NULL COMMENT 'Email user (unique)',
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Waktu verifikasi email',
  `password` VARCHAR(255) NOT NULL COMMENT 'Password (hashed)',
  `profile_photo_path` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Path foto profil',
  `domisili` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Kota domisili user',
  `remember_token` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Token remember me',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan akun',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update terakhir',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel user/penghuni';

-- ============================================
-- Table: electricity_notes
-- Deskripsi: Menyimpan catatan penggunaan listrik
-- ============================================
CREATE TABLE IF NOT EXISTS `electricity_notes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'ID user pemilik catatan',
  `date` DATE NOT NULL COMMENT 'Tanggal pencatatan',
  `price_per_kwh` DECIMAL(10,2) NOT NULL COMMENT 'Harga per kWh',
  `house_power` INT(11) NOT NULL COMMENT 'Daya listrik rumah (VA)',
  `total_cost` DECIMAL(12,2) NOT NULL COMMENT 'Total biaya listrik',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update',
  PRIMARY KEY (`id`),
  INDEX `idx_electricity_notes_user_id` (`user_id`),
  INDEX `idx_electricity_notes_date` (`date`),
  INDEX `idx_electricity_notes_user_date` (`user_id`, `date`),
  CONSTRAINT `fk_electricity_notes_user_id` 
    FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel catatan listrik utama';

-- ============================================
-- Table: electricity_items
-- Deskripsi: Menyimpan detail alat elektronik per catatan
-- ============================================
CREATE TABLE IF NOT EXISTS `electricity_items` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `note_id` BIGINT(20) UNSIGNED NOT NULL COMMENT 'ID catatan listrik',
  `appliance_name` VARCHAR(255) NOT NULL COMMENT 'Nama alat elektronik',
  `quantity` INT(11) NOT NULL COMMENT 'Jumlah alat',
  `duration_hours` INT(11) NOT NULL COMMENT 'Durasi penggunaan (jam)',
  `duration_minutes` INT(11) NOT NULL COMMENT 'Durasi penggunaan (menit)',
  `wattage` INT(11) NOT NULL COMMENT 'Daya alat (watt)',
  `cost` DECIMAL(10,2) NOT NULL COMMENT 'Biaya per alat',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu update',
  PRIMARY KEY (`id`),
  INDEX `idx_electricity_items_note_id` (`note_id`),
  CONSTRAINT `fk_electricity_items_note_id` 
    FOREIGN KEY (`note_id`) 
    REFERENCES `electricity_notes` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel detail alat elektronik';

-- ============================================
-- Table: password_resets
-- Deskripsi: Menyimpan token reset password
-- ============================================
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_password_resets_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel reset password';

-- ============================================
-- Table: failed_jobs
-- Deskripsi: Menyimpan job yang gagal (untuk queue system)
-- ============================================
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel failed jobs';

-- ============================================
-- Table: sessions (Optional - untuk session database)
-- ============================================
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_sessions_user_id` (`user_id`),
  INDEX `idx_sessions_last_activity` (`last_activity`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel sessions';

-- buat sistem badge dan point
ALTER TABLE `users`
ADD COLUMN `points` INT DEFAULT 0 AFTER `profile_photo_path`,
ADD COLUMN `last_login_date` DATE NULL DEFAULT NULL AFTER `points`,
ADD COLUMN `last_claim` DATE NULL DEFAULT NULL AFTER `last_login_date`,
ADD COLUMN `login_count` INT DEFAULT 0 AFTER `last_claim`;
