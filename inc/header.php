<?php ob_start(); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?".">"; ?>

<?php
$P_TIME_START = time();
error_reporting(E_ALL);

$online_time = time();
if ((isset($_SESSION['member_id'])) && (isset($_SESSION['member_nick']))) {
    require_once("member_config.php");

    $sql_online = "SELECT member_id FROM member_online WHERE member_id= '".$_SESSION['member_id']."' LIMIT 1;";
    $result_online  = mysqli_query($link, $sql_online) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_online)) {
        mysqli_query($link, "UPDATE member_online SET member_time='".$online_time."' WHERE member_id='".$_SESSION["member_id"]."';") or die(mysqli_error($link));
    } else {
        mysqli_query($link, "INSERT INTO member_online (member_id,member_time) VALUES ('".$_SESSION["member_id"]."', '".$online_time."')") or die(mysqli_error($link));
    }

    // set member inactive after 14 days without login
    $sql_member_active = "SELECT member_id, member_last_login FROM member WHERE member_active = 1";
    $result_member_active = mysqli_query($link, $sql_member_active) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_member_active)) {
        while ($row = mysqli_fetch_assoc($result_member_active)) {
            $datum = $row['member_last_login'];
            if ($datum <= time() - (60 * 60 * 24 * 14)) {
                mysqli_query($link, "UPDATE member SET member_active = 0 WHERE member_id = '".$row['member_id']."'") OR die(mysqli_error($link));
            }
        }
    }
}
mysqli_query($link, "DELETE FROM member_online WHERE (member_time+300)<'".$online_time."';") or die(mysqli_error($link));
?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
    <link rel="shortcut icon" href="<?php echo HOST_URL; ?>/favicon.ico" />
    <title><?php echo TCG_META_TITLE; ?></title>
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
    <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/bootstrap-table.min.css?<?php echo date('YmdH', time()); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/font-awesome-all.min.css?<?php echo date('YmdH', time()); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/simple-sidebar.css?<?php echo date('YmdH', time()); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/style.css?<?php echo date('YmdH', time()); ?>" type="text/css" />
    <?php if (isset($_SESSION['member_rank'])) { ?>
        <link rel="stylesheet" href="<?php echo HOST_URL; ?>/assets/css/cards.css?<?php echo date('YmdH', time()); ?>" type="text/css" />
        <?php
    }
    ?>
</head>
<body>

<div class="d-flex" id="wrapper">
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">
            <a href="<?php echo HOST_URL; ?>"><?php echo TCG_NAME; ?></a>
        </div>
        <div class="list-group list-group-flush">
            <?php
            require_once("inc/navigation/sidebar.php");
            ?>
        </div>
    </div>

    <div id="page-content-wrapper">
        <?php
        require_once("inc/navigation/header.php");
        ?>
        <div class="container-fluid mt-3">