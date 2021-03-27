<?php
global $link;
$online_time = time();
if (isset($_SESSION['member_rank'])) {
    $sql_online = "SELECT member_online_member_id FROM member_online WHERE member_online_member_id= '".$_SESSION['member_id']."' LIMIT 1;";
    $result_online  = mysqli_query($link, $sql_online) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_online)) {
        mysqli_query($link, "UPDATE member_online SET member_online_member_time = '".$online_time."' WHERE member_online_member_id = '".$_SESSION["member_id"]."' LIMIT 1;") or die(mysqli_error($link));
    } else {
        mysqli_query($link, "INSERT INTO member_online (member_online_member_id, member_online_member_time) VALUES ('".$_SESSION["member_id"]."', '".$online_time."')") or die(mysqli_error($link));
    }

    // set member inactive after 14 days without login
    $sql_member_active = "SELECT member_id, member_last_login FROM member WHERE member_active = 1";
    $result_member_active = mysqli_query($link, $sql_member_active) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_member_active)) {
        while ($row = mysqli_fetch_assoc($result_member_active)) {
            $date = $row['member_last_login'];
            if ($date <= time() - (60 * 60 * 24 * 14)) {
                mysqli_query($link, "UPDATE member SET member_active = 0 WHERE member_id = '".$row['member_id']."' LIMIT 1") OR die(mysqli_error($link));
            }
        }
    }
}
// delete member from onlinelist after 3 minutes of inactivation
mysqli_query($link, "DELETE FROM member_online WHERE (member_online_member_time + 60 * 3) < '".$online_time."';") or die(mysqli_error($link));
?>