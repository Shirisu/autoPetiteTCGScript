<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    $member_id = $_SESSION['member_id'];

    $breadcrumb = array(
        '/' => 'Home',
        '/trade' => TRANSLATIONS[$GLOBALS['language']]['general']['text_trade'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['general']['text_trade']);

    if (isset($trade_id)) {
        if (isset($_POST['accept'])) {
            $action = 'accept';
        } elseif (isset($_POST['decline'])) {
            $action = 'decline';
        }

        if (isset($action)) {
            if ($action == 'withdraw') {
                $wherestring = "AND trade_from_member_id = '".$member_id."'";
            } else {
                $wherestring = "AND trade_to_member_id = '".$member_id."'";
            }

            $trade_message = '';
            if (isset($_POST['trade_message'])) {
                $trade_message = mysqli_real_escape_string($link, trim($_POST['trade_message']));
            }

            $sql_trade = "SELECT trade_from_member_id, trade_from_member_card_id, trade_to_member_id, trade_to_member_card_id
                          FROM trade
                          WHERE trade_id = '".$trade_id."'
                            ".$wherestring."
                          LIMIT 1";
            $result_trade = mysqli_query($link, $sql_trade) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_trade)) {
                $row_trade = mysqli_fetch_assoc($result_trade);
                if ($action == 'accept') {
                    // change owner of trade card accept
                    mysqli_query($link, "UPDATE member_cards
                                         SET member_cards_cat = '".MEMBER_CARDS_NEW."',
                                             member_cards_active = 1,
                                             member_cards_member_id = '".$member_id."'
                                         WHERE member_cards_id = '".$row_trade['trade_from_member_card_id']."'
                                           AND member_cards_member_id = '".$row_trade['trade_from_member_id']."'
                                         LIMIT 1")
                    OR die(mysqli_error($link));

                    // change owner of own card accept
                    mysqli_query($link, "UPDATE member_cards
                                         SET member_cards_cat = '".MEMBER_CARDS_NEW."',
                                             member_cards_active = 1,
                                             member_cards_member_id = '".$row_trade['trade_from_member_id']."'
                                         WHERE member_cards_id = '".$row_trade['trade_to_member_card_id']."'
                                           AND member_cards_member_id = '".$member_id."'
                                         LIMIT 1")
                    OR die(mysqli_error($link));

                    // delete trade
                    mysqli_query($link, "DELETE FROM trade
                                         WHERE trade_to_member_id = '" . $member_id . "'
                                           AND trade_id = '".$trade_id."'
                                         LIMIT 1")
                    OR die(mysqli_error($link));

                    $trade_card_name = get_carddeck_name_from_member_cards_id($row_trade['trade_from_member_card_id']).sprintf("%'.02d", get_card_number_from_member_cards_id($row_trade['trade_from_member_card_id']));
                    $trade_member_nick = get_member_link($row_trade['trade_from_member_id']);
                    $trade_own_member_nick = get_member_link($row_trade['trade_to_member_id']);
                    $own_card_name = get_carddeck_name_from_member_cards_id($row_trade['trade_to_member_card_id']).sprintf("%'.02d", get_card_number_from_member_cards_id($row_trade['trade_to_member_card_id']));

                    // insert log for owner of trade
                    $language = get_member_language($row_trade['trade_from_member_id']);
                    $topic = TRANSLATIONS[$language]['trade']['text_accept_trade'];
                    $text = TRANSLATIONS[$language]['trade']['text_log_trade_accept'].': '.$trade_card_name.' ('.TRANSLATIONS[$language]['trade']['text_log_trade_text_from'].' '.$trade_member_nick.') '.TRANSLATIONS[$language]['trade']['text_log_trade_text_against']. ' '.$own_card_name.' ('.TRANSLATIONS[$language]['trade']['text_log_trade_text_from'].' '.$trade_own_member_nick.')';
                    insert_log($topic, $text, $row_trade['trade_from_member_id']);

                    if ($trade_message != '') {
                        $topic .= ' ('.TRANSLATIONS[$language]['trade']['text_trade_message_answer'].')';
                        $text .= '<br /><br />'.TRANSLATIONS[$language]['trade']['text_trade_message'].':<br />'.$trade_message;
                    }
                    // send message to owner of trade
                    insert_message($_SESSION['member_id'], $row_trade['trade_from_member_id'], $topic, $text, 1);

                    // insert own log
                    $topic_own = TRANSLATIONS[$GLOBALS['language']]['trade']['text_accept_trade'];
                    $text_own = TRANSLATIONS[$GLOBALS['language']]['trade']['text_log_trade_accept'].': '.$trade_card_name.' ('.TRANSLATIONS[$GLOBALS['language']]['trade']['text_log_trade_text_from'].' '.$trade_member_nick.') '.TRANSLATIONS[$GLOBALS['language']]['trade']['text_log_trade_text_against']. ' '.$own_card_name.' ('.TRANSLATIONS[$GLOBALS['language']]['trade']['text_log_trade_text_from'].' '.$trade_own_member_nick.')';
                    insert_log($topic_own, $text_own, $member_id);

                    alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_trade_accepted'], 'success');
                } elseif ($action == 'decline') {
                    // set trade card active
                    mysqli_query($link, "UPDATE member_cards
                                         SET member_cards_active = 1
                                         WHERE member_cards_id = '".$row_trade['trade_from_member_card_id']."'
                                           AND member_cards_member_id = '".$row_trade['trade_from_member_id']."'
                                         LIMIT 1")
                    OR die(mysqli_error($link));

                    // set own card active
                    mysqli_query($link, "UPDATE member_cards
                                         SET member_cards_active = 1
                                         WHERE member_cards_id = '".$row_trade['trade_to_member_card_id']."'
                                           AND member_cards_member_id = '".$member_id."'
                                         LIMIT 1")
                    OR die(mysqli_error($link));

                    // delete trade
                    mysqli_query($link, "DELETE FROM trade
                                         WHERE trade_to_member_id = '" . $member_id . "'
                                           AND trade_id = '".$trade_id."'
                                         LIMIT 1")
                    OR die(mysqli_error($link));

                    $trade_card_name = get_carddeck_name_from_member_cards_id($row_trade['trade_from_member_card_id']).sprintf("%'.02d", get_card_number_from_member_cards_id($row_trade['trade_from_member_card_id']));
                    $trade_member_nick = get_member_link($row_trade['trade_from_member_id']);
                    $trade_own_member_nick = get_member_link($row_trade['trade_to_member_id']);
                    $own_card_name = get_carddeck_name_from_member_cards_id($row_trade['trade_to_member_card_id']).sprintf("%'.02d", get_card_number_from_member_cards_id($row_trade['trade_to_member_card_id']));

                    // insert log for owner of trade
                    $language = get_member_language($row_trade['trade_from_member_id']);
                    $topic = TRANSLATIONS[$language]['trade']['text_decline_trade'];
                    $text = TRANSLATIONS[$language]['trade']['text_log_trade_decline'].': '.$trade_card_name.' ('.TRANSLATIONS[$language]['trade']['text_log_trade_text_from'].' '.$trade_member_nick.') '.TRANSLATIONS[$language]['trade']['text_log_trade_text_against']. ' '.$own_card_name.' ('.TRANSLATIONS[$language]['trade']['text_log_trade_text_from'].' '.$trade_own_member_nick.')';
                    insert_log($topic, $text, $row_trade['trade_from_member_id']);

                    if ($trade_message != '') {
                        $topic .= ' ('.TRANSLATIONS[$language]['trade']['text_trade_message_answer'].')';
                        $text .= '<br /><br />'.TRANSLATIONS[$language]['trade']['text_trade_message'].':<br />'.$trade_message;
                    }

                    // send message to owner of trade
                    insert_message($_SESSION['member_id'], $row_trade['trade_from_member_id'], $topic, $text, 1);

                    // insert own log
                    $topic_own = TRANSLATIONS[$GLOBALS['language']]['trade']['text_decline_trade'];
                    $text_own = TRANSLATIONS[$GLOBALS['language']]['trade']['text_log_trade_decline'].': '.$trade_card_name.' ('.TRANSLATIONS[$GLOBALS['language']]['trade']['text_log_trade_text_from'].' '.$trade_member_nick.') '.TRANSLATIONS[$GLOBALS['language']]['trade']['text_log_trade_text_against']. ' '.$own_card_name.' ('.TRANSLATIONS[$GLOBALS['language']]['trade']['text_log_trade_text_from'].' '.$trade_own_member_nick.')';
                    insert_log($topic_own, $text_own, $member_id);

                    alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_trade_declined'], 'success');
                } elseif ($action == 'withdraw') {
                    // set trade card active
                    mysqli_query($link, "UPDATE member_cards
                                             SET member_cards_active = 1
                                             WHERE member_cards_id = '" . $row_trade['trade_to_member_card_id'] . "'
                                               AND member_cards_member_id = '" . $row_trade['trade_to_member_id'] . "'
                                             LIMIT 1")
                    OR die(mysqli_error($link));

                    // set own card active
                    mysqli_query($link, "UPDATE member_cards
                                             SET member_cards_active = 1
                                             WHERE member_cards_id = '" . $row_trade['trade_from_member_card_id'] . "'
                                               AND member_cards_member_id = '" . $member_id . "'
                                             LIMIT 1")
                    OR die(mysqli_error($link));

                    // delete trade
                    mysqli_query($link, "DELETE FROM trade
                                     WHERE trade_from_member_id = '" . $member_id . "'
                                       AND trade_id = '" . $trade_id . "'
                                     LIMIT 1")
                    OR die(mysqli_error($link));

                    alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_trade_withdrawn'], 'success');
                } else {
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_trade_dont_exists'], 'danger');
                }
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_trade_dont_exists'], 'danger');
            }
        }
    }

    if (isset($trade_box_type) && $trade_box_type == 'inbox') {
        $wherestring = "WHERE trade_to_member_id = '" . $member_id . "'";
    } elseif (isset($trade_box_type) && $trade_box_type == 'outbox') {
        $wherestring = "WHERE trade_from_member_id = '" . $member_id . "'";
    } else {
        $wherestring = "WHERE trade_to_member_id = '" . $member_id . "'";
    }

    $sql_trade = "SELECT trade_id, trade_from_member_id, trade_from_member_card_id, trade_to_member_id, trade_to_member_card_id, trade_text, trade_date
            FROM trade
            " . $wherestring . "
            ORDER BY trade_date ASC";
    $result_trade = mysqli_query($link, $sql_trade) OR die(mysqli_error($link));
    $count_trade = mysqli_num_rows($result_trade);
    ?>
    <div class="row">
        <div class="col col-12 mb-3">
            <div class="row">
                <div class="d-grid col col-6 mb-2">
                    <a href="<?php echo HOST_URL; ?>/trade/inbox"
                       class="btn btn-outline-info btn-sm <?php echo($trade_box_type == 'inbox' ? 'active' : ''); ?>"><i
                            class="fas fa-level-down-alt"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_inbox']; ?>
                    </a>
                </div>
                <div class="d-grid col col-6 mb-2">
                    <a href="<?php echo HOST_URL; ?>/trade/outbox"
                       class="btn btn-outline-info btn-sm <?php echo($trade_box_type == 'outbox' ? 'active' : ''); ?>"><i
                            class="fas fa-level-up-alt"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_outbox']; ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="col col-12">
            <?php
            if ($count_trade) {
                ?>
                <div class="table-responsive">
                    <table class="optional w-100" data-mobile-responsive="true">
                        <thead>
                        <tr>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="row m-0">
                        <?php
                        while ($row_trade = mysqli_fetch_assoc($result_trade)) {
                        ?>
                            <tr class="col-12 col-md-6 p-0">
                                <td class="card h-full mb-3 ms-md-2 me-md-2">
                                    <div class="card-header">
                                        <span class="d-none"><?php echo $row_trade['trade_date']; ?></span>
                                        <small><?php
                                            if ($trade_box_type == 'inbox') {
                                                echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_sender'].' '.get_member_link($row_trade['trade_from_member_id'], '', true);
                                            } else {
                                                echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_receiver'].' '.get_member_link($row_trade['trade_to_member_id'], '', true);
                                            }
                                            ?>
                                            <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_trade['trade_date']); ?>
                                        </small>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="row align-items-center">
                                            <div class="col-12 col-md-5">
                                                <small class="d-block text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_trade_card']; ?></small>
                                                <?php
                                                $card_id = ($trade_box_type == 'inbox' ? $row_trade['trade_from_member_card_id'] : $row_trade['trade_to_member_card_id']);
                                                $trade_card_carddeck_id = get_carddeck_id_from_member_cards_id($card_id);
                                                $trade_card_carddeck_name = get_carddeck_name_from_carddeck_id($trade_card_carddeck_id);
                                                $trade_card_number = get_card_number_from_member_cards_id($card_id);
                                                $filename = get_card($trade_card_carddeck_id, $trade_card_number, true);
                                                if ($trade_box_type == 'inbox' && TCG_SHOW_TRADE_FILTER == true) {
                                                    $filterclass = get_card_filter_class($trade_card_carddeck_id, $trade_card_number);
                                                } else {
                                                    $filterclass = '';
                                                }
                                                ?>
                                                <span
                                                        class="card-wrapper<?php echo $filterclass; ?>" <?php echo(file_exists('.' . substr($filename, strlen(HOST_URL))) ? 'style="background-image:url(' . $filename . ');"' : ''); ?>></span>
                                                <br /><small><?php echo $trade_card_carddeck_name.sprintf("%'.02d", $trade_card_number); ?></small>
                                            </div>
                                            <div class="col-12 col-md-2 my-2"><i class="fas fa-exchange-alt fs-1"></i></div>
                                            <div class="col-12 col-md-5">
                                                <small class="d-block text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_own_card']; ?></small>
                                                <?php
                                                $card_id = ($trade_box_type == 'inbox' ? $row_trade['trade_to_member_card_id'] : $row_trade['trade_from_member_card_id']);
                                                $trade_card_carddeck_id = get_carddeck_id_from_member_cards_id($card_id);
                                                $trade_card_carddeck_name = get_carddeck_name_from_carddeck_id($trade_card_carddeck_id);
                                                $trade_card_number = get_card_number_from_member_cards_id($card_id);
                                                $filename = get_card($trade_card_carddeck_id, $trade_card_number, true);
                                                ?>
                                                <span
                                                        class="card-wrapper" <?php echo(file_exists('.' . substr($filename, strlen(HOST_URL))) ? 'style="background-image:url(' . $filename . ');"' : ''); ?>></span>
                                                <br /><small><?php echo $trade_card_carddeck_name.sprintf("%'.02d", $trade_card_number); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                            <p class="card-text overflow-auto"><?php echo nl2br($row_trade['trade_text']); ?></p>
                                            <?php
                                            if ($trade_box_type == 'inbox') {
                                                ?>
                                                <form action="<?php echo HOST_URL; ?>/trade/inbox/<?php echo $row_trade['trade_id']; ?>" method="post">
                                                    <div class="row ms-auto me-auto">
                                                        <div class="col col-12 mb-3">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <textarea class="form-control" id="trade_message" name="trade_message" aria-describedby="ariaDescribedbyTradeText" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col col-12 col-xl-6 mb-4">
                                                            <div class="d-grid form-group">
                                                                <button type="submit" name="accept" class="btn badge bg-success py-2">
                                                                    <i class="fas fa-check"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_button_accept']; ?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col col-12 col-xl-6 mb-4">
                                                            <div class="d-grid form-group">
                                                                <button type="submit" name="decline" class="btn badge badge bg-danger py-2">
                                                                    <i class="fas fa-times"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_button_decline']; ?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <?php
                                            } else {
                                                ?>
                                                <a href="<?php echo HOST_URL; ?>/trade/<?php echo $row_trade['trade_id']; ?>/withdraw"
                                                   class="badge bg-primary p-2"><i
                                                            class="fas fa-undo"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_button_withdraw']; ?>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_no_trade_yet'], 'danger');
            }
            ?>
        </div>
    </div>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>