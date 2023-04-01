<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/addgame' => TRANSLATIONS[$GLOBALS['language']]['admin']['level_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['level_add_headline'],
    );
    breadcrumb($breadcrumb);

    title(TRANSLATIONS[$GLOBALS['language']]['admin']['level_add_headline']);

    if (isset($_POST['level_name']) && isset($_POST['level_from']) && isset($_POST['level_to'])) {
        $level_name = mysqli_real_escape_string($link, strip_tags(trim($_POST['level_name'])));
        $level_from = mysqli_real_escape_string($link, strip_tags(trim($_POST['level_from'])));
        $level_to = mysqli_real_escape_string($link, strip_tags(trim($_POST['level_to'])));

        if ($level_from < $level_to) {
            $sql_check_before_insert = "SELECT member_level_id
                                    FROM member_level
                                    WHERE member_level_name = '".$level_name."'
                                      AND member_level_from = '".$level_from."'
                                      AND member_level_to = '".$level_to."'
                                    LIMIT 1";
            $result_check_before_insert = mysqli_query($link, $sql_check_before_insert);
            if (!mysqli_num_rows($result_check_before_insert)) {
                mysqli_query($link, "
                INSERT INTO member_level
                (member_level_name, member_level_from, member_level_to)
                VALUES
                ('" . $level_name . "', '" . $level_from . "', '" . $level_to . "')")
                OR die(mysqli_error($link));

                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_level_add'], 'success');
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_duplicate_entry'], 'danger');
            }
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_level_to_cards_too_low'], 'danger');
        }
    }

    $sql_level = "SELECT member_level_to
                  FROM member_level
                  ORDER BY member_level_id DESC";
    $result_level = mysqli_query($link, $sql_level);
    if (mysqli_num_rows($result_level)) {
        $row_level = mysqli_fetch_assoc($result_level);
        $level_from = $row_level['member_level_to'] + 1;
    } else {
        $level_from = 1;
    }

    ?>
    <form action="<?php echo HOST_URL; ?>/administration/addlevel" method="post">
        <div class="row align-items-center">
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text" id="ariaDescribedbyName"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_name']; ?></span>
                    <input type="text" class="form-control" id="level_name" name="level_name" aria-describedby="ariaDescribedbyName" maxlength="55" value="" />
                </div>
            </div>
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text" id="ariaDescribedbyFrom"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_level_from']; ?></span>
                    <input type="number" class="form-control" id="level_from" name="level_from" min="<?php echo $level_from; ?>" aria-describedby="ariaDescribedbyFrom" maxlength="11" value="" required />
                </div>
            </div>
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text" id="ariaDescribedbyTo"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_level_to']; ?></span>
                    <input type="number" class="form-control" id="level_to" name="level_to" min="<?php echo $level_from + 1; ?>" aria-describedby="ariaDescribedbyTo" maxlength="11" value="" required />
                </div>
            </div>
            <div class="form-group col col-12 mb-2">
                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_add']; ?></button>
            </div>
        </div>
    </form>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>