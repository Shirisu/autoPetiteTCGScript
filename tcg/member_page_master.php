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
                '/member/'.$member_id.'/master' => 'Master',
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
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/master" class="btn btn-outline-info btn-sm btn-block active"><i class="fas fa-award"></i> Master</a>
                        </div>
                        <div class="col col-6 col-md-3 mb-2 mb-md-0">
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/wishlist" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-star"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist']; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col col-12 mb-3 member-cards-container">
                    <?php
                    $sql_master = "SELECT member_master_id, member_master_date, carddeck_id, carddeck_name
                                   FROM member_master, carddeck
                                   WHERE member_master_member_id = '".$member_id."'
                                     AND member_master_carddeck_id = carddeck_id
                                   ORDER BY carddeck_name ASC";
                    $result_master = mysqli_query($link, $sql_master) OR die(mysqli_error($link));
                    $count_master = mysqli_num_rows($result_master);
                    if ($count_master) {
                        title_small($count_master.' Master');
                        ?>
                        <table class="optional profile-cards master-cards" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th><?php echo title_small($count_master.' Master'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row_master = mysqli_fetch_assoc($result_master)) {
                                $carddeck_id = $row_master['carddeck_id'];
                                $carddeck_name = $row_master['carddeck_name'];
                                ?>
                                <tr>
                                    <td>
                                        <div class="profile-cards-wrapper">
                                            <a class="carddeck-link" href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>"><?php echo show_card($carddeck_id, 'master'); ?></a><br />
                                            <small><span class="mastered"><i class="fas fa-medal"></i></span> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_mastered_on']; ?> <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $row_master['member_master_date']); ?></small>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        title_small('0 Master');
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