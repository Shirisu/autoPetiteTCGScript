SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE IF NOT EXISTS `member_activation` (
    `member_activation_member_id` int(11) NOT NULL AUTO_INCREMENT,
    `member_activation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`member_activation_member_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `member_rank` (
    `member_rank_id` int(11) NOT NULL AUTO_INCREMENT,
    `member_rank_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`member_rank_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `member_rank` (member_rank_id, member_rank_name)
VALUES
(1,'Admin'),
(2,'Co-Admin'),
(3,'CardMaker'),
(4,'Moderator'),
(5,'Member')
    ON DUPLICATE KEY UPDATE
                         member_rank_name = VALUES(member_rank_name);


CREATE TABLE IF NOT EXISTS `member` (
    `member_id` int(11) NOT NULL AUTO_INCREMENT,
    `member_ip` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
    `member_nick` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
    `member_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `member_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `member_active` int(1) NOT NULL DEFAULT '3',
    `member_register` int(11) NOT NULL DEFAULT '0',
    `member_last_login` int(11) NOT NULL DEFAULT '0',
    `member_last_active` int(11) NOT NULL DEFAULT '0',
    `member_rank` int(11) NOT NULL DEFAULT '5',
    `member_level` int(11) NOT NULL DEFAULT '1',
    `member_cards` int(11) NOT NULL DEFAULT '0',
    `member_master` int(11) NOT NULL DEFAULT '0',
    `member_wish` int(11) NOT NULL DEFAULT '0',
    `member_currency` int(11) NOT NULL DEFAULT '0',
    `member_text` text COLLATE utf8_unicode_ci NULL,
    `member_tradeable` int(1) NOT NULL DEFAULT '1' COMMENT '0 = nicht antauschbar, 1 = antauschbar',
    `member_showonlyusefultrades` int(1) NOT NULL DEFAULT '0',
    `member_master_order` INT(11) NOT NULL DEFAULT '0',
    `member_timezone` VARCHAR(100) NOT NULL DEFAULT 'Europe/Berlin',
    `member_language` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
    PRIMARY KEY (`member_id`),
    KEY `member_nick` (`member_nick`),
    KEY `member_rank` (`member_rank`),
    KEY `member_cards` (`member_cards`),
    KEY `member_master` (`member_master`),
    KEY `member_tradeable` (`member_tradeable`),
    KEY `member_showonlyusefultrades` (`member_showonlyusefultrades`),
    CONSTRAINT `member_ibfk_1` FOREIGN KEY (`member_rank`) REFERENCES `member_rank` (`member_rank_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `carddeck_cat` (
    `carddeck_cat_id` int(11) NOT NULL AUTO_INCREMENT,
    `carddeck_cat_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`carddeck_cat_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `carddeck_sub_cat` (
    `carddeck_sub_cat_id` int(11) NOT NULL AUTO_INCREMENT,
    `carddeck_sub_cat_main_cat_id` int(11) NOT NULL,
    `carddeck_sub_cat_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`carddeck_sub_cat_id`),
    CONSTRAINT `carddeck_sub_cat_ibfk_1` FOREIGN KEY (`carddeck_sub_cat_main_cat_id`) REFERENCES `carddeck_cat` (`carddeck_cat_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `carddeck` (
    `carddeck_id` int(11) NOT NULL AUTO_INCREMENT,
    `carddeck_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `carddeck_creator` int(11) NOT NULL,
    `carddeck_series` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `carddeck_copyright` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `carddeck_artist` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `carddeck_imagesources` text COLLATE utf8_unicode_ci NOT NULL,
    `carddeck_cat` int(11) NOT NULL,
    `carddeck_sub_cat` int(11) NOT NULL,
    `carddeck_is_puzzle` int(1) NOT NULL DEFAULT '0',
    `carddeck_active` int(1) NOT NULL DEFAULT '0',
    `carddeck_date` int(11) NOT NULL,
    PRIMARY KEY (`carddeck_id`),
    KEY `carddeck_cat` (`carddeck_cat`),
    KEY `carddeck_sub_cat` (`carddeck_sub_cat`),
    KEY `carddeck_creator` (`carddeck_creator`),
    KEY `carddeck_name` (`carddeck_name`),
    CONSTRAINT `carddeck_ibfk_1` FOREIGN KEY (`carddeck_creator`) REFERENCES `member` (`member_id`),
    CONSTRAINT `carddeck_ibfk_2` FOREIGN KEY (`carddeck_cat`) REFERENCES `carddeck_cat` (`carddeck_cat_id`),
    CONSTRAINT `carddeck_ibfk_3` FOREIGN KEY (`carddeck_sub_cat`) REFERENCES `carddeck_sub_cat` (`carddeck_sub_cat_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `cardupdate` (
    `cardupdate_id` int(11) NOT NULL AUTO_INCREMENT,
    `cardupdate_date` int(11) NOT NULL,
    `cardupdate_carddeck_id` text COLLATE utf8_unicode_ci NOT NULL,
    `cardupdate_count_cards` int(11) NOT NULL,
    PRIMARY KEY (`cardupdate_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `news` (
    `news_id` int(11) NOT NULL AUTO_INCREMENT,
    `news_member_id` int(11) NOT NULL,
    `news_title` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
    `news_text` longtext COLLATE utf8_unicode_ci NOT NULL,
    `news_date` int(11) DEFAULT NULL,
    `news_cardupdate_id` INT(11) DEFAULT NULL,
    PRIMARY KEY (`news_id`),
    KEY `news_member_id` (`news_member_id`),
    CONSTRAINT `news_ibfk_1` FOREIGN KEY (`news_member_id`) REFERENCES `member` (`member_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `games` (
    `games_id` int(11) NOT NULL AUTO_INCREMENT,
    `games_name` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
    `games_file` VARCHAR(255) NOT NULL,
    `games_interval` int(11) NOT NULL,
    `games_status` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0 = inactive, 1 = active',
    `games_type` ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1 = lucky, 2 = skill',
    `games_is_default` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0 = self implemented game, 1 = default game',
    `games_lucky_choices` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
    `games_is_lucky_category_game` ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0 = normal, 1 = lucky category',
    PRIMARY KEY (`games_id`),
    KEY `games_id` (`games_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `games` (games_name, games_file, games_interval, games_status, games_type, games_is_default, games_lucky_choices, games_is_lucky_category_game)
VALUES
('Lucky', 'lucky.php', 3600, '1', '1', '1', NULL, '1'),
('Memory', 'memory.php', 3600, '1', '2', '1', NULL, '0'),
('Right Number', 'right_number.php', 3600, '1', '1', '1', NULL, '0'),
('Tic Tac Toe', 'tictactoe.php', 3600, '1', '2', '1', NULL, '0');


CREATE TABLE IF NOT EXISTS `member_game_played` (
    `member_game_played_id` int(11) NOT NULL AUTO_INCREMENT,
    `member_game_played_member_id` int(11) NOT NULL,
    `member_game_played_game_id` int(11) NOT NULL,
    `member_game_played_lucky_category_id` int(11) DEFAULT NULL,
    `member_game_played_last_played` int(11) NOT NULL,
    PRIMARY KEY (`member_game_played_id`),
    KEY `member_game_played_member_id` (`member_game_played_member_id`,`member_game_played_game_id`),
    CONSTRAINT `member_game_played_ibfk_1` FOREIGN KEY (`member_game_played_member_id`) REFERENCES `member` (`member_id`),
    CONSTRAINT `member_game_played_ibfk_2` FOREIGN KEY (`member_game_played_game_id`) REFERENCES `games` (`games_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_cards` (
    `member_cards_id` int(11) NOT NULL AUTO_INCREMENT,
    `member_cards_carddeck_id` int(11) NOT NULL,
    `member_cards_number` int(11) NOT NULL,
    `member_cards_member_id` int(11) NOT NULL,
    `member_cards_cat` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = new, 2 = collect, 3 = trade, 4 = keep',
    `member_cards_active` int(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (`member_cards_id`),
    KEY `member_cards_number` (`member_cards_number`),
    KEY `member_cards_member_id` (`member_cards_member_id`),
    KEY `member_cards_cat` (`member_cards_cat`),
    KEY `member_cards_carddeck_id` (`member_cards_carddeck_id`),
    CONSTRAINT `member_cards_ibfk_1` FOREIGN KEY (`member_cards_carddeck_id`) REFERENCES `carddeck` (`carddeck_id`),
    CONSTRAINT `member_cards_ibfk_2` FOREIGN KEY (`member_cards_member_id`) REFERENCES `member` (`member_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_level` (
    `member_level_id` int(11) NOT NULL AUTO_INCREMENT,
    `member_level_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `member_level_from` int(11) NOT NULL,
    `member_level_to` int(11) NOT NULL,
    PRIMARY KEY (`member_level_id`),
    KEY `member_level_from` (`member_level_from`),
    KEY `member_level_to` (`member_level_to`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `member_level` (member_level_id, member_level_name, member_level_from, member_level_to)
VALUES
(1,'',0,300),(2,'',301,1000),(3,'',1001,1600),(4,'',1601,2500),(5,'',2501,3500),(6,'',3501,5100),(7,'',5101,7700),(8,'',7701,10300),(9,'',10301,13900),(10,'',13901,15600),(11,'',15601,17300),(12,'',17301,19000),(13,'',19001,21700),(14,'',21701,23400),(15,'',23401,25100),(16,'',25101,28800),(17,'',28801,32500),(18,'',32501,45200),(19,'',45201,48900),(20,'',48901,51700),(21,'',51701,55500),(22,'',55501,59300),(23,'',59301,63300),(24,'',63301,68400),(25,'',68401,72500),(26,'',16501,18000),(27,'',18001,19500),(28,'',19501,21100),(29,'',21101,22800),(30,'',22801,24600),(31,'',24601,26500),(32,'',26501,28400),(33,'',28401,30500),(34,'',30501,32600),(35,'',32601,34900),(36,'',34901,37200),(37,'',37201,39700),(38,'',39701,42200),(39,'',42201,44900),(40,'',44901,47600)
    ON DUPLICATE KEY UPDATE
                         member_level_from = VALUES(member_level_from),
                         member_level_to = VALUES(member_level_to);

CREATE TABLE IF NOT EXISTS `member_log` (
    `member_log_id` int(11) NOT NULL AUTO_INCREMENT,
    `member_log_member_id` int(11) NOT NULL,
    `member_log_date` int(11) NOT NULL,
    `member_log_cat` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
    `member_log_text` text COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`member_log_id`),
    KEY `member_log_member_id` (`member_log_member_id`),
    CONSTRAINT `member_log_ibfk_1` FOREIGN KEY (`member_log_member_id`) REFERENCES `member` (`member_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_master` (
    `member_master_id` int(11) NOT NULL AUTO_INCREMENT,
    `member_master_member_id` int(11) NOT NULL DEFAULT '0',
    `member_master_carddeck_id` int(11) NOT NULL DEFAULT '0',
    `member_master_date` int(11) NOT NULL,
    PRIMARY KEY (`member_master_id`),
    KEY `member_master_member_id` (`member_master_member_id`),
    KEY `member_master_carddeck_id` (`member_master_carddeck_id`),
    CONSTRAINT `member_master_ibfk_1` FOREIGN KEY (`member_master_member_id`) REFERENCES `member` (`member_id`),
    CONSTRAINT `member_master_ibfk_2` FOREIGN KEY (`member_master_carddeck_id`) REFERENCES `carddeck` (`carddeck_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_online` (
    `member_online_member_id` int(11) NOT NULL DEFAULT '0',
    `member_online_member_time` int(15) NOT NULL DEFAULT '0'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_update` (
    `member_update_cardupdate_id` int(11) NOT NULL,
    `member_update_carddeck_id` text COLLATE utf8_unicode_ci NOT NULL,
    `member_update_member_id` int(11) NOT NULL,
    `member_update_cards_count` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`member_update_cardupdate_id`, `member_update_member_id`),
    KEY `member_update_cardupdate_id` (`member_update_cardupdate_id`),
    KEY `member_update_member_id` (`member_update_member_id`),
    CONSTRAINT `member_update_ibfk_1` FOREIGN KEY (`member_update_cardupdate_id`) REFERENCES `cardupdate` (`cardupdate_id`),
    CONSTRAINT `member_update_ibfk_2` FOREIGN KEY (`member_update_member_id`) REFERENCES `member` (`member_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_wishlist` (
    `member_wishlist_member_id` int(11) NOT NULL,
    `member_wishlist_carddeck_id` int(11) NOT NULL,
    `member_wishlist_date` int(11) NOT NULL,
    PRIMARY KEY (`member_wishlist_member_id`,`member_wishlist_carddeck_id`),
    KEY `member_wishlist_member_id` (`member_wishlist_member_id`),
    KEY `member_wishlist_carddeck_id` (`member_wishlist_carddeck_id`),
    CONSTRAINT `member_wishlist_ibfk_1` FOREIGN KEY (`member_wishlist_member_id`) REFERENCES `member` (`member_id`),
    CONSTRAINT `member_wishlist_ibfk_2` FOREIGN KEY (`member_wishlist_carddeck_id`) REFERENCES `carddeck` (`carddeck_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `message` (
    `message_id` int(11) NOT NULL AUTO_INCREMENT,
    `message_sender_member_id` int(11) NOT NULL,
    `message_receiver_member_id` int(11) NOT NULL,
    `message_subject` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    `message_text` text COLLATE utf8_unicode_ci NOT NULL,
    `message_date` int(11) NOT NULL,
    `message_read` int(1) NOT NULL DEFAULT '0',
    `message_system` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 = member, 1 = system',
    PRIMARY KEY (`message_id`),
    KEY `message_sender_member_id` (`message_sender_member_id`),
    KEY `message_receiver_member_id` (`message_receiver_member_id`),
    CONSTRAINT `message_ibfk_1` FOREIGN KEY (`message_sender_member_id`) REFERENCES `member` (`member_id`),
    CONSTRAINT `message_ibfk_2` FOREIGN KEY (`message_receiver_member_id`) REFERENCES `member` (`member_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `trade` (
    `trade_id` int(11) NOT NULL AUTO_INCREMENT,
    `trade_from_member_id` int(11) NOT NULL,
    `trade_from_member_card_id` int(11) NOT NULL,
    `trade_to_member_id` int(11) NOT NULL,
    `trade_to_member_card_id` int(11) NOT NULL,
    `trade_text` text COLLATE utf8_unicode_ci NOT NULL,
    `trade_date` int(11) NOT NULL,
    `trade_seen` int(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`trade_id`),
    KEY `trade_from_member_id` (`trade_from_member_id`),
    KEY `trade_from_member_card_id` (`trade_from_member_card_id`),
    KEY `trade_to_member_id` (`trade_to_member_id`),
    KEY `trade_to_member_card_id` (`trade_to_member_card_id`),
    CONSTRAINT `trade_ibfk_1` FOREIGN KEY (`trade_from_member_id`) REFERENCES `member` (`member_id`),
    CONSTRAINT `trade_ibfk_2` FOREIGN KEY (`trade_from_member_card_id`) REFERENCES `member_cards` (`member_cards_id`),
    CONSTRAINT `trade_ibfk_3` FOREIGN KEY (`trade_to_member_id`) REFERENCES `member` (`member_id`),
    CONSTRAINT `trade_ibfk_4` FOREIGN KEY (`trade_to_member_card_id`) REFERENCES `member_cards` (`member_cards_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_tradein` (
  `member_tradein_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_tradein_member_id` int(11) NOT NULL,
  `member_tradein_last_tradein` int(11) NOT NULL,
  PRIMARY KEY (`member_tradein_id`),
  KEY `member_tradein_member_id` (`member_tradein_member_id`),
  CONSTRAINT `member_tradein_ibfk_1` FOREIGN KEY (`member_tradein_member_id`) REFERENCES `member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `shop` (
    `shop_id` int(11) NOT NULL AUTO_INCREMENT,
    `shop_carddeck_name` VARCHAR(255) NOT NULL,
    `shop_carddeck_id` int(11) NOT NULL,
    `shop_card_number` int(11) NOT NULL,
    `shop_price` int(11) NOT NULL,
    PRIMARY KEY (`shop_id`),
    KEY `shop_carddeck_id` (`shop_carddeck_id`),
    CONSTRAINT `shop_ibfk_1` FOREIGN KEY (`shop_carddeck_id`) REFERENCES `carddeck` (`carddeck_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `table_last_update` (
    `table_last_update_name` varchar(255) NOT NULL,
    `table_last_update_date` int(11) NOT NULL DEFAULT current_timestamp(),
    UNIQUE KEY `table_last_update_name` (`table_last_update_name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;