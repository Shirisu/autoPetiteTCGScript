<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/cards' => TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'],
        '/cards/collect' => 'Collect',
    );
    breadcrumb($breadcrumb);
    title('Collect');

    $member_id = $_SESSION['member_id'];

    if (isset($_POST['carddeck_id']) && isset($_POST['action'])) {
        $carddeck_id = mysqli_real_escape_string($link, $_POST['carddeck_id']);
        $action = mysqli_real_escape_string($link, $_POST['action']);

        $sql_card_number = "SELECT member_cards_number
                            FROM member_cards
                            WHERE member_cards_member_id = '".$member_id."'
                              AND member_cards_carddeck_id = '".$carddeck_id."'
                              AND member_cards_cat = 2
                              AND member_cards_active = 1
                            GROUP BY member_cards_number";
        $result_card_number = mysqli_query($link, $sql_card_number) OR die(mysqli_error($link));
        $cards_quantity = mysqli_num_rows($result_card_number);

        if ($cards_quantity == TCG_CARDDECK_MAX_CARDS && $action == 'master') { // master carddeck
            $sql_mastered_yet = "SELECT member_master_member_id, carddeck_name
                                 FROM member_master, carddeck
                                 WHERE member_master_member_id = '" . $member_id . "'
                                   AND member_master_carddeck_id = '" . $carddeck_id . "'
                                   AND member_master_carddeck_id = carddeck_id
                                 LIMIT 1";
            $result_mastered_yet = mysqli_query($link, $sql_mastered_yet) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_mastered_yet)) {
                $row_carddeck = mysqli_fetch_assoc($result_mastered_yet);
                alert_box($row_carddeck['carddeck_name'] . ' ' . TRANSLATIONS[$GLOBALS['language']]['member']['text_card_not_mastered'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['member']['text_already_mastered'], 'danger');
            } else {
                $sql_carddeck = "SELECT carddeck_id, carddeck_name
                         FROM member_cards mc, carddeck
                         WHERE member_cards_member_id = '" . $member_id . "'
                           AND member_cards_carddeck_id = '" . $carddeck_id . "'
                           AND member_cards_cat = 2
                           AND member_cards_active = 1
                           AND member_cards_carddeck_id = carddeck_id
                         LIMIT 1";
                $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
                if (mysqli_num_rows($result_carddeck)) {
                    $row_carddeck = mysqli_fetch_assoc($result_carddeck);
                    mysqli_query($link, "DELETE FROM member_cards
                                         WHERE member_cards_carddeck_id = '".$carddeck_id."'
                                           AND member_cards_member_id = '".$member_id."'
                                           AND member_cards_cat = 2
                                           AND member_cards_active = 1")
                    OR die(mysqli_error($link));
                    mysqli_query($link, "INSERT INTO member_master
                                         (member_master_carddeck_id, member_master_member_id, member_master_date)
                                         VALUES
                                         ('".$carddeck_id."', '".$member_id."', '".time()."')")
                    OR die(mysqli_error($link));
                    mysqli_query($link, "UPDATE member
                                         SET member_master = member_master + 1
                                         WHERE member_id = '".$member_id."'
                                         LIMIT 1")
                    OR die(mysqli_error($link));

                    insert_cards($member_id, TCG_MASTER_CARD_REWARD);
                    $master_text = $row_carddeck['carddeck_name'] . ' ' . TRANSLATIONS[$GLOBALS['language']]['general']['text_mastered'].'. '.TRANSLATIONS[$GLOBALS['language']]['general']['text_reward'].' - '.TCG_MASTER_CARD_REWARD.' '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].': '.implode(', ', $_SESSION['insert_cards']);

                    if (TCG_USE_WISH == true) {
                        $master_text .= ' &amp; 1 Wish';
                        mysqli_query($link, "UPDATE member
                                         SET member_wish = member_wish + 1
                                         WHERE member_id = '".$member_id."'
                                         LIMIT 1")
                        OR die(mysqli_error($link));
                    }

                    insert_log('Master', $master_text, $member_id);

                    alert_box($master_text, 'success');
                } else {
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['member']['text_carddeck_not_in_collect'], 'danger');
                }
            }
        } else { // dissolve carddeck and move cards to new
            $sql_carddeck = "SELECT carddeck_id, carddeck_name
                         FROM member_cards mc, carddeck
                         WHERE member_cards_member_id = '" . $member_id . "'
                           AND member_cards_carddeck_id = '" . $carddeck_id . "'
                           AND member_cards_cat = 2
                           AND member_cards_active = 1
                           AND member_cards_carddeck_id = carddeck_id
                         LIMIT 1";
            $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_carddeck)) {
                $row_carddeck = mysqli_fetch_assoc($result_carddeck);
                mysqli_query($link, "UPDATE member_cards
                                 SET member_cards_cat = 1
                                 WHERE member_cards_carddeck_id = '" . $carddeck_id . "'
                                   AND member_cards_member_id = '" . $member_id . "'
                                   AND member_cards_cat = 2
                                   AND member_cards_active = 1")
                OR die(mysqli_error($link));
                alert_box($row_carddeck['carddeck_name'] . ' ' . TRANSLATIONS[$GLOBALS['language']]['member']['text_dissolved'], 'success');
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['member']['text_carddeck_not_in_collect'], 'danger');
            }
        }
    }
    ?>
    <div class="row cards-sorting">
        <div class="col col-12 mb-3">
            <div class="row">
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/new" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-fire"></i> New</a>
                </div>
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/trade" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-exchange-alt"></i> Trade</a>
                </div>
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/collect" class="btn btn-outline-info btn-sm btn-block active"><i class="fas fa-heart"></i> Collect</a>
                </div>
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/master" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-award"></i> Master</a>
                </div>
            </div>
        </div>
        <div class="col col-12 mb-3 cards-sorting-container">
            <?php
            $sql_cards = "SELECT member_cards_carddeck_id, member_cards_number, carddeck_name, carddeck_is_puzzle
                          FROM member_cards, carddeck
                          WHERE member_cards_member_id = '".$member_id."'
                            AND member_cards_cat = 2
                            AND member_cards_active = 1
                            AND member_cards_carddeck_id = carddeck_id
                          GROUP BY member_cards_carddeck_id
                          ORDER BY carddeck_name, member_cards_number ASC";
            $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
            $count_cards = mysqli_num_rows($result_cards);
            if ($count_cards) {
                title_small($count_cards.' Collect '.TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']);
                ?>
                <div class="row">
                    <div class="col col-12">
                        <table class="optional cards-sorting-table collect-cards" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th></th>
                                <th data-searchable="false"><?php echo title_small($count_cards.' Collect '.TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row_cards = mysqli_fetch_assoc($result_cards)) {
                                $carddeck_id = $row_cards['member_cards_carddeck_id'];
                                $carddeck_name = $row_cards['carddeck_name'];

                                $cardnumbers = array();
                                $sql_card_number = "SELECT member_cards_number
                                                FROM member_cards
                                                WHERE member_cards_member_id = '".$member_id."'
                                                  AND member_cards_carddeck_id = '".$row_cards['member_cards_carddeck_id']."'
                                                  AND member_cards_cat = 2
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
                                $cards_quantity = count($cardnumbers);

                                $sql_mastered_yet = "SELECT member_master_member_id, carddeck_name
                                                     FROM member_master, carddeck
                                                     WHERE member_master_member_id = '" . $member_id . "'
                                                       AND member_master_carddeck_id = '" .$row_cards['member_cards_carddeck_id'] . "'
                                                       AND member_master_carddeck_id = carddeck_id
                                                     LIMIT 1";
                                $result_mastered_yet = mysqli_query($link, $sql_mastered_yet) OR die(mysqli_error($link));
                                $mastered_yet = mysqli_num_rows($result_mastered_yet);
                                ?>
                                <tr>
                                    <td class="d-none"><?php echo $carddeck_name; ?> <?php echo count($cardnumbers); ?>/<?php echo TCG_CARDDECK_MAX_CARDS; ?></td>
                                    <td>
                                        <form action="<?php echo HOST_URL; ?>/cards/collect" method="post">
                                            <small><a href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>">[<?php echo strtoupper($carddeck_name); ?>]</a> (<?php echo $cards_quantity; ?>/<?php echo TCG_CARDDECK_MAX_CARDS; ?>)</small>
                                            <div class="carddeck-wrapper" data-is-puzzle="<?php echo ($row_cards['carddeck_is_puzzle'] ? $row_cards['carddeck_is_puzzle'] : 0); ?>">
                                                <?php
                                                for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS; $i++) {
                                                    if (in_array($i, $cardnumbers)) {
                                                        $filename = show_card($row_cards['member_cards_carddeck_id'], $i, true);
                                                        ?>
                                                        <span class="card-wrapper" <?php echo(file_exists('.' . substr($filename, strlen(HOST_URL))) ? 'style="background-image:url(' . $filename . ');"' : ''); ?>></span>
                                                        <?php
                                                    } else {
                                                        $filename_filler = TCG_CARDS_FOLDER . '/'.TCG_CARDS_FILLER_NAME.'.' . TCG_CARDS_FILE_TYPE;
                                                        ?>
                                                        <a href="<?php echo HOST_URL; ?>/memberarea/search?carddeck_id=<?php echo $row_cards['member_cards_carddeck_id']; ?>&card_number=<?php echo $i; ?>"><span class="card-wrapper" <?php echo(file_exists('.' . $filename_filler) ? 'style="background-image:url(' . HOST_URL.$filename_filler . ');"' : ''); ?>></span></a>
                                                        <?php
                                                    }
                                                    if (($i % TCG_CARDS_PER_ROW) == 0) {
                                                        ?>
                                                        <br />
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <div class="col col-12 text-center mt-2">
                                                <input type="hidden" name="carddeck_id" value="<?php echo $carddeck_id; ?>"/>
                                                <input type="hidden" name="action" value="<?php echo (($cards_quantity == TCG_CARDDECK_MAX_CARDS && !$mastered_yet) ? 'master' : 'dissolve'); ?>"/>
                                                <button type="submit" class="btn btn-primary"><?php echo (($cards_quantity == TCG_CARDDECK_MAX_CARDS && !$mastered_yet) ? TRANSLATIONS[$GLOBALS['language']]['general']['text_master'] : TRANSLATIONS[$GLOBALS['language']]['member']['text_button_dissolve']); ?></button>
                                            </div>
                                        </form>
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
                title_small('0 Collect '.TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']);
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
