ALTER TABLE `member`
    ADD COLUMN `member_master_order` INT(11) NOT NULL DEFAULT 0 AFTER `member_showonlyusefultrades`;