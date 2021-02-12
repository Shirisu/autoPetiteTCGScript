<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/memberarea' => TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'],
        '/memberarea/changeprofile' => TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_change'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_change']);

    $member_id = $_SESSION['member_id'];

    $sql = "SELECT member_email, member_language, member_text
            FROM member
            WHERE member_id = '".$member_id."'
            LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);
        $member_language = $row['member_language'];
        $member_email = $row['member_email'];
        $member_text = $row['member_text'];

        if (isset($_POST['member_language']) && isset($_POST['member_email'])) {
            $member_email = mysqli_real_escape_string($link, trim($_POST['member_email']));
            $member_language = mysqli_real_escape_string($link, trim($_POST['member_language']));
            $member_text = trim($_POST['member_text']);
            $member_text_to_save = mysqli_real_escape_string($link, strip_tags(trim($_POST['member_text'])));

            mysqli_query($link, "UPDATE member
                                 SET member_email = '".$member_email."',
                                     member_language = '".$member_language."',
                                     member_text = '".$member_text_to_save."'
                                 WHERE member_id = ".$member_id."
                                 LIMIT 1")
            OR die(mysqli_error($link));

            $_SESSION['language'] = $member_language;

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_save'], 'success');
        }
        ?>
        <div class="row">
            <div class="col">
                <form action="<?php echo HOST_URL; ?>/memberarea/changeprofile" method="post">
                    <div class="row align-items-center">
                        <div class="form-group col col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ariaDescribedbyLanguage"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language']; ?></span>
                                </div>
                                <select class="custom-select" id="member_language" name="member_language" aria-describedby="ariaDescribedbyLanguage" required>
                                    <option selected disabled hidden value=""></option>
                                    <option value="en" <?php if ($member_language == 'en') { ?>selected="selected"<?php } ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language_en']; ?></option>
                                    <option value="de" <?php if ($member_language == 'de') { ?>selected="selected"<?php } ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language_de']; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ariaDescribedbyEmail"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_email']; ?></span>
                                </div>
                                <input type="text" class="form-control" aria-describedby="ariaDescribedbyEmail"
                                       id="member_email" name="member_email"
                                       pattern="^[a-zA-Z0-9_\+-]+(\.[a-zA-Z0-9_\+-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.([a-zA-Z]{2,4})$"
                                       value="<?php echo $member_email; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group col col-12 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ariaDescribedbyText">Text</span>
                                </div>
                                <textarea class="form-control" id="member_text" name="member_text"
                                      aria-describedby="ariaDescribedbyText" rows="10"
                                      required><?php echo $member_text; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group col col-12">
                            <button type="submit"
                                    class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_save']; ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
} else {
    show_no_access_message();
}
?>