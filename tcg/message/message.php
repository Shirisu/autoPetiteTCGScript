<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    $member_id = $_SESSION['member_id'];

    if (isset($action) && $action == 'delete_all_systemmessages') {
        // delete messages
        mysqli_query($link, "DELETE FROM message
                     WHERE message_receiver_member_id = '" . $member_id . "'
                       AND message_system = '1'
                       ")
        or die(mysqli_error($link));

        alert_box(TRANSLATIONS[$GLOBALS['language']]['message']['hint_all_system_messages_deleted'], 'success');
    } elseif (isset($action) && $action == 'delete' && isset($message_id)) {
        // delete message
        mysqli_query($link, "DELETE FROM message
                 WHERE (message_receiver_member_id = '" . $member_id . "'
                     OR message_sender_member_id = '" . $member_id . "')
                   AND message_id = '" . $message_id . "'
                 LIMIT 1")
        OR die(mysqli_error($link));

        alert_box(TRANSLATIONS[$GLOBALS['language']]['message']['hint_message_deleted'], 'success');
    }

    if (!isset($action) && isset($message_id)) {
        $sql_message = "SELECT message_id, message_sender_member_id, message_receiver_member_id, message_subject, message_text, message_date, message_read, message_system
                FROM message
                WHERE (message_receiver_member_id = '" . $member_id . "'
                    OR message_sender_member_id = '" . $member_id . "')
                  AND message_id = '" . $message_id . "'
                LIMIT 1";
        $result_message = mysqli_query($link, $sql_message) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_message)) {
            $row_message = mysqli_fetch_assoc($result_message);

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
                                        <span
                                            class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_sender'] . ':'; ?></span>
                                        <?php echo($row_message['message_system'] == 1 ? TRANSLATIONS[$GLOBALS['language']]['message']['text_system_message'] : get_member_link($row_message['message_sender_member_id'], '', true)); ?>
                                    </small>
                                </div>
                                <div class="col col-12 col-md-4 order-1 order-md-2 text-right">
                                    <small
                                        class="text-muted"><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_message['message_date']); ?></small>
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
                                        <div class="d-grid col col-6">
                                            <a href="<?php echo HOST_URL; ?>/message/reply/<?php echo $message_id; ?>"
                                               class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_button_reply']; ?></a>
                                        </div>
                                    <?php } ?>
                                    <div
                                        class="d-grid col <?php echo($row_message['message_system'] == 1 ? 'col-12' : 'col-6'); ?> text-right">
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

        if (isset($message_box_type) && $message_box_type == 'inbox') {
            $wherestring = "WHERE message_receiver_member_id = '" . $member_id . "'";
        } elseif (isset($message_box_type) && $message_box_type == 'outbox') {
            $wherestring = "WHERE message_sender_member_id = '" . $member_id . "'
                          AND message_system = '0'";
        } else {
            $wherestring = "WHERE message_receiver_member_id = '" . $member_id . "'";
        }

        $sql_message = "SELECT message_id, message_sender_member_id, message_receiver_member_id, message_subject, message_date, message_read, message_system
                FROM message
                " . $wherestring . "
                ORDER BY message_date DESC";
        $result_message = mysqli_query($link, $sql_message) OR die(mysqli_error($link));
        $count_message = mysqli_num_rows($result_message);
        ?>
        <div class="row">
            <div class="col col-12 mb-1">
                <div class="row">
                    <div class="d-grid col col-6 mb-2">
                        <a href="<?php echo HOST_URL; ?>/message/inbox"
                           class="btn btn-outline-info btn-sm <?php echo($message_box_type == 'inbox' ? 'active' : ''); ?>"><i
                                class="fas fa-inbox"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_inbox']; ?>
                        </a>
                    </div>
                    <div class="d-grid col col-6 mb-2">
                        <a href="<?php echo HOST_URL; ?>/message/outbox"
                           class="btn btn-outline-info btn-sm <?php echo($message_box_type == 'outbox' ? 'active' : ''); ?>"><i
                                class="fas fa-envelope-open"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_outbox']; ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            if ($message_box_type == 'inbox') {
                ?>
                <div class="col col-12 mb-1">
                    <div class="row justify-content-center">
                        <div class="d-grid col col-auto mb-2">
                            <a class="btn btn-primary"
                               href="<?php echo HOST_URL; ?>/message/write"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_write_message']; ?></a>
                        </div>
                        <div class="d-grid col col-auto mb-2">
                            <a class="btn btn-danger"
                               href="<?php echo HOST_URL; ?>/message/delete/allsystemmessages"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_delete_all_system_messages']; ?></a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="col col-12">
                <?php
                if ($count_message) {
                ?>
                    <table class="optional w-100">
                        <thead>
                        <tr>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="row row-cols-1 row-cols-md-1 g-3">
                        <?php
                        while ($row_message = mysqli_fetch_assoc($result_message)) {
                        ?>
                            <tr class="col">
                                <td class="card w-100 h-100 mb-3">
                                    <div class="card-header">
                                        <span class="d-none"><?php echo $row_message['message_date']; ?></span>
                                        <div class="row">
                                            <div class="col-8">
                                                <small>
                                                    <?php
                                                    if ($message_box_type == 'inbox') {
                                                        echo TRANSLATIONS[$GLOBALS['language']]['message']['text_sender'];
                                                    } else {
                                                        echo TRANSLATIONS[$GLOBALS['language']]['message']['text_receiver'];
                                                    }
                                                    echo($row_message['message_system'] == 1 ? TRANSLATIONS[$GLOBALS['language']]['message']['text_system_message'] : ($message_box_type == 'inbox' ? get_member_link($row_message['message_sender_member_id'], '', true) : get_member_link($row_message['message_receiver_member_id'], '', true)));
                                                    echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_message['message_date']);
                                                    ?>
                                                </small>
                                            </div>
                                            <div class="col text-end">
                                                <?php echo($row_message['message_read'] == 0 ? '<span class="badge bg-secondary"><i class="fas fa-times"></i> ' . TRANSLATIONS[$GLOBALS['language']]['message']['text_unread'] . '</span>' : '<span class="badge bg-success"><i class="fas fa-check"></i> ' . TRANSLATIONS[$GLOBALS['language']]['message']['text_read'] . '</span>'); ?>
                                                <a href="<?php echo HOST_URL; ?>/message/delete/<?php echo $row_message['message_id']; ?>"
                                                   class="badge bg-danger"><i
                                                            class="fas fa-trash-alt"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_button_delete']; ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body text-center">
                                        <a href="<?php echo HOST_URL; ?>/message/<?php echo $row_message['message_id']; ?>"><?php echo $row_message['message_subject']; ?></a>
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
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['message']['hint_no_message_yet'], 'danger');
                }
                ?>
            </div>
        </div>
        <?php
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>