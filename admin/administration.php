<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 3 || $_SESSION['member_rank'] == 4)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
    );
    breadcrumb($breadcrumb);
    title('Administration');

    if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline']);
        ?>
        <div class="row mb-3 administration">
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-folder-plus fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/addcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['category_add_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-folder fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/editcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['category_edit_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-folder-plus fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/addsubcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_add_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-folder fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/editsubcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_edit_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 3) {
        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_administration_headline']);
        ?>
        <div class="row mb-3 administration">
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-image fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/addcarddeck"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_add_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-images fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/editcarddeck"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_edit_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php

        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['cardupdate_administration_headline']);
        ?>
        <div class="row mb-3 administration">
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-gifts fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/addcardupdate"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['cardupdate_add_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['news_administration_headline']);
        ?>
        <div class="row mb-3 administration">
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-bullhorn fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/addnews"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['news_add_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-bullhorn fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/editnews"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['news_edit_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['level_administration_headline']);
        ?>
        <div class="row mb-3 administration">
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-feather-alt fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/addlevel"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['level_add_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-feather-alt fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/editlevel"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['level_edit_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 4) {
        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline']);
        ?>
        <div class="row mb-3 administration">
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users-cog fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/editmember/all"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $sql_carddeck_yet = "SELECT carddeck_id
                                 FROM carddeck
                                 WHERE carddeck_active = 1
                                 LIMIT 1";
            $result_carddeck_yet = mysqli_query($link, $sql_carddeck_yet) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_carddeck_yet)) {
                ?>
                <div class="col col-12 col-md-6 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-plus-square fa-2x me-3"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <a href="<?php echo HOST_URL; ?>/administration/distributecards"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['card_distribute_headline']; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-plus-square fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/distributecurrency"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['currency_distribute_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (TCG_WISH_USE == true) { ?>
                <div class="col col-12 col-md-6 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-plus-square fa-2x me-3"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <a href="<?php echo HOST_URL; ?>/administration/distributewish"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['wish_distribute_headline']; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }

    if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
        title_small(TRANSLATIONS[$GLOBALS['language']]['admin']['games_administration_headline']);
        ?>
        <div class="row mb-3 administration">
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-gamepad fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/addgame"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['games_add_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-12 col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-gamepad fa-2x me-3"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <a href="<?php echo HOST_URL; ?>/administration/editgame"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['games_edit_headline']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>
