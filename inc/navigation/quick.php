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
        <div class="col col-12"><?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_lostpassword'],'tcg/lostpassword'); ?></div>
        <div class="col col-12"><?php navilink(TRANSLATIONS[$GLOBALS['language']]['general']['text_register'],'tcg/register'); ?></div>
    </div>
    <?php
}
?>