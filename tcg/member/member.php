<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    
    $breadcrumb = array(
        '/' => 'Home',
        '/member' => 'Member',
    );

    breadcrumb($breadcrumb);
    title('Member');

    $sql_member = "SELECT member_id, member_nick, member_last_login, member_register, member_level, member_rank_name
                   FROM member
                   JOIN member_rank ON member_rank_id = member_rank
                   WHERE member_active = 1
                   ORDER BY member_nick ASC";
    $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
    $count_member = mysqli_num_rows($result_member);
    ?>
    <div class="row">
        <div class="col">
            <?php
            if ($count_member) {
                ?>
                <div class="table-responsive">
                    <table id="member-table" data-mobile-responsive="true">
                        <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="nickname" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_nickname']; ?></th>
                            <th data-field="level" data-sortable="true">Level</th>
                            <th data-field="rank"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_rank']; ?></th>
                            <th data-field="registered" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_registered']; ?></th>
                            <th data-field="lastlogin" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_lastlogin']; ?></th>
                            <th data-field="online" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row_member = mysqli_fetch_assoc($result_member)) {
                            ?>
                            <tr>
                                <td><?php echo sprintf('%03d', $row_member['member_id']); ?></td>
                                <td><span class="d-none"><?php echo get_member_nick_plain($row_member['member_id']); ?></span><?php echo get_member_link($row_member['member_id'], '', true); ?></td>
                                <td><?php echo sprintf('%02d', $row_member['member_level']); ?></td>
                                <td><?php echo $row_member['member_rank_name']; ?></td>
                                <td><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $row_member['member_register']); ?></td>
                                <td><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_member['member_last_login']); ?></td>
                                <td><?php echo get_online_status($row_member['member_id']); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_no_member_yet'], 'danger');
            }
            ?>
        </div>
    </div>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>