<?php
if (isset($_SESSION['member_id'])) {
    $sqlm = "SELECT member_level
             FROM member
             WHERE member_id = '" . $_SESSION['member_id'] . "'
             LIMIT 1;";
    $resultm = mysqli_query($link, $sqlm) OR die(mysqli_error());
    $rowm = mysqli_fetch_assoc($resultm);
    $member_level = $rowm['member_level'];

    if($member_level > 3) {
        $anz_minutes_timestamp = 3600;
    } else {
        $anz_minutes_timestamp = 1800;
    }
}
?>