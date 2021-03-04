<?php
$breadcrumb = array(
    '/' => 'Home',
);
breadcrumb($breadcrumb);
title('News');

global $link;
$sql_news = "SELECT news_id, news_member_id, news_title, news_text, news_date, news_cardupdate_id
             FROM news
             ORDER BY news_id DESC";
$result_news = mysqli_query($link, $sql_news) OR die(mysqli_error($link));
if (mysqli_num_rows($result_news)) {
    ?>
    <div class="row news-container">
        <div class="col col-12">
            <table class="optional news" data-mobile-responsive="true">
                <thead>
                    <tr>
                        <th>News</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row_news = mysqli_fetch_assoc($result_news)) {
                        $has_cardupdate = ($row_news['news_cardupdate_id'] != NULL);
                        ?>
                        <tr>
                            <td>
                                <div class="card">
                                    <div class="card-header"><i class="fas fa-<?php echo ($has_cardupdate ? 'gifts' : 'bullhorn'); ?>"></i> <?php echo $row_news['news_title']; ?></div>
                                    <div class="card-body">
                                        <p class="card-text"><?php echo shorten_text($row_news['news_text'], 100); ?></p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col col-8 text-left">
                                                <small class="text-muted">
                                                    <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_news['news_date']); ?> - <?php echo get_member_link($row_news['news_member_id']); ?>
                                                </small>
                                            </div>
                                            <div class="col col-4 text-right">
                                                <small><a href="<?php echo HOST_URL; ?>/news/<?php echo $row_news['news_id']; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_news_read_more']; ?></a></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
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
    alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_news_yet'], 'danger');
}
?>