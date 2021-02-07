<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    if (isset($category_id)) {
        $sql_carddeck_cat_name = "SELECT carddeck_cat_name
                                  FROM carddeck_cat
                                  WHERE carddeck_cat_id = '".$category_id."'
                                  LIMIT 1";
        $result_carddeck_cat_name = mysqli_query($link, $sql_carddeck_cat_name) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_carddeck_cat_name)) {
            $row_carddeck_cat_name = mysqli_fetch_assoc($result_carddeck_cat_name);
            $breadcrumb = array(
                '/' => 'Home',
                '/carddeck' => TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'],
                '/carddeck/'.$category_id => $row_carddeck_cat_name['carddeck_cat_name'],
            );
            $title = TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'].' - '.$row_carddeck_cat_name['carddeck_cat_name'];
            $category_filter = "AND carddeck_cat_id = '".$category_id."'";
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/carddeck' => TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'],
            );
            breadcrumb($breadcrumb);

            title(TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']);
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_category_dont_exists'], 'danger');
            exit;
        }
    } else {
        $breadcrumb = array(
            '/' => 'Home',
            '/carddeck' => TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'],
        );
        $title = TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'];
        $category_filter = '';
    }

    breadcrumb($breadcrumb);
    title($title);

    $sql_carddeck = "SELECT carddeck_id, carddeck_name, carddeck_series, carddeck_date, carddeck_creator, carddeck_cat_name, carddeck_sub_cat_name
                     FROM carddeck, carddeck_cat, carddeck_sub_cat
                     WHERE carddeck_cat = carddeck_cat_id
                       AND carddeck_sub_cat = carddeck_sub_cat_id
                         ".$category_filter."
                     ORDER BY carddeck_name ASC";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    $count_carddeck = mysqli_num_rows($result_carddeck);
    ?>
    <div class="row">
        <div class="col">
            <?php
            if ($count_carddeck) {
                ?>
                <table id="carddecks_table" data-mobile-responsive="true">
                    <thead>
                    <tr>
                        <th data-field="name">Name (<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_abbreviation']; ?>)</th>
                        <th data-field="series"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_series']; ?></th>
                        <th data-field="category"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category']; ?></th>
                        <th data-field="date"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_date']; ?></th>
                        <th data-field="creator"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_creator']; ?></th>
                        <th data-field="options"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($row_carddeck = mysqli_fetch_assoc($result_carddeck)) {
                        $sql_on_wishlist = "SELECT member_wishlist_carddeck_id
                                            FROM member_wishlist
                                            WHERE member_wishlist_member_id = '".$_SESSION['member_id']."'
                                              AND member_wishlist_carddeck_id = '".$row_carddeck['carddeck_id']."'
                                            LIMIT 1";
                        $result_on_wishlist = mysqli_query($link, $sql_on_wishlist) OR die(mysqli_error($link));
                        $is_on_wishlist = mysqli_num_rows($result_on_wishlist) > 0;
                        ?>
                        <tr>
                            <td><a href="/carddeck/<?php echo $row_carddeck['carddeck_name']; ?>"><?php echo $row_carddeck['carddeck_name']; ?></a></td>
                            <td><?php echo $row_carddeck['carddeck_series']; ?></td>
                            <td><?php echo ($category_filter == '' ? $row_carddeck['carddeck_cat_name'].' <i class="fas fa-chevron-right"></i>' : ''); ?> <?php echo $row_carddeck['carddeck_sub_cat_name']; ?></td>
                            <td><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $row_carddeck['carddeck_date']); ?></td>
                            <td><?php echo member_link($row_carddeck['carddeck_creator']); ?></td>
                            <td>
                                <span class="<?php echo ($is_on_wishlist ? 'remove-from-wishlist' : 'add-to-wishlist'); ?>" data-carddeck-id="<?php echo $row_carddeck['carddeck_id']; ?>">
                                    <i class="fas fa-star"></i><?php echo ($is_on_wishlist ? '<i class="fas fa-minus"></i>' : '<i class="fas fa-plus"></i>'); ?>
                                </span>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_carddeck_in_this_category_yet'], 'danger');
            }
            ?>
        </div>
    </div>
    <?php
} else {
    show_no_access_message();
}
?>