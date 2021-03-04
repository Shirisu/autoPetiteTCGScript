<?php
define('HOST_URL_PROTOCOL', stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://'); // DO NOT EDIT THIS LINE
define('HOST_URL_PLAIN', $_SERVER['SERVER_NAME']); // DO NOT EDIT THIS LINE
define('HOST_URL', '//'.HOST_URL_PLAIN); // DO NOT EDIT THIS LINE
define('TCG_CARDS_FOLDER', '/assets/cards'); // DO NOT EDIT THIS LINE

// BELOW THIS YOU CAN EDIT THE VALUES - BUT BE CAREFUL!
define('TCG_CARDS_FILLER_NAME', 'filler'); // name of the filler/search card - should be placed in the the folder "/assets/cards/"
define('TCG_NAME', 'autoPetiteTCGScript'); // for title-attribute & meta title
define('TCG_SLOGAN', 'a simple mini Trading Card Game'); // for title-attribute & meta title
define('TCG_META_TITLE', TCG_NAME .' :: '.TCG_SLOGAN); // title-tag & meta title
define('TCG_META_DESC', 'a mini TCG'); // meta description
define('TCG_META_KEYWORDS', 'TCG, tcg, tgc, trading card game, trade card game, trade, card, game, ccg, collectible card game, virtual card game, vcg'); // meta keywords
define('TCG_META_OWNER', 'mail@host.com'); // meta owner
define('TCG_META_AUTHOR', 'Admin'); // meta author
define('TCG_MAIN_LANGUAGE', 'en'); // main language (en or de)
define('TCG_CARDDECK_MAX_CARDS', '12'); // max cards of carddecks
define('TCG_CARDS_FILE_TYPE', 'png'); // file type for cards (allowed types: gif, jpg, png - use only one!)
define('TCG_CARDS_WIDTH', '115'); // width of card
define('TCG_CARDS_HEIGHT', '95'); // height of card
define('TCG_CARDS_PER_ROW', '4'); // how many cards are in one row
define('TCG_MASTERCARDS_WIDTH', '153'); // width of master
define('TCG_MASTERCARDS_HEIGHT', '123'); // height of master
define('TCG_CARDS_START_PACKAGE', '12'); // cards of startdeck
define('TCG_CURRENCY', 'Dollar'); // currency name
define('TCG_USE_WISH', true); // will you use wish? (yes = true - no = false)
define('TCG_WISH', 'Wish'); // wish name
define('TCG_MASTER_CARD_REWARD', '3'); // reward card amount for mastering
define('TCG_MEMBER_MAX_LVL', '20'); // max level for member
define('TCG_SHOP_CURRENCY_FOR_RANDOM', '100'); // how much currency do 1 random cost?
?>
