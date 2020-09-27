<?php
if(isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
    $text_statistic = 'Statistic';
    $text_linkstuff = 'Link stuff';
} else {
    $text_statistic = 'Statistik';
    $text_linkstuff = 'Linkstuff';
}
navlink('Team','main/team');
navlink($text_statistic,'main/statistic');
navlink($text_linkstuff,'main/linkstuff');
navlink('Partner','main/partner');
?>