<?php
$password = passwordgenerator();

if(isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
    $text_lostpassword = "Lost password";
    $text_intro = 'Forgotten your password? Then request a new one!';
    $text_hint_success = 'You\'ve gotten <b>a new password</b> via email!<br />
  							Please also check your <b>spam folder</b>.<br /><br />
                If the email has not arrived after 10 minutes, please write an <a href="mailto:'.TCG_META_OWNER.'?Subject=Lost password"><b>email to the admin</b></a>.';
    $text_hint_notmatched = 'The nickname and email do not match!';
    $text_hint_empty = 'You must enter your nickname + email!';
    $text_button = 'Request new password!';
    $text_hint_info = '<b>P.S.:</b> It may be that at hotmail addresses (@hotmail or @live) an error occurs.<br />
             If this happens, please write an <a href="mailto:'.TCG_META_OWNER.'?Subject=Lost password"><b>email to the admin</b></a>!';
} else {
    $text_lostpassword = "Passwort vergessen";
    $text_intro = 'Du hast dein Passwort vergessen? Dann lasse dir ein neues zuschicken!';
    $text_hint_success = 'Dir wurde ein <b>neues Passwort</b> zugeschickt!<br />
  							Bitte &uuml;berpr&uuml;fe auch deinen <b>Spam-Ordner</b>.<br /><br />
  							Sollte die Email nach <b>10 Minuten</b> immer noch nicht ankommen sein, dann schreib bitte eine <a href="mailto:'.TCG_META_OWNER.'?Subject=Passwort vergessen"><b>email an den Admin</b></a>.';
    $text_hint_notmatched = 'Der Nickname und die email stimmen nicht &uuml;berein!';
    $text_hint_empty = 'Du musst deinen Nicknamen + email eingeben!';
    $text_button = 'Neues Passwort anfordern!';
    $text_hint_info = '<b>P.S.:</b> Es kann sein, dass bei Hotmail-Adressen (@hotmail bzw @live) ein Fehler auftritt.<br />
             Sollte dies passieren, dann schreib bitte eine <a href="mailto:'.TCG_META_OWNER.'?Subject=Passwort vergessen"><b>Email an den Admin</b></a>!';
}

title($text_lostpassword);

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
            $empfaenger = $email;

            if(isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
                $betreff = "Password request on ".TCG_NAME;
                $text = "Hi $nick!
You have requested a new password ".HOST_URL.".

Your new password: ".$password."

You can now login with your username and the new password.
Please remember to change your password immediately afterwards!

Greetings,
das ".TCG_NAME."-Team

".HOST_URL."
trading card game

** This message has been generated automatically! ** ";
            } else {
                title("Passwort vergessen");
                $betreff = "Passwort Anforderung auf ".TCG_NAME;
                $text = "Hi $nick!
Du hast ein neues Passwort auf ".HOST_URL." angefordert.

Dein neues Passwort: ".$password."

Du kannst dich nun mit deinem Nicknamen und neuem Passwort einloggen.
Bitte denke daran, dein Passwort danach sofort zu &auml;ndern!

Liebe Gr&uuml;&szlig;e,
das ".TCG_NAME."-Team

".HOST_URL."
trading card game

** Diese Mail wurde automatisch erzeugt! ** ";
            }

            require_once('./inc/class.passwordhash_tcg.php');
            $password_hashed = create_hash_for_tcg($password);

            mail($empfaenger, $betreff, $text,
                "From: $sender <$sendermail>");
            mysqli_query($link, "UPDATE member SET member_password = '".$password_hashed."' WHERE member_nick = '".$nick."' AND member_email = '".$email."' LIMIT 1");

            alert_box($text_hint_success, "success");
        } else {
            alert_box($text_hint_notmatched, "danger");
        }
    } else {
        alert_box($text_hint_empty, "danger");
    }
}
?>
<div class="row">
    <div class="col col-12 mb-3">
        <?php
        echo $text_intro;
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
            <button type="submit" class="btn btn-primary"><?php echo $text_button; ?></button>
        </form>
    </div>
    <div class="col col-12 col-md-6">
        <?php alert_box($text_hint_info); ?>
    </div>
</div>