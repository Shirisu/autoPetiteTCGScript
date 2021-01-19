<?php
global $link;
/**
 * the quick navigation is only shown on tablet and desktop
 * on mobile it's nested in the main navigation
 */
// Login or Memberarea
if (!isset($_SESSION['member_id'])) {
    if (isset($_GET['error'])) {
        $error = mysqli_real_escape_string($link, $_GET['error']);
        if ($error == 1) {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['text_error_login'], "danger");
        }
    }
    ?>
    <div class="row">
        <div class="col">
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
    </div>
    <div class="row mt-2">
        <div class="col col-12"><?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_lostpassword'],'lostpassword'); ?></div>
        <div class="col col-12"><?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_register'],'register'); ?></div>
    </div>
    <?php
} else {
    title('Quicknavigation');
    ?>
    <div class="row">
        <?php
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
        } else {
            $text_pn_count = TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'];
        }
        ?>
        <div class="col col-6">
            <a href="<?php echo HOST_URL; ?>/tcg/message"><?php echo $text_pn_count; ?></a>
        </div>

        <?php

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
        ?>
        <div class="col col-6">
            <a href="<?php echo HOST_URL; ?>/tcg/userarea/cards/new"><?php echo $text_cards_count; ?></a>
        </div>

    </div>
    <?php
}
?>