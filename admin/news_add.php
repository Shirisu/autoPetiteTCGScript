<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/addnews' => TRANSLATIONS[$GLOBALS['language']]['admin']['news_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['news_add_headline'],
    );
    breadcrumb($breadcrumb);

    title(TRANSLATIONS[$GLOBALS['language']]['admin']['news_add_headline']);

    if (isset($_POST['news_title']) && isset($_POST['news_text'])) {
        $news_title = mysqli_real_escape_string($link, strip_tags(trim($_POST['news_title'])));
        $news_text = mysqli_real_escape_string($link, strip_tags(trim($_POST['news_text'])));

        $sql_check_before_insert = "SELECT news_id
                                    FROM news
                                    WHERE news_title = '".$news_title."'
                                      AND news_text = '".$news_text."'
                                    LIMIT 1";
        $result_check_before_insert = mysqli_query($link, $sql_check_before_insert);
        if (!mysqli_num_rows($result_check_before_insert)) {
            mysqli_query($link, "
            INSERT INTO news
            (news_member_id, news_title, news_text, news_date)
            VALUES
            ('" . $_SESSION['member_id'] . "', '" . $news_title . "', '" . $news_text . "', '" . time() . "')")
            OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_news_add'], 'success');
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_duplicate_entry'], 'danger');
        }
    }

    ?>
    <form action="<?php echo HOST_URL; ?>/administration/addnews" method="post">
        <div class="row align-items-center">
            <div class="form-group col col-12 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyTitle"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_title']; ?></span>
                    </div>
                    <input type="text" class="form-control" id="news_title" name="news_title" aria-describedby="ariaDescribedbyTitle" maxlength="55" value="" required />
                </div>
            </div>
            <div class="form-group col col-12 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyText">Text</span>
                    </div>
                    <textarea class="form-control" id="news_text" name="news_text" aria-describedby="ariaDescribedbyText" rows="10" required></textarea>
                </div>
            </div>
            <div class="form-group col col-12">
                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_add']; ?></button>
            </div>
        </div>
    </form>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>