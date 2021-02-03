<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/addnews' => TRANSLATIONS[$GLOBALS['language']]['admin']['news_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['news_add_headline'],
    );
    breadcrumb($breadcrumb);

    title(TRANSLATIONS[$GLOBALS['language']]['admin']['news_add_headline']);

    if (isset($_POST['news_text'])) {
        $news_text = mysqli_real_escape_string($link, strip_tags(trim($_POST['news_text'])));

        mysqli_query($link, "
            INSERT INTO news
            (news_member_id, news_text, news_date)
            VALUES
            ('".$_SESSION['member_id']."', '".$news_text."', '".time()."')")
        OR die(mysqli_error($link));

        alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_news_add'], 'success');
    }

    ?>
    <form action="/administration/addnews" method="post">
        <div class="row align-items-center">
            <div class="form-group col mb-2">
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
}
?>