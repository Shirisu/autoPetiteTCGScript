<?php
global $link;
/**
 * the quick navigation is only shown on tablet and desktop
 * on mobile it's nested in the main navigation
 */
if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
    $text_password = 'Password';
    $text_lostpassword = 'Lost Password?';
    $text_register = 'Register';
    $text_error_login = 'Wrong data - please try again.';
} else {
    $text_password = 'Passwort';
    $text_lostpassword = 'Passwort vergessen?';
    $text_register = 'Registrierung';
    $text_error_login = 'Falsche Daten - bitte versuche es noch einmal.';
}

// Login or Memberarea
if (!isset($_SESSION['member_id'])) {
    if (isset($_GET['error'])) {
        $error = mysqli_real_escape_string($link, $_GET['error']);
        if ($error == 1) {
            alert_box($text_error_login, "danger");
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
                    <input type="password" class="form-control" name="member_password" placeholder="<?php echo $text_password; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col col-12"><?php navilink($text_lostpassword,'tcg/lostpassword'); ?></div>
        <div class="col col-12"><?php navilink($text_register,'tcg/register'); ?></div>
    </div>
    <?php
}
?>