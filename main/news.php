<?php
global $link;
$sql_news = "SELECT news_id, news_member_id, news_title, news_text, news_date, news_cardupdate_id
             FROM news
             WHERE news_id = '".$news_id."'
             ORDER BY news_id DESC";
$result_news = mysqli_query($link, $sql_news) OR die(mysqli_error($link));
if (mysqli_num_rows($result_news)) {
    $row_news = mysqli_fetch_assoc($result_news);
    $has_cardupdate = ($row_news['news_cardupdate_id'] != NULL);

    $breadcrumb = array(
        '/' => 'Home',
        '/news/'.$news_id => 'News - '.$row_news['news_title'],
    );
    breadcrumb($breadcrumb);
    title($row_news['news_title']);
    ?>
    <div class="row news-container">
        <div class="col col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-<?php echo ($has_cardupdate ? 'gifts' : 'bullhorn'); ?>"></i> <?php echo $row_news['news_title']; ?></div>
                <div class="card-body">
                    <p class="card-text"><?php echo nl2br($row_news['news_text']); ?></p>
                    <?php if ($has_cardupdate) {
                        ?>
                        <div class="text-center">
                            <?php
                            title_small(TRANSLATIONS[$GLOBALS['language']]['general']['text_new_card_decks']);
                            $sql_cardupdate = "SELECT cardupdate_carddeck_id
                                               FROM cardupdate
                                               WHERE cardupdate_id = '".$row_news['news_cardupdate_id']."'
                                               LIMIT 1";
                            $result_cardupdate = mysqli_query($link, $sql_cardupdate) OR die(mysqli_error($link));
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
                                    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
                                    if (mysqli_num_rows($result_carddeck)) {
                                        $row_carddeck = mysqli_fetch_assoc($result_carddeck);
                                        ?>
                                        <a href="/carddeck/<?php echo $row_carddeck['carddeck_name']; ?>"><img
                                                src="<?php echo TCG_CARDS_FOLDER . '/' . $row_carddeck['carddeck_name'] . '/' . $row_carddeck['carddeck_name'] . '01.' . TCG_CARDS_FILE_TYPE; ?>"/></a>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col col-6 text-left">
                            <small class="text-muted">
                                <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_news['news_date']); ?> - <?php echo member_link($row_news['news_member_id']); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}  else {
    alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_valid_id'], 'danger');
}
?>