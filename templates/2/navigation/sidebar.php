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
    <li class="no-hover">
        <div class="nav-link">
            <form id="loginform" action="<?php echo HOST_URL; ?>/login" method="post">
                <div class="form-group mb-2">
                    <input type="text" class="form-control" id="member_nick" name="member_nick" placeholder="Nickname">
                </div>
                <div class="form-group mb-2">
                    <input type="password" class="form-control" name="member_password" placeholder="<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_password']; ?>">
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </li>
    <li>
        <?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_lostpassword'], 'lostpassword', 'nav-link'); ?>
    </li>
    <li>
        <?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_register'], 'register', 'nav-link'); ?>
    </li>
    <?php
} else {
    ?>
    <li>
        <?php
        navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'], 'games', 'nav-link', 'fas fa-gamepad');
        ?>
    </li>
    <?php

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
    ?>
    <li>
        <?php
        navilink($text_cards_count, 'cards/new', 'nav-link', 'fas fa-images');
        ?>
    </li>
    <?php

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
    ?>
    <li>
        <?php
        navilink($text_trades_count, 'trade', 'nav-link', 'fas fa-exchange-alt');
        ?>
    </li>
    <?php

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
    ?>
    <li>
        <?php
        navilink($text_pn_count, 'message', 'nav-link', 'fas fa-'.$icon);
        ?>
    </li>

    <li>
        <?php
        navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'], 'memberarea', 'nav-link', 'fas fa-atom');
        ?>
    </li>

    <li>
        <?php
        navilink('Member', 'member', 'nav-link', 'fas fa-address-book');
        ?>
    </li>
    <?php

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
        <li>
            <div class="nav-link"><i class="fas fa-folder-open"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']; ?></div>
        </li>
        <?php
        while ($row_carddeck_cat = mysqli_fetch_assoc($result_carddeck_cat)) {
            $sql_carddeck = "SELECT carddeck_id
                             FROM carddeck
                             WHERE carddeck_active = 1
                               AND carddeck_cat = '".$row_carddeck_cat['carddeck_cat_id']."'";
            $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
            $count_carddeck = mysqli_num_rows($result_carddeck);

            navilink($row_carddeck_cat['carddeck_cat_name'].' ('.$count_carddeck.')', 'carddecks/'.$row_carddeck_cat['carddeck_cat_id'], 'nav-link');
        }
        navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_all'].' ('.$count_all_carddeck.')', 'carddecks/all', 'nav-link');
    }
    $sql_unreleased_carddeck = "SELECT carddeck_id
                                    FROM carddeck
                                    WHERE carddeck_active = 0";
    $result_unreleased_carddeck = mysqli_query($link, $sql_unreleased_carddeck) OR die(mysqli_error($link));
    $count_unreleased_carddeck = mysqli_num_rows($result_unreleased_carddeck);
    ?>
    <li>
        <?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks_unreleased'].' ('.$count_unreleased_carddeck.')', 'carddecks/unreleased', 'nav-link', 'fas fa-folder-plus'); ?>
    </li>
    <?php
}

// show admin link
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 4)) {
    ?>
    <li>
        <?php
        navilink('Administration', 'administration', 'nav-link', 'fas fa-user-cog');
        ?>
    </li>
    <?php
}
?>