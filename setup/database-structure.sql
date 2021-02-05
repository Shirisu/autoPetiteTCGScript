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
  `member_register` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `member_last_login` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `member_rank` int(11) NOT NULL DEFAULT '5',
  `member_level` int(11) NOT NULL DEFAULT '1',
  `member_cards` int(11) NOT NULL,
  `member_master` int(11) NOT NULL,
  `member_wish` int(11) NOT NULL,
  `member_currency` int(11) NOT NULL,
  `member_text` text COLLATE utf8_unicode_ci NOT NULL,
  `member_tradeable` int(1) NOT NULL DEFAULT '1' COMMENT '0 = nicht antauschbar, 1 = antauschbar',
  `member_showonlyusefultrades` int(1) NOT NULL DEFAULT '0',
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
  `cardupdate_date` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `cardupdate_carddeck_id` text COLLATE utf8_unicode_ci NOT NULL,
  `cardupdate_count_cards` int(11) NOT NULL,
  PRIMARY KEY (`cardupdate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `news_member_id` int(11) NOT NULL,
  `news_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `news_date` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `news_cardupdate_id` INT(11) DEFAULT NULL,
  PRIMARY KEY (`news_id`),
  KEY `news_member_id` (`news_member_id`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`news_member_id`) REFERENCES `member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_news_id` int(11) NOT NULL,
  `comment_member_id` int(11) NOT NULL,
  `comment_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `comment_date` int(11) NOT NULL,
  `comment_active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`comment_id`),
  KEY `comment_news_id` (`comment_news_id`),
  KEY `comment_member_id` (`comment_member_id`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`comment_news_id`) REFERENCES `news` (`news_id`),
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`comment_member_id`) REFERENCES `member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `game_lucky` (
  `game_lucky_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_lucky_member_id` int(11) NOT NULL,
  `game_lucky_cat_id` int(11) NOT NULL,
  `game_lucky_last_played` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`game_lucky_id`),
  KEY `game_lucky_member_id` (`game_lucky_member_id`,`game_lucky_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `game_memory` (
  `game_memory_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_memory_member_id` int(11) NOT NULL,
  `game_memory_last_played` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`game_memory_id`),
  KEY `game_memory_member_id` (`game_memory_member_id`),
  CONSTRAINT `game_memory_ibfk_1` FOREIGN KEY (`game_memory_member_id`) REFERENCES `member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `game_rightnumber` (
  `game_rightnumber_id` int(11) NOT NULL AUTO_INCREMENT,
  `game_rightnumber_member_id` int(11) NOT NULL,
  `game_rightnumber_last_played` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`game_rightnumber_id`),
  KEY `game_rightnumber_member_id` (`game_rightnumber_member_id`),
  CONSTRAINT `game_rightnumber_ibfk_1` FOREIGN KEY (`game_rightnumber_member_id`) REFERENCES `member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_cards` (
  `member_cards_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_cards_carddeck_id` int(11) NOT NULL,
  `member_cards_number` int(11) NOT NULL,
  `member_cards_member_id` int(11) NOT NULL,
  `member_cards_cat` enum('1','2','3','4','5','6','7') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
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
  `member_log_active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`member_log_id`),
  KEY `member_log_member_id` (`member_log_member_id`),
  CONSTRAINT `member_log_ibfk_1` FOREIGN KEY (`member_log_member_id`) REFERENCES `member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_master` (
  `member_master_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_master_member_id` int(11) NOT NULL DEFAULT '0',
  `member_master_carddeck_id` int(11) NOT NULL DEFAULT '0',
  `member_master_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`member_master_id`),
  KEY `member_master_member_id` (`member_master_member_id`),
  KEY `member_master_carddeck_id` (`member_master_carddeck_id`),
  CONSTRAINT `member_master_ibfk_1` FOREIGN KEY (`member_master_member_id`) REFERENCES `member` (`member_id`),
  CONSTRAINT `member_master_ibfk_2` FOREIGN KEY (`member_master_carddeck_id`) REFERENCES `carddeck` (`carddeck_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_online` (
  `member_id` int(11) NOT NULL DEFAULT '0',
  `member_time` int(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_update` (
  `member_update_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_update_cardupdate_id` int(11) NOT NULL,
  `member_update_carddeck_id` text COLLATE utf8_unicode_ci NOT NULL,
  `member_update_member_id` int(11) NOT NULL,
  `member_update_cards_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_update_id`),
  KEY `member_update_cardupdate_id` (`member_update_cardupdate_id`),
  KEY `member_update_member_id` (`member_update_member_id`),
  CONSTRAINT `member_update_ibfk_1` FOREIGN KEY (`member_update_cardupdate_id`) REFERENCES `cardupdate` (`cardupdate_id`),
  CONSTRAINT `member_update_ibfk_2` FOREIGN KEY (`member_update_member_id`) REFERENCES `member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `member_wishlist` (
  `member_wishlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_wishlist_member_id` int(11) NOT NULL,
  `member_wishlist_carddeck_id` int(11) NOT NULL,
  `member_wishlist_date` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`member_wishlist_id`),
  KEY `member_wishlist_member_id` (`member_wishlist_member_id`),
  KEY `member_wishlist_carddeck_id` (`member_wishlist_carddeck_id`),
  CONSTRAINT `member_wishlist_ibfk_1` FOREIGN KEY (`member_wishlist_member_id`) REFERENCES `member` (`member_id`),
  CONSTRAINT `member_wishlist_ibfk_2` FOREIGN KEY (`member_wishlist_carddeck_id`) REFERENCES `carddeck` (`carddeck_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `message_from_member_id` int(11) NOT NULL,
  `message_to_member_id` int(11) NOT NULL,
  `message_subject` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `message_text` text COLLATE utf8_unicode_ci NOT NULL,
  `message_date` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `message_read` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`),
  KEY `message_from_member_id` (`message_from_member_id`),
  KEY `message_to_member_id` (`message_to_member_id`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`message_from_member_id`) REFERENCES `member` (`member_id`),
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`message_to_member_id`) REFERENCES `member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `trade` (
  `trade_id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_from_member_id` int(11) NOT NULL,
  `trade_from_member_card_id` int(11) NOT NULL,
  `trade_to_member_id` int(11) NOT NULL,
  `trade_to_member_card_id` int(11) NOT NULL,
  `trade_text` text COLLATE utf8_unicode_ci NOT NULL,
  `trade_date` varchar(55) NOT NULL,
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