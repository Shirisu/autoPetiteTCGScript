<?php
date_default_timezone_set('Europe/Berlin'); // choose your timezone: https://www.php.net/manual/en/timezones.php

error_reporting(E_ALL);

$host = 'localhost'; //location of the mysql
$user_db = 'USERNAME'; //user name for logging into mys
$pass = 'PASSWORD'; //password for logging into mysql
$db   = 'DATABASENAME'; //database

$link = mysqli_connect($host, $user_db, $pass, $db) OR die(mysqli_error($link));

define('HOST_URL_PLAIN', $_SERVER['SERVER_NAME']); // DO NOT EDIT THIS LINE
define('HOST_URL', '//'.HOST_URL_PLAIN); // DO NOT EDIT THIS LINE
define('TCG_PATH', '/'); // path to your files
define('TCG_NAME', 'auto petite TCG Script'); // for title-attribute & meta title
define('TCG_SLOGAN', 'a simple mini Trading Card Game'); // for title-attribute & meta title
define('TCG_META_TITLE', TCG_NAME .' :: '.TCG_SLOGAN); // title-tag & meta title
define('TCG_META_DESC', 'a mini TCG'); // meta description
define('TCG_META_KEYWORDS', 'TCG, tcg, tgc, trading card game, trade card game, trade, card, game, ccg, collectible card game, virtual card game, vcg'); // meta keywords
define('TCG_META_OWNER', 'mail@host.com'); // meta owner
define('TCG_META_AUTHOR', 'Admin'); // meta author
define('TCG_MAX_CARDS', '12'); // max cards of carddecks
define('TCG_CARDS_STARTDECK', '30'); // cards of startdeck
define('TCG_CURRENCY', 'Dollar'); // currency name
define('TCG_DATE_FORMAT', 'd. M Y'); // date format
define('TCG_MAIN_LANGUAGE', 'en'); // main language
define('MEMBER_MAX_LVL', '20'); // max level for members
?>
