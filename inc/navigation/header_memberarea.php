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

if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
    $text_userarea = 'User area';
    $text_collectlist = 'Collect list';
    $text_ownlog = 'Own log';
    $text_contactlist = 'Contact List';
    $text_personalwishlist = 'Personal Wishlist';
} else {
    $text_userarea = 'Userarea';
    $text_collectlist = 'Collectlist';
    $text_ownlog = 'Eigener log';
    $text_contactlist = 'Kontaktliste';
    $text_personalwishlist = 'Pers. Wunschliste';
}

navlink($text_trade,'tcg/trade');
navlink($text_userarea,'tcg/userarea');
navlink($text_collectlist,'tcg/collectlist');
navlink($text_ownlog,'tcg/memberlog');
navlink('Wish','tcg/wish');
navlink($text_contactlist,'tcg/contactlist');
navlink($text_personalwishlist,'tcg/ownwishlist');
?>
