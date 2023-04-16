<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    $breadcrumb = array(
        '/' => 'Home',
        '/carddecks' => TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks_unreleased'],
    );
    $title = TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks_unreleased'];

    breadcrumb($breadcrumb);

    $sql_carddeck = "SELECT carddeck_id, carddeck_name, carddeck_series, carddeck_date, carddeck_creator, carddeck_cat_name, carddeck_sub_cat_name
                     FROM carddeck
                     JOIN carddeck_cat ON carddeck_cat_id = carddeck_cat
                     JOIN carddeck_sub_cat ON carddeck_sub_cat_id = carddeck_sub_cat
                     WHERE carddeck_active = 0
                     ORDER BY carddeck_name ASC";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    $count_carddeck = mysqli_num_rows($result_carddeck);

    title($title.' ('.$count_carddeck.')');
    ?>
    <div class="row">
        <?php
        if ($count_carddeck) {
            while ($row_carddeck = mysqli_fetch_assoc($result_carddeck)) {
                $carddeck_id = $row_carddeck['carddeck_id'];
                $carddeck_name = $row_carddeck['carddeck_name'];
                $filename_master = get_card($row_carddeck['carddeck_id'], 'master', true);
                ?>
                <div class="col col-12 col-sm-6 col-md-3 mb-2 text-center">
                    <div class="card">
                        <div class="card-img-top">
                            <?php echo get_card($carddeck_id, 'master', false, true); ?>
                        </div>
                        <div class="card-body">
                            <div class="card-title">
                                <?php echo $row_carddeck['carddeck_series']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_carddeck_in_this_category_yet'], 'danger');
        }
        ?>
    </div>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>