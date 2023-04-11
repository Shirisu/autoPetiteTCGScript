<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    if (isset($member_id)) {
        $sql = "SELECT member_id, member_ip, member_nick, member_active, member_register, member_last_login, member_last_active, member_rank, member_email, member_language
                FROM member
                WHERE member_id = '".$member_id."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);

            $member_active = $row['member_active'];
            $member_rank = $row['member_rank'];
            $member_email = $row['member_email'];
            $member_language = $row['member_language'];
            if (isset($_POST['member_id'])) {
                $member_active = mysqli_real_escape_string($link, $_POST['member_active']);
                $member_rank = mysqli_real_escape_string($link, $_POST['member_rank']);
                $member_email = mysqli_real_escape_string($link, trim($_POST['member_email']));
                $member_language = mysqli_real_escape_string($link, trim($_POST['member_language']));

                mysqli_query($link, "UPDATE member
                             SET member_active = '".$member_active."',
                                 member_rank = '".$member_rank."',
                                 member_email = '".$member_email."',
                                 member_language = '".$member_language."'
                             WHERE member_id = ".$member_id."
                             LIMIT 1")
                OR die(mysqli_error($link));

                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_save'], 'success');
            }

            $breadcrumb = array(
                '/' => 'Home',
                '/administration' => 'Administration',
                '/administration/editmember/all' => TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline'],
                '/administration/editmember/'.$row['member_id'] => $row['member_nick'],
            );
            breadcrumb($breadcrumb);
            title(TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline']);

            $sql_rank = "SELECT member_rank_id, member_rank_name
                         FROM member_rank
                         ORDER BY member_rank_name";
            $result_rank = mysqli_query($link, $sql_rank) OR die(mysqli_error($link));
            $count_rank = mysqli_num_rows($result_rank);
            if ($count_rank) {
                ?>
                <form action="<?php echo HOST_URL; ?>/administration/editmember/<?php echo $member_id; ?>" method="post">
                    <div class="row align-items-center">
                        <div class="form-group col col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <span class="input-group-text" id="ariaDescribedbyID">ID</span>
                                <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyID" required value="<?php echo $row['member_id']; ?>" />
                            </div>
                            <input type="hidden" class="form-control" id="member_id" name="member_id" value="<?php echo $row['member_id']; ?>" />
                        </div>
                        <?php
                        if ($member_active != 4) {
                            ?>
                            <div class="form-group col col-12 col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text" id="ariaDescribedbyIP">IP</span>
                                    <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyIP" required value="<?php echo $row['member_ip']; ?>" />
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-group col col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <span class="input-group-text" id="ariaDescribedbyNickname"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_nickname']; ?></span>
                                <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyNickname" required value="<?php echo $row['member_nick']; ?>" />
                            </div>
                        </div>
                        <div class="form-group col col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <span class="input-group-text" id="ariaDescribedbyActive"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?></span>
                                <?php
                                if ($member_active == 4) {
                                    ?>
                                        <input type="text" disabled class="form-control" required value="<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_deleted']; ?>" />
                                        <input type="hidden" class="form-control" id="member_active" name="member_active" required value="4" />
                                    <?php
                                } else {
                                    ?>
                                    <select class="selectpicker" data-live-search="true" data-size="10" id="member_active" name="member_active"
                                            aria-describedby="ariaDescribedbyActive" required>
                                        <option value="1"
                                                <?php echo ($member_active == 1 ? 'selected="selected"' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_active']; ?></option>
                                        <option value="0"
                                                <?php echo ($member_active == 0 ? 'selected="selected"' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive']; ?></option>
                                        <option value="2"
                                                <?php echo ($member_active == 2 ? 'selected="selected"' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_blocked']; ?></option>
                                        <option value="3"
                                                <?php echo ($member_active == 3 ? 'selected="selected"' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_not_activated_yet']; ?></option>
                                    </select>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        if ($member_active != 4) {
                            ?>
                            <div class="form-group col col-12 col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text" id="ariaDescribedbyRegister"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_registered']; ?></span>
                                    <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyRegister" required value="<?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'],$row['member_register']); ?>" />
                                </div>
                            </div>
                            <div class="form-group col col-12 col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text" id="ariaDescribedbyLastLogin"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_lastlogin']; ?></span>
                                    <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyLastLogin" required value="<?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'],$row['member_last_login']); ?>" />
                                </div>
                            </div>
                            <div class="form-group col col-12 col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text" id="ariaDescribedbyLastActive"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_lastactive']; ?></span>
                                    <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyLastActive" required value="<?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'],$row['member_last_active']); ?>" />
                                </div>
                            </div>
                            <div class="form-group col col-12 col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text" id="ariaDescribedbyRank"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_rank']; ?></span>
                                    <select class="selectpicker" data-live-search="true" data-size="10" id="member_rank" name="member_rank" aria-describedby="ariaDescribedbyRank" required>
                                        <?php
                                        while ($row_rank = mysqli_fetch_assoc($result_rank)) {
                                            ?>
                                            <option value="<?php echo $row_rank['member_rank_id']; ?>" <?php echo ($row_rank['member_rank_id'] == $member_rank ? 'selected="selected"' : '') ?>><?php echo $row_rank['member_rank_name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col col-12 col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text" id="ariaDescribedbyLanguage"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language']; ?></span>
                                    <select class="selectpicker" data-live-search="true" data-size="10" id="member_language" name="member_language" aria-describedby="ariaDescribedbyLanguage" required>
                                        <option selected disabled hidden value=""></option>
                                        <option value="en" <?php if ($member_language == 'en') { ?>selected="selected"<?php } ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language_en']; ?></option>
                                        <option value="de" <?php if ($member_language == 'de') { ?>selected="selected"<?php } ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language_de']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col col-12 col-md-6 mb-2">
                                <div class="input-group">
                                    <span class="input-group-text" id="ariaDescribedbyEmail"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_email']; ?></span>
                                    <input type="text" class="form-control" aria-describedby="ariaDescribedbyEmail" id="member_email" name="member_email" pattern="^[a-zA-Z0-9_\+-]+(\.[a-zA-Z0-9_\+-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.([a-zA-Z]{2,4})$" value="<?php echo $member_email; ?>" required />
                                </div>
                            </div>
                            <div class="form-group col col-12 mb-2">
                                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_save']; ?></button>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <?php
                            if (isset($_GET['resetpassword'])) {
                                // create new password and show it
                                require_once('./inc/class.passwordhash_tcg.php');
                                $password = passwordgenerator();
                                $password_hashed = create_hash_for_tcg($password);

                                mysqli_query($link, "UPDATE member
                                     SET member_password = '" . $password_hashed . "'
                                     WHERE member_id = '" . $member_id . "'
                                     LIMIT 1")
                                OR die(mysqli_error($link));
                                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_new_password'].': '.$password, 'success');
                            }
                            ?>
                            <a href="<?php echo HOST_URL; ?>/administration/editmember/<?php echo $member_id; ?>/?resetpassword"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_reset_password']; ?></a>
                        </div>
                    </div>
                </form>
                <?php
            }
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/administration' => 'Administration',
                '/administration/editmember/all' => TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline'],
            );
            breadcrumb($breadcrumb);

            title(TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline']);
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_valid_id'], 'danger');
        }
    } else {
        $breadcrumb = array(
            '/' => 'Home',
            '/administration' => 'Administration',
            '/administration/editmember/all' => TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline'],
        );
        breadcrumb($breadcrumb);
        title(TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline']);
        ?>
        <div class="row justify-content-center">
            <div class="d-grid col col-6 col-md-4 mb-2">
                <a href="<?php echo HOST_URL; ?>/administration/editmember/all" class="btn btn-outline-info btn-sm<?php echo $rank == 'all' ? ' active' : ''; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_all']; ?></a>
            </div>
            <div class="d-grid col col-6 col-md-4 mb-2">
                <a href="<?php echo HOST_URL; ?>/administration/editmember/active" class="btn btn-outline-info btn-sm<?php echo $rank == 'active' ? ' active' : ''; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_active']; ?></a>
            </div>
            <div class="d-grid col col-6 col-md-4 mb-2">
                <a href="<?php echo HOST_URL; ?>/administration/editmember/inactive" class="btn btn-outline-info btn-sm<?php echo $rank == 'inactive' ? ' active' : ''; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_inactive']; ?></a>
            </div>
            <div class="d-grid col col-6 col-md-4 mb-2">
                <a href="<?php echo HOST_URL; ?>/administration/editmember/notactivatedyet" class="btn btn-outline-info btn-sm<?php echo $rank == 'notactivatedyet' ? ' active' : ''; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_not_active']; ?></a>
            </div>
            <div class="d-grid col col-6 col-md-4 mb-2">
                <a href="<?php echo HOST_URL; ?>/administration/editmember/deleted" class="btn btn-outline-info btn-sm<?php echo $rank == 'deleted' ? ' active' : ''; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_deleted']; ?></a>
            </div>
            <div class="d-grid col col-6 col-md-4 mb-2">
                <a href="<?php echo HOST_URL; ?>/administration/editmember/blocked" class="btn btn-outline-info btn-sm<?php echo $rank == 'blocked' ? ' active' : ''; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_blocked']; ?></a>
            </div>
        </div>

        <?php
        if (isset($_SESSION['member_edit_active_status'])) {
            if (isset($rank)) {
                if ($rank == 'all') {
                    $_SESSION['member_edit_active_status'] = 100;
                } elseif ($rank == 'active') {
                    $_SESSION['member_edit_active_status'] = 1;
                } elseif ($rank == 'inactive') {
                    $_SESSION['member_edit_active_status'] = 0;
                } elseif ($rank == 'blocked') {
                    $_SESSION['member_edit_active_status'] = 2;
                } elseif ($rank == 'notactivatedyet') {
                    $_SESSION['member_edit_active_status'] = 3;
                } elseif ($rank == 'deleted') {
                    $_SESSION['member_edit_active_status'] = 4;
                }
            }
        } else {
            $_SESSION['member_edit_active_status'] = 100;
        }

        if ($_SESSION['member_edit_active_status'] == 100) {
            $memberactive = '';
        } else {
            $memberactive = 'WHERE member_active = "' . $_SESSION['member_edit_active_status'] . '"';
        }

        $sql = "SELECT member_id, member_nick, member_active, member_register, member_last_login, member_last_active, member_rank_id, member_rank_name, member_ip
                FROM member
                JOIN member_rank ON member_rank = member_rank_id
                  " . $memberactive . "
                ORDER BY member_id ASC";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $count = mysqli_num_rows($result);
        if ($count) {
            ?>
            <div class="row">
                <div class="col">
                    <table id="admin-member-edit-table" data-mobile-responsive="true">
                        <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="ip" data-sortable="true">IP</th>
                            <th data-field="nickname" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_nickname']; ?></th>
                            <th data-field="lastlogin" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_lastlogin']; ?></th>
                            <th data-field="lastactive" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_lastactive']; ?></th>
                            <th data-field="status" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?></th>
                            <th data-field="rank" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_rank']; ?></th>
                            <th data-field="options"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['member_id']; ?></td>
                                <td><?php echo ($row['member_active'] == 4 ? '---' : $row['member_ip']); ?></td>
                                <td><?php echo ($row['member_active'] == 4 ? $row['member_nick'] : '<a href="'.HOST_URL.'/member/'.$row['member_id'].'">'.$row['member_nick'].'</a>'); ?>
                                </td>
                                <td><span class="d-none"><?php echo $row['member_last_login']; ?></span> <?php echo ($row['member_active'] == 4 ? '---' : date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row['member_last_login'])); ?></td>
                                <td><span class="d-none"><?php echo $row['member_last_active']; ?></span> <?php echo ($row['member_active'] == 4 ? '---' : date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row['member_last_active'])); ?></td>
                                <td><?php echo get_active_status($row['member_active']); ?></td>
                                <td><?php echo ($row['member_active'] == 4 ? '---' : sprintf('%02d', $row['member_rank_id']) . ' - ' . $row['member_rank_name']); ?></td>
                                <td>
                                    <?php echo ($row['member_active'] == 4 ? '' : '<a href="'.HOST_URL.'/administration/editmember/'.$row['member_id'].'">Edit</a>'); ?>
                                    <?php echo ($row['member_active'] == 4 ? '' : '<br /><a href="'.HOST_URL.'/administration/deletemember/'.$row['member_id'].'">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_delete'].'</a>'); ?>
                                </td>
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