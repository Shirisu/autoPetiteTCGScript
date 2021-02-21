<?php
global $link;
$online_time = time();
if ((isset($_SESSION['member_id'])) && (isset($_SESSION['member_nick']))) {
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