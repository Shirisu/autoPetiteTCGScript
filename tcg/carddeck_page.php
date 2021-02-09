<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    if (isset($carddeck_name)) {
        $sql_carddeck = "SELECT carddeck_id, carddeck_name, carddeck_series, carddeck_date, carddeck_creator, carddeck_is_puzzle, carddeck_cat, carddeck_artist, carddeck_copyright, carddeck_imagesources, carddeck_cat_name, carddeck_sub_cat_name
                         FROM carddeck, carddeck_cat, carddeck_sub_cat
                         WHERE carddeck_name = '".$carddeck_name."'
                           AND carddeck_cat = carddeck_cat_id
                           AND carddeck_sub_cat = carddeck_sub_cat_id
                         ORDER BY carddeck_name ASC";
        $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
        $count_carddeck = mysqli_num_rows($result_carddeck);

        if ($count_carddeck) {
            $row_carddeck = mysqli_fetch_assoc($result_carddeck);

            $breadcrumb = array(
                '/' => 'Home',
                '/carddecks' => TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'],
                '/carddeck/'.$carddeck_name => $carddeck_name,
            );

            breadcrumb($breadcrumb);
            title('['.strtoupper($carddeck_name).'] '.$row_carddeck['carddeck_series']);

            $remove_host = ['https://', 'https//', 'http://', 'http//', '://', '//'];
            ?>
            <div class="row">
                <div class="col col-12 col-xl-5 order-2 order-xl-1">
                    <div class="table-responsive">
                        <table class="table optional carddeck">
                            <tbody>
                            <tr>
                                <th scope="row" class="auto">Name (<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_abbreviation']; ?>)</th>
                                <td><?php echo $row_carddeck['carddeck_name']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_series']; ?></th>
                                <td><?php echo $row_carddeck['carddeck_series']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category_main']; ?></th>
                                <td><a href="/carddecks/<?php echo $row_carddeck['carddeck_cat']; ?>"><?php echo $row_carddeck['carddeck_cat_name']; ?></a></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category_sub']; ?></th>
                                <td><?php echo $row_carddeck['carddeck_sub_cat_name']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_creator']; ?></th>
                                <td><?php echo member_link($row_carddeck['carddeck_creator']); ?></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_date']; ?></th>
                                <td><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $row_carddeck['carddeck_date']); ?></td>
                            </tr>
                            <?php if ($row_carddeck['carddeck_artist'] != '') { ?>
                                <tr>
                                    <th scope = "row" ><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_artist']; ?></th>
                                    <td><?php
                                        $carddeck_copyright = $row_carddeck['carddeck_copyright'];
                                        $copyright_url = str_replace($remove_host, '', $carddeck_copyright);
                                        echo($copyright_url != '' ?
                                            '<a href="//' . $copyright_url . '" target="_blank">' . $row_carddeck['carddeck_artist'] . '</a>'
                                            :
                                            $row_carddeck['carddeck_artist']
                                        ); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($row_carddeck['carddeck_imagesources'] != '') { ?>
                                <tr>
                                    <th scope = "row" ><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_imagesources']; ?></th>
                                    <td><?php
                                        $carddeck_imagesources = $row_carddeck['carddeck_imagesources'];
                                        $imagesources_urls = explode(';;', str_replace($remove_host, '', $carddeck_imagesources));
                                        for ($i = 0; $i < count($imagesources_urls); $i++) {
                                            ?>
                                            <a href="//<?php echo $imagesources_urls[$i]; ?>" target="_blank"><?php echo ($i + 1); ?></a><?php echo (($i + 1) != count($imagesources_urls) ? ', ' : ''); ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td scope="row" colspan="2" class="text-center text-md-left pl-0">
                                    <?php
                                    $sql_mastered = "SELECT member_master_date
                                                     FROM member_master
                                                     WHERE member_master_member_id = '".$_SESSION['member_id']."'
                                                       AND member_master_carddeck_id = '".$row_carddeck['carddeck_id']."'
                                                    LIMIT 1";
                                    $result_mastered = mysqli_query($link, $sql_mastered) OR die(mysqli_error($link));
                                    $already_mastered = mysqli_num_rows($result_mastered) > 0;
                                    if ($already_mastered) {
                                        $row_mastered = mysqli_fetch_assoc($result_mastered);
                                        ?>
                                        <span class="mastered"><i class="fas fa-medal"></i></span> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_mastered_on']; ?> <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $row_mastered['member_master_date']); ?>
                                        <?php
                                    } else {
                                        $sql_on_wishlist = "SELECT member_wishlist_carddeck_id
                                                            FROM member_wishlist
                                                            WHERE member_wishlist_member_id = '" . $_SESSION['member_id'] . "'
                                                              AND member_wishlist_carddeck_id = '" . $row_carddeck['carddeck_id'] . "'
                                                            LIMIT 1";
                                        $result_on_wishlist = mysqli_query($link, $sql_on_wishlist) OR die(mysqli_error($link));
                                        $is_on_wishlist = mysqli_num_rows($result_on_wishlist) > 0;
                                        ?>
                                        <span
                                            class="<?php echo($is_on_wishlist ? 'remove-from-wishlist' : 'add-to-wishlist'); ?>"
                                            data-carddeck-id="<?php echo $row_carddeck['carddeck_id']; ?>">
                                            <i class="fas fa-star"></i><?php echo($is_on_wishlist ? '<i class="fas fa-minus"></i>' : '<i class="fas fa-plus"></i>'); ?>
                                        </span>
                                        <span class="wishlist-text"><?php echo($is_on_wishlist ? TRANSLATIONS[$GLOBALS['language']]['wishlist']['text_remove_from_wishlist'] : TRANSLATIONS[$GLOBALS['language']]['wishlist']['text_add_to_wishlist']); ?></span>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col col-12 col-xl-7 text-center order-1 order-xl-2 mb-2 mb-xl-0 overflow-auto">
                    <div class="carddeck_wrapper" data-is-puzzle="<?php echo ($row_carddeck['carddeck_is_puzzle'] ? $row_carddeck['carddeck_is_puzzle'] : 0); ?>">
                        <?php
                        for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS; $i++) {
                            $filename = TCG_CARDS_FOLDER . '/' . $carddeck_name . '/' . $carddeck_name . sprintf("%'.02d", $i) . '.' . TCG_CARDS_FILE_TYPE;
                            ?>
                            <span class="card_wrapper" <?php echo (file_exists('.'.$filename) ? 'style="background-image:url('.$filename.');"' : ''); ?>></span>
                            <?php
                            if (($i % TCG_CARDS_PER_ROW) == 0) {
                                ?>
                                <br />
                                <?php
                            }
                        }
                        $filename_master = TCG_CARDS_FOLDER . '/' . $carddeck_name . '/' . $carddeck_name . 'master.' . TCG_CARDS_FILE_TYPE;
                        ?>
                        <span class="card_wrapper mastercard" <?php echo (file_exists('.'.$filename_master) ? 'style="background-image:url('.$filename_master.');"' : ''); ?>></span>
                    </div>
                </div>
            </div>
            <div class="row mt-3 card-same-height">
                <div class="col col-12 col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            Collect / Wishlist
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text">
                                <?php
                                $collect_wishlist_member = array();
                                $sql_collect = "SELECT member_id, member_nick
                                                FROM member_cards, member
                                                WHERE member_cards_carddeck_id = '".$row_carddeck['carddeck_id']."'
                                                   AND member_cards_member_id = member_id
                                                   AND member_cards_cat = 2
                                                ORDER BY member_nick ASC";
                                $result_collect = mysqli_query($link, $sql_collect);
                                $count_collect = mysqli_num_rows($result_collect);
                                if ($count_collect) {
                                    while ($row_collect = mysqli_fetch_assoc($result_collect)) {
                                        $collect_wishlist_member[$row_collect['member_nick']] = $row_collect['member_id'];
                                    }
                                }

                                $sql_wishlist = "SELECT member_id, member_nick
                                                 FROM member, member_wishlist
                                                 WHERE member_wishlist_carddeck_id = '".$row_carddeck['carddeck_id']."'
                                                   AND member_wishlist_member_id = member_id
                                                 ORDER BY member_nick ASC";
                                $result_wishlist = mysqli_query($link, $sql_wishlist);
                                $count_wishlist = mysqli_num_rows($result_wishlist);
                                if ($count_wishlist) {
                                    while ($row_wishlist = mysqli_fetch_assoc($result_wishlist)) {
                                        $collect_wishlist_member[$row_wishlist['member_nick']] = $row_wishlist['member_id'];
                                    }
                                }

                                if (count($collect_wishlist_member) > 0) {
                                    foreach ($collect_wishlist_member as $index => $member_id) {
                                        echo member_link($member_id);
                                        echo ($index != array_key_last($collect_wishlist_member) ? ', ' : '');
                                    }
                                } else {
                                    alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_nobody_yet'], 'danger');
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            Trade
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text">
                                <?php
                                $trade_member = array();
                                $sql_trade = "SELECT member_id, member_nick
                                              FROM member_cards, member
                                              WHERE member_cards_carddeck_id = '".$row_carddeck['carddeck_id']."'
                                                AND member_cards_member_id = member_id
                                                AND member_cards_cat = 3
                                              ORDER BY member_nick ASC";
                                $result_trade = mysqli_query($link, $sql_trade);
                                $count_trade = mysqli_num_rows($result_trade);
                                if ($count_trade) {
                                    while ($row_trade = mysqli_fetch_assoc($result_trade)) {
                                        $trade_member[$row_trade['member_nick']] = $row_trade['member_id'];
                                    }

                                    foreach ($trade_member as $index => $member_id) {
                                        echo member_link($member_id);
                                        echo ($index != array_key_last($trade_member) ? ', ' : '');
                                    }
                                } else {
                                    alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_nobody_yet'], 'danger');
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            Master
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text">
                                <?php
                                $master_member = array();
                                $sql_master = "SELECT member_id, member_nick
                                               FROM member_master, member
                                               WHERE member_master_carddeck_id = '".$row_carddeck['carddeck_id']."'
                                                 AND member_master_member_id = member_id
                                               ORDER BY member_nick ASC";
                                $result_master = mysqli_query($link, $sql_master);
                                $count_master = mysqli_num_rows($result_master);
                                if ($count_master) {
                                    while ($row_master = mysqli_fetch_assoc($result_master)) {
                                        $master_member[$row_master['member_nick']] = $row_master['member_id'];
                                    }

                                    foreach ($master_member as $index => $member_id) {
                                        echo member_link($member_id);
                                        echo ($index != array_key_last($master_member) ? ', ' : '');
                                    }
                                } else {
                                    alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_nobody_yet'], 'danger');
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/carddecks' => TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'],
                '/carddeck/'.$carddeck_name => $carddeck_name,
            );

            breadcrumb($breadcrumb);
            title(TRANSLATIONS[$GLOBALS['language']]['general']['text_carddeck'].' - '.$carddeck_name);

            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_carddeck_dont_exists'], 'danger');
        }
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['text_pagenotexist'], 'danger');
    }
} else {
    show_no_access_message();
}
?>