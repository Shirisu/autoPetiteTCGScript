<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/memberarea' => TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'],
        '/memberarea/log' => 'Log',
    );
    breadcrumb($breadcrumb);
    title('Log');

    $member_id = $_SESSION['member_id'];

    $sql_log = "SELECT member_log_date, member_log_cat, member_log_text
                FROM member_log
                WHERE member_log_member_id  = '".$member_id."'
                  AND DATE_ADD(FROM_UNIXTIME(member_log_date), INTERVAL 14 DAY) >= NOW()
                ORDER BY member_log_date DESC";
    $result_log = mysqli_query($link, $sql_log) OR die(mysqli_error($link));
    $count_log = mysqli_num_rows($result_log);
    ?>
    <div class="row">
        <div class="col member-log-container">
            <?php
            if ($count_log) {
                ?>
                <table id="member-log-table" data-mobile-responsive="true">
                    <thead>
                    <tr>
                        <th data-field="date" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_date']; ?></th>
                        <th data-field="topic" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category']; ?></th>
                        <th data-field="text">Text</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($row_log = mysqli_fetch_assoc($result_log)) {
                        ?>
                        <tr>
                            <td><span class="d-none"><?php echo $row_log['member_log_date']; ?></span> <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_log['member_log_date']); ?></td>
                            <td><?php echo $row_log['member_log_cat']; ?></td>
                            <td><div class="overflow-auto"><?php echo nl2br($row_log['member_log_text']); ?></div></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['member']['hint_no_log_in_this_category_yet'], 'danger');
            }
            ?>
        </div>
    </div>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>