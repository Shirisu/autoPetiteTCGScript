<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    if (isset($member_id)) {
        if ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
            $member_active_string = '';
        } else {
            $member_active_string = 'AND member_active = 1';
        }

        $sql_member = "SELECT member_nick
                       FROM member
                       WHERE member_id = '".$member_id."'
                         ".$member_active_string."
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
                    <?php get_member_menu($member_id, 'wishlist'); ?>
                </div>
                <div class="col col-12 mb-3 member-wishlist-container">
                    <?php
                    $sql_wishlist = "SELECT member_wishlist_date, carddeck_name, carddeck_series, carddeck_cat_name, carddeck_sub_cat_name
                                     FROM member_wishlist
                                     JOIN carddeck ON carddeck_id = member_wishlist_carddeck_id
                                     JOIN carddeck_cat ON carddeck_cat_id = carddeck_cat
                                     JOIN carddeck_sub_cat ON carddeck_sub_cat_id = carddeck_sub_cat
                                     WHERE member_wishlist_member_id = '".$member_id."'
                                       AND carddeck_active = 1
                                     ORDER BY carddeck_name ASC";
                    $result_wishlist = mysqli_query($link, $sql_wishlist) OR die(mysqli_error($link));
                    $count_wishlist = mysqli_num_rows($result_wishlist);
                    if ($count_wishlist) {
                        title_small($count_wishlist.' '.($count_wishlist == 1 ? TRANSLATIONS[$GLOBALS['language']]['general']['text_carddeck'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']).' '.TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_on_wishlist']);
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
    show_no_access_message_with_breadcrumb();
}
?>