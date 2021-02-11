<?php
navlink('F.A.Q.','faq');
navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_rules'],'rules');
if (isset($_SESSION['member_rank'])) {
    navlink('Member','member');
}
?>
