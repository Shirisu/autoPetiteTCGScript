<?php ob_start();
if (isset($_SESSION['member_rank'])) {
    global $link;
    $sql_member_timezone = "SELECT member_timezone 
                            FROM member 
                            WHERE member_id = '".$_SESSION['member_id']."' 
                            LIMIT 1";
    $result_member_timezone = mysqli_query($link, $sql_member_timezone) OR die(mysqli_error($link));
    $row_member_timezone = mysqli_fetch_assoc($result_member_timezone);
    if (empty($row_member_timezone['member_timezone'])) {
        date_default_timezone_set(TCG_DEFAULT_TIMEZONE);
    } else {
        date_default_timezone_set($row_member_timezone['member_timezone']);
    }
} else {
    date_default_timezone_set(TCG_DEFAULT_TIMEZONE);
}
?>
<?php echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?".">"; ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
    <link rel="shortcut icon" href="<?php echo HOST_URL; ?>/favicon.ico" />
    <title><?php echo TCG_META_TITLE; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="title" content="<?php echo TCG_META_TITLE; ?>" />
    <meta name="description" content="<?php echo TCG_META_DESC; ?>" />
    <meta name="keywords" content="<?php echo TCG_META_KEYWORDS; ?>" />
    <meta name="owner" content="<?php echo TCG_META_OWNER; ?>" />
    <meta name="author" content="<?php echo TCG_META_AUTHOR; ?>" />
    <meta name="audience" lang="de" content="alle">
    <meta name="audience" lang="en" content="all">
    <meta name="siteinfo" content="robots.txt" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/bootstrap-select.min.css" />
    <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/bootstrap-table.min.css?<?php echo date('YmdH', time()); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/font-awesome-all.min.css?<?php echo date('YmdH', time()); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/style.css?<?php echo date('YmdH', time()); ?>" type="text/css" />
    <?php

    /**
     * do not remove the style block - you need it for displaying the cards!
     */
    ?>
    <style>
        .carddeck-wrapper {
            width: <?php echo (TCG_CARDS_WIDTH * TCG_CARDS_PER_ROW + (TCG_CARDS_PER_ROW * 4)); ?>px;
        }
        .carddeck-wrapper[data-is-puzzle="1"] {
            width: <?php echo TCG_CARDS_WIDTH * TCG_CARDS_PER_ROW; ?>px;
        }
        .card-wrapper {
            width: <?php echo TCG_CARDS_WIDTH; ?>px;
            height: <?php echo TCG_CARDS_HEIGHT; ?>px;
        }
        .card-wrapper.mastercard {
            width: <?php echo TCG_MASTERCARDS_WIDTH; ?>px;
            height: <?php echo TCG_MASTERCARDS_HEIGHT; ?>px;
        }
        .cards-sorting-wrapper {
            flex: 0 0 <?php echo (TCG_CARDS_WIDTH + 24); ?>px;
            max-width: <?php echo (TCG_CARDS_WIDTH + 24); ?>px;
        }

        .cards-sorting-container table.cards-sorting-table.new-cards tbody tr,
        .cards-sorting-container table.cards-sorting-table.trade-cards tbody tr,
        .member-cards-container table.profile-cards.trade-cards tbody tr,
        .cards-sorting-container table.cards-sorting-table.keep-cards tbody tr,
        .member-cards-container table.profile-cards.keep-cards tbody tr {
            flex: 0 0 <?php echo (TCG_CARDS_WIDTH + 24); ?>px;
            max-width: <?php echo (TCG_CARDS_WIDTH + 24); ?>px;
        }
    </style>

    <?php
    $filename = "templates/".TCG_TEMPLATE."/_include_styles.php";
    if (file_exists($filename)) {
        require_once($filename);
    } else {
        require_once("templates/1/_include_styles.php");
    }
    ?>
</head>
<body>

<?php
$filename = "templates/".TCG_TEMPLATE."/header.php";
if (file_exists($filename)) {
    require_once($filename);
} else {
    require_once("templates/1/header.php");
}
?>