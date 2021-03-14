<?php
define('HOST_URL_PROTOCOL', stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://'); // DO NOT EDIT THIS LINE
define('HOST_URL_PLAIN', $_SERVER['SERVER_NAME']); // DO NOT EDIT THIS LINE
define('HOST_URL', '//'.HOST_URL_PLAIN); // DO NOT EDIT THIS LINE
define('MEMBER_CARDS_NEW', '1'); // DO NOT EDIT THIS LINE
define('MEMBER_CARDS_COLLECT', '2'); // DO NOT EDIT THIS LINE
define('MEMBER_CARDS_TRADE', '3'); // DO NOT EDIT THIS LINE
define('MYSQL_VERSION', mysqli_get_client_info()); // DO NOT EDIT THIS LINE
define('TCG_CARDS_FOLDER', '/assets/cards'); // DO NOT EDIT THIS LINE

// BELOW THIS YOU CAN EDIT THE VALUES - BUT BE CAREFUL!
define('TCG_CARDS_FILE_TYPE', 'png'); // file type for cards (allowed types: gif, jpg, png - use only one!)
define('TCG_CARDS_FILLER_NAME', 'filler'); // name of the filler/search card - should be placed in the the folder "/assets/cards/"
define('TCG_CARDS_HEIGHT', 100); // height of card
define('TCG_CARDS_PER_ROW', 4); // how many cards are in one row
define('TCG_CARDS_START_PACKAGE', 12); // cards of start package
define('TCG_CARDS_WIDTH', 100); // width of card
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
define('TCG_META_TITLE', TCG_NAME .' :: '.TCG_SLOGAN); // title-tag & meta title
define('TCG_META_OWNER', 'mail@host.com'); // meta owner
define('TCG_NAME', 'autoPetiteTCGScript'); // for title-attribute & meta title
define('TCG_SHOP_CURRENCY_FOR_RANDOM', 100); // how much currency do 1 random cost?
define('TCG_SLOGAN', 'a simple mini Trading Card Game'); // for title-attribute & meta title
define('TCG_WISH', 'Wish'); // wish name
define('TCG_WISH_USE', true); // will you use wish? (yes = true - no = false)
?>
