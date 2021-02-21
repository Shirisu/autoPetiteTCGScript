<?php
global $link;

if (!isset($_SESSION['member_id'])) {
    if (isset($_GET['error'])) {
        $error = mysqli_real_escape_string($link, $_GET['error']);
        if ($error == 1) {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['text_error_login'], "danger");
        }
    }
    ?>
    <div class="list-group-item list-group-item-action bg-light">
        <form id="loginform" action="<?php echo HOST_URL; ?>/login" method="post">
            <div class="form-group">
                <input type="text" class="form-control" id="member_nick" name="member_nick" placeholder="Nickname">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="member_password" placeholder="<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_password']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
    <?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_lostpassword'],'lostpassword'); ?>
    <?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_register'],'register'); ?>
    <?php
} else {
    navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'],'games','gamepad');

    // own cards
    $sql_cards = "SELECT member_cards_id
                  FROM member_cards
                  WHERE member_cards_member_id = '".$_SESSION['member_id']."'
                    AND member_cards_cat = 1
                    AND member_cards_active = 1";
    $result_cards = mysqli_query($link, $sql_cards) or die(mysqli_error($link));
    $count_cards = mysqli_num_rows($result_cards);
    if ($count_cards > 0) {
        $text_cards_count = '<span class="font-weight-bold">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].' ('.$count_cards.')</span>';
    } else {
        $text_cards_count = TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'];
    }
    navilink($text_cards_count,'cards/new','images');

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
    navilink($text_trades_count,'trade','exchange-alt');

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
    navilink($text_pn_count,'message',$icon);


    navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'],'memberarea','atom');

    // card deck main categories
    $sql_carddeck_cat = "SELECT carddeck_cat_id, carddeck_cat_name
                         FROM carddeck_cat
                         ORDER BY carddeck_cat_name ASC";
    $result_carddeck_cat = mysqli_query($link, $sql_carddeck_cat) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck_cat)) {
        ?>
        <div class="sidebar-subheading"><i class="fas fa-folder-open"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']; ?></div>
        <?php
        while ($row_carddeck_cat = mysqli_fetch_assoc($result_carddeck_cat)) {
            navilink($row_carddeck_cat['carddeck_cat_name'],'carddecks/'.$row_carddeck_cat['carddeck_cat_id']);
        }
        navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_all'],'carddecks/all');
    }
}


// online member
$sql_member_online = "SELECT member.member_id, member.member_nick, member.member_rank, member_online.*
                      FROM member, member_online
                      WHERE member.member_id = member_online.member_id
                      ORDER BY member_nick ASC;";
$result_member_online = mysqli_query($link, $sql_member_online) OR die(mysqli_error($link));
$count_member = mysqli_num_rows($result_member_online);
?>
<div class="sidebar-subheading"><i class="fas fa-users"></i> Online: <?php echo $count_member; ?></div>
<?php
if (isset($_SESSION['member_rank'])) {
    require_once("header_onlinemember.php");
}

// show admin link
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 4)) {
    navilink('Administration','administration','user-cog');
}
?>