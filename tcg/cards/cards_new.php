<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/cards' => TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'],
        '/cards/new' => 'New',
    );
    breadcrumb($breadcrumb);
    title('New');

    $member_id = $_SESSION['member_id'];

    member_level_up($member_id);

    if (isset($_POST['card_id']) && isset($_POST['card_category'])) {
        foreach ($_POST['card_id'] as $index => $card) {
            $card_id = mysqli_real_escape_string($link, $card);
            $card_category = mysqli_real_escape_string($link, $_POST['card_category'][$index]);

            $sql_carddeck = "SELECT carddeck_id, carddeck_name, member_cards_number
                             FROM member_cards
                             JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                             WHERE member_cards_member_id = '".$_SESSION['member_id']."'
                               AND member_cards_id = '".$card_id."'
                             LIMIT 1";
            $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_carddeck)) {
                $row_carddeck = mysqli_fetch_assoc($result_carddeck);

                // check if carddeck is mastered yet
                if ($card_category == 2) {
                    $sql_mastered_yet = "SELECT member_master_member_id
                                     FROM member_master
                                     WHERE member_master_member_id = '" . $_SESSION['member_id'] . "'
                                       AND member_master_carddeck_id = '" . $row_carddeck['carddeck_id'] . "'
                                     LIMIT 1";
                    $result_mastered_yet = mysqli_query($link, $sql_mastered_yet) OR die(mysqli_error($link));
                    if (mysqli_num_rows($result_mastered_yet) && TCG_MULTI_MASTER == false) {
                        alert_box($row_carddeck['carddeck_name'] . sprintf('%02d', $row_carddeck['member_cards_number']) . ' ' . TRANSLATIONS[$GLOBALS['language']]['member']['text_card_not_moved'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['member']['text_already_mastered'], 'danger');
                    } else {
                        // check if card is in collect yet
                        $sql_in_collect_yet = "SELECT member_cards_id
                                           FROM member_cards
                                           WHERE member_cards_member_id = '" . $_SESSION['member_id'] . "'
                                             AND member_cards_carddeck_id = '" . $row_carddeck['carddeck_id'] . "'
                                             AND member_cards_number = '" . $row_carddeck['member_cards_number'] . "'
                                             AND member_cards_cat = '".MEMBER_CARDS_COLLECT."'
                                           LIMIT 1";
                        $result_in_collect_yet = mysqli_query($link, $sql_in_collect_yet) OR die(mysqli_error($link));
                        if (mysqli_num_rows($result_in_collect_yet)) {
                            alert_box($row_carddeck['carddeck_name'] . sprintf('%02d', $row_carddeck['member_cards_number']) . ' ' . TRANSLATIONS[$GLOBALS['language']]['member']['text_card_not_moved'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['member']['text_card_already_in_collect'], 'danger');
                        } else {
                            mysqli_query($link, "UPDATE member_cards
                                     SET member_cards_cat = '" . $card_category . "'
                                     WHERE member_cards_id = '" . $card_id . "'
                                       AND member_cards_member_id = '" . $_SESSION['member_id'] . "'
                                     LIMIT 1")
                            OR die(mysqli_error($link));
                        }
                    }
                } else {
                    mysqli_query($link, "UPDATE member_cards
                                     SET member_cards_cat = '" . $card_category . "'
                                     WHERE member_cards_id = '" . $card_id . "'
                                       AND member_cards_member_id = '" . $_SESSION['member_id'] . "'
                                     LIMIT 1")
                    OR die(mysqli_error($link));
                }
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['member']['text_card_does_not_exist'], 'danger');
            }
        }
    }
    ?>
    <div class="row cards-sorting">
        <div class="col col-12 mb-3">
            <?php get_cards_menu('new'); ?>
        </div>
        <div class="col col-12 mb-3 cards-sorting-container">
            <?php
            $sql_cards = "SELECT member_cards_id, member_cards_number, carddeck_id, carddeck_name, carddeck_active,
                             EXISTS (SELECT member_cards_id
                              FROM member_cards
                              WHERE member_cards_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                                AND member_cards_cat = '".MEMBER_CARDS_COLLECT."'
                                AND member_cards_active = 1
                              GROUP BY member_cards_carddeck_id) as carddeck_in_collect,
                             EXISTS (SELECT member_cards_id
                              FROM member_cards
                              WHERE member_cards_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                                AND mc.member_cards_number = member_cards_number
                                AND member_cards_cat = '".MEMBER_CARDS_COLLECT."'
                                AND member_cards_active = 1
                              GROUP BY member_cards_carddeck_id, member_cards_number) as card_already_in_collect,
                             EXISTS (SELECT member_cards_id
                              FROM member_cards
                              WHERE member_cards_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                                AND member_cards_cat = '".MEMBER_CARDS_KEEP."'
                                AND member_cards_active = 1
                              GROUP BY member_cards_carddeck_id) as carddeck_in_keep,
                             EXISTS (SELECT member_cards_id
                              FROM member_cards
                              WHERE member_cards_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                                AND mc.member_cards_number = member_cards_number
                                AND member_cards_cat = '".MEMBER_CARDS_KEEP."'
                                AND member_cards_active = 1
                              GROUP BY member_cards_carddeck_id, member_cards_number) as card_already_in_keep,
                             EXISTS (SELECT member_cards_id
                              FROM member_cards
                              WHERE member_cards_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                                AND member_cards_cat = '".MEMBER_CARDS_TRADE."'
                                AND member_cards_active = 1
                              GROUP BY member_cards_carddeck_id) as carddeck_in_trade,
                             EXISTS (SELECT member_wishlist_member_id
                              FROM member_wishlist
                              WHERE member_wishlist_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_wishlist_carddeck_id
                              GROUP BY member_wishlist_carddeck_id) as carddeck_on_wishlist,
                             EXISTS (SELECT member_master_id
                              FROM member_master
                              WHERE member_master_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_master_carddeck_id
                              GROUP BY member_master_carddeck_id) as carddeck_already_mastered
                          FROM member_cards mc
                          JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                          WHERE member_cards_member_id = '".$member_id."'
                            AND member_cards_cat = '".MEMBER_CARDS_NEW."'
                            AND member_cards_active = 1
                            AND carddeck_active = 1
                          ORDER BY carddeck_name, member_cards_number ASC";
            $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
            $count_cards = mysqli_num_rows($result_cards);
            if ($count_cards) {
                title_small($count_cards.' New '.($count_cards == 1 ? TRANSLATIONS[$GLOBALS['language']]['general']['text_card'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']));
                ?>
                <form action="<?php echo HOST_URL; ?>/cards/new" method="post">
                    <div class="row">
                        <div class="col col-12">
                            <table class="optional cards-sorting-table new-cards" data-mobile-responsive="true">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th data-searchable="false"><?php echo title_small($count_cards.' New '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($row_cards = mysqli_fetch_assoc($result_cards)) {
                                    if ($row_cards['carddeck_active'] == 0) {
                                        ?>
                                        <tr>
                                            <td class="d-none"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_unkown']; ?></td>
                                            <td>
                                                <div
                                                    class="cards-sorting-wrapper">
                                                    <?php echo get_card(); ?>
                                                    <small><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_unkown']; ?></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        $card_id = $row_cards['member_cards_id'];
                                        $carddeck_id = $row_cards['carddeck_id'];
                                        $carddeck_name = $row_cards['carddeck_name'];
                                        $cardnumber_plain = $row_cards['member_cards_number'];
                                        $cardnumber = sprintf("%'.02d", $cardnumber_plain);
                                        $carddeck_in_collect = $row_cards['carddeck_in_collect'];
                                        $card_already_in_collect = $row_cards['card_already_in_collect'];
                                        $carddeck_in_keep = $row_cards['carddeck_in_keep'];
                                        $card_already_in_keep = $row_cards['card_already_in_keep'];
                                        $carddeck_in_trade = $row_cards['carddeck_in_trade'];
                                        $carddeck_on_wishlist = $row_cards['carddeck_on_wishlist'];
                                        $carddeck_already_mastered = $row_cards['carddeck_already_mastered'];

                                        $trade_selected = false;
                                        $keep_selected = false;
                                        $collect_selected = false;
                                        $hide_collect = false;

                                        // set hide collect
                                        if (
                                            ($carddeck_already_mastered == 1 && !TCG_MULTI_MASTER) ||
                                            ($card_already_in_collect == 1)
                                        ) {
                                            $hide_collect = true;
                                        }

                                        // set selected category
                                        if (
                                            $carddeck_already_mastered == 1 && !TCG_MULTI_MASTER
                                        ) {
                                            $trade_selected = true;
                                        } elseif (
                                            $card_already_in_collect == 0 AND $carddeck_in_collect == 1
                                        ) {
                                            $collect_selected = true;
                                        } elseif (
                                            $card_already_in_keep == 0 AND $carddeck_in_keep == 1
                                        ) {
                                            $keep_selected = true;
                                        } elseif (
                                            $carddeck_on_wishlist == 1 AND $card_already_in_keep == 0 AND $carddeck_in_keep == 1
                                        ) {
                                            $keep_selected = true;
                                        } elseif (
                                            $carddeck_in_trade == 1 AND $carddeck_in_keep == 0 AND $carddeck_in_collect == 0
                                        ) {
                                            $trade_selected = true;
                                        }
                                        if (!TCG_CATEGORY_KEEP_USE) {
                                            $keep_selected = false;
                                        }
                                        ?>
                                        <tr>
                                            <td class="d-none"><?php echo $carddeck_name . $cardnumber; ?></td>
                                            <td>
                                                <div
                                                    class="cards-sorting-wrapper<?php echo($carddeck_already_mastered == 1 ? ' mastered' : ''); ?>">
                                                    <?php echo get_card($carddeck_id, $cardnumber_plain); ?>
                                                    <a class="carddeck-link"
                                                       href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>">
                                                        <small><?php echo $carddeck_name . $cardnumber; ?></small>
                                                    </a>
                                                    <br/>
                                                    <div class="form-group mt-2">
                                                        <div class="input-group">
                                                            <select class="form-select" id="card_category[]"
                                                                    name="card_category[]"
                                                                    aria-describedby="ariaDescribedbyLanguage" required>
                                                                <option value="1" selected>New</option>
                                                                <option
                                                                    value="3" <?php echo($trade_selected ? 'selected' : ''); ?>>
                                                                Trade
                                                                </option>
                                                                <?php if (TCG_CATEGORY_KEEP_USE) { ?>
                                                                    <option
                                                                        value="4" <?php echo($keep_selected ? 'selected' : ''); ?>>
                                                                    Keep
                                                                    </option>
                                                                <?php } ?>
                                                                <?php if (!$hide_collect) { ?>
                                                                <option
                                                                    value="2" <?php echo($collect_selected ? 'selected' : ''); ?>>
                                                                        Collect</option><?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="card_id[]" name="card_id[]"
                                                           value="<?php echo $card_id; ?>"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col col-12 text-center mt-2">
                            <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['member']['text_button_sort_cards']; ?></button>
                        </div>
                    </div>
                </form>
                <?php
            } else {
                title_small('0 New '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']);
                alert_box(TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_no_cards_in_category'], 'danger');
            }
            ?>
        </div>
    </div>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>
