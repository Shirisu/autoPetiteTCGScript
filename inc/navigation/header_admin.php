<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    // categories
    navlink(TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'],'admin/categoryadministration');
    ?>
    <div class="dropdown-divider"></div>
    <?php
    // card decks
    navlink(TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_administration_headline'],'admin/carddeckadministration');
    ?>
    <div class="dropdown-divider"></div>
    <?php
    // member
    navlink(TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'],'admin/memberadministration');
}
?>