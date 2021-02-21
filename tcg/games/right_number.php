<?php
if (isset($_SESSION['member_rank'])) {
    $member_id = $_SESSION['member_id'];

    if (isset($game_id)) {
        global $link;

        $sql_lucky_game = "SELECT games_id, games_name, games_intervall
                           FROM games
                           WHERE games_id = '".$game_id."'
                           LIMIT 1";
        $result_lucky_game = mysqli_query($link, $sql_lucky_game) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_lucky_game)) {
            $row_lucky_game = mysqli_fetch_assoc($result_lucky_game);
            $game_id = $row_lucky_game['games_id'];
            $game_name = $row_lucky_game['games_name'];

            $breadcrumb = array(
                '/' => 'Home',
                '/games' => TRANSLATIONS[$GLOBALS['language']]['general']['text_games'],
                '/games/' . $game_id => $game_name,
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
                $next_game_time = $row_last_played['member_game_played_last_played'] + $row_lucky_game['games_intervall'];

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
                    if (isset($_POST['right_number'])) {
                        $right_number_choice = mysqli_real_escape_string($link, $_POST['right_number']);
                        $random_choice = mt_rand(1, 6);

                        if ($random_choice == 1 || $random_choice == 4) {
                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($right_number_choice).
                                '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_lost'].'...'
                                , 'danger');
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_lost'], $member_id);
                        } elseif ($random_choice == 2 || $random_choice == 5) {
                            insert_cards($member_id, 2);
                            $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_2_cards'].': '.implode(', ',$_SESSION['insert_cards']);
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($right_number_choice).
                                '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_win'].'!<br />2 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].': '.implode(', ',$_SESSION['insert_cards'])
                                , 'success');
                        } elseif ($random_choice == 3 || $random_choice == 6) {
                            insert_cards($member_id, 1);
                            $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_1_card'].': '.implode(', ',$_SESSION['insert_cards']);
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($right_number_choice).
                                '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_win'].'!<br />1 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_card'].': '.implode(', ',$_SESSION['insert_cards'])
                                , 'success');
                        }

                        insert_game_played($member_id, $game_id);
                    } else {
                        ?>
                        <form action="<?php echo HOST_URL; ?>/games/<?php echo $game_id; ?>" method="post">
                            <div class="row mb-5 games-lucky-container">
                                <div class="col col-12 mb-3 text-center">
                                    <?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_right_number_game']; ?>
                                </div>
                                <?php
                                $right_number = array(1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six');
                                for ($i = 1; $i <= count($right_number); $i++) { ?>
                                    <div class="col col-4 col-md-2 mb-2 text-center">
                                        <button class="btn btn-secondary" type="submit" name="right_number"
                                                id="<?php echo $i; ?>"
                                                value="<?php echo $i; ?>">
                                            <i class="fas fa-dice-<?php echo $right_number[$i]; ?> fa-3x"></i>
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </form>
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
    show_no_access_message();
}
?>