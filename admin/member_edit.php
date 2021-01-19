<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    global $link;
    if (isset($memberId)) {
        $sql = "SELECT member_id, member_ip, member_nick, member_active, member_register, member_last_login, member_rank, member_wish, member_email, member_language
                FROM member
                WHERE member_id = '".$memberId."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $row = mysqli_fetch_assoc($result);

        $member_active = $row['member_active'];
        $member_rank = $row['member_rank'];
        $member_wish = $row['member_wish'];
        $member_email = $row['member_email'];
        $member_language = $row['member_language'];
        if (isset($_POST['member_id'])) {
            $member_active = mysqli_real_escape_string($link, $_POST['member_active']);
            $member_rank = mysqli_real_escape_string($link, $_POST['member_rank']);
            $member_wish = mysqli_real_escape_string($link, $_POST['member_wish']);
            $member_email = mysqli_real_escape_string($link, trim($_POST['member_email']));
            $member_language = mysqli_real_escape_string($link, trim($_POST['member_language']));

            mysqli_query($link, "UPDATE member
                         SET member_active = '".$member_active."',
                             member_rank = '".$member_rank."',
                             member_wish = '".$member_wish."',
                             member_email = '".$member_email."',
                             member_language = '".$member_language."'
                         WHERE member_id = ".$memberId."
                         LIMIT 1") OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_save'], 'success');
        }

        $breadcrumb = array(
            '/' => 'Home',
            '/admin/memberadministration' => 'Admin - '.TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'],
            '/admin/editmember/' => TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline'],
            '/admin/editmember/'.$row['member_id'] => $row['member_nick'],
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
            <form action="/admin/editmember/<?php echo $memberId; ?>" method="post">
                <div class="row align-items-center">
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyID">ID</span>
                            </div>
                            <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyID" required value="<?php echo $row['member_id']; ?>" />
                        </div>
                        <input type="hidden" class="form-control" id="member_id" name="member_id" value="<?php echo $row['member_id']; ?>" />
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyIP">IP</span>
                            </div>
                            <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyIP" required value="<?php echo $row['member_ip']; ?>" />
                        </div>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyNickname"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_nickname']; ?></span>
                            </div>
                            <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyNickname" required value="<?php echo $row['member_nick']; ?>" />
                        </div>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyActive"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?></span>
                            </div>
                            <select class="custom-select" id="member_active" name="member_active" aria-describedby="ariaDescribedbyActive" required>
                                <option value="1" <?php if($member_active == 1) { ?>selected="selected"<?php } ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_active']; ?></option>
                                <option value="0" <?php if($member_active == 0) { ?>selected="selected"<?php } ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive']; ?></option>
                                <option value="2" <?php if($member_active == 2) { ?>selected="selected"<?php } ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_blocked']; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyRegister"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_registered']; ?></span>
                            </div>
                            <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyRegister" required value="<?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'],$row['member_register']); ?>" />
                        </div>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyLastLogin"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_lastlogin']; ?></span>
                            </div>
                            <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyLastLogin" required value="<?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'],$row['member_last_login']); ?>" />
                        </div>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyRank"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_rank']; ?></span>
                            </div>
                            <select class="custom-select" id="member_rank" name="member_rank" aria-describedby="ariaDescribedbyRank" required>
                                <?php
                                while($row_rank = mysqli_fetch_assoc($result_rank)) {
                                    ?>
                                    <option value="<?php echo $row_rank['member_rank_id']; ?>" <?php echo ($row_rank['member_rank_id'] == $member_rank ? 'selected="selected"' : '') ?>><?php echo $row_rank['member_rank_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyWish"><?php echo TCG_WISH; ?></span>
                            </div>
                            <input type="number" class="form-control" aria-describedby="ariaDescribedbyWish" id="member_wish" name="member_wish" min="0" value="<?php echo $member_wish; ?>" required />
                        </div>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyEmail"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_email']; ?></span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="ariaDescribedbyEmail" id="member_email" name="member_email" pattern="^[a-zA-Z0-9_\+-]+(\.[a-zA-Z0-9_\+-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.([a-zA-Z]{2,4})$" value="<?php echo $member_email; ?>" required />
                        </div>
                    </div>
                    <div class="form-group col col-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyLanguage"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language']; ?></span>
                            </div>
                            <select class="custom-select" id="member_language" name="member_language" aria-describedby="ariaDescribedbyLanguage" required>
                                <option selected disabled hidden value=""></option>
                                <option value="en" <?php if($member_language == 'en') { ?>selected="selected"<?php } ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language_en']; ?></option>
                                <option value="de" <?php if($member_language == 'de') { ?>selected="selected"<?php } ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language_de']; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col col-12">
                        <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_save']; ?></button>
                    </div>
                </div>
            </form>
            <?php
        }
    } else {
        $breadcrumb = array(
            '/' => 'Home',
            '/admin/memberadministration' => 'Admin - '.TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'],
            '/admin/editmember/all' => TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline'],
        );
        breadcrumb($breadcrumb);
        title(TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline']);
        ?>
        <div class="row">
            <div class="form-group col col-12 col-md-3">
                <a href="/admin/editmember/all"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_all']; ?></a>
            </div>
            <div class="form-group col col-12 col-md-3">
                <a href="/admin/editmember/active"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_active']; ?></a>
            </div>
            <div class="form-group col col-12 col-md-3">
                <a href="/admin/editmember/inactive"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_inactive']; ?></a>
            </div>
            <div class="form-group col col-12 col-md-3">
                <a href="/admin/editmember/blocked"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_blocked']; ?></a>
            </div>
        </div>

        <?php
        if(isset($_SESSION['mrank'])) {
            if(isset($rank)) {
                if($rank == 'all') {
                    $_SESSION['mrank'] = 100;
                } elseif($rank == 'active') {
                    $_SESSION['mrank'] = 1;
                } elseif($rank == 'inactive') {
                    $_SESSION['mrank'] = 0;
                } elseif($rank == 'blocked') {
                    $_SESSION['mrank'] = 2;
                }
            }
        } else {
            $_SESSION['mrank'] = 100;
        }

        if ($_SESSION['mrank'] == 100) {
            $memberactive = '';
        } else {
            $memberactive = 'AND member_active = "' . $_SESSION['mrank'] . '"';
        }

        $sql = "SELECT member_id, member_nick, member_active, member_register, member_last_login, member_rank_id, member_rank_name, member_ip
                FROM member, member_rank
                WHERE member_rank = member_rank_id
                  " . $memberactive . "
                GROUP BY member_id";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $count = mysqli_num_rows($result);
        if($count) {
            ?>
            <div class="row">
                <div class="col">
                    <table id="admin_member_edit_table" data-mobile-responsive="true">
                        <thead>
                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="ip">IP</th>
                            <th data-field="nickname"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_nickname']; ?></th>
                            <th data-field="lastlogin"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_lastlogin']; ?></th>
                            <th data-field="status"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?></th>
                            <th data-field="rank"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_rank']; ?></th>
                            <th data-field="options"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['member_id']; ?></td>
                                <td><?php echo $row['member_ip']; ?></td>
                                <td>
                                    <a href="/member/<?php echo $row['member_id']; ?>"><?php echo $row['member_nick']; ?></a>
                                </td>
                                <td><?php echo date('d.m.Y H:i', $row['member_last_login']); ?></td>
                                <td><?php echo get_active_status($row['member_active']); ?></td>
                                <td><?php echo sprintf('%02d', $row['member_rank_id']) . ' - ' . $row['member_rank_name'] ?></td>
                                <td><a href="/admin/editmember/<?php echo $row['member_id']; ?>">Edit</a></td>
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
            ?>
            <div class="row">
                <div class="form-group col mt-2">
                    <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_nodata'], 'danger'); ?>
                </div>
            </div>
            <?php
        }
    }
}
?>