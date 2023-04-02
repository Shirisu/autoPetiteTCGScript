<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    if (isset($member_id)) {
        $sql_member_config = "SELECT member_master_order
                          FROM member
                          WHERE member_id = '".$_SESSION['member_id']."'
                          LIMIT 1";
        $result_member_config = mysqli_query($link, $sql_member_config) OR die(mysqli_error($link));
        $row_member_config = mysqli_fetch_assoc($result_member_config);

        if ($row_member_config['member_master_order'] == 0) {
            $master_order = 'carddeck_name';
            $master_order_multi = 'carddeck_name, member_master_date DESC';
        } elseif ($row_member_config['member_master_order'] == 1) {
            $master_order = 'member_master_date DESC';
            $master_order_multi = 'member_master_date DESC, carddeck_name';
        } else {
            $master_order = 'carddeck_name';
            $master_order_multi = 'carddeck_name, member_master_date DESC';
        }

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
                '/member/'.$member_id.'/master' => 'Master',
            );

            breadcrumb($breadcrumb);
            title($row_member['member_nick'].' <small>'.get_online_status($member_id).'</small>');
            ?>
            <div class="row member-profile">
                <div class="col col-12 mb-3">
                    <?php get_member_menu($member_id, 'master'); ?>
                </div>
                <div class="col col-12 mb-3 member-cards-container">
                    <?php
                    if (TCG_MULTI_MASTER == true) {
                        $sql_master = "SELECT carddeck_id, carddeck_name, member_master_date, carddeck_active,
                                        (SELECT COUNT(member_master_id)
                                         FROM member_master
                                         WHERE member_master_carddeck_id = mc.member_master_carddeck_id
                                           AND member_master_member_id = mc.member_master_member_id) AS master_count
                                      FROM member_master as mc
                                      JOIN carddeck ON carddeck_id = member_master_carddeck_id
                                      WHERE member_master_member_id = '" . $member_id . "'
                                        AND carddeck_active = 1
                                      GROUP BY member_master_carddeck_id
                                      ORDER BY ".$master_order;
                    } else {
                        $sql_master = "SELECT member_master_id, member_master_date, carddeck_id, carddeck_name, carddeck_active
                                   FROM member_master
                                   JOIN carddeck ON carddeck_id = member_master_carddeck_id
                                   WHERE member_master_member_id = '".$member_id."'
                                     AND carddeck_active = 1
                                   ORDER BY ".$master_order;
                    }
                    $result_master = mysqli_query($link, $sql_master) OR die(mysqli_error($link));
                    $count_master = mysqli_num_rows($result_master);
                    if ($count_master) {
                        title_small($count_master.' Master');
                        ?>
                        <table class="optional profile-cards master-cards" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th></th>
                                <th data-searchable="false"><?php echo title_small($count_master.' Master'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row_master = mysqli_fetch_assoc($result_master)) {
                                if ($row_master['carddeck_active'] == 0) {
                                    ?>
                                    <tr>
                                        <td class="d-none"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_unkown']; ?></td>
                                        <td>
                                            <div class="profile-cards-wrapper">
                                                <?php echo get_card(0, 'master'); ?><br/>
                                                <small><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_unkown']; ?></small>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    $carddeck_id = $row_master['carddeck_id'];
                                    $carddeck_name = $row_master['carddeck_name'];
                                    $master_count = 0;

                                    if (TCG_MULTI_MASTER == true) {
                                        $master_count = $row_master['master_count'];
                                    }
                                    ?>
                                    <tr>
                                        <td class="d-none"><?php echo $carddeck_name; ?></td>
                                        <td>
                                            <div class="profile-cards-wrapper">
                                                <a class="carddeck-link<?php echo($master_count > 1 ? ' show-counter" data-count="' . $master_count . 'x' : ''); ?>"
                                                   href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>"><?php echo get_card($carddeck_id, 'master'); ?></a><br/>
                                                <small><span class="mastered"><i
                                                            class="fas fa-medal"></i></span> <?php echo (TCG_MULTI_MASTER == true ? TRANSLATIONS[$GLOBALS['language']]['general']['text_mastered_on_first'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_mastered_on']); ?> <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $row_master['member_master_date']); ?>
                                                </small>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
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
    show_no_access_message_with_breadcrumb();
}
?>