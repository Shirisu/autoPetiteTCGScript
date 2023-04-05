CREATE TABLE IF NOT EXISTS `shop` (
  `shop_id` int(11) NOT NULL AUTO_INCREMENT,
  `shop_carddeck_name` VARCHAR(255) NOT NULL,
  `shop_carddeck_id` int(11) NOT NULL,
  `shop_card_number` int(11) NOT NULL,
  `shop_price` int(11) NOT NULL,
  `shop_last_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shop_id`),
  KEY `shop_carddeck_id` (`shop_carddeck_id`),
  CONSTRAINT `shop_ibfk_1` FOREIGN KEY (`shop_carddeck_id`) REFERENCES `carddeck` (`carddeck_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;