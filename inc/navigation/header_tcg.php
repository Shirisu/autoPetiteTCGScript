<?php
navlink('F.A.Q.','tcg/faq');
navlink(TRANSLATIONS[$GLOBALS['language']]['text_rules'],'tcg/rules');
if (isset($_SESSION['member_id'])) {
    navlink('Members','tcg/member');
    navlink(TRANSLATIONS[$GLOBALS['language']]['text_carddecks'],'tcg/carddecks');
    navlink(TRANSLATIONS[$GLOBALS['language']]['text_cardsearch'],'tcg/cardsearch');
    navlink(TRANSLATIONS[$GLOBALS['language']]['text_exchangeoffice'],'tcg/exchangeoffice');

    $sqlupi = "SELECT cardupdate_id FROM cardupdate ORDER BY cardupdate_id DESC LIMIT 1";
    $resultupi = mysqli_query($link, $sqlupi) OR die(mysqli_error());
    $rowupi = mysqli_fetch_assoc($resultupi);
    if(mysqli_num_rows($resultupi)) {
        $uppilink = '?update='.$rowupi['cardupdate_id'];
    } else {
        $uppilink = '';
    }
    navlink(TRANSLATIONS[$GLOBALS['language']]['text_cardupdate'],'tcg/update'.$uppilink);
    navlink('Wishlist (Make a wish)','tcg/wishlist');
}
?>
