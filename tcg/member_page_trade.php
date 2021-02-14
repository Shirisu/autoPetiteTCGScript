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
                '/member/'.$member_id.'/trade' => 'Trade',
            );

            breadcrumb($breadcrumb);
            title($row_member['member_nick'].' <small>'.get_online_status($member_id).'</small>');
            ?>
            <div class="row member-profile">
                <div class="col col-12 mb-3">
                    <div class="row">
                        <div class="col col-6 col-md-3 mb-2 mb-md-0">
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/trade" class="btn btn-outline-info btn-sm btn-block active"><i class="fas fa-exchange-alt"></i> Trade</a>
                        </div>
                        <div class="col col-6 col-md-3 mb-2 mb-md-0">
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/collect" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-heart"></i> Collect</a>
                        </div>
                        <div class="col col-6 col-md-3 mb-2 mb-md-0">
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/master" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-award"></i> Master</a>
                        </div>
                        <div class="col col-6 col-md-3 mb-2 mb-md-0">
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/wishlist" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-star"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist']; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col col-12 mb-3 member-cards-container">
                    <?php
                    $sql_cards = "SELECT member_cards_id, member_cards_number, carddeck_id, carddeck_name,
                                     (SELECT COUNT(member_cards_id)
                                      FROM member_cards
                                      WHERE member_cards_member_id = '".$member_id."'
                                        AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                                        AND mc.member_cards_number = member_cards_number
                                        AND member_cards_cat = 3
                                        AND member_cards_active = 1
                                      GROUP BY member_cards_carddeck_id, member_cards_number) as card_count
                                  FROM member_cards mc, carddeck
                                  WHERE member_cards_member_id = '".$member_id."'
                                    AND member_cards_cat = 3
                                    AND member_cards_active = 1
                                    AND member_cards_carddeck_id = carddeck_id
                                  GROUP BY member_cards_carddeck_id, member_cards_number
                                  ORDER BY carddeck_name, member_cards_number ASC";
                    $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
                    $count_cards = mysqli_num_rows($result_cards);
                    if ($count_cards) {
                        title_small($count_cards.' Trade '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']);
                        ?>
                        <table class="optional profile-cards trade-cards" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th><?php echo title_small($count_cards.' Trade '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row_cards = mysqli_fetch_assoc($result_cards)) {
                                $carddeck_id = $row_cards['carddeck_id'];
                                $carddeck_name = $row_cards['carddeck_name'];
                                $cardnumber_plain = $row_cards['member_cards_number'];
                                $cardnumber = sprintf("%'.02d", $cardnumber_plain);
                                $card_count = $row_cards['card_count'];
                                ?>
                                <tr>
                                    <td>
                                        <div class="profile-cards-wrapper<?php echo ($card_count > 1 ? ' show-counter" data-count="'.$card_count.'x' : ''); ?>">
                                            <?php echo ($member_id != $_SESSION['member_id'] ? '<a href="'.HOST_URL.'/trade/'.$row_cards['member_cards_id'].'">' : ''); ?><?php echo show_card($carddeck_id, $cardnumber_plain); ?><?php echo ($member_id != $_SESSION['member_id'] ? '</a>' : ''); ?>
                                            <a class="carddeck-link" href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>"><small><?php echo $carddeck_name.$cardnumber; ?></small></a>
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
                        title_small('0 Trade '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']);
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