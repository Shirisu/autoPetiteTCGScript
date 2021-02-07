<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 3 || $_SESSION['member_rank'] == 4)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
    );
    breadcrumb($breadcrumb);
    if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline']);
        ?>
        <div class="row mb-5">
            <div class="col col-12 col-md-6">
                <a href="/administration/addcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['category_add_headline']; ?></a>
            </div>
            <div class="col col-12 col-md-6">
                <a href="/administration/editcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['category_edit_headline']; ?></a>
            </div>
            <div class="col col-12 col-md-6">
                <a href="/administration/addsubcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_add_headline']; ?></a>
            </div>
            <div class="col col-12 col-md-6">
                <a href="/administration/editsubcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_edit_headline']; ?></a>
            </div>
        </div>
        <?php
    }

    if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 3) {
        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_administration_headline']);
        ?>
        <div class="row mb-5">
            <div class="col col-12 col-md-6">
                <a href="/administration/addcarddeck"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_add_headline']; ?></a>
            </div>
            <div class="col col-12 col-md-6">
                <a href="/administration/editcarddeck"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_edit_headline']; ?></a>
            </div>
        </div>
        <?php

        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['cardupdate_administration_headline']);
        ?>
        <div class="row mb-5">
            <div class="col col-12 col-md-6">
                <a href="/administration/addcardupdate"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['cardupdate_add_headline']; ?></a>
            </div>
        </div>
        <?php

        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['news_administration_headline']);
        ?>
        <div class="row mb-5">
            <div class="col col-12 col-md-6">
                <a href="/administration/addnews"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['news_add_headline']; ?></a>
            </div>
            <div class="col col-12 col-md-6">
                <a href="/administration/editnews"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['news_edit_headline']; ?></a>
            </div>
        </div>
        <?php
    }

    if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 4) {
        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline']);
        ?>
        <div class="row">
            <div class="col col-12 col-md-6">
                <a href="/administration/editmember/all"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline']; ?></a>
            </div>
            <?php
            $sql_carddeck_yet = "SELECT carddeck_id
                                 FROM carddeck
                                 WHERE carddeck_active = 1
                                 LIMIT 1";
            $result_carddeck_yet = mysqli_query($link, $sql_carddeck_yet) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_carddeck_yet)) {
                ?>
                <div class="col col-12 col-md-6">
                    <a href="/administration/distributecards"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['card_distribute_headline']; ?></a>
                </div>
                <?php
            }
            ?>
            <div class="col col-12 col-md-6">
                <a href="/administration/distributecurrency"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['currency_distribute_headline']; ?></a>
            </div>
            <div class="col col-12 col-md-6">
                <a href="/administration/distributewish"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['wish_distribute_headline']; ?></a>
            </div>
        </div>
        <?php
    }
} else {
    show_no_access_message();
}
?>
