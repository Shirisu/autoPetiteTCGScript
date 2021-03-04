<?php
$breadcrumb = array(
    '/' => 'Home',
    '/team' => 'Team',
);
breadcrumb($breadcrumb);

global $link;
$sql_a = "SELECT member_id, member_nick, member_rank_name, member_language, member_active
          FROM member, member_rank
          WHERE member_rank != 5
            AND member_rank = member_rank_id
          ORDER BY member_rank, member_nick";
$result_a = mysqli_query($link, $sql_a) OR die(mysqli_error($link));
$anz_a = mysqli_num_rows($result_a);
title('Team ('.$anz_a.')');
?>
<div class="row">
    <?php
    if ($anz_a) {
        while ($row_a = mysqli_fetch_assoc($result_a)) {
            if ($row_a['member_active'] == 1) {
                $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_active'];
            } else {
                $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive'];
            }
            ?>
            <div class="col col-12 col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <?php echo get_member_link($row_a['member_id']); ?>
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            <p>
                                 <span class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_rank']; ?>:</span>
                                <?php echo $row_a['member_rank_name']; ?>
                            </p>
                            <p>
                                 <span class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?>:</span>
                                <?php echo $status; ?>
                            </p>
                            <p>
                                 <span class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_main_language']; ?>:</span>
                                <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language_'.$row_a['member_language'].'']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>