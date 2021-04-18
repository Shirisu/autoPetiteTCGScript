ALTER TABLE `member`
ADD COLUMN `member_last_active` INT(11) NOT NULL DEFAULT 0 AFTER `member_last_login`;