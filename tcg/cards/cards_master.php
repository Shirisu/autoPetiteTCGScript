<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/cards' => TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'],
        '/cards/trade' => 'Master',
    );
    breadcrumb($breadcrumb);
    title('Master');

    $member_id = $_SESSION['member_id'];

    $sql_member_config = "SELECT member_master_order
                          FROM member
                          WHERE member_id = '".$member_id."'
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
    ?>
    <div class="row cards-sorting">
        <div class="col col-12 mb-3">
            <?php get_cards_menu('master'); ?>
        </div>
        <div class="col col-12 mb-3 cards-sorting-container">
            <?php
            if (TCG_MULTI_MASTER == true) {
                $sql_cards = "SELECT carddeck_id, carddeck_name, member_master_date, carddeck_active,
                                (SELECT COUNT(member_master_id)
                                 FROM member_master
                                 WHERE member_master_carddeck_id = mc.member_master_carddeck_id
                                   AND member_master_member_id = mc.member_master_member_id) AS master_count
                              FROM member_master as mc
                              JOIN carddeck ON carddeck_id = member_master_carddeck_id
                              WHERE member_master_member_id = '" . $member_id . "'
                                AND carddeck_active = 1
                              ORDER BY ".$master_order_multi;
            } else {
                $sql_cards = "SELECT carddeck_id, carddeck_name, member_master_date, carddeck_active
                              FROM member_master as mc
                              JOIN carddeck ON carddeck_id = member_master_carddeck_id
                              WHERE member_master_member_id = '" . $member_id . "'
                                AND carddeck_active = 1
                              ORDER BY ".$master_order;
            }
            $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
            $count_cards = mysqli_num_rows($result_cards);
            if ($count_cards) {
                title_small($count_cards.' Master');
                ?>
                <div class="row">
                    <div class="col col-12">
                        <table class="optional cards-sorting-table master-cards" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th></th>
                                <th data-searchable="false"><?php echo title_small($count_cards.' Master'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row_cards = mysqli_fetch_assoc($result_cards)) {
                                if ($row_cards['carddeck_active'] == 0) {
                                    ?>
                                    <tr>
                                        <td class="d-none"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_unkown']; ?></td>
                                        <td>
                                            <div class="cards-sorting-wrapper">
                                                <?php echo get_card(0, 'master'); ?><br/>
                                                <small><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_unkown']; ?></small>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    $carddeck_id = $row_cards['carddeck_id'];
                                    $carddeck_name = $row_cards['carddeck_name'];
                                    $master_date = $row_cards['member_master_date'];
                                    ?>
                                    <tr>
                                        <td class="d-none"><?php echo $carddeck_name; ?></td>
                                        <td>
                                            <div class="cards-sorting-wrapper">
                                                <a class="carddeck-link"
                                                   href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>"><?php echo get_card($carddeck_id, 'master'); ?></a><br/>
                                                <small><span class="mastered"><i
                                                            class="fas fa-medal"></i></span> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_mastered_on']; ?> <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $master_date); ?>
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
                    </div>
                </div>
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
    show_no_access_message_with_breadcrumb();
}
?>
