<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/addgame' => TRANSLATIONS[$GLOBALS['language']]['admin']['games_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['games_add_headline'],
    );
    breadcrumb($breadcrumb);

    title(TRANSLATIONS[$GLOBALS['language']]['admin']['games_add_headline']);

    if (isset($_POST['games_name']) && isset($_POST['games_interval']) && isset($_POST['games_type'])) {
        $games_name = mysqli_real_escape_string($link, strip_tags(trim($_POST['games_name'])));
        $games_file = mysqli_real_escape_string($link, strip_tags(trim($_POST['games_file'])));
        $games_interval = mysqli_real_escape_string($link, strip_tags(trim($_POST['games_interval'])));
        $games_type = mysqli_real_escape_string($link, strip_tags(trim($_POST['games_type'])));
        if ($games_type == 1) {
            $games_lucky_choices = mysqli_real_escape_string($link, strip_tags(trim($_POST['games_lucky_choices'])));
        } else {
            $games_lucky_choices = '';
        }

        $sql_check_before_insert = "SELECT games_id
                                    FROM games
                                    WHERE games_name = '".$games_name."'
                                      AND games_file = '".$games_file."'
                                      AND games_interval = '".$games_interval."'
                                      AND games_type = '".$games_type."'
                                      AND games_lucky_choices = '".$games_lucky_choices."'
                                    LIMIT 1";
        $result_check_before_insert = mysqli_query($link, $sql_check_before_insert);
        if (!mysqli_num_rows($result_check_before_insert)) {
            mysqli_query($link, "
            INSERT INTO games
            (games_name, games_file, games_interval, games_type, games_lucky_choices)
            VALUES
            ('" . $games_name . "', '" . $games_file . "', '" . $games_interval . "', '" . $games_type . "', '" . $games_lucky_choices . "')")
            OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_games_add'], 'success');
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_duplicate_entry'], 'danger');
        }
    }

    ?>
    <form action="<?php echo HOST_URL; ?>/administration/addgame" method="post">
        <div class="row align-items-center">
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text" id="ariaDescribedbyName"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_name']; ?></span>
                    <input type="text" class="form-control" id="games_name" name="games_name" aria-describedby="ariaDescribedbyName" maxlength="55" value="" required />
                </div>
            </div>
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text" id="ariaDescribedbyFile"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_file']; ?></span>
                    <input type="text" class="form-control" id="games_file" name="games_file" aria-describedby="ariaDescribedbyFile" maxlength="55" value="" required />
                </div>
            </div>
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text" id="ariaDescribedbyInterval"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_interval']; ?></span>
                    <select class="selectpicker" data-live-search="true" data-size="10" id="games_interval" name="games_interval" aria-describedby="ariaDescribedbyInterval" required>
                        <option selected disabled hidden value=""></option>
                        <option value="1800">30 min / 0.5 h</option>
                        <option value="3600">60 min / 1 h</option>
                        <option value="5400">90 min / 1.5 h</option>
                        <option value="7200">120 min / 2 h</option>
                        <option value="9000">150 min / 2.5 h</option>
                        <option value="10800">180 min / 3 h</option>
                        <option value="12600">210 min / 3.5 h</option>
                        <option value="14400">240 min / 4 h</option>
                        <option value="16200">270 min / 4.5 h</option>
                        <option value="18000">300 min / 5 h</option>
                    </select>
                </div>
            </div>
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text" id="ariaDescribedbyIcon"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type']; ?></span>
                    <select class="selectpicker" data-live-search="true" data-size="10" id="games_type" name="games_type" aria-describedby="ariaDescribedbyIcon" required>
                        <option selected disabled hidden value=""></option>
                        <option value="1"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type_lucky']; ?></option>
                        <option value="2"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_type_skill']; ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group col col-12 mb-2">
                <div class="input-group">
                    <span class="input-group-text" id="ariaDescribedbyChoices"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_game_choices']; ?></span>
                    <textarea class="form-control" id="games_lucky_choices" name="games_lucky_choices" aria-describedby="ariaDescribedbyChoices" rows="2"></textarea>
                </div>
                <small id="ariaDescribedbyPassword" class="form-text text-muted">
                    <?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['hint_game_choices']; ?>
                </small>
            </div>
            <div class="form-group col col-12">
                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_add']; ?></button>
            </div>
        </div>
    </form>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>