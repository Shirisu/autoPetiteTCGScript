<?php
// recieved new cards
$sql_tr = "SELECT *
           FROM trade
           WHERE trade_to_member_id = '".$_SESSION['member_id']."'
             AND trade_seen = 0";
$result_tr = mysqli_query($link, $sql_tr) OR die(mysqli_error($link));
$anz_tr = mysqli_num_rows($result_tr);
if ($anz_tr > 0) {
    navlink('<span class="font-weight-bold">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_trade'].'</span>','trade');
} else {
    navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_trade'],'trade');
}

navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_userarea'],'userarea');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_collectlist'],'collectlist');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_ownlog'],'memberlog');
navlink('Wish','wish');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_contactlist'],'contactlist');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist'],'wishlist');
?>
