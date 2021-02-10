<?php
navlink('F.A.Q.','faq');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_rules'],'rules');
if (isset($_SESSION['member_rank'])) {
    navlink('Member','member');
    navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'],'carddecks');
    navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_cardsearch'],'cardsearch');
    navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_exchangeoffice'],'exchangeoffice');

    $sqlupi = "SELECT cardupdate_id FROM cardupdate ORDER BY cardupdate_id DESC LIMIT 1";
    $resultupi = mysqli_query($link, $sqlupi) OR die(mysqli_error($link));
    $rowupi = mysqli_fetch_assoc($resultupi);
    if (mysqli_num_rows($resultupi)) {
        $uppilink = '?update='.$rowupi['cardupdate_id'];
    } else {
        $uppilink = '';
    }
    navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_cardupdate'],'update'.$uppilink);
}
?>
