<?php
/**
 * when you will use the script without top level or sub domain (for example "www.yourtcg.com/tcg" instead of "tcg.yourtcg.com" or "www.yourtcg.com")
 * not recommended!
*/
define('HOST_URL_SUBFOLDER', ''); // name of your subfolder ("tcg" of url for example "www.yourtcg.com/tcg")

define('HOST_URL_PROTOCOL', stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://'); // DO NOT EDIT THIS LINE
define('HOST_URL_PLAIN', $_SERVER['SERVER_NAME'].(HOST_URL_SUBFOLDER != '' ? '/'.HOST_URL_SUBFOLDER : '')); // DO NOT EDIT THIS LINE
define('HOST_URL', '//'.HOST_URL_PLAIN); // DO NOT EDIT THIS LINE
define('MEMBER_CARDS_NEW', '1'); // DO NOT EDIT THIS LINE
define('MEMBER_CARDS_COLLECT', '2'); // DO NOT EDIT THIS LINE
define('MEMBER_CARDS_TRADE', '3'); // DO NOT EDIT THIS LINE
define('MEMBER_CARDS_KEEP', '4'); // DO NOT EDIT THIS LINE
define('MYSQL_VERSION', mysqli_get_client_info()); // DO NOT EDIT THIS LINE
define('TCG_CARDS_FOLDER', '/assets/cards'); // DO NOT EDIT THIS LINE

// BELOW THIS YOU CAN EDIT THE VALUES - BUT BE CAREFUL!
define('TCG_NAME', 'autoPetiteTCGScript'); // for title-attribute & meta title
define('TCG_SLOGAN', 'a simple mini Trading Card Game'); // for title-attribute & meta title
define('TCG_META_TITLE', TCG_NAME .' :: '.TCG_SLOGAN); // title-tag & meta title
define('TCG_DEFAULT_TIMEZONE', 'Europe/Berlin'); // default timezone - use string from here: https://www.php.net/manual/en/timezones.php

define('TCG_TEMPLATE', 1); // which template you want to use (1 is the main template)

define('TCG_CARDS_FILE_TYPE', 'png'); // file type for cards (allowed types: gif, jpg, png - use only one!)
define('TCG_CARDS_FILLER_NAME', 'filler'); // name of the filler/search card - should be placed in the folder "/assets/cards/"
define('TCG_CARDS_HEIGHT', 100); // height of card
define('TCG_CARDS_UPLOAD_REQUIRED', true); // is cardupload required on add card deck page? (yes = true - no = false)
define('TCG_CARDS_PER_ROW', 4); // how many cards are in one row
define('TCG_CARDS_START_PACKAGE', 12); // cards of start package
define('TCG_CARDS_WIDTH', 100); // width of card
define('TCG_CATEGORY_KEEP_USE', true); // should it be possible to use the keep category? (yes = true - no = false)
define('TCG_CARDDECK_MAX_CARDS', 12); // max cards of carddecks
define('TCG_CURRENCY', 'Dollar'); // currency name
define('TCG_CURRENCY_USE', true); // will you use the currency? (yes = true - no = false)
define('TCG_LEVEL_UP_CARD_REWARD', 3); // reward card amount for level up
define('TCG_MAIN_LANGUAGE', 'en'); // main language (en or de)
define('TCG_MASTER_CARD_REWARD', 3); // reward card amount for mastering
define('TCG_MASTERCARDS_HEIGHT', 100); // height of master
define('TCG_MASTERCARDS_WIDTH', 100); // width of master
define('TCG_META_AUTHOR', 'Admin'); // meta author
define('TCG_META_DESC', 'a mini TCG'); // meta description
define('TCG_META_KEYWORDS', 'TCG, tcg, tgc, trading card game, trade card game, trade, card, game, ccg, collectible card game, virtual card game, vcg'); // meta keywords
define('TCG_META_OWNER', 'mail@host.com'); // meta owner
define('TCG_MULTI_MASTER', false); // should it be possible to master a deck multiple times? (yes = true - no = false)
define('TCG_SHOP_CURRENCY_FOR_CARD_RANGE_MAX', 150); // how much currency do 1 card in shop cost? Range max value
define('TCG_SHOP_CURRENCY_FOR_CARD_RANGE_MIN', 100); // how much currency do 1 card in shop cost? Range min value
define('TCG_SHOP_CURRENCY_FOR_RANDOM', 100); // how much currency do 1 random cost?
define('TCG_SHOP_MAX_CARDS', 20); // max cards shown in shop
define('TCG_SHOW_TRADE_FILTER', true); // will you use the trade filter function? (yes = true - no = false)
define('TCG_TRADE_IN_HOURS', 4); // how many hours do you need to wait to trade in new duplicate cards?
define('TCG_WISH', 'Wish'); // wish name
define('TCG_WISH_USE', true); // will you use wish? (yes = true - no = false)
?>
