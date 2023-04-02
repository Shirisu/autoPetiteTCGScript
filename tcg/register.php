<?php
require_once('./inc/class.passwordhash_tcg.php');

$breadcrumb = array(
    '/' => 'Home',
    '/register' => TRANSLATIONS[$GLOBALS['language']]['general']['text_register'],
);
breadcrumb($breadcrumb);

title(TRANSLATIONS[$GLOBALS['language']]['general']['text_register']);

if (isset($_POST['nickname'])) {
    if (!empty($_POST['nickname']) && isset($_POST['email']) && !empty($_POST['email']) &&
        isset($_POST['language'])
    ) {
        global $link;
        $nickname = mysqli_real_escape_string($link, trim($_POST['nickname']));
        $sql_name = "SELECT member_nick
                     FROM member
                     WHERE member_nick LIKE '".$nickname."'
                       AND member_active != 4
                     LIMIT 1;";
        $result_name = mysqli_query($link, $sql_name) OR die(mysqli_error($link));

        $email = mysqli_real_escape_string($link, trim($_POST['email']));
        $sql_email = "SELECT member_email
                      FROM member
                      WHERE member_email LIKE '".$email."'
                       AND member_active != 4
                      LIMIT 1;";
        $result_email = mysqli_query($link, $sql_email) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_name)) {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['register']['hint_nameinuse'], 'danger');
        } elseif (mysqli_num_rows($result_email)) {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['register']['hint_emailinuse'], 'danger');
        } else {
            $password = trim($_POST['password']);
            $password2 = trim($_POST['password2']);

            if ($password !== $password2) {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['register']['hint_nomatch'], 'danger');
            } else {
                $activationcode = passwordgenerator();
                $password_hashed = create_hash_for_tcg($password);
                $language = mysqli_real_escape_string($link, $_POST['language']);

                mysqli_query($link, "
                    INSERT INTO member
                    (member_nick, member_password, member_email, member_register, member_language, member_ip)
                    VALUES
                    ('".$nickname."', '".$password_hashed."', '".$email."', '".time()."', '".$language."', '".ip()."')")
                OR die(mysqli_error($link));
                $new_member_id = mysqli_insert_id($link);

                mysqli_query($link, "INSERT INTO member_activation
                             (member_activation_member_id,member_activation_code)
                             VALUES
                             (".($new_member_id).",'".$activationcode."')")
                OR die(mysqli_error($link));

                $sender = TCG_NAME.' Admin';
                $sendermail = TCG_META_OWNER;
                $receiver = $email;

                $subject = TRANSLATIONS[$GLOBALS['language']]['register']['mail_subject'];
                $text = 'Hi '.$nickname.'!
'.TRANSLATIONS[$GLOBALS['language']]['register']['mail_part_1'].' '.HOST_URL_PROTOCOL.HOST_URL_PLAIN.'/activation/'.$activationcode.'

'.TRANSLATIONS[$GLOBALS['language']]['general']['text_email_enclosure'];

                mail($receiver, mb_encode_mimeheader($subject,'UTF-8','Q'), $text,
                    "From: $sender <$sendermail>");

                alert_box(TRANSLATIONS[$GLOBALS['language']]['register']['hint_success'], 'success');
            }
        }
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['register']['hint_empty'], 'danger');
    }
}
?>

<div class="row">
    <div class="col col-12">
        <?php
        if (isset($_SESSION['member_rank'])) {
            echo TRANSLATIONS[$GLOBALS['language']]['register']['already_registered'];
        } else {
        ?>
            <form action="<?php echo HOST_URL; ?>/register" method="post">
                <div class="row">
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <span class="input-group-text" id="ariaDescribedbyNickname"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_nickname']; ?></span>
                            <input type="text" class="form-control" id="nickname" name="nickname" aria-describedby="ariaDescribedbyNickname" required />
                        </div>
                        <small id="ariaDescribedbyNickname" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_nickname']; ?></small>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <span class="input-group-text" id="ariaDescribedbyEmail"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_email']; ?></span>
                            <input type="email" class="form-control" id="email" name="email" pattern="^[a-zA-Z0-9_\+-]+(\.[a-zA-Z0-9_\+-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.([a-zA-Z]{2,4})$" autocomplete="off" aria-describedby="ariaDescribedbyEmail" required />
                        </div>
                        <small id="ariaDescribedbyEmail" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_email']; ?></small>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <span class="input-group-text" id="ariaDescribedbyPassword"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_password']; ?></span>
                            <input type="password" class="form-control" id="password" name="password" pattern="(?=.{8,}$)((?=.*[0-9])(?=.*[!?\+\-_#*&$§%]))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*" aria-describedby="ariaDescribedbyPassword" required />
                        </div>
                        <small id="ariaDescribedbyPassword" class="form-text text-muted">
                            <?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_password']; ?>
                            <ul>
                                <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_password_rule_1']; ?></li>
                                <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_password_rule_2']; ?></li>
                                <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_password_rule_3']; ?></li>
                                <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_password_rule_4']; ?></li>
                                <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_password_rule_5']; ?></li>
                            </ul>
                        </small>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <span class="input-group-text" id="ariaDescribedbyPasswordRepeat"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['password_repeat']; ?></span>
                            <input type="password" class="form-control" id="password2" name="password2" pattern="(?=.{8,}$)((?=.*[0-9])(?=.*[!?\+\-_#*&$§%]))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*" aria-describedby="ariaDescribedbyPasswordRepeat" required />
                        </div>
                        <small id="ariaDescribedbyPasswordRepeat" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_password2']; ?></small>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <span class="input-group-text" id="ariaDescribedbyLanguage"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language']; ?></span>
                            <select class="selectpicker" data-live-search="true" data-size="10" id="language" name="language" aria-describedby="ariaDescribedbyLanguage" required>
                                <option selected disabled hidden value=""></option>
                                <option value="en"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language_en']; ?></option>
                                <option value="de"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language_de']; ?></option>
                            </select>
                        </div>
                        <small id="ariaDescribedbyLanguage" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['hint_language']; ?></small>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_register']; ?></button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col col-12">
                    <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['register']['text_mistake']); ?>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
