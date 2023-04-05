ALTER TABLE `member`
    ADD COLUMN `member_timezone` VARCHAR(100) NOT NULL AFTER `member_master_order`;