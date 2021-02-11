<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 4)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/distributecards' => TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['card_distribute_headline'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['admin']['card_distribute_headline']);

    if (isset($_POST['member_id']) && isset($_POST['quantity']) && isset($_POST['topic'])) {
        $member_id = mysqli_real_escape_string($link, trim($_POST['member_id']));
        $quantity = mysqli_real_escape_string($link, trim($_POST['quantity']));
        $topic = mysqli_real_escape_string($link, strip_tags(trim($_POST['topic'])));

        $sql_language = "SELECT member_language
                         FROM member
                         WHERE member_id = '".$member_id."'
                         LIMIT 1";
        $result_language = mysqli_query($link, $sql_language) OR die(mysqli_error($link));
        $row_language = mysqli_fetch_assoc($result_language);
        $language = $row_language['member_language'];

        insert_cards($member_id, $quantity);
        $inserted_cards_text = TRANSLATIONS[$language]['admin']['text_log_distribute_cards'].': '.implode(', ',$_SESSION['insert_cards']);
        insert_log(TRANSLATIONS[$language]['admin']['text_log_distribute_cards_topic'], $inserted_cards_text, $member_id);
        $text = TRANSLATIONS[$language]['admin']['text_distribution_topic'].': '.$topic.' - '.$inserted_cards_text;
        send_message($_SESSION['member_id'], $member_id, TRANSLATIONS[$language]['admin']['text_log_distribute_cards_topic'], $text);

        alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_card_add'], 'success');
    }

    $sql_member = "SELECT member_id, member_nick, member_language
                   FROM member
                   WHERE member_active = 1
                   ORDER BY member_nick ASC";
    $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
    $sql_carddeck_quantity = "SELECT carddeck_id
                              FROM carddeck
                              WHERE carddeck_active = 1";
    $result_carddeck_quantity = mysqli_query($link, $sql_carddeck_quantity) OR die(mysqli_error($link));
    $carddeck_quantity = mysqli_num_rows($result_carddeck_quantity);
    if ($carddeck_quantity <= 50) {
        $random_carddeck_quantity = $carddeck_quantity;
    } else {
        $random_carddeck_quantity = 50;
    }
    ?>
    <form action="/administration/distributecards" method="post">
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
                        for ($i = 1; $i <= $random_carddeck_quantity; $i++) {
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
                        <span class="input-group-text" id="ariaDescribedbyTopic"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_distribution_topic']; ?></span>
                    </div>
                    <input type="text" class="form-control" id="topic" name="topic" aria-describedby="ariaDescribedbyTopic" maxlength="255" value="" required />
                </div>
                <small id="ariaDescribedbyTopic" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['hint_card_distribution']; ?></small>
            </div>
            <div class="form-group col col-12">
                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_add']; ?></button>
            </div>
        </div>
    </form>
    <?php
} else {
    show_no_access_message();
}
?>
