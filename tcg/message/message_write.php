<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    $member_id = $_SESSION['member_id'];

    if (isset($message_id)) {
        $sql_message = "SELECT message_id, message_sender_member_id, message_subject, message_text, message_date, message_read, message_system
                        FROM message
                        WHERE message_receiver_member_id = '" . $member_id . "'
                          AND message_id = '" . $message_id . "'
                          AND message_system = '0'
                        LIMIT 1";
        $result_message = mysqli_query($link, $sql_message) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_message)) {
            $row_message = mysqli_fetch_assoc($result_message);

            if (isset($_POST['message_subject']) && isset($_POST['message_text']) && isset($_POST['message_receiver'])) {
                $breadcrumb = array(
                    '/' => 'Home',
                    '/message' => TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'],
                    '/message/reply/' => TRANSLATIONS[$GLOBALS['language']]['message']['text_message_sent'],
                );
                breadcrumb($breadcrumb);
                title(TRANSLATIONS[$GLOBALS['language']]['message']['text_message_sent']);

                $message_subject = mysqli_real_escape_string($link, trim($_POST['message_subject']));
                $message_text = mysqli_real_escape_string($link, trim($_POST['message_text']));
                $message_receiver = mysqli_real_escape_string($link, trim($_POST['message_receiver']));

                insert_message($member_id, $message_receiver, $message_subject, $message_text);

                alert_box(TRANSLATIONS[$GLOBALS['language']]['message']['hint_message_sent'], 'success');
            } else {
                $breadcrumb = array(
                    '/' => 'Home',
                    '/message' => TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'],
                    '/message/' . $message_id => $row_message['message_subject'],
                    '/message/reply/' . $message_id => TRANSLATIONS[$GLOBALS['language']]['message']['text_write_reply'],
                );
                breadcrumb($breadcrumb);
                title(TRANSLATIONS[$GLOBALS['language']]['message']['text_write_reply']);

                $sql_member = "SELECT member_id, member_nick
                       FROM member
                       WHERE member_active = 1
                         AND member_id = '".$row_message['message_sender_member_id']."'
                       LIMIT 1";
                $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));

                $message_text = PHP_EOL.PHP_EOL.'-----------------'.PHP_EOL.get_member_nick_plain($row_message['message_sender_member_id']).' '.TRANSLATIONS[$GLOBALS['language']]['message']['text_wrote_on'].' '.date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_message['message_date']).':'.PHP_EOL.$row_message['message_text'];
                ?>
                <form action="<?php echo HOST_URL; ?>/message/reply/<?php echo $message_id; ?>" method="post">
                    <div class="row message-container">
                        <div class="form-group col col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ariaDescribedbySubject"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_subject']; ?></span>
                                </div>
                                <input type="text" class="form-control" aria-describedby="ariaDescribedbySubject"
                                       id="message_subject" name="message_subject" maxlength="50"
                                       value="RE: <?php echo trim($row_message['message_subject']); ?>" required />
                            </div>
                        </div>
                        <div class="form-group col col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ariaDescribedbyReceiver"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_receiver']; ?></span>
                                </div>
                                <select class="custom-select" disabled aria-describedby="ariaDescribedbyReceiver" required>
                                    <option selected disabled hidden value=""></option>
                                    <?php
                                    while ($row_member = mysqli_fetch_assoc($result_member)) {
                                        ?>
                                        <option value="<?php echo $row_member['member_id']; ?>" selected><?php echo $row_member['member_nick']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col col-12 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ariaDescribedbyText">Text</span>
                                </div>
                                <textarea class="form-control" id="message_text" name="message_text"
                                          aria-describedby="ariaDescribedbyText" rows="10"><?php echo $message_text; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group col col-12">
                            <input type="hidden" class="form-control" id="message_receiver"
                                   name="message_receiver"
                                   value="<?php echo $row_message['message_sender_member_id']; ?>"/>
                            <button type="submit"
                                    class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_button_reply']; ?></button>
                        </div>
                    </div>
                </form>
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
        if (isset($_POST['message_subject']) && isset($_POST['message_text']) && isset($_POST['message_receiver'])) {
            $breadcrumb = array(
                '/' => 'Home',
                '/message' => TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'],
                '/message/write' => TRANSLATIONS[$GLOBALS['language']]['message']['text_message_sent'],
            );
            breadcrumb($breadcrumb);
            title(TRANSLATIONS[$GLOBALS['language']]['message']['text_message_sent']);

            $message_subject = mysqli_real_escape_string($link, trim($_POST['message_subject']));
            $message_text = mysqli_real_escape_string($link, trim($_POST['message_text']));
            $message_receiver = mysqli_real_escape_string($link, trim($_POST['message_receiver']));

            insert_message($member_id, $message_receiver, $message_subject, $message_text);

            alert_box(TRANSLATIONS[$GLOBALS['language']]['message']['hint_message_sent'], 'success');
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/message' => TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'],
                '/message/write/' => TRANSLATIONS[$GLOBALS['language']]['message']['text_write_message'],
            );
            breadcrumb($breadcrumb);
            title(TRANSLATIONS[$GLOBALS['language']]['message']['text_write_message']);

            $sql_member = "SELECT member_id, member_nick
                       FROM member
                       WHERE member_active = 1
                         AND member_id != '" . $member_id . "'
                       LIMIT 1";
            $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
            ?>
            <form action="<?php echo HOST_URL; ?>/message/write" method="post">
                <div class="row message-container">
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                      id="ariaDescribedbySubject"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_subject']; ?></span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="ariaDescribedbySubject"
                                   id="message_subject" name="message_subject" maxlength="50"
                                   value="" required/>
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                      id="ariaDescribedbyReceiver"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_receiver']; ?></span>
                            </div>
                            <select class="custom-select" id="message_receiver" name="message_receiver"
                                    aria-describedby="ariaDescribedbyReceiver" required>
                                <option selected disabled hidden value=""></option>
                                <?php
                                while ($row_member = mysqli_fetch_assoc($result_member)) {
                                    ?>
                                    <option
                                        value="<?php echo $row_member['member_id']; ?>" <?php echo(isset($receiver_id) && $receiver_id == $row_member['member_id'] ? 'selected' : ''); ?>><?php echo $row_member['member_nick']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyText">Text</span>
                            </div>
                                <textarea class="form-control" id="message_text" name="message_text"
                                          aria-describedby="ariaDescribedbyText" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="form-group col col-12">
                        <button type="submit"
                                class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['message']['text_button_reply']; ?></button>
                    </div>
                </div>
            </form>
            <?php
        }
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>