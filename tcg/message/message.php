<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    $member_id = $_SESSION['member_id'];

    if (isset($message_id)) {
        $sql_message = "SELECT message_id, message_sender_member_id, message_receiver_member_id, message_subject, message_text, message_date, message_read, message_system
                    FROM message
                    WHERE (message_receiver_member_id = '" . $member_id . "'
                        OR message_sender_member_id = '" . $member_id . "')
                      AND message_id = '" . $message_id . "'
                    LIMIT 1";
        $result_message = mysqli_query($link, $sql_message) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_message)) {
            $row_message = mysqli_fetch_assoc($result_message);

            if (isset($action) && $action == 'delete') {
                $breadcrumb = array(
                    '/' => 'Home',
                    '/message' => TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'],
                    '/message/delete' => TRANSLATIONS[$GLOBALS['language']]['message']['text_delete_message'],
                );
                breadcrumb($breadcrumb);
                title(TRANSLATIONS[$GLOBALS['language']]['message']['text_delete_message']);

                // delete message
                mysqli_query($link, "DELETE FROM message
                             WHERE (message_receiver_member_id = '" . $member_id . "'
                                 OR message_sender_member_id = '" . $member_id . "')
                               AND message_id = '".$message_id."'
                             LIMIT 1")
                OR die(mysqli_error($link));

                alert_box(TRANSLATIONS[$GLOBALS['language']]['message']['hint_message_deleted'], 'success');
            } else {
                // set message reading status to read
                mysqli_query($link, "UPDATE message
                                 SET message_read = 1
                                 WHERE message_id = '" . $message_id . "'
                                   AND message_receiver_member_id = '" . $member_id . "'
                                 LIMIT 1")
                OR die(mysqli_error($link));

                $breadcrumb = array(
                    '/' => 'Home',
                    '/message' => TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'],
                    '/message/' . $message_id => $row_message['message_subject'],
                );
                breadcrumb($breadcrumb);
                title($row_message['message_subject']);
                ?>
                <div class="row message-container">
                    <div class="col col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col col-12 col-md-8 order-2 order-md-1">
                                        <small>
                                            <span class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_sender'].':'; ?></span>
                                            <?php echo($row_message['message_system'] == 1 ? TRANSLATIONS[$GLOBALS['language']]['message']['text_system_message'] : member_link($row_message['message_sender_member_id'], '', true)); ?>
                                        </small>
                                    </div>
                                    <div class="col col-12 col-md-4 order-1 order-md-2 text-right">
                                        <small class="text-muted"><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_message['message_date']); ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?php echo nl2br($row_message['message_text']); ?></p>
                            </div>
                            <?php if ($row_message['message_receiver_member_id'] == $member_id) { ?>
                                <div class="card-footer">
                                    <div class="row">
                                        <?php if ($row_message['message_system'] == 0) { ?>
                                            <div class="col col-6">
                                                <a href="<?php echo HOST_URL; ?>/message/reply/<?php echo $message_id; ?>"
                                                   class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_button_reply']; ?></a>
                                            </div>
                                        <?php } ?>
                                        <div class="col <?php echo ($row_message['message_system'] == 1 ? 'col-12' : 'col-6'); ?> text-right">
                                            <a href="<?php echo HOST_URL; ?>/message/delete/<?php echo $message_id; ?>"
                                               class="btn btn-danger"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_button_delete']; ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/message' => TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'],
            );
            breadcrumb($breadcrumb);
            title(TRANSLATIONS[$GLOBALS['language']]['general']['text_pm']);

            alert_box(TRANSLATIONS[$GLOBALS['language']]['message']['hint_message_dont_exists'], 'danger');
        }
    } else {
        $breadcrumb = array(
            '/' => 'Home',
            '/message' => TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'],
        );
        breadcrumb($breadcrumb);
        title(TRANSLATIONS[$GLOBALS['language']]['general']['text_pm']);

        if ($message_box_type == 'inbox') {
            $wherestring = "WHERE message_receiver_member_id = '" . $member_id . "'";
        } elseif ($message_box_type == 'outbox') {
            $wherestring = "WHERE message_sender_member_id = '" . $member_id . "'
                              AND message_system = '0'";
        } else {
            $wherestring = "WHERE message_receiver_member_id = '" . $member_id . "'";
        }

        $sql_message = "SELECT message_id, message_sender_member_id, message_receiver_member_id, message_subject, message_date, message_read, message_system
                    FROM message
                    ".$wherestring."
                    ORDER BY message_date DESC";
        $result_message = mysqli_query($link, $sql_message) OR die(mysqli_error($link));
        $count_message = mysqli_num_rows($result_message);
        ?>
        <div class="row">
            <div class="col col-12 mb-3">
                <div class="row">
                    <div class="col col-6 mb-2 mb-md-0">
                        <a href="<?php echo HOST_URL; ?>/message/inbox" class="btn btn-outline-info btn-sm btn-block <?php echo ($message_box_type == 'inbox' ? 'active' : ''); ?>"><i class="fas fa-inbox"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_inbox']; ?></a>
                    </div>
                    <div class="col col-6 mb-2 mb-md-0">
                        <a href="<?php echo HOST_URL; ?>/message/outbox" class="btn btn-outline-info btn-sm btn-block <?php echo ($message_box_type == 'outbox' ? 'active' : ''); ?>"><i class="fas fa-envelope-open"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_outbox']; ?></a>
                    </div>
                </div>
            </div>
            <?php
            if ($message_box_type == 'inbox') {
                ?>
                <div class="col col-12 mb-3">
                    <a class="btn btn-primary"
                       href="<?php echo HOST_URL; ?>/message/write"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_write_message']; ?></a>
                </div>
                <?php
            }
            ?>
            <div class="col col-12">
                <?php
                if ($count_message) {
                    ?>
                    <div class="table-responsive">
                        <table id="message-table" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th data-field="sender"
                                    data-sortable="true"><?php echo ($message_box_type == 'inbox' ? TRANSLATIONS[$GLOBALS['language']]['message']['text_sender'] : TRANSLATIONS[$GLOBALS['language']]['message']['text_receiver']); ?></th>
                                <th data-field="subject"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_subject']; ?></th>
                                <th data-field="date"
                                    data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_date']; ?></th>
                                <th data-field="status"
                                    data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_read_status']; ?></th>
                                <th data-field="option"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row_message = mysqli_fetch_assoc($result_message)) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo($row_message['message_system'] == 1 ? TRANSLATIONS[$GLOBALS['language']]['message']['text_system_message'] : ($message_box_type == 'inbox' ? member_link($row_message['message_sender_member_id'], '', true) : member_link($row_message['message_receiver_member_id'], '', true))); ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo HOST_URL; ?>/message/<?php echo $row_message['message_id']; ?>"><?php echo $row_message['message_subject']; ?></a>
                                    </td>
                                    <td><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_message['message_date']); ?></td>
                                    <td><?php echo($row_message['message_read'] == 0 ? '<span class="badge badge-secondary"><i class="fas fa-times"></i> ' . TRANSLATIONS[$GLOBALS['language']]['message']['text_unread'] . '</span>' : '<span class="badge badge-success"><i class="fas fa-check"></i> ' . TRANSLATIONS[$GLOBALS['language']]['message']['text_read'] . '</span>'); ?></td>
                                    <td><a href="<?php echo HOST_URL; ?>/message/delete/<?php echo $row_message['message_id']; ?>" class="badge badge-danger"><i class="fas fa-trash-alt"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_button_delete']; ?></a></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else {
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['message']['hint_no_message_yet'], 'danger');
                }
                ?>
            </div>
        </div>
        <?php
    }
} else {
    show_no_access_message();
}
?>