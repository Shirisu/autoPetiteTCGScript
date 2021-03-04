<?php
require_once("connection.php");
require_once("constants.php");
// start session
session_start ();
require_once("function.php");
require_once('class.passwordhash_tcg.php');
$ip = ip();

if ((isset($_REQUEST["member_nick"])) && isset($_REQUEST["member_password"])) {
    global $link;

    $nick = mysqli_real_escape_string($link,$_REQUEST["member_nick"]);
    $pass = mysqli_real_escape_string($link,$_REQUEST["member_password"]);

    $sql = "SELECT member_id, member_ip, member_nick, member_password, member_rank, member_last_login, member_language, member_active
            FROM member
            WHERE member_nick = '".$nick."'
                AND (member_active = 1
                  OR member_active = 0)
            LIMIT 1;";
    $result = mysqli_query($link,$sql) OR die(mysqli_error($link));

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_array($result);

        $password_validate = validate_password_for_tcg($pass, $data["member_password"]);
        if ($password_validate == 1) {
            $_SESSION["member_id"] = $data["member_id"];
            $_SESSION["member_ip"] = $data["member_ip"];
            $_SESSION["member_nick"] = $data["member_nick"];
            $_SESSION["member_rank"] = $data["member_rank"];
            $_SESSION["member_last_login"] = $data["member_last_login"];
            $_SESSION["language"] = $data["member_language"];

            mysqli_query($link,
                "UPDATE member
                 SET member_ip = '".$ip."',
                     member_last_login='".time()."'
                WHERE member_id='".$_SESSION["member_id"]."'
               LIMIT 1;") or die(mysqli_error($link));

            if ($data["member_active"] == 0) {
                mysqli_query($link,
                    "UPDATE member
                     SET member_active = 1
                     WHERE member_id='".$_SESSION["member_id"]."'
                     LIMIT 1;") or die(mysqli_error($link));
            }
            header ("Location: ".HOST_URL."/");
        } else {
            header ("Location: ".HOST_URL."/?error=1");
        }
    } else {
        header ("Location: ".HOST_URL."/?error=1");
    }
} else {
    header ("Location: ".HOST_URL."/?error=1");
}
?>