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
    $sql = "SELECT *
    FROM member, message
    WHERE message_to_member_id = '".$_SESSION['member_id']."'
    AND message_from_member_id = member_id
    AND message_read = 0
    ORDER BY message_id DESC";
    $result = mysqli_query($link, $sql) or die(mysqli_error($link));
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $text_pn_count = '<span class="font-weight-bold">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'].' ('.$count.')</span>';
        $icon = 'envelope-open-text';
    } else {
        $text_pn_count = TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'];
        $icon = 'envelope';
    }
    navilink($text_pn_count,'message',$icon);

    $sql = "SELECT *
        FROM member_cards
        WHERE member_cards_member_id = '".$_SESSION['member_id']."'
         AND member_cards_cat = 1
         AND member_cards_active = 1";
    $result = mysqli_query($link, $sql) or die(mysqli_error($link));
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $text_cards_count = '<span class="font-weight-bold">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].' ('.$count.')</span>';
    } else {
        $text_cards_count = TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'];
    }
    navilink($text_cards_count,'cards/new','images');
}

$sql_member_online = "SELECT member.member_id, member.member_nick, member.member_rank, member_online.*
                      FROM member,member_online
                      WHERE member.member_id = member_online.member_id
                      ORDER BY member_nick ASC;";
$result_member_online = mysqli_query($link, $sql_member_online) OR die(mysqli_error($link));
$count_member = mysqli_num_rows($result_member_online);

?>
<div class="sidebar-subheading"><i class="fas fa-user"></i> Online: <?php echo $count_member; ?></div>
<?php
if (isset($_SESSION['member_id'])) {
    require_once("header_onlinemember.php");
}

// show admin link
if(isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 4)) {
    navilink('Administration','administration','user-cog');
}
?>