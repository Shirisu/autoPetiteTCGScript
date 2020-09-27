<?php
ob_start();
require_once("connection.php");

session_start();
if(isset($_SESSION['member_id'])) {
    mysqli_query($link, "DELETE FROM member_online WHERE member_id = '".$_SESSION['member_id']."' LIMIT 1;");
}

session_unset();
session_destroy();

header ("Location: ".HOST_URL."");
ob_end_flush ();
?>
