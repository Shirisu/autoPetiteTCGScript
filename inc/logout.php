<?php
ob_start();
require_once("connection.php");
require_once("constants.php");

session_start();
if (isset($_SESSION['member_rank'])) {
    global $link;

    mysqli_query($link, "DELETE FROM member_online WHERE member_id = '".$_SESSION['member_id']."' LIMIT 1;") OR die(mysqli_error($link));
}

session_unset();
session_destroy();

header ("Location: ".HOST_URL."");
ob_end_flush ();
?>
