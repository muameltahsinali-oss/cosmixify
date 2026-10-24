-- Cosmixify Seed Data
-- Default admin password: admin123 (change immediately after first login)
-- After import, run: php database/sync-apod.php

USE `cosmixify`;

INSERT INTO `admin` (`name`, `user_name`, `email`, `password`) VALUES
('Admin', 'admin', 'admin@cosmixify.local', '$2y$10$iLJq6Rllc39uvxvissD7.OChjsIDOi3r7Y/ytBICYFd32JttGgJo2')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Content is loaded from reseed-content.sql + sync-apod.php
