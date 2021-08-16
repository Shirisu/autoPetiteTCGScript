<?php
if (isset($_SESSION['member_rank'])) {
    $member_id = $_SESSION['member_id'];

    if (isset($game_id)) {
        global $link;

        $sql_lucky_game = "SELECT games_id, games_name, games_interval
                           FROM games
                           WHERE games_id = '".$game_id."'
                             AND games_status = '1'
                           LIMIT 1";
        $result_lucky_game = mysqli_query($link, $sql_lucky_game) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_lucky_game)) {
            $row_lucky_game = mysqli_fetch_assoc($result_lucky_game);
            $game_id = $row_lucky_game['games_id'];
            $game_name = $row_lucky_game['games_name'];

            $breadcrumb = array(
                '/' => 'Home',
                '/games' => TRANSLATIONS[$GLOBALS['language']]['general']['text_games'],
                '/games/skill/' . $game_id => $game_name,
            );
            breadcrumb($breadcrumb);
            title($game_name);

            $can_play = true;
            $sql_last_played = "SELECT member_game_played_last_played
                                FROM member_game_played
                                WHERE member_game_played_member_id = '" . $member_id . "'
                                  AND member_game_played_game_id = '" . $game_id . "'
                                ORDER BY member_game_played_id DESC
                                LIMIT 1";
            $result_last_played = mysqli_query($link, $sql_last_played) OR die(mysqli_error($link));
            $row_last_played = mysqli_fetch_assoc($result_last_played);
            if (mysqli_num_rows($result_last_played)) {
                $next_game_time = $row_last_played['member_game_played_last_played'] + $row_lucky_game['games_interval'];

                if ($next_game_time <= time()) {
                    $can_play = true;
                } else {
                    $can_play = false;
                }
            } else {
                $can_play = true;
            }
            ?>
                <?php
                if ($can_play) {
                    if (isset($_POST['attempts_count']) && $_POST['attempts_count'] > 0) {
                        $random_choice = mt_rand(1, 3);

                        if ($random_choice == 1) {
                            insert_cards($member_id, 2);
                            $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_2_cards'].': '.implode(', ' ,$_SESSION['insert_cards']);
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_win'].'!<br />2 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].': '.implode(', ',$_SESSION['insert_cards']).
                                '<br />'.
                                $_SESSION['insert_cards_images']
                                , 'success');
                        } elseif ($random_choice == 2) {
                            insert_cards($member_id, 3);
                            $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_3_cards'].': '.implode(', ', $_SESSION['insert_cards']);
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_win'].'!<br />3 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].': '.implode(', ',$_SESSION['insert_cards']).
                                '<br />'.
                                $_SESSION['insert_cards_images']
                                , 'success');
                        } elseif ($random_choice == 3) {
                            insert_cards($member_id, 1);
                            $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_1_card'].': '.implode(', ', $_SESSION['insert_cards']);
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_win'].'!<br />1 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_card'].': '.implode(', ',$_SESSION['insert_cards']).
                                '<br />'.
                                $_SESSION['insert_cards_images']
                                , 'success');
                        }

                        insert_game_played($member_id, $game_id);
                    } else {
                        $theme = 1;
                        $sql_memory = "SELECT carddeck_id
                                       FROM carddeck
                                       WHERE carddeck_is_puzzle = 0
                                         AND carddeck_active = 1
                                       ORDER BY RAND()
                                       LIMIT 1";
                        $result_memory = mysqli_query($link, $sql_memory) OR die(mysqli_error($link));
                        if(mysqli_num_rows($result_memory)) {
                            while($row_memory = mysqli_fetch_assoc($result_memory)) {
                                $theme = $row_memory['carddeck_id'];
                            }
                        }

                        $max = 0;
                        ?>
                        <div class="row mb-5 games-lucky-container">
                            <div class="col col-12 mb-3 text-center">
                                <?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_memory_game']; ?>
                            </div>
                            <div class="col col-12 mb-3 text-center">
                                <script language="javascript">
                                    var memory_rows = <?php echo floor(TCG_CARDDECK_MAX_CARDS / 3); ?>; // count of rows
                                    var memory_cols = <?php echo floor(TCG_CARDDECK_MAX_CARDS / 3); ?>; // count of cols
                                    var memory_images = memory_rows * memory_cols;

                                    function isEven(value) {
                                        return (value % 2 == 0);
                                    }
                                    var before_rows = memory_rows;
                                    var before_cols = memory_cols;
                                    while (!isEven(memory_images)) {
                                        if (before_cols == memory_cols) {
                                            before_cols = memory_cols;
                                            memory_cols--;
                                        } else if(before_rows == memory_rows) {
                                            before_rows = memory_rows;
                                            memory_rows--;
                                        }
                                        memory_images = memory_rows * memory_cols;
                                    }

                                    var memo_theme = '<?php echo $theme; ?>';

                                    var image_path = '<?php echo get_card_path_without_number($theme); ?>';
                                    var cover = '<?php echo HOST_URL . TCG_CARDS_FOLDER . '/'.TCG_CARDS_FILLER_NAME.'.' . TCG_CARDS_FILE_TYPE; ?>';
                                    var found = '<?php echo HOST_URL . TCG_CARDS_FOLDER . '/'.TCG_CARDS_FILLER_NAME.'.' . TCG_CARDS_FILE_TYPE; ?>';
                                    var image_width = <?php echo TCG_CARDS_WIDTH; ?>;
                                    var image_height = <?php echo TCG_CARDS_HEIGHT; ?>;

                                    var valuation = 5;

                                    var attempt_number = 0;
                                    var attempts_count = 0;
                                    var result_count = 0;
                                    var points_count = 0;
                                    var correct_count = 0;
                                    var wrong_attempts_count = 0;

                                    start();

                                    var pos = 1;
                                    document.write('<table class="memorygame optional table-responsive">');
                                    for (z = 0; z < memory_rows; z++) {
                                        document.write('<tr class="memory">');
                                        for (s = 0; s < memory_cols; s++) {
                                            document.write('<td class="memorycard"><a href="javascript:attempt(' + pos + ')"><img id="' + pos + '" src="' + cover + '" alt="" name=' + pos + ' width="' + image_width + '" height="' + image_height + '"></a></td>');
                                            pos++;
                                        }
                                        document.write('</tr>');
                                    }
                                    document.write('</table>');

                                    function start() {
                                        image_with = new Array(memory_images);
                                        var i=1;
                                        while(i <= memory_images) {
                                            var same = 0;
                                            image_with[i] = Math.round(Math.random() * (memory_images/2));
                                            if (image_with[i] < 1) image_with[i] = 1;
                                            if (image_with[i] > (memory_images / 2)) image_with[i] = (memory_images / 2);
                                            if (i > 1) {
                                                for (var comparison = 1; comparison <= i-1; comparison++) {
                                                    if (image_with[i] == image_with[comparison]) {
                                                        var same = same + 1;
                                                        if (same == 2) break;
                                                    }
                                                }
                                            }
                                            if (same < 2) i++;
                                        }
                                    }

                                    function attempt(field) {
                                        if (image_with[field] != "g") {
                                            attempt_number = attempt_number + 1;
                                            actual_image = image_with[field];

                                            if (attempt_number == 1) {
                                                if (result_count == 1) {
                                                    change_field(attempt1pos, found);
                                                    change_field(attempt2pos, found);
                                                    addFoundClass(attempt1pos, 'memory_found');
                                                    addFoundClass(attempt2pos, 'memory_found');
                                                    result_count = 0;
                                                }
                                                if (result_count == 2) {
                                                    change_field(attempt1pos, cover);
                                                    change_field(attempt2pos, cover);
                                                    addFoundClass(attempt1pos, 'memory_not_found');
                                                    addFoundClass(attempt2pos, 'memory_not_found');
                                                    result_count = 0;
                                                }
                                                attempt1pos = field;
                                            }

                                            if (attempt_number == 2) {
                                                attempts_count = attempts_count + 1;
                                                if (field != attempt1pos) {
                                                    attempt_number = 0;
                                                    attempt2pos = field;
                                                    if (image_with[field] == image_with[attempt1pos]) {
                                                        points_count = points_count + valuation;
                                                        correct_count = correct_count + 1;
                                                        image_with[field] = "g";
                                                        image_with[attempt1pos] = "g";
                                                        result_count = 1;
                                                        if (correct_count == memory_images / 2) {
                                                            var image_act_number = actual_image;
                                                            if (actual_image > 9) {
                                                                image_act_number = actual_image;
                                                            } else {
                                                                image_act_number = '0' + actual_image;
                                                            }
                                                            output();
                                                            change_field(field, image_path + image_act_number + '.<?php echo TCG_CARDS_FILE_TYPE; ?>');
                                                            document.cookie = 'memory_points=' + points_count;
                                                            document.memory.submit();
                                                        }
                                                    } else {
                                                        result_count = 2;
                                                        points_count = points_count - 1;
                                                        wrong_attempts_count = wrong_attempts_count + 1;
                                                    }
                                                } else {
                                                    attempt_number = 1;
                                                }
                                            }
                                            var image_act_number = actual_image;
                                            if (actual_image > 9) {
                                                image_act_number = actual_image;
                                            } else {
                                                image_act_number = '0'+actual_image;
                                            }
                                            change_field(field, image_path + image_act_number + '.<?php echo TCG_CARDS_FILE_TYPE; ?>');
                                            output();
                                        }
                                    }

                                    function change_field(position, bildname) {
                                        document.getElementById(position).src = bildname;
                                    }

                                    function addFoundClass(position, classname) {
                                        document.getElementById(position).className = classname;
                                    }

                                    function output() {
                                        document.memory.points_count.value = points_count;
                                        document.memory.attempts_count.value = attempts_count;
                                        document.memory.wrong_attempts_count.value = wrong_attempts_count;
                                    }
                                </script>
                            </div>
                            <div class="col col-12 col-md-6 mb-3 text-center">
                                <table class="memorygame optional">
                                    <form name="memory" action="<?php echo HOST_URL; ?>/games/skill/<?php echo $game_id; ?>" method="post">
                                        <tr>
                                            <td><b><?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_memory_points']; ?></b></td>
                                            <td><b><?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_memory_attempts']; ?></b></td>
                                            <td><b><?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_memory_attempts_wrong'] ?></b></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="points_count" value="0" size="5"></td>
                                            <td><input type="text" name="attempts_count" value="0" size="5"></td>
                                            <td><input type="text" name="wrong_attempts_count" value="0" size="5"></td>
                                        </tr>
                                    </form>
                                </table>
                            </div>

                            <div class="col col-12 col-md-6 mb-3 text-center">
                                <table class="memorygame optional">
                                    <tr>
                                        <th><?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_memory_found']; ?></th>
                                        <th><?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_memory_found_not']; ?></th>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;"><img class="memory_found" src="<?php echo HOST_URL. '/' .TCG_CARDS_FOLDER . '/'.TCG_CARDS_FILLER_NAME.'.' . TCG_CARDS_FILE_TYPE; ?>" alt="<?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_memory_found']; ?>" width="<?php echo TCG_CARDS_HEIGHT; ?>" height="<?php echo TCG_CARDS_WIDTH; ?>" /></td>
                                        <td style="text-align:center;"><img class="memory_not_found" src="<?php echo HOST_URL. '/' .TCG_CARDS_FOLDER . '/'.TCG_CARDS_FILLER_NAME.'.' . TCG_CARDS_FILE_TYPE; ?>" alt="<?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_memory_found_not']; ?>" width="<?php echo TCG_CARDS_HEIGHT; ?>" height="<?php echo TCG_CARDS_WIDTH; ?>" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php
                    }
                ?>
                <?php } else { ?>
                    <div class="row mb-5">
                        <div class="col col-12">
                            <?php alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['hint_already_played_part_1'].'<br />'.
                                TRANSLATIONS[$GLOBALS['language']]['games']['hint_already_played_part_2'].' '.
                                date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_time'], $next_game_time).'!'
                                , 'danger'); ?>
                        </div>
                    </div>
                <?php } ?>
            <?php
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/games' => TRANSLATIONS[$GLOBALS['language']]['general']['text_games'],
            );
            breadcrumb($breadcrumb);
            title(TRANSLATIONS[$GLOBALS['language']]['general']['text_games']);

            alert_box(TRANSLATIONS[$GLOBALS['language']]['games']['hint_game_not_exist'], 'danger');
        }
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>
