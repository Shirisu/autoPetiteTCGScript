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
                '/member/'.$member_id.'/collect' => 'Collect',
            );

            breadcrumb($breadcrumb);
            title($row_member['member_nick'].' <small>'.get_online_status($member_id).'</small>');
            ?>
            <div class="row member-profile">
                <div class="col col-12 mb-3">
                    <?php get_member_menu($member_id, 'collect'); ?>
                </div>
                <div class="col col-12 mb-3 member-cards-container">
                    <?php
                    $sql_cards = "SELECT member_cards_carddeck_id, carddeck_name, carddeck_is_puzzle, carddeck_active
                                  FROM member_cards
                                  JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                                  WHERE member_cards_member_id = '".$member_id."'
                                    AND member_cards_cat = '".MEMBER_CARDS_COLLECT."'
                                    AND member_cards_active = 1
                                    AND carddeck_active = 1
                                  GROUP BY member_cards_carddeck_id
                                  ORDER BY carddeck_name ASC";
                    $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
                    $count_cards = mysqli_num_rows($result_cards);
                    if ($count_cards) {
                        title_small($count_cards.' Collect '.($count_cards == 1 ? TRANSLATIONS[$GLOBALS['language']]['general']['text_carddeck'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']));
                        ?>
                        <table class="optional profile-cards collect-cards" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th></th>
                                <th data-searchable="false"><?php echo title_small($count_cards.' Collect '.TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']); ?></th>
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
                                            <small><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_unkown']; ?></small>
                                            <div class="carddeck-wrapper"
                                                 data-is-puzzle="<?php echo($row_cards['carddeck_is_puzzle'] ? $row_cards['carddeck_is_puzzle'] : 0); ?>">
                                                <?php
                                                for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS; $i++) {
                                                    $filename = get_card($row_cards['member_cards_carddeck_id'], $i, true);
                                                    ?>
                                                    <span class="card-wrapper"></span>
                                                    <?php
                                                    if (($i % TCG_CARDS_PER_ROW) == 0) {
                                                        ?>
                                                        <br/>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    $carddeck_name = $row_cards['carddeck_name'];

                                    $cardnumbers = array();
                                    $sql_card_number = "SELECT member_cards_number
                                                    FROM member_cards
                                                    WHERE member_cards_member_id = '" . $member_id . "'
                                                      AND member_cards_carddeck_id = '" . $row_cards['member_cards_carddeck_id'] . "'
                                                      AND member_cards_cat = '" . MEMBER_CARDS_COLLECT . "'
                                                      AND member_cards_active = 1
                                                    GROUP BY member_cards_number
                                                    ORDER BY member_cards_number ASC";
                                    $result_card_number = mysqli_query($link, $sql_card_number) OR die(mysqli_error($link));
                                    $count_card_number = mysqli_num_rows($result_card_number);
                                    if ($count_card_number) {
                                        while ($row_card_number = mysqli_fetch_assoc($result_card_number)) {
                                            array_push($cardnumbers, $row_card_number['member_cards_number']);
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td class="d-none"><?php echo $carddeck_name; ?> <?php echo count($cardnumbers); ?>
                                            /<?php echo TCG_CARDDECK_MAX_CARDS; ?></td>
                                        <td>
                                            <small>
                                                <a
                                                    href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>">
                                                    [<?php echo strtoupper($carddeck_name); ?>]
                                                </a> (<?php echo count($cardnumbers); ?>/<?php echo TCG_CARDDECK_MAX_CARDS; ?>)
                                            </small>
                                            <div class="carddeck-wrapper"
                                                 data-is-puzzle="<?php echo($row_cards['carddeck_is_puzzle'] ? $row_cards['carddeck_is_puzzle'] : 0); ?>">
                                                <?php
                                                for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS; $i++) {
                                                    if (in_array($i, $cardnumbers)) {
                                                        $filename = get_card($row_cards['member_cards_carddeck_id'], $i, true);
                                                        ?>
                                                        <span
                                                            class="card-wrapper" <?php echo(file_exists('.' . substr($filename, strlen(HOST_URL))) ? 'style="background-image:url(' . $filename . ');"' : ''); ?>></span>
                                                        <?php
                                                    } else {
                                                        $filename_filler = TCG_CARDS_FOLDER . '/' . TCG_CARDS_FILLER_NAME . '.' . TCG_CARDS_FILE_TYPE;
                                                        ?>
                                                        <span
                                                            class="card-wrapper" <?php echo(file_exists('.' . $filename_filler) ? 'style="background-image:url(' . HOST_URL . $filename_filler . ');"' : ''); ?>></span>
                                                        <?php
                                                    }
                                                    if (($i % TCG_CARDS_PER_ROW) == 0) {
                                                        ?>
                                                        <br/>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
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
                        title_small('0 Collect '.TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']);
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