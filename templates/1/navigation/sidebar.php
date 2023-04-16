<?php
global $link;

if (!isset($_SESSION['member_rank'])) {
    if (isset($_GET['error'])) {
        $error = mysqli_real_escape_string($link, $_GET['error']);
        if ($error == 1) {
            ?>
            <div class="container">
                <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['text_error_login'], 'danger'); ?>
            </div>
            <?php
        }
    }
    ?>
    <div class="list-group-item list-group-item-action bg-light">
        <form id="loginform" action="<?php echo HOST_URL; ?>/login" method="post">
            <div class="form-group mb-2">
                <input type="text" class="form-control" id="member_nick" name="member_nick" placeholder="Nickname">
            </div>
            <div class="form-group mb-2">
                <input type="password" class="form-control" name="member_password" placeholder="<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_password']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
    <?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_lostpassword'], 'lostpassword', 'list-group-item list-group-item-action bg-light'); ?>
    <?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_register'], 'register', 'list-group-item list-group-item-action bg-light'); ?>
    <?php
} else {
    navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'], 'games', 'list-group-item list-group-item-action bg-light', 'fas fa-gamepad');

    // own cards
    $sql_cards = "SELECT member_cards_id
                  FROM member_cards
                  JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                  WHERE member_cards_member_id = '".$_SESSION['member_id']."'
                    AND member_cards_cat = '".MEMBER_CARDS_NEW."'
                    AND member_cards_active = 1
                    AND carddeck_active = 1";
    $result_cards = mysqli_query($link, $sql_cards) or die(mysqli_error($link));
    $count_cards = mysqli_num_rows($result_cards);
    if ($count_cards > 0) {
        $text_cards_count = '<span class="font-weight-bold">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].' ('.$count_cards.')</span>';
    } else {
        $text_cards_count = TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'];
    }
    navilink($text_cards_count, 'cards/new', 'list-group-item list-group-item-action bg-light', 'fas fa-images');

    // trades
    $sql_trades = "SELECT trade_id
                     FROM member, trade
                     WHERE trade_to_member_id = '".$_SESSION['member_id']."'
                       AND trade_from_member_id = member_id
                       AND trade_seen = 0
                     ORDER BY trade_id DESC";
    $result_trades = mysqli_query($link, $sql_trades) or die(mysqli_error($link));
    $count_trades = mysqli_num_rows($result_trades);
    if ($count_trades > 0) {
        $text_trades_count = '<span class="font-weight-bold">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_trade'].' ('.$count_trades.')</span>';
    } else {
        $text_trades_count = TRANSLATIONS[$GLOBALS['language']]['general']['text_trade'];
    }
    navilink($text_trades_count, 'trade', 'list-group-item list-group-item-action bg-light', 'fas fa-exchange-alt');

    // messages
    $sql_messages = "SELECT message_id
                     FROM member, message
                     WHERE message_receiver_member_id = '".$_SESSION['member_id']."'
                       AND message_sender_member_id = member_id
                       AND message_read = 0
                     ORDER BY message_id DESC";
    $result_messages = mysqli_query($link, $sql_messages) or die(mysqli_error($link));
    $count_messages = mysqli_num_rows($result_messages);
    if ($count_messages > 0) {
        $text_pn_count = '<span class="font-weight-bold">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'].' ('.$count_messages.')</span>';
        $icon = 'envelope-open-text';
    } else {
        $text_pn_count = TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'];
        $icon = 'envelope';
    }
    navilink($text_pn_count, 'message', 'list-group-item list-group-item-action bg-light', 'fas fa-'.$icon);

    navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'], 'memberarea', 'list-group-item list-group-item-action bg-light', 'fas fa-atom');

    // card deck main categories
    $sql_carddeck_cat = "SELECT carddeck_cat_id, carddeck_cat_name
                         FROM carddeck_cat
                         JOIN carddeck ON carddeck_cat = carddeck_cat_id
                          AND carddeck_active = 1
                         GROUP BY carddeck_cat_id
                         ORDER BY carddeck_cat_name ASC";
    $result_carddeck_cat = mysqli_query($link, $sql_carddeck_cat) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck_cat)) {
        $sql_all_carddeck = "SELECT carddeck_id
                             FROM carddeck
                             WHERE carddeck_active = 1";
        $result_all_carddeck = mysqli_query($link, $sql_all_carddeck) OR die(mysqli_error($link));
        $count_all_carddeck = mysqli_num_rows($result_all_carddeck);
        ?>
        <div class="sidebar-subheading"><i class="fas fa-folder-open"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']; ?></div>
        <?php
        while ($row_carddeck_cat = mysqli_fetch_assoc($result_carddeck_cat)) {
            $sql_carddeck = "SELECT carddeck_id
                             FROM carddeck
                             WHERE carddeck_active = 1
                               AND carddeck_cat = '".$row_carddeck_cat['carddeck_cat_id']."'";
            $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
            $count_carddeck = mysqli_num_rows($result_carddeck);

            navilink($row_carddeck_cat['carddeck_cat_name'].' ('.$count_carddeck.')', 'carddecks/'.$row_carddeck_cat['carddeck_cat_id'], 'list-group-item list-group-item-action bg-light');
        }
        navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_all'].' ('.$count_all_carddeck.')', 'carddecks/all', 'list-group-item list-group-item-action bg-light');
    }
    $sql_unreleased_carddeck = "SELECT carddeck_id
                                FROM carddeck
                                WHERE carddeck_active = 0";
    $result_unreleased_carddeck = mysqli_query($link, $sql_unreleased_carddeck) OR die(mysqli_error($link));
    $count_unreleased_carddeck = mysqli_num_rows($result_unreleased_carddeck);
    navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks_unreleased'].' ('.$count_unreleased_carddeck.')', 'carddecks/unreleased', 'list-group-item list-group-item-action bg-light', 'fas fa-folder-plus');

    navilink('Member', 'member', 'list-group-item list-group-item-action bg-light', 'fas fa-address-book');
}


// online member
$sql_member_online = "SELECT member_id, member_nick, member_rank, member_online.*
                      FROM member_online
                      JOIN member ON member_id = member_online_member_id
                      ORDER BY member_nick ASC;";
$result_member_online = mysqli_query($link, $sql_member_online) OR die(mysqli_error($link));
$count_member = mysqli_num_rows($result_member_online);
?>
<div class="sidebar-subheading"><i class="fas fa-users"></i> Online: <?php echo $count_member; ?></div>
<?php
if (isset($_SESSION['member_rank'])) {
    while ($row_member_online = mysqli_fetch_assoc($result_member_online)) {
        echo get_member_link($row_member_online['member_id'], "useron list-group-item list-group-item-action bg-light", true);
    }
}

// show admin link
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 4)) {
    navilink('Administration', 'administration', 'list-group-item list-group-item-action bg-light', 'fas fa-user-cog');
}
?>