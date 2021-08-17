<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    if (isset($carddeck_name)) {
        $sql_carddeck = "SELECT carddeck_id, carddeck_name, carddeck_series, carddeck_date, carddeck_creator, carddeck_is_puzzle, carddeck_cat, carddeck_sub_cat, carddeck_artist, carddeck_copyright, carddeck_imagesources, carddeck_cat_name, carddeck_sub_cat_name
                         FROM carddeck
                         JOIN carddeck_cat ON carddeck_cat_id = carddeck_cat
                         JOIN carddeck_sub_cat ON carddeck_sub_cat_id = carddeck_sub_cat
                         WHERE carddeck_name = '".$carddeck_name."'
                           AND carddeck_active = 1
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
                                <td><a href="<?php echo HOST_URL; ?>/carddecks/<?php echo $row_carddeck['carddeck_cat']; ?>"><?php echo $row_carddeck['carddeck_cat_name']; ?></a></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category_sub']; ?></th>
                                <td><a href="<?php echo HOST_URL; ?>/carddecks/<?php echo $row_carddeck['carddeck_cat']; ?>/<?php echo $row_carddeck['carddeck_sub_cat']; ?>"><?php echo $row_carddeck['carddeck_sub_cat_name']; ?></a></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_creator']; ?></th>
                                <td><?php echo get_member_link($row_carddeck['carddeck_creator']); ?></td>
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
                                    <div class="row m-0">
                                        <?php
                                        $sql_mastered = "SELECT member_master_date
                                                         FROM member_master
                                                         WHERE member_master_member_id = '".$_SESSION['member_id']."'
                                                           AND member_master_carddeck_id = '".$row_carddeck['carddeck_id']."'
                                                         ORDER BY member_master_date ASC";
                                        $result_mastered = mysqli_query($link, $sql_mastered) OR die(mysqli_error($link));
                                        $already_mastered = mysqli_num_rows($result_mastered) > 0;

                                        if (!$already_mastered || TCG_MULTI_MASTER == true) {
                                            $sql_on_wishlist = "SELECT member_wishlist_carddeck_id
                                                            FROM member_wishlist
                                                            WHERE member_wishlist_member_id = '" . $_SESSION['member_id'] . "'
                                                              AND member_wishlist_carddeck_id = '" . $row_carddeck['carddeck_id'] . "'
                                                            LIMIT 1";
                                            $result_on_wishlist = mysqli_query($link, $sql_on_wishlist) OR die(mysqli_error($link));
                                            $is_on_wishlist = mysqli_num_rows($result_on_wishlist) > 0;
                                            ?>
                                            <div class="col col-auto">
                                                <span
                                                    class="<?php echo($is_on_wishlist ? 'remove-from-wishlist' : 'add-to-wishlist'); ?>"
                                                    data-carddeck-id="<?php echo $row_carddeck['carddeck_id']; ?>">
                                                    <i class="fas fa-star"></i><?php echo($is_on_wishlist ? '<i class="fas fa-minus"></i>' : '<i class="fas fa-plus"></i>'); ?>
                                                </span>
                                                <span
                                                    class="wishlist-text"><?php echo($is_on_wishlist ? TRANSLATIONS[$GLOBALS['language']]['wishlist']['text_remove_from_wishlist'] : TRANSLATIONS[$GLOBALS['language']]['wishlist']['text_add_to_wishlist']); ?></span>
                                            </div>
                                            <?php
                                        }

                                        if ($already_mastered) {
                                            ?>
                                            <div class="col col-auto">
                                                <div class="row">
                                                    <?php
                                                    while($row_mastered = mysqli_fetch_assoc($result_mastered)) {
                                                        ?>
                                                        <div class="col col-auto">
                                                            <span class="mastered"><i
                                                                    class="fas fa-medal"></i></span> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_mastered_on']; ?>
                                                            <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $row_mastered['member_master_date']); ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-3 card-same-height">
                        <!-- by botric 17/08/2021 moved the collect, trade and master users to closer to the top -->
                <div class="col col-12 col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header text-center">
                            <i class="fas fa-heart"></i> Collect / <i class="fas fa-star"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist']; ?>
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text">
                                <?php
                                $collect_wishlist_member = array();
                                $sql_collect = "SELECT member_id, member_nick
                                                FROM member_cards
                                                JOIN member ON member_id = member_cards_member_id
                                                WHERE member_cards_carddeck_id = '".$row_carddeck['carddeck_id']."'
                                                   AND member_cards_cat = '".MEMBER_CARDS_COLLECT."'
                                                ORDER BY member_nick ASC";
                                $result_collect = mysqli_query($link, $sql_collect);
                                $count_collect = mysqli_num_rows($result_collect);
                                if ($count_collect) {
                                    while ($row_collect = mysqli_fetch_assoc($result_collect)) {
                                        $collect_wishlist_member[$row_collect['member_nick']] = $row_collect['member_id'];
                                    }
                                }

                                $sql_wishlist = "SELECT member_id, member_nick
                                                 FROM member_wishlist
                                                 JOIN member ON member_id = member_wishlist_member_id
                                                 WHERE member_wishlist_carddeck_id = '".$row_carddeck['carddeck_id']."'
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
                                        echo get_member_link($member_id);
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
                        <div class="card-header text-center">
                            <i class="fas fa-exchange-alt"></i> Trade
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text">
                                <?php
                                $trade_member = array();
                                $sql_trade = "SELECT member_id, member_nick
                                              FROM member_cards
                                              JOIN member ON member_id = member_cards_member_id
                                              WHERE member_cards_carddeck_id = '".$row_carddeck['carddeck_id']."'
                                                AND member_cards_cat = '".MEMBER_CARDS_TRADE."'
                                              ORDER BY member_nick ASC";
                                $result_trade = mysqli_query($link, $sql_trade);
                                $count_trade = mysqli_num_rows($result_trade);
                                if ($count_trade) {
                                    while ($row_trade = mysqli_fetch_assoc($result_trade)) {
                                        $trade_member[$row_trade['member_nick']] = $row_trade['member_id'];
                                    }

                                    foreach ($trade_member as $index => $member_id) {
                                        echo get_member_link($member_id);
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
                        <div class="card-header text-center">
                            <i class="fas fa-award"></i> Master
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text">
                                <?php
                                $master_member = array();
                                if (TCG_MULTI_MASTER == true) {
                                    $sql_master = "SELECT member_id, member_nick,
                                                    (SELECT COUNT(member_master_id)
                                                     FROM member_master
                                                     WHERE member_master_carddeck_id = mc.member_master_carddeck_id
                                                       AND member_master_member_id = mc.member_master_member_id) AS master_count
                                                   FROM member_master mc
                                                    JOIN member ON member_id = member_master_member_id
                                                   WHERE member_master_carddeck_id = '" . $row_carddeck['carddeck_id'] . "'
                                                   GROUP BY member_master_id
                                                   ORDER BY member_nick ASC";
                                } else {
                                    $sql_master = "SELECT member_id, member_nick
                                                   FROM member_master
                                                    JOIN member ON member_id = member_master_member_id
                                                   WHERE member_master_carddeck_id = '" . $row_carddeck['carddeck_id'] . "'
                                                   GROUP BY member_master_id
                                                   ORDER BY member_nick ASC";
                                }
                                $result_master = mysqli_query($link, $sql_master);
                                $count_master = mysqli_num_rows($result_master);
                                if ($count_master) {
                                    while ($row_master = mysqli_fetch_assoc($result_master)) {
                                        $master_member[$row_master['member_nick']]['member_id'] = $row_master['member_id'];
                                        $master_member[$row_master['member_nick']]['master_count'] = 0;

                                        if (TCG_MULTI_MASTER == true) {
                                            $master_member[$row_master['member_nick']]['master_count'] = $row_master['master_count'];
                                        }
                                    }

                                    foreach ($master_member as $index => $member_info) {
                                        echo get_member_link($member_info['member_id']);
                                        if (TCG_MULTI_MASTER == true) {
                                            ?>
                                            <span class="badge badge-secondary"><?php echo $member_info['master_count']; ?>x</span>
                                            <?php
                                        }
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
                </div>
                <div class="col col-12 col-xl-7 text-center order-1 order-xl-2 mb-2 mb-xl-0 overflow-auto">
                    <div class="carddeck-wrapper" data-is-puzzle="<?php echo ($row_carddeck['carddeck_is_puzzle'] ? $row_carddeck['carddeck_is_puzzle'] : 0); ?>">
                        <?php
                        for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS; $i++) {
                            $filename = get_card($row_carddeck['carddeck_id'], $i, true);
                            ?>
                            <span class="card-wrapper" <?php echo (file_exists('.'.substr($filename, strlen(HOST_URL))) ? 'style="background-image:url('.$filename.');"' : ''); ?>></span>
                            <?php
                            if (($i % TCG_CARDS_PER_ROW) == 0) {
                                ?>
                                <br />
                                <?php
                            }
                        }
                        $filename_master = get_card($row_carddeck['carddeck_id'], 'master', true);
                        ?>
                        <span class="card-wrapper mastercard" <?php echo (file_exists('.'.substr($filename_master, strlen(HOST_URL))) ? 'style="background-image:url('.$filename_master.');"' : ''); ?>></span>
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
    show_no_access_message_with_breadcrumb();
}
?>