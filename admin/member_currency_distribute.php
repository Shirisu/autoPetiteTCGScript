<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 4) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/distributecurrency' => TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['currency_distribute_headline'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['admin']['currency_distribute_headline']);

    if (isset($_POST['member_id']) && isset($_POST['quantity']) && isset($_POST['topic'])) {
        $member_id = mysqli_real_escape_string($link, trim($_POST['member_id']));
        $quantity = mysqli_real_escape_string($link, trim($_POST['quantity']));
        $topic = mysqli_real_escape_string($link, strip_tags(trim($_POST['topic'])));

        insert_currency($member_id, $quantity);
        $inserted_currency_text = TRANSLATIONS[$GLOBALS['language']]['admin']['text_log_distribute_currency'].': '.$quantity;

        insert_log(TRANSLATIONS[$GLOBALS['language']]['admin']['text_log_distribute_currency_topic'], $inserted_currency_text, $member_id);
        $text = $inserted_currency_text.' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['text_log_distribute_currency_topic'].': '.$topic;
        send_message($_SESSION['member_id'], $member_id, $topic, $text);

        alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_currency_add'], 'success');
    }

    $sql_member = "SELECT member_id, member_nick
                   FROM member
                   WHERE member_active = 1
                   ORDER BY member_nick ASC";
    $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
    ?>
    <form action="/administration/distributecurrency" method="post">
        <div class="row align-items-center">
            <div class="form-group col col-12 col-md-6 mb-2">
                <?php
                if (mysqli_num_rows($result_member)) {
                    ?>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyMember">Member</span>
                        </div>
                        <select class="custom-select" id="member_id" name="member_id" aria-describedby="ariaDescribedbyMember" required>
                            <option selected disabled hidden value=""></option>
                            <?php
                            while ($row_member = mysqli_fetch_assoc($result_member)) {
                                ?>
                                <option value="<?php echo $row_member['member_id']; ?>"><?php echo $row_member['member_nick']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                } else {
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_no_member_yet'], 'danger');
                }
                ?>
            </div>
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyQuantity"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_quantity']; ?></span>
                    </div>
                    <select class="custom-select" id="quantity" name="quantity" aria-describedby="ariaDescribedbyQuantity" required>
                        <option selected disabled hidden value=""></option>
                        <?php
                        for ($i = 1; $i <= 50; $i++) {
                            ?>
                            <option><?php echo $i; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyTopic"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_card_distribution_topic']; ?></span>
                    </div>
                    <input type="text" class="form-control" id="topic" name="topic" aria-describedby="ariaDescribedbyTopic" maxlength="255" value="" required />
                </div>
                <small id="ariaDescribedbyTopic" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['hint_currency_distribution']; ?></small>
            </div>
            <div class="form-group col col-12">
                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_add']; ?></button>
            </div>
        </div>
    </form>
    <?php
}
?>