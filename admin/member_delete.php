<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    if (isset($member_id)) {
        $sql = "SELECT member_nick, member_active
                FROM member
                WHERE member_id = '" . $member_id . "'
                  AND member_active != 4
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);

            $breadcrumb = array(
                '/' => 'Home',
                '/administration' => 'Administration',
                '/administration/editmember/all' => TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline'],
                '/administration/deletemember/'.$member_id => TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['member_delete_headline'],
            );
            breadcrumb($breadcrumb);
            title(TRANSLATIONS[$GLOBALS['language']]['admin']['member_delete_headline']);

            if (isset($_POST['member_id'])) {
                // set cards to active if they are pending in trade with this member
                $sql_trade = "SELECT trade_from_member_id, trade_from_member_card_id, trade_to_member_id, trade_to_member_card_id
                              FROM trade
                              WHERE (trade_from_member_id = '".$member_id."'
                                  OR trade_to_member_id = '".$member_id."')";
                $result_trade = mysqli_query($link, $sql_trade) OR die(mysqli_error($link));
                $count_trade = mysqli_num_rows($result_trade);
                if ($count_trade) {
                    while ($row_trade = mysqli_fetch_assoc($result_trade)) {
                        mysqli_query($link, "UPDATE member_cards
                                             SET member_cards_active = 1
                                             WHERE member_cards_id = '".$row_trade['trade_from_member_card_id']."'
                                               AND member_cards_member_id = '".$row_trade['trade_from_member_id']."'
                                             LIMIT 1")
                        OR die(mysqli_error($link));
                        mysqli_query($link, "UPDATE member_cards
                                             SET member_cards_active = 1
                                             WHERE member_cards_id = '".$row_trade['trade_to_member_card_id']."'
                                               AND member_cards_member_id = '".$row_trade['trade_to_member_id']."'
                                             LIMIT 1")
                        OR die(mysqli_error($link));
                    }
                }

                // delete trade from member
                mysqli_query($link, "DELETE FROM trade
                                     WHERE (trade_from_member_id = '".$member_id."'
                                         OR trade_to_member_id = '".$member_id."')")
                OR die(mysqli_error($link));

                // delete cards from member
                mysqli_query($link, "DELETE FROM member_cards
                                     WHERE member_cards_member_id = '".$member_id."'")
                OR die(mysqli_error($link));

                // delete master from member
                mysqli_query($link, "DELETE FROM member_master
                                     WHERE member_master_member_id = '".$member_id."'")
                OR die(mysqli_error($link));

                // delete log from member
                mysqli_query($link, "DELETE FROM member_log
                                     WHERE member_log_member_id = '".$member_id."'")
                OR die(mysqli_error($link));

                // delete wishlist from member
                mysqli_query($link, "DELETE FROM member_wishlist
                                     WHERE member_wishlist_member_id = '".$member_id."'")
                OR die(mysqli_error($link));

                // delete update from member
                mysqli_query($link, "DELETE FROM member_update
                                     WHERE member_update_member_id = '".$member_id."'")
                OR die(mysqli_error($link));

                // delete message from member
                mysqli_query($link, "DELETE FROM message
                                     WHERE (message_sender_member_id = '".$member_id."'
                                         OR message_receiver_member_id = '".$member_id."')")
                OR die(mysqli_error($link));

                // reset member data - id and name needed for carddecks
                mysqli_query($link, "UPDATE member
                             SET member_active = 4,
                                 member_email = '',
                                 member_level = 1,
                                 member_cards = 0,
                                 member_master = 0,
                                 member_wish = 0,
                                 member_currency = 0,
                                 member_text = ''
                             WHERE member_id = '".$member_id."'
                             LIMIT 1")
                OR die(mysqli_error($link));

                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_member_deleted'], 'success');
            } else {
                ?>
                <div class="row">
                    <div class="col col-12 mt-2">
                        <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_irreversible'] . ' ' . TRANSLATIONS[$GLOBALS['language']]['general']['hint_delete_all_data'], 'warning'); ?>
                    </div>
                    <div class="col col-12 mt-2">
                        <form action="<?php echo HOST_URL; ?>/administration/deletemember/<?php echo $member_id; ?>" method="post">
                            <div class="row">
                                <div class="form-group col col-12 mb-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"
                                                  id="ariaDescribedbyNickname"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_nickname']; ?></span>
                                        </div>
                                        <input type="text" disabled class="form-control"
                                               aria-describedby="ariaDescribedbyNickname" required
                                               value="<?php echo $row['member_nick']; ?>"/>
                                        <input type="hidden" class="form-control" id="member_id" name="member_id"
                                               value="<?php echo $member_id; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group col col-12 mb-2">
                                    <button type="submit"
                                            class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_delete_member']; ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_data'], 'danger');
        }
    }
} else {
    show_no_access_message();
}
?>