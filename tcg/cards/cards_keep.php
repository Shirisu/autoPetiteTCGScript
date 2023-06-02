<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/cards' => TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'],
        '/cards/keep' => 'Keep',
    );
    breadcrumb($breadcrumb);
    title('Keep');

    $member_id = $_SESSION['member_id'];

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
            <?php get_cards_menu('keep'); ?>
        </div>
        <div class="col col-12 mb-3 cards-sorting-container">
            <?php
            $sql_cards = "SELECT member_cards_id, member_cards_number, carddeck_id, carddeck_name, carddeck_active
                          FROM member_cards mc
                          JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                          WHERE member_cards_member_id = '".$member_id."'
                            AND member_cards_cat = '".MEMBER_CARDS_KEEP."'
                            AND member_cards_active = 1
                            AND carddeck_active = 1
                          ORDER BY carddeck_name, member_cards_number ASC";
            $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
            $count_cards = mysqli_num_rows($result_cards);
            if ($count_cards) {
                title_small($count_cards.' Keep '.($count_cards == 1 ? TRANSLATIONS[$GLOBALS['language']]['general']['text_card'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']));
                ?>
                <form action="<?php echo HOST_URL; ?>/cards/keep" method="post">
                    <div class="row">
                        <div class="col col-12">
                            <table class="optional cards-sorting-table keep-cards" data-mobile-responsive="true">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th data-searchable="false"><?php echo title_small($count_cards.' Keep '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $can_use_strcontains = PHP_VERSION >= '8.0.0';
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

                                        $filterclass = get_card_filter_class($carddeck_id, $cardnumber_plain);
                                        $carddeck_already_mastered = $can_use_strcontains ? str_contains($filterclass, 'deck-mastered') : strpos($filterclass, 'deck-mastered');
                                        $card_need_in_collect = $can_use_strcontains ? str_contains($filterclass, 'needed collect') : strpos($filterclass, 'needed collect');
                                        $card_need_in_keep = $can_use_strcontains ? str_contains($filterclass, 'needed keep') : strpos($filterclass, 'needed keep');
                                        $card_need_on_wishlist = $can_use_strcontains ? str_contains($filterclass, 'needed wishlist') : strpos($filterclass, 'needed wishlist');
                                        $card_already_there = $can_use_strcontains ? str_contains($filterclass, 'already-in-') : strpos($filterclass, 'already-in-');

                                        $trade_selected = false;
                                        $keep_selected = true;
                                        $collect_selected = false;
                                        $hide_collect = false;

                                        // set hide collect
                                        if (
                                            ($carddeck_already_mastered &&
                                            !TCG_MULTI_MASTER) ||
                                            $card_already_there
                                        ) {
                                            $hide_collect = true;
                                        }

                                        // set selected category
                                        if (
                                            $carddeck_already_mastered && !TCG_MULTI_MASTER
                                        ) {
                                            $trade_selected = true;
                                        } elseif (
                                            $card_need_in_collect
                                        ) {
                                            $collect_selected = true;
                                            $hide_collect = false;
                                        } elseif (
                                            $card_need_in_keep
                                        ) {
                                            $keep_selected = true;
                                        }
                                        elseif (
                                            $card_need_on_wishlist
                                        ) {
                                            $keep_selected = true;
                                        }
                                        if (!TCG_CATEGORY_KEEP_USE) {
                                            $keep_selected = false;
                                        }
                                        ?>
                                        <tr>
                                            <td class="d-none"><?php echo $carddeck_name . $cardnumber; ?></td>
                                            <td>
                                                <div
                                                    class="cards-sorting-wrapper">
                                                    <div class="card-highlight<?php echo $filterclass; ?>">
                                                        <?php echo get_card($carddeck_id, $cardnumber_plain); ?>
                                                    </div>
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
                                                                <option
                                                                    value="3" <?php echo($trade_selected ? 'selected' : ''); ?>>
                                                                    Trade
                                                                </option>
                                                                <option
                                                                    value="4" <?php echo($keep_selected ? 'selected' : ''); ?>>
                                                                    Keep
                                                                </option>
                                                                <?php if ($hide_collect == false) { ?>
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

                <div class="row">
                    <div class="col col-12 my-4 text-center">
                        <?php get_card_highlight_legend(); ?>
                    </div>
                </div>
                <?php
            } else {
                title_small('0 Keep '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']);
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
