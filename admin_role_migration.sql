-- Add is_admin column to users table
-- Run this SQL query in phpMyAdmin to enable admin role functionality

ALTER TABLE `users` ADD COLUMN `is_admin` INT DEFAULT 0 AFTER `password`;

-- Add index for faster queries
CREATE INDEX `idx_is_admin` ON `users` (`is_admin`);

-- Optional: Set first user as admin (replace with your first user's ID if needed)
-- UPDATE `users` SET `is_admin` = 1 WHERE id = 'BcjKNX58e4x7bIqIvxG7' LIMIT 1;
