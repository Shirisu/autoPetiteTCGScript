<?php
navlink('F.A.Q.','tcg/faq');
if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
    $text_rules = 'Rules';
    $text_carddecks = 'Card decks';
    $text_cardsearch = 'Card search';
    $text_exchangeoffice = 'Exchange office';
    $text_cardupdate = 'Card update';
} else {
    $text_rules = 'Regeln';
    $text_carddecks = 'Carddecks';
    $text_cardsearch = 'Cardsearch';
    $text_exchangeoffice = 'Wechselstube';
    $text_cardupdate = 'Cardupdate';
}
navlink($text_rules,'tcg/rules');
if (isset($_SESSION['member_id'])) {
    navlink('Members','tcg/member');
    navlink($text_carddecks,'tcg/carddecks');
    navlink($text_cardsearch,'tcg/cardsearch');
    navlink($text_exchangeoffice,'tcg/exchangeoffice');

    $sqlupi = "SELECT cardupdate_id FROM cardupdate ORDER BY cardupdate_id DESC LIMIT 1";
    $resultupi = mysqli_query($link, $sqlupi) OR die(mysqli_error());
    $rowupi = mysqli_fetch_assoc($resultupi);
    if(mysqli_num_rows($resultupi)) {
        $uppilink = '?update='.$rowupi['cardupdate_id'];
    } else {
        $uppilink = '';
    }
    navlink($text_cardupdate,'tcg/update'.$uppilink);
    navlink('Wishlist (Make a wish)','tcg/wishlist');
}
?>
