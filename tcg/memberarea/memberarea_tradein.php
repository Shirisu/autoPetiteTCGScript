<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/memberarea' => TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'],
        '/memberarea/tradein' => TRANSLATIONS[$GLOBALS['language']]['general']['text_tradein'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['general']['text_tradein']);

    $member_id = $_SESSION['member_id'];

    if (isset($_POST['tradein_card_id']) && isset($_POST['tradein_card_deck_name']) && isset($_POST['tradein_card_number'])) {
        $tradein_card_id = mysqli_real_escape_string($link, trim($_POST['tradein_card_id']));
        $tradein_card_deck_name = mysqli_real_escape_string($link, trim($_POST['tradein_card_deck_name']));
        $tradein_card_number = mysqli_real_escape_string($link, trim($_POST['tradein_card_number']));
        card_tradein($tradein_card_id, $tradein_card_deck_name, $tradein_card_number);
    }

    $can_tradein = true;
    $sql_last_tradein = "SELECT member_tradein_last_tradein
                         FROM member_tradein
                         WHERE member_tradein_member_id = '" . $member_id . "'
                         ORDER BY member_tradein_id DESC
                         LIMIT 1";
    $result_last_tradein = mysqli_query($link, $sql_last_tradein) OR die(mysqli_error($link));
    $row_last_tradein = mysqli_fetch_assoc($result_last_tradein);
    if (mysqli_num_rows($result_last_tradein)) {
        $next_tradein_time = $row_last_tradein['member_tradein_last_tradein'] + (60 * 60 * TCG_TRADE_IN_HOURS);

        if ($next_tradein_time <= time()) {
            $can_tradein = true;
        } else {
            $can_tradein = false;
        }
    } else {
        $can_tradein = true;
    }

    if (!$can_tradein) {
        alert_box(
            TRANSLATIONS[$GLOBALS['language']]['tradein']['hint_already_tradein_part_1'].'<br />'.
            TRANSLATIONS[$GLOBALS['language']]['tradein']['hint_already_tradein_part_2'].' '.
            date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_time'], $next_tradein_time).'!'
            , 'danger');
        return;
    }

    $sql_duplicate_cards = "SELECT member_cards_id, member_cards_carddeck_id, member_cards_number, carddeck_name
                            FROM member_cards
                            JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                            WHERE member_cards_member_id = '".$member_id."'
                              AND member_cards_cat = '".MEMBER_CARDS_TRADE."'
                              AND member_cards_active = 1
                            GROUP BY member_cards_number, member_cards_carddeck_id
                            HAVING COUNT(member_cards_id) > 1
                            ORDER BY carddeck_name, member_cards_number ASC";
    $result_duplicate_cards = mysqli_query($link, $sql_duplicate_cards) OR die(mysqli_error($link));
    $count_duplicate_cards = mysqli_num_rows($result_duplicate_cards);

    if ($count_duplicate_cards) {
        ?>
        <div class="row tradein-container">
            <div class="col col-12 mb-4 text-left text-md-center">
                <span
                    class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['tradein']['text_duplicate_cards']; ?>:</span>
                <?php echo $count_duplicate_cards; ?>
            </div>
            <div class="col col-12">
                <table class="optional tradein" data-mobile-responsive="true">
                    <thead>
                    <tr>
                        <th></th>
                        <th data-searchable="false"><?php echo title_small($count_duplicate_cards.' '.TRANSLATIONS[$GLOBALS['language']]['tradein']['text_duplicate_cards'].' '.($count_duplicate_cards == 1 ? TRANSLATIONS[$GLOBALS['language']]['general']['text_card'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'])); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($row_duplicate_cards = mysqli_fetch_assoc($result_duplicate_cards)) {
                        $carddeck_id = $row_duplicate_cards['member_cards_carddeck_id'];
                        $carddeck_name = $row_duplicate_cards['carddeck_name'];
                        $cardnumber_plain = $row_duplicate_cards['member_cards_number'];
                        $cardnumber = sprintf("%'.02d", $cardnumber_plain);
                        ?>
                        <tr>
                            <td class="d-none"><?php echo $carddeck_name . $cardnumber; ?></td>
                            <td>
                                <div
                                    class="cards-wrapper">
                                    <form action="<?php echo HOST_URL; ?>/memberarea/tradein" method="POST">
                                        <input type="hidden" name="tradein_card_id" value="<?php echo $row_duplicate_cards['member_cards_id']; ?>" />
                                        <input type="hidden" name="tradein_card_deck_name" value="<?php echo $carddeck_name; ?>" />
                                        <input type="hidden" name="tradein_card_number" value="<?php echo $cardnumber; ?>" />
                                        <button type="submit" class="btn"><?php echo get_card($carddeck_id, $cardnumber_plain); ?></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['tradein']['hint_no_duplicate_cards'], 'danger');
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>