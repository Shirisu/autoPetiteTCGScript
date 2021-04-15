<?php
$breadcrumb = array(
    '/' => 'Home',
    '/activation' => TRANSLATIONS[$GLOBALS['language']]['activation']['headline'],
);
breadcrumb($breadcrumb);

title(TRANSLATIONS[$GLOBALS['language']]['activation']['headline']);
?>

<div class="row">
    <div class="col col-12">
        <?php
        if (isset($activation_code)) {
            global $link;
            $activation_code = mysqli_real_escape_string($link, trim($activation_code));
            $sql_activation = "SELECT member_activation_member_id
                               FROM member_activation
                               WHERE member_activation_code = '".$activation_code."'
                               LIMIT 1;";
            $result_activation = mysqli_query($link, $sql_activation) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_activation)) {
                $row_activation = mysqli_fetch_assoc($result_activation);
                $new_member_id = $row_activation['member_activation_member_id'];

                $sql_member = "SELECT member_id, member_active
                               FROM member
                               WHERE member_id = '".$new_member_id."'
                               LIMIT 1;";
                $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
                if (mysqli_num_rows($result_member)) {
                    $row_member = mysqli_fetch_assoc($result_member);

                    if ($row_member['member_active'] == 1) {
                        alert_box(TRANSLATIONS[$GLOBALS['language']]['activation']['hint_already_activated'], 'danger');
                    } else {
                        $sql_cardecks_quantity = "SELECT carddeck_id
                                                  FROM carddeck
                                                  WHERE carddeck_active = 1";
                        $result_carddecks_quantity = mysqli_query($link, $sql_cardecks_quantity) OR die(mysqli_error($link));
                        $carddeck_quantity = mysqli_num_rows($result_carddecks_quantity);
                        if ($carddeck_quantity) {
                            mysqli_query($link, "UPDATE member
                                             SET member_active = 1
                                             WHERE member_id = ".$new_member_id."
                                             LIMIT 1")
                            OR die(mysqli_error($link));

                            $use_carddeck_quantity = TCG_CARDS_START_PACKAGE;

                            insert_cards($new_member_id, $use_carddeck_quantity);
                            $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['register']['start_package'] . ': ' . implode(', ', $_SESSION['insert_cards']);
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_register'], $inserted_cards_text, $new_member_id);

                            alert_box(TRANSLATIONS[$GLOBALS['language']]['activation']['hint_success_activation'], 'success');
                        } else {
                            alert_box(TRANSLATIONS[$GLOBALS['language']]['activation']['hint_not_enough_carddecks'], 'danger');
                        }
                    }
                } else {
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['activation']['hint_no_member'], 'danger');
                }
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['activation']['hint_wrong_code'], 'danger');
            }
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['activation']['hint_no_code'], 'danger');
        }
        ?>
    </div>
</div>
