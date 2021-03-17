<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    if (isset($level_id)) {
        $sql = "SELECT member_level_name, member_level_from, member_level_to
                FROM member_level
                WHERE member_level_id = '".$level_id."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);

            $level_name = $row['member_level_name'];
            $level_from = $row['member_level_from'];
            $level_to = $row['member_level_to'];
            if (isset($_POST['level_name']) && isset($_POST['level_from']) && isset($_POST['level_to'])) {
                $level_name = mysqli_real_escape_string($link, strip_tags(trim($_POST['level_name'])));
                $level_from = mysqli_real_escape_string($link, strip_tags(trim($_POST['level_from'])));
                $level_to = mysqli_real_escape_string($link, strip_tags(trim($_POST['level_to'])));

                mysqli_query($link, "UPDATE member_level
                     SET member_level_name = '" . $level_name . "',
                         member_level_from = '" . $level_from . "',
                         member_level_to = '" . $level_to . "'
                     WHERE member_level_id = " . $level_id . "
                     LIMIT 1")
                OR die(mysqli_error($link));

                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_save'], 'success');
            }

            $breadcrumb = array(
                '/' => 'Home',
                '/administration' => 'Administration',
                '/administration/editlevel' => TRANSLATIONS[$GLOBALS['language']]['admin']['level_administration_headline'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['admin']['level_edit_headline'],
                '/administration/editlevel/' . $level_id => ($level_name == '' ? 'Level '.$level_id : $level_name),
            );
            breadcrumb($breadcrumb);

            title(TRANSLATIONS[$GLOBALS['language']]['admin']['level_edit_headline']);

            ?>
            <form action="<?php echo HOST_URL; ?>/administration/editlevel/<?php echo $level_id; ?>" method="post">
                <div class="row align-items-center">
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyId">ID</span>
                            </div>
                            <input type="text" class="form-control" maxlength="55" value="<?php echo $level_id; ?>" disabled />
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyName"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_name']; ?></span>
                            </div>
                                <input type="text" class="form-control" id="level_name" name="level_name" aria-describedby="ariaDescribedbyName" maxlength="55" value="<?php echo $level_name; ?>" />
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyFrom"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_level_from']; ?></span>
                            </div>
                            <input type="number" class="form-control" id="level_from" name="level_from" min="0" aria-describedby="ariaDescribedbyFrom" maxlength="11" value="<?php echo $level_from; ?>" required />
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyTo"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_level_to']; ?></span>
                            </div>
                            <input type="number" class="form-control" id="level_to" name="level_to" min="1" aria-describedby="ariaDescribedbyTo" maxlength="11" value="<?php echo $level_to; ?>" required />
                        </div>
                    </div>
                    <div class="form-group col col-12">
                        <button type="submit"
                                class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_save']; ?></button>
                    </div>
                </div>
            </form>
            <?php
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/administration' => 'Administration',
                '/administration/editlevel' => TRANSLATIONS[$GLOBALS['language']]['admin']['level_administration_headline'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['admin']['level_edit_headline'],
            );
            breadcrumb($breadcrumb);

            title(TRANSLATIONS[$GLOBALS['language']]['admin']['level_edit_headline']);
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_valid_id'], 'danger');
        }
    } else {
        $breadcrumb = array(
            '/' => 'Home',
            '/administration' => 'Administration',
            '/administration/editlevel' => TRANSLATIONS[$GLOBALS['language']]['admin']['level_administration_headline'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['admin']['level_edit_headline'],
        );
        breadcrumb($breadcrumb);

        title(TRANSLATIONS[$GLOBALS['language']]['admin']['level_edit_headline']);

        $sql = "SELECT member_level_id, member_level_name, member_level_from, member_level_to
                FROM member_level
                ORDER BY member_level_id ASC";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $count = mysqli_num_rows($result);
        if ($count) {
            ?>
            <div class="row">
                <div class="col">
                    <table id="admin-member-edit-table" data-mobile-responsive="true">
                        <thead>
                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="name"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_name']; ?></th>
                            <th data-field="from"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_level_from']; ?></th>
                            <th data-field="to"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_level_to']; ?></th>
                            <th data-field="options"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['member_level_id']; ?></td>
                                <td><?php echo $row['member_level_name']; ?></td>
                                <td><?php echo $row['member_level_from']; ?></td>
                                <td><?php echo $row['member_level_to']; ?></td>
                                <td><a href="<?php echo HOST_URL; ?>/administration/editlevel/<?php echo $row['member_level_id']; ?>">Edit</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_data'], 'danger');
        }
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>