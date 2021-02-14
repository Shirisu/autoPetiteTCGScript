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

    if (isset($_POST['card_id']) && isset($_POST['card_category'])) {
        foreach ($_POST['card_id'] as $index => $card) {
            $card_id = mysqli_real_escape_string($link, $card);
            $card_category = mysqli_real_escape_string($link, $_POST['card_category'][$index]);

            // check if category is ok (not mastered, not in collect yet) and sort it
        }
    }
    ?>
    <div class="row cards-sorting">
        <div class="col col-12 mb-3">
            <div class="row">
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/new" class="btn btn-outline-info btn-sm btn-block active"><i class="fas fa-fire"></i> New</a>
                </div>
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/trade" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-exchange-alt"></i> Trade</a>
                </div>
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/collect" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-heart"></i> Collect</a>
                </div>
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/master" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-award"></i> Master</a>
                </div>
            </div>
        </div>
        <div class="col col-12 mb-3 cards-sorting-container">
            <?php
            $sql_cards = "SELECT member_cards_id, member_cards_number, carddeck_id, carddeck_name,
                             EXISTS (SELECT member_cards_id
                              FROM member_cards
                              WHERE member_cards_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                                AND member_cards_cat = 2
                                AND member_cards_active = 1
                              GROUP BY member_cards_carddeck_id) as carddeck_in_collect,
                             EXISTS (SELECT member_cards_id
                              FROM member_cards
                              WHERE member_cards_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                                AND mc.member_cards_number = member_cards_number
                                AND member_cards_cat = 2
                                AND member_cards_active = 1
                              GROUP BY member_cards_carddeck_id, member_cards_number) as card_already_in_collect,
                             EXISTS (SELECT member_wishlist_id
                              FROM member_wishlist
                              WHERE member_wishlist_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_wishlist_carddeck_id
                              GROUP BY member_wishlist_carddeck_id) as carddeck_on_wishlist,
                             EXISTS (SELECT member_master_id
                              FROM member_master
                              WHERE member_master_member_id = '".$member_id."'
                                AND mc.member_cards_carddeck_id = member_master_carddeck_id
                              GROUP BY member_master_carddeck_id) as carddeck_already_mastered
                          FROM member_cards mc, carddeck
                          WHERE member_cards_member_id = '".$member_id."'
                            AND member_cards_cat = 1
                            AND member_cards_active = 1
                            AND member_cards_carddeck_id = carddeck_id
                          ORDER BY carddeck_name, member_cards_number ASC";
            $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
            $count_cards = mysqli_num_rows($result_cards);
            if ($count_cards) {
                title_small($count_cards.' New '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']);
                ?>
                <form action="<?php echo HOST_URL; ?>/cards/new" method="post">
                    <div class="row">
                        <div class="col col-12">
                            <table class="optional cards-sorting-table new-cards" data-mobile-responsive="true">
                                <thead>
                                <tr>
                                    <th><?php echo title_small($count_cards.' New '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($row_cards = mysqli_fetch_assoc($result_cards)) {
                                    $card_id = $row_cards['member_cards_id'];
                                    $carddeck_id = $row_cards['carddeck_id'];
                                    $carddeck_name = $row_cards['carddeck_name'];
                                    $cardnumber_plain = $row_cards['member_cards_number'];
                                    $cardnumber = sprintf("%'.02d", $cardnumber_plain);
                                    $carddeck_in_collect = $row_cards['carddeck_in_collect'];
                                    $card_already_in_collect = $row_cards['card_already_in_collect'];
                                    $carddeck_on_wishlist = $row_cards['carddeck_on_wishlist'];
                                    $carddeck_already_mastered = $row_cards['carddeck_already_mastered'];

                                    if ($carddeck_already_mastered == 1) {
                                        $trade_selected = true;
                                        $collect_selected = false;
                                    } elseif ($carddeck_in_collect == 1 && $card_already_in_collect == 1) {
                                        $trade_selected = true;
                                        $collect_selected = false;
                                    } elseif (
                                        ($carddeck_in_collect == 1 && $card_already_in_collect == 0) ||
                                        ($carddeck_on_wishlist == 1 && $carddeck_in_collect == 0)
                                    ) {
                                        $trade_selected = false;
                                        $collect_selected = true;
                                    } else {
                                        $trade_selected = false;
                                        $collect_selected = false;
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="cards-sorting-wrapper<?php echo ($carddeck_already_mastered == 1 ? ' mastered' : ''); ?>">
                                                <?php echo show_card($carddeck_id, $cardnumber_plain); ?>
                                                <a class="carddeck-link" href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>"><small><?php echo $carddeck_name.$cardnumber; ?></small></a>
                                                <br />
                                                <div class="form-group mt-2">
                                                    <div class="input-group">
                                                        <select class="custom-select" id="card_category[]" name="card_category[]" aria-describedby="ariaDescribedbyLanguage" required>
                                                            <option value="1" selected>New</option>
                                                            <option value="3" <?php echo ($trade_selected ? 'selected' : ''); ?>>Trade</option>
                                                            <option value="2" <?php echo ($collect_selected ? 'selected' : ''); ?>>Collect</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="hidden" class="form-control" id="card_id[]" name="card_id[]" value="<?php echo $card_id; ?>"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
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
                title_small('0 Trade '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']);
                alert_box(TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_no_cards_in_category'], 'danger');
            }
            ?>
        </div>
    </div>
    <?php
} else {
    show_no_access_message();
}
?>
