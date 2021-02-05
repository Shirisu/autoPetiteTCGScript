<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    global $link;
    if (isset($newsId)) {
        $sql = "SELECT news_text, news_cardupdate_id
                FROM news
                WHERE news_id = '".$newsId."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $row = mysqli_fetch_assoc($result);

        $news_text = $row['news_text'];
        if (isset($_POST['news_text'])) {
            $news_text = trim($_POST['news_text']);
            $news_text_to_save = mysqli_real_escape_string($link, strip_tags(trim($_POST['news_text'])));

            mysqli_query($link, "UPDATE news
                         SET news_text = '".$news_text_to_save."'
                         WHERE news_id = ".$newsId."
                         LIMIT 1") OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_save'], 'success');
        }

        $breadcrumb = array(
            '/' => 'Home',
            '/administration' => 'Administration',
            '/administration/editnews' => TRANSLATIONS[$GLOBALS['language']]['admin']['news_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['news_edit_headline'],
            '/administration/editnews/'.$newsId => $newsId,
        );
        breadcrumb($breadcrumb);

        title(TRANSLATIONS[$GLOBALS['language']]['admin']['news_edit_headline']);

        ?>
        <form action="/administration/editnews/<?php echo $newsId; ?>" method="post">
            <div class="row align-items-center">
                <div class="form-group col mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyText">Text</span>
                        </div>
                        <textarea class="form-control" id="news_text" name="news_text" aria-describedby="ariaDescribedbyText" rows="10" required><?php echo $news_text; ?></textarea>
                    </div>
                </div>
                <div class="form-group col col-12">
                    <button type="submit"
                            class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_save']; ?></button>
                </div>
            </div>
            <?php if ($row['news_cardupdate_id'] != NULL) { ?>
                <div class="form-group col col-12">
                    <span class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_preview_for_cardupdate']; ?></span>
                    <p class="text-center mt-2">
                        <?php
                        $sql_cardupdate = "SELECT cardupdate_carddeck_id
                                           FROM cardupdate
                                           WHERE cardupdate_id = '".$row['news_cardupdate_id']."'
                                           LIMIT 1";
                        $result_cardupdate = mysqli_query($link, $sql_cardupdate);
                        $count_cardupdate = mysqli_num_rows($result_cardupdate);
                        if ($count_cardupdate) {
                            $row_cardupdate = mysqli_fetch_assoc($result_cardupdate);
                            $updatedecks = $row_cardupdate['cardupdate_carddeck_id'];
                            $updatecarddecks_array = explode(';', $updatedecks);
                            $count_decks = sizeof($updatecarddecks_array);

                            for ($i = 0; $i < $count_decks; $i++) {
                                $sql_carddeck = "SELECT carddeck_id, carddeck_name
                                                 FROM carddeck
                                                 WHERE carddeck_id = '".$updatecarddecks_array[$i]."'
                                                 LIMIT 1";
                                $result_carddeck = mysqli_query($link, $sql_carddeck);
                                if (mysqli_num_rows($result_carddeck)) {
                                    $row_carddeck = mysqli_fetch_assoc($result_carddeck);
                                    ?>
                                    <a href="/deck/<?php echo $row_carddeck['carddeck_name']; ?>"><img
                                            src="<?php echo TCG_CARDS_FOLDER . '/' . $row_carddeck['carddeck_name'] . '/' . $row_carddeck['carddeck_name'] . '01.' . TCG_CARDS_FILE_TYPE; ?>"/></a>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </p>
                </div>
            <?php } ?>
        </form>
        <?php
    } else {
        $breadcrumb = array(
            '/' => 'Home',
            '/administration' => 'Administration',
            '/administration/editnews' => TRANSLATIONS[$GLOBALS['language']]['admin']['news_administration_headline'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['admin']['news_edit_headline'],
        );
        breadcrumb($breadcrumb);

        title(TRANSLATIONS[$GLOBALS['language']]['admin']['news_edit_headline']);

        $sql = "SELECT news_id, news_text, news_date, news_cardupdate_id, member_nick
                FROM news, member
                WHERE news_member_id = member_id
                GROUP BY news_id DESC";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $count = mysqli_num_rows($result);
        if($count) {
            ?>
            <div class="row">
                <div class="col">
                    <table id="admin_member_edit_table" data-mobile-responsive="true">
                        <thead>
                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="author"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_news_author']; ?></th>
                            <th data-field="text"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_text_snippet']; ?></th>
                            <th data-field="date"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_date']; ?></th>
                            <th data-field="cardupdate"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_have_cardupdate']; ?></th>
                            <th data-field="options"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['news_id']; ?></td>
                                <td><?php echo $row['member_nick']; ?></td>
                                <td>
                                    <?php
                                    if (strlen($row['news_text']) >= 20) {
                                        echo preg_replace('/\s+?(\S+)?$/', '', substr($row['news_text'], 0, 20)).'...';
                                        $string = wordwrap($row['news_text'], 10);
                                        $string = substr($row['news_text'], 0, strpos($row['news_text'], '\n'));
                                    } else {
                                        echo $row['news_text'];
                                    }
                                    ?>
                                </td>
                                <td><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row['news_date']); ?></td>
                                <td><?php echo ($row['news_cardupdate_id'] != NULL ? TRANSLATIONS[$GLOBALS['language']]['general']['text_yes'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_no']); ?></td>
                                <td><a href="/administration/editnews/<?php echo $row['news_id']; ?>">Edit</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="form-group col mt-2">
                    <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_data'], 'danger'); ?>
                </div>
            </div>
            <?php
        }
    }
}
?>