<?php
$breadcrumb = array(
    '/' => 'Home',
    '/lostpassword' => TRANSLATIONS[$GLOBALS['language']]['lostpassword']['headline'],
);
breadcrumb($breadcrumb);

title(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['headline']);

if (isset($_POST['nickname']) && isset($_POST['email'])) {
    global $link;
    $password = passwordgenerator();
    $nickname = mysqli_real_escape_string($link, $_POST['nickname']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    if ($nickname != '' && $email != '') {
        $sql = "SELECT member_nick, member_email
                FROM member
                WHERE member_nick = '".$nickname."'
                  AND member_email = '".$email."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            $sender = TCG_NAME.' Admin';
            $sendermail = TCG_META_OWNER;
            $receiver = $email;

            $subject = TRANSLATIONS[$GLOBALS['language']]['lostpassword']['mail_subject'];
            $text = 'Hi $nickname!
'.TRANSLATIONS[$GLOBALS['language']]['lostpassword']['mail_part_1'].' '.$password.'

'.TRANSLATIONS[$GLOBALS['language']]['lostpassword']['mail_part_2'].'

'.TRANSLATIONS[$GLOBALS['language']]['general']['text_email_enclosure'];
            require_once('./inc/class.passwordhash_tcg.php');
            $password_hashed = create_hash_for_tcg($password);

            mail($receiver, $subject, $text,
                "From: $sender <$sendermail>");
            mysqli_query($link, "UPDATE member SET member_password = '".$password_hashed."' WHERE member_nick = '".$nickname."' AND member_email = '".$email."' LIMIT 1") OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['hint_success'], 'success');
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['hint_notmatched'], 'danger');
        }
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['hint_empty'], 'danger');
    }
}
?>
<div class="row">
    <div class="col col-12 mb-3">
        <?php
        echo TRANSLATIONS[$GLOBALS['language']]['lostpassword']['intro'];
        ?>
    </div>
    <div class="col col-12 col-md-6 mb-3">
        <form action="<?php echo HOST_URL; ?>/lostpassword" method="post">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyNickname"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_nickname']; ?></span>
                    </div>
                    <input type="text" class="form-control" id="nickname" name="nickname" aria-describedby="ariaDescribedbyNickname" required>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyEmail"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_email']; ?></span>
                    </div>
                    <input type="email" class="form-control" name="email" aria-describedby="ariaDescribedbyEmail" required>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['lostpassword']['button']; ?></button>
            </div>
        </form>
    </div>
    <div class="col col-12 col-md-6">
        <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['hint_info']); ?>
    </div>
</div>