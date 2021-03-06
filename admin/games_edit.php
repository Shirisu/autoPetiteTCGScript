<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    if (isset($games_id)) {
        $sql = "SELECT games_name, games_interval, games_status, games_icon
                FROM games
                WHERE games_id = '".$games_id."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);

            $games_name = $row['games_name'];
            $games_interval = $row['games_interval'];
            $games_icon = $row['games_icon'];
            $games_status = $row['games_status'];
            if (isset($_POST['games_name']) && isset($_POST['games_interval']) && isset($_POST['games_icon']) && isset($_POST['games_status'])) {
                $games_name = mysqli_real_escape_string($link, strip_tags(trim($_POST['games_name'])));
                $games_interval = mysqli_real_escape_string($link, strip_tags(trim($_POST['games_interval'])));
                $games_icon = mysqli_real_escape_string($link, strip_tags(trim($_POST['games_icon'])));
                $games_status = mysqli_real_escape_string($link, strip_tags(trim($_POST['games_status'])));

                if ($games_id > 3) {
                    mysqli_query($link, "UPDATE games
                         SET games_name = '" . $games_name . "',
                             games_interval = '" . $games_interval . "',
                             games_icon = '" . $games_icon . "',
                             games_status = '" . $games_status . "'
                         WHERE games_id = " . $games_id . "
                         LIMIT 1")
                    OR die(mysqli_error($link));
                } else {
                    mysqli_query($link, "UPDATE games
                         SET games_interval = '" . $games_interval . "'
                         WHERE games_id = " . $games_id . "
                         LIMIT 1")
                    OR die(mysqli_error($link));
                }

                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_save'], 'success');
            }

            $breadcrumb = array(
                '/' => 'Home',
                '/administration' => 'Administration',
                '/administration/editgame' => TRANSLATIONS[$GLOBALS['language']]['admin']['games_administration_headline'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['admin']['games_edit_headline'],
                '/administration/editgame/' . $games_id => $games_name,
            );
            breadcrumb($breadcrumb);

            title(TRANSLATIONS[$GLOBALS['language']]['admin']['games_edit_headline']);

            ?>
            <form action="<?php echo HOST_URL; ?>/administration/editgame/<?php echo $games_id; ?>" method="post">
                <div class="row align-items-center">
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyId">ID</span>
                            </div>
                            <input type="text" class="form-control" maxlength="55" value="<?php echo $games_id; ?>" disabled />
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyName"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_name']; ?></span>
                            </div>
                            <?php if ($games_id > 3) { ?>
                                <input type="text" class="form-control" id="games_name" name="games_name" aria-describedby="ariaDescribedbyName" maxlength="55" value="<?php echo $games_name; ?>" required />
                            <?php } else {?>
                                <input type="text" class="form-control" maxlength="55" value="<?php echo $games_name; ?>" disabled />
                                <input type="hidden" id="games_name" name="games_name" aria-describedby="ariaDescribedbyName" maxlength="55" value="<?php echo $games_name; ?>" required />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyInterval"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_interval']; ?></span>
                            </div>
                            <select class="custom-select" id="games_interval" name="games_interval" aria-describedby="ariaDescribedbyInterval" required>
                                <option selected disabled hidden value=""></option>
                                <option value="1800" <?php echo ($games_interval == '1800' ? 'selected' : ''); ?>>30 min</option>
                                <option value="3600" <?php echo ($games_interval == '3600' ? 'selected' : ''); ?>>60 min</option>
                                <option value="5400" <?php echo ($games_interval == '5400' ? 'selected' : ''); ?>>90 min</option>
                                <option value="7200" <?php echo ($games_interval == '7200' ? 'selected' : ''); ?>>120 min</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyIcon"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type']; ?></span>
                            </div>
                            <?php if ($games_id > 3) { ?>
                                <select class="custom-select" id="games_icon" name="games_icon" aria-describedby="ariaDescribedbyIcon" required>
                                    <option value="dice" <?php echo ($games_icon == 'dice' ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type_lucky']; ?></option>
                                    <option value="puzzle-piece" <?php echo ($games_icon == 'puzzle-piece' ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type_skill']; ?></option>
                                </select>
                            <?php } else {?>
                                <select class="custom-select" disabled>
                                    <option value="dice" <?php echo ($games_icon == 'dice' ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type_lucky']; ?></option>
                                    <option value="puzzle-piece" <?php echo ($games_icon == 'puzzle-piece' ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type_skill']; ?></option>
                                </select>
                                <input type="hidden" id="games_icon" name="games_icon" aria-describedby="ariaDescribedbyIcon" maxlength="55" value="<?php echo $games_icon; ?>" required />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyStatus"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?></span>
                            </div>
                            <?php if ($games_id > 3) { ?>
                                <select class="custom-select" id="games_status" name="games_status" aria-describedby="ariaDescribedbyStatus" required>
                                    <option value="1" <?php echo ($games_status == 1 ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_active']; ?></option>
                                    <option value="0" <?php echo ($games_status == 0 ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive']; ?></option>
                                </select>
                            <?php } else {?>
                                <select class="custom-select" disabled>
                                    <option value="1" <?php echo ($games_status == 1 ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_active']; ?></option>
                                    <option value="0" <?php echo ($games_status == 0 ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive']; ?></option>
                                </select>
                                <input type="hidden" id="games_status" name="games_status" aria-describedby="ariaDescribedbyStatus" maxlength="55" value="<?php echo $games_icon; ?>" required />
                            <?php } ?>
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
                '/administration/editgame' => TRANSLATIONS[$GLOBALS['language']]['admin']['games_administration_headline'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['admin']['games_edit_headline'],
            );
            breadcrumb($breadcrumb);

            title(TRANSLATIONS[$GLOBALS['language']]['admin']['games_edit_headline']);
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_valid_id'], 'danger');
        }
    } else {
        $breadcrumb = array(
            '/' => 'Home',
            '/administration' => 'Administration',
            '/administration/editgame' => TRANSLATIONS[$GLOBALS['language']]['admin']['games_administration_headline'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['admin']['games_edit_headline'],
        );
        breadcrumb($breadcrumb);

        title(TRANSLATIONS[$GLOBALS['language']]['admin']['games_edit_headline']);

        $sql = "SELECT games_id, games_name, games_interval, games_status, games_icon, games_is_lucky_category_game
                FROM games
                GROUP BY games_id ASC";
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
                            <th data-field="interval"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_interval']; ?></th>
                            <th data-field="icon"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type']; ?></th>
                            <th data-field="active"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_active']; ?></th>
                            <th data-field="options"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $games_interval = $row['games_interval'];
                            $games_status = $row['games_status'];
                            $games_icon = $row['games_icon'];
                            ?>
                            <tr>
                                <td><?php echo $row['games_id']; ?></td>
                                <td><?php echo $row['games_name']; ?></td>
                                <td>
                                    <?php
                                    if ($games_interval == '1800') {
                                        ?>
                                        30 min
                                        <?php
                                    } elseif ($games_interval == '3600') {
                                        ?>
                                        60 min
                                        <?php
                                    } elseif ($games_interval == '5400') {
                                        ?>
                                        90 min
                                        <?php
                                    } elseif ($games_interval == '7200') {
                                        ?>
                                        120 min
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td><?php echo ($games_icon == 'dice' || $row['games_is_lucky_category_game'] == 1 ? TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type_lucky'] : TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type_skill']); ?></td>
                                <td><?php echo ($games_status == 1 ? TRANSLATIONS[$GLOBALS['language']]['general']['text_active'] : TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive']); ?></td>
                                <td><a href="<?php echo HOST_URL; ?>/administration/editgame/<?php echo $row['games_id']; ?>">Edit</a></td>
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