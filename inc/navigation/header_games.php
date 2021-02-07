<?php
$sql_cat = "SELECT carddeck_cat_id, carddeck_cat_name
            FROM carddeck_cat
            ORDER BY carddeck_cat_name";
$result_cat = mysqli_query($link,$sql_cat) OR die(mysqli_error($link));
$row_cat = mysqli_fetch_assoc($result_cat);
if (mysqli_num_rows($result_cat)) {
    while ($row_cat = mysqli_fetch_assoc($result_cat)) {

        $sql_lc = "SELECT game_lucky_last_played
        		   FROM game_lucky
        		   WHERE game_lucky_member_id = '".$_SESSION['member_id']."'
        		     AND game_lucky_cat_id = '".$row_cat['carddeck_cat_id']."'
               ORDER BY game_lucky_id DESC
               LIMIT 1";
        $result_lc = mysqli_query($link,$sql_lc) OR die(mysqli_error($link));
        $row_lc = mysqli_fetch_assoc($result_lc);
        if (mysqli_num_rows($result_lc)) {
            if ( ($row_lc['game_lucky_last_played']+$anz_minutes_timestamp) <= time() ) {
                $play_text = 'Lucky '.$row_cat['carddeck_cat_name'];
            } else {
                $play_text = '<span class="inactive">Lucky '.$row_cat['carddeck_cat_name'].'</span>';
            }
        } else {
            $play_text = 'Lucky '.$row_cat['carddeck_cat_name'];
        }
        navlink($play_text,'games/lucky/'.$row_cat['carddeck_cat_id']);

    }
}

$sql_mm = "SELECT game_memory_last_played
    		   FROM game_memory
    		   WHERE game_memory_member_id = '".$_SESSION['member_id']."'
           ORDER BY game_memory_id DESC
           LIMIT 1";
$result_mm = mysqli_query($link,$sql_mm) OR die(mysqli_error($link));
$row_mm = mysqli_fetch_assoc($result_mm);
if (mysqli_num_rows($result_mm)) {
    if ( ($row_mm['game_memory_last_played']+$anz_minutes_timestamp) <= time() ) {
        $play_text = 'Memory';
    } else {
        $play_text = '<span class="inactive">Memory</span>';
    }
} else {
    $play_text = 'Memory';
}
navlink($play_text,'games/memory');

$sql_rn = "SELECT game_rightnumber_last_played
    		   FROM game_rightnumber
    		   WHERE game_rightnumber_member_id = '".$_SESSION['member_id']."'
           ORDER BY game_rightnumber_id DESC
           LIMIT 1";
$result_rn = mysqli_query($link,$sql_rn) OR die(mysqli_error($link));
$row_rn = mysqli_fetch_assoc($result_rn);
if (mysqli_num_rows($result_rn)) {
    if ( ($row_rn['game_rightnumber_last_played']+$anz_minutes_timestamp) <= time() ) {
        $play_text = 'Right Number';
    } else {
        $play_text = '<span class="inactive">Right Number</span>';
    }
} else {
    $play_text = 'Right Number';
}
navlink($play_text,'games/rightnumber');
?>
