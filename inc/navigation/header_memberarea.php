<?php
// recieved new cards
$sql_tr = "SELECT *
           FROM trade_history
           WHERE trade_history_to_member_id = '".$_SESSION['member_id']."'
             AND trade_seen = 0";
$result_tr = mysqli_query($link, $sql_tr);
$anz_tr = mysqli_num_rows($result_tr);
if ($anz_tr > 0) {
    $text_trade = '<b>Trade History</b>';
} else {
    $text_trade = 'Trade History';
}

navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_trade'],'tcg/trade');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_userarea'],'tcg/userarea');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_collectlist'],'tcg/collectlist');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_ownlog'],'tcg/memberlog');
navlink('Wish','tcg/wish');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_contactlist'],'tcg/contactlist');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_personalwishlist'],'tcg/ownwishlist');
?>
