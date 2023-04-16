CREATE TABLE IF NOT EXISTS `table_last_update` (
  `table_last_update_name` varchar(255) NOT NULL,
  `table_last_update_date` int(11) NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `table_last_update_name` (`table_last_update_name`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;