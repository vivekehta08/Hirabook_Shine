-- HiraShine: Diamond Hisab Diary - Database Schema
-- All tables are designed for Android ID based user identification

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `android_id` VARCHAR(255) NOT NULL UNIQUE,
  `fcm_token` TEXT DEFAULT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_android_id` (`android_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Diamond Rates Table (User-specific rates)
CREATE TABLE IF NOT EXISTS `diamond_rates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `android_id` VARCHAR(255) NOT NULL,
  `rate` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_android_id` (`android_id`),
  FOREIGN KEY (`android_id`) REFERENCES `users`(`android_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Daily Diamond Entries Table
CREATE TABLE IF NOT EXISTS `daily_entries` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `android_id` VARCHAR(255) NOT NULL,
  `entry_date` DATE NOT NULL,
  `weight` DECIMAL(10,3) NOT NULL,
  `rate` DECIMAL(10,2) NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_android_id` (`android_id`),
  INDEX `idx_entry_date` (`entry_date`),
  FOREIGN KEY (`android_id`) REFERENCES `users`(`android_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Withdrawals Table
CREATE TABLE IF NOT EXISTS `withdrawals` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `android_id` VARCHAR(255) NOT NULL,
  `withdrawal_date` DATE NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_android_id` (`android_id`),
  INDEX `idx_withdrawal_date` (`withdrawal_date`),
  FOREIGN KEY (`android_id`) REFERENCES `users`(`android_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

