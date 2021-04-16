CREATE TABLE IF NOT EXISTS `member_tradein` (
  `member_tradein_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_tradein_member_id` int(11) NOT NULL,
  `member_tradein_last_tradein` int(11) NOT NULL,
  PRIMARY KEY (`member_tradein_id`),
  KEY `member_tradein_member_id` (`member_tradein_member_id`),
  CONSTRAINT `member_tradein_ibfk_1` FOREIGN KEY (`member_tradein_member_id`) REFERENCES `member` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;