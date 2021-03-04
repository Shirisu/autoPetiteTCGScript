<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    if (isset($member_id)) {
        $sql_member = "SELECT member_id, member_nick, member_level, member_cards, member_master, member_register, member_last_login, member_wish, member_currency, member_text, member_rank_name
                       FROM member, member_rank
                       WHERE member_id = '".$member_id."'
                         AND member_active = 1
                         AND member_rank = member_rank_id
                       LIMIT 1";
        $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
        $count_member = mysqli_num_rows($result_member);

        if ($count_member) {
            $row_member = mysqli_fetch_assoc($result_member);

            $breadcrumb = array(
                '/' => 'Home',
                '/member' => 'Member',
                '/member/'.$member_id => $row_member['member_nick'],
            );

            breadcrumb($breadcrumb);
            title($row_member['member_nick'].' <small>'.get_online_status($row_member['member_id']).'</small>');
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
                            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/wishlist" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-star"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist']; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-xl-5 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col col-6 col-md-3 mb-2 font-weight-bold">Level</div>
                                <div class="col col-6 col-md-3 mb-2"><?php echo $row_member['member_level']; ?></div>
                                <div class="col col-6 col-md-3 mb-2 font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_rank']; ?></div>
                                <div class="col col-6 col-md-3 mb-2"><?php echo $row_member['member_rank_name']; ?></div>
                                <div class="col col-6 col-md-3 mb-2 font-weight-bold">Cards</div>
                                <div class="col col-6 col-md-3 mb-2"><?php echo $row_member['member_cards']; ?></div>
                                <div class="col col-6 col-md-3 mb-2 font-weight-bold">Master</div>
                                <div class="col col-6 col-md-3 mb-2"><?php echo $row_member['member_master']; ?></div>
                                <div class="col col-6 col-md-3 mb-2 font-weight-bold"><?php echo TCG_CURRENCY; ?></div>
                                <div class="col col-6 <?php echo (TCG_USE_WISH == true ? 'col-md-3' : 'col-md-9'); ?> mb-2"><?php echo $row_member['member_currency']; ?></div>
                                <?php if (TCG_USE_WISH == true) { ?>
                                    <div class="col col-6 col-md-3 mb-2 font-weight-bold"><?php echo TCG_WISH; ?></div>
                                    <div class="col col-6 col-md-3 mb-2"><?php echo $row_member['member_wish']; ?></div>
                                <?php } ?>
                                <div class="col col-6 col-md-6 mb-2 font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_registered']; ?></div>
                                <div class="col col-6 col-md-6 mb-2"><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $row_member['member_register']); ?></div>
                                <div class="col col-6 col-md-6 mb-2 font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_lastlogin']; ?></div>
                                <div class="col col-6 col-md-6 mb-2"><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_member['member_last_login']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-12 col-xl-7 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-text"><?php echo ($row_member['member_text'] ? nl2br($row_member['member_text']) : alert_box(TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_no_text'], 'info')); ?></div>
                        </div>
                    </div>
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