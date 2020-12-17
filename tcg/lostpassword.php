<?php
$password = passwordgenerator();

title(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['headline']);

if(isset($_POST['nick']) && isset($_POST['email'])) {
    global $link;
    $nick = mysqli_real_escape_string($link, $_POST['nick']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    if($nick != '' && $email != '') {
        $sqlid = "SELECT member_nick, member_email
		          FROM member
                  WHERE member_nick = '".$nick."'
                    AND member_email = '".$email."'
                  LIMIT 1";
        $resultid = mysqli_query($link, $sqlid);
        if(mysqli_num_rows($resultid)) {
            $sender = TCG_NAME.' Admin';
            $sendermail = TCG_META_OWNER;
            $receiver = $email;

            $subject = TRANSLATIONS[$GLOBALS['language']]['lostpassword']['mail_subject'];
            $text = "Hi $nick!
".TRANSLATIONS[$GLOBALS['language']]['lostpassword']['mail_part_1']." ".$password."

".TRANSLATIONS[$GLOBALS['language']]['lostpassword']['mail_part_2'];
            require_once('./inc/class.passwordhash_tcg.php');
            $password_hashed = create_hash_for_tcg($password);

            mail($receiver, $subject, $text,
                "From: $sender <$sendermail>");
            mysqli_query($link, "UPDATE member SET member_password = '".$password_hashed."' WHERE member_nick = '".$nick."' AND member_email = '".$email."' LIMIT 1");

            alert_box(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['hint_success'], "success");
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['hint_notmatched'], "danger");
        }
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['hint_empty'], "danger");
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
        <form action="/tcg/lostpassword" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="nick" placeholder="Nickname">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['lostpassword']['button']; ?></button>
        </form>
    </div>
    <div class="col col-12 col-md-6">
        <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['lostpassword']['hint_info']); ?>
    </div>
</div>