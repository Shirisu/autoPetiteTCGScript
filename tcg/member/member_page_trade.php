<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    if (isset($member_id)) {
        if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
            $member_active_string = '';
        } else {
            $member_active_string = 'AND member_active = 1';
        }

        $sql_member = "SELECT member_nick
                       FROM member
                       WHERE member_id = '".$member_id."'
                         ".$member_active_string."
                       LIMIT 1";
        $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
        $count_member = mysqli_num_rows($result_member);

        if ($count_member) {
            $row_member = mysqli_fetch_assoc($result_member);

            $breadcrumb = array(
                '/' => 'Home',
                '/member' => 'Member',
                '/member/'.$member_id => $row_member['member_nick'],
                '/member/'.$member_id.'/trade' => 'Trade',
            );

            breadcrumb($breadcrumb);
            title($row_member['member_nick'].' <small>'.get_online_status($member_id).'</small>');
            ?>
            <div class="row member-profile">
                <div class="col col-12 mb-3">
                    <?php get_member_menu($member_id, 'trade'); ?>
                </div>
                <div class="col col-12 mb-3 member-cards-container">
                    <?php
                    $can_use_sum = (MYSQL_VERSION >= 'mysqlnd 8.0.0' ? true : false);

                    if ($can_use_sum) {
                        $sql_cards = "SELECT MIN(member_cards_id) as member_cards_id, member_cards_number, carddeck_id, carddeck_name, carddeck_active,
                                    COUNT(*) AS card_count,
                                    SUM(COUNT(member_cards_number)) OVER() AS total_card_count
                                FROM member_cards mc
                                JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                                WHERE member_cards_member_id = '" . $member_id . "'
                                  AND member_cards_cat = '" . MEMBER_CARDS_TRADE . "'
                                  AND member_cards_active = 1
                                  AND carddeck_active = 1
                                GROUP BY member_cards_carddeck_id, member_cards_number
                                ORDER BY carddeck_name, member_cards_number ASC";
                    } else {
                        $sql_count_cards = "SELECT member_cards_id
                                    FROM member_cards
                                    WHERE member_cards_member_id = '".$member_id."'
                                      AND member_cards_cat = '".MEMBER_CARDS_TRADE."'
                                      AND member_cards_active = 1";
                        $result_count_cards = mysqli_query($link, $sql_count_cards) OR die(mysqli_error($link));
                        $count_count_cards = mysqli_num_rows($result_count_cards);

                        $sql_cards = "SELECT MIN(member_cards_id) as member_cards_id, member_cards_number, carddeck_id, carddeck_name, carddeck_active,
                                    COUNT(*) AS card_count
                                FROM member_cards mc
                                JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                                WHERE member_cards_member_id = '" . $member_id . "'
                                  AND member_cards_cat = '" . MEMBER_CARDS_TRADE . "'
                                  AND member_cards_active = 1
                                  AND carddeck_active = 1
                                GROUP BY member_cards_carddeck_id, member_cards_number
                                ORDER BY carddeck_name, member_cards_number ASC";
                    }
                    $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
                    $count_cards = mysqli_num_rows($result_cards);
                    if ($count_cards) {
                        $row_total_card_count = mysqli_fetch_assoc($result_cards);
                        $total_card_count = ($can_use_sum ? $row_total_card_count['total_card_count'] : $count_count_cards);
                        title_small($total_card_count.' Trade '.($total_card_count == 1 ? TRANSLATIONS[$GLOBALS['language']]['general']['text_card'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']));
                        mysqli_data_seek($result_cards, 0);

                        if ($member_id != $_SESSION['member_id'] && TCG_SHOW_TRADE_FILTER == true) {
                            ?>
                            <div class="text-center">
                                <button class="btn btn-secondary" id="filterTradeCards"><?php echo TRANSLATIONS[$GLOBALS['language']]['member']['text_show_only_needed_cards']; ?></button>
                                <button class="btn btn-secondary" id="resetFilterTradeCards"><?php echo TRANSLATIONS[$GLOBALS['language']]['member']['text_show_all_cards']; ?></button>
                            </div>
                            <?php
                        }
                        ?>
                        <table class="optional profile-cards trade-cards" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th data-field="filtercard"></th>
                                <th data-searchable="false"><?php echo title_small($count_cards.' Trade '.($count_cards == 1 ? TRANSLATIONS[$GLOBALS['language']]['general']['text_card'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'])); ?></th>
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
                                                class="profile-cards-wrapper">
                                                <?php echo get_card(); ?>
                                                <small><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_unkown']; ?></small>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    $carddeck_id = $row_cards['carddeck_id'];
                                    $carddeck_name = $row_cards['carddeck_name'];
                                    $cardnumber_plain = $row_cards['member_cards_number'];
                                    $cardnumber = sprintf("%'.02d", $cardnumber_plain);
                                    $card_count = $row_cards['card_count'];

                                    $filterclass = '';
                                    if ($member_id != $_SESSION['member_id']) {
                                        $filterclass = get_card_filter_class($carddeck_id, $cardnumber_plain, $_SESSION['member_id'], $member_id);
                                    }
                                    ?>
                                    <tr>
                                        <td class="d-none filtercard"><?php echo $carddeck_name . $cardnumber; ?><?php echo $filterclass; ?></td>
                                        <td>
                                            <div class="card-highlight<?php echo $filterclass; ?><?php echo($card_count > 1 ? ' show-counter" data-count="' . $card_count . 'x' : ''); ?>">
                                                <div
                                                    class="profile-cards-wrapper">
                                                    <?php echo ($member_id != $_SESSION['member_id'] ? '<a href="' . HOST_URL . '/trade/' . $member_id . '/' . $row_cards['member_cards_id'] . '">' : ''); ?><?php echo get_card($carddeck_id, $cardnumber_plain); ?><?php echo($member_id != $_SESSION['member_id'] ? '</a>' : ''); ?>
                                                </div>
                                            </div>
                                            <a class="carddeck-link"
                                               href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>">
                                                <small><?php echo $carddeck_name . $cardnumber; ?></small>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
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
            $breadcrumb = array(
                '/' => 'Home',
                '/member' => 'Member',
            );

            breadcrumb($breadcrumb);
            title('Member');

            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_member_dont_exists'], 'danger');
        }
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['text_pagenotexist'], 'danger');
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>