-- Cosmixify Database Schema
-- Engine: InnoDB | Charset: utf8mb4_unicode_ci

CREATE DATABASE IF NOT EXISTS `cosmixify`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `cosmixify`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `favs`;
DROP TABLE IF EXISTS `photos_tags`;
DROP TABLE IF EXISTS `user_photos`;
DROP TABLE IF EXISTS `photos`;
DROP TABLE IF EXISTS `astronomical_events`;
DROP TABLE IF EXISTS `apod`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `admin`;
DROP TABLE IF EXISTS `site_visits`;

SET FOREIGN_KEY_CHECKS = 1;

-- Site visit counter
CREATE TABLE `site_visits` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `visit_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_visit_date` (`visit_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site administrators
CREATE TABLE `admin` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `user_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Registered users
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Fname` VARCHAR(100) NOT NULL,
  `Lname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `birthday` DATE DEFAULT NULL,
  `regdate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_image` VARCHAR(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_email` (`email`),
  KEY `idx_regdate` (`regdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Main site photos
CREATE TABLE `photos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `ardescription` TEXT,
  `image` VARCHAR(500) NOT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `source` VARCHAR(255) DEFAULT NULL,
  `sourceUrl` VARCHAR(500) DEFAULT NULL,
  `date` DATE DEFAULT NULL,
  `postDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_postDate` (`postDate`),
  KEY `idx_views` (`views`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Photo tags (many-to-one with photos)
CREATE TABLE `photos_tags` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `photo_id` INT UNSIGNED NOT NULL,
  `tag` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tag` (`tag`),
  KEY `idx_photo_id` (`photo_id`),
  CONSTRAINT `fk_photos_tags_photo` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- NASA APOD / EPIC images (unified schema)
CREATE TABLE `apod` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NOT NULL,
  `explanation` TEXT,
  `arexplanation` TEXT,
  `url` VARCHAR(1000) DEFAULT NULL,
  `image` VARCHAR(500) DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `source` VARCHAR(100) DEFAULT 'NASA APOD',
  `sourceUrl` VARCHAR(1000) DEFAULT NULL,
  `description` TEXT,
  `date` DATE NOT NULL,
  `postDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_apod_date` (`date`),
  KEY `idx_apod_postDate` (`postDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Astronomical events calendar
CREATE TABLE `astronomical_events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_title` VARCHAR(255) NOT NULL,
  `event_description` TEXT,
  `event_image` VARCHAR(500) DEFAULT NULL,
  `event_date` DATE NOT NULL,
  `event_end` DATE DEFAULT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'Coming',
  `best_viewing_method` VARCHAR(50) DEFAULT NULL,
  `light_pollution_viewability` VARCHAR(20) DEFAULT NULL,
  `best_viewing_location` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_event_date` (`event_date`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Community user-submitted photos
CREATE TABLE `user_photos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `image` VARCHAR(500) NOT NULL,
  `capture_date` DATE DEFAULT NULL,
  `device` VARCHAR(100) DEFAULT NULL,
  `user_email` VARCHAR(255) DEFAULT NULL,
  `user_name` VARCHAR(200) DEFAULT NULL,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `accept` ENUM('pending', 'on', 'off') NOT NULL DEFAULT 'pending',
  `views` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_accept` (`accept`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_views` (`views`),
  CONSTRAINT `fk_user_photos_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User favorites
CREATE TABLE `favs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `photo_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_photo` (`user_id`, `photo_id`),
  KEY `idx_photo_id` (`photo_id`),
  CONSTRAINT `fk_favs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favs_photo` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
