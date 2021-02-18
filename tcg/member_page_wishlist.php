<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    if (isset($member_id)) {
        $sql_member = "SELECT member_nick
                       FROM member
                       WHERE member_id = '".$member_id."'
                         AND member_active = 1
                       LIMIT 1";
        $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
        $count_member = mysqli_num_rows($result_member);

        if ($count_member) {
            $row_member = mysqli_fetch_assoc($result_member);

            $breadcrumb = array(
                '/' => 'Home',
                '/member' => 'Member',
                '/member/'.$member_id => $row_member['member_nick'],
                '/member/'.$member_id.'/wishlist' => TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist'],
            );

            breadcrumb($breadcrumb);
            title($row_member['member_nick'].' <small>'.get_online_status($member_id).'</small>');
            ?>
            <div class="row member-profile">
                <div class="col col-12 mb-3">
                    <div class="row">
                        <div class="col col-6 col-md-3 mb-2 mb-md-0">
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/trade" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-exchange-alt"></i> Trade</a>
                        </div>
                        <div class="col col-6 col-md-3 mb-2 mb-md-0">
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/collect" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-heart"></i> Collect</a>
                        </div>
                        <div class="col col-6 col-md-3 mb-2 mb-md-0">
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/master" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-award"></i> Master</a>
                        </div>
                        <div class="col col-6 col-md-3 mb-2 mb-md-0">
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/wishlist" class="btn btn-outline-info btn-sm btn-block active"><i class="fas fa-star"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist']; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col col-12 mb-3 member-wishlist-container">
                    <?php
                    $sql_wishlist = "SELECT member_wishlist_date, carddeck_name, carddeck_series, carddeck_cat_name, carddeck_sub_cat_name
                                     FROM member_wishlist, carddeck, carddeck_cat, carddeck_sub_cat
                                     WHERE member_wishlist_member_id = '".$member_id."'
                                       AND member_wishlist_carddeck_id = carddeck_id
                                       AND carddeck_cat = carddeck_cat_id
                                       AND carddeck_sub_cat = carddeck_sub_cat_id
                                     ORDER BY carddeck_name ASC";
                    $result_wishlist = mysqli_query($link, $sql_wishlist) OR die(mysqli_error($link));
                    $count_wishlist = mysqli_num_rows($result_wishlist);
                    if ($count_wishlist) {
                        title_small($count_wishlist.' '.TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'].' '.TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_on_wishlist']);
                        ?>
                        <table data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th data-field="name" data-sortable="true">Name (<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_abbreviation']; ?>)</th>
                                <th data-field="series" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_series']; ?></th>
                                <th data-field="category" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category']; ?></th>
                                <th data-field="date" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_on_wishlist_since']; ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row_wishlist = mysqli_fetch_assoc($result_wishlist)) {
                                $carddeck_name = $row_wishlist['carddeck_name'];
                                ?>
                                <tr>
                                    <td><a href="<?php echo HOST_URL; ?>/carddeck/<?php echo $row_wishlist['carddeck_name']; ?>"><?php echo $row_wishlist['carddeck_name']; ?></a></td>
                                    <td><?php echo $row_wishlist['carddeck_series']; ?></td>
                                    <td><?php echo $row_wishlist['carddeck_cat_name'].' <i class="fas fa-angle-right"></i>'; ?> <?php echo $row_wishlist['carddeck_sub_cat_name']; ?></td>
                                    <td><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $row_wishlist['member_wishlist_date']); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        title_small('0 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks'].' '.TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_on_wishlist']);
                        alert_box(TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_no_cards_in_category'], 'danger');
                    }
                    ?>
                </div>
            </div>
            <?php
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/member' => 'Member',
            );

            breadcrumb($breadcrumb);
            title('Member');

            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_member_dont_exists'], 'danger');
        }
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['text_pagenotexist'], 'danger');
    }
} else {
    show_no_access_message();
}
?>