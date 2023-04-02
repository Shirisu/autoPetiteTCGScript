<?php
if (isset($_SESSION['member_rank'])) {
    $member_id = $_SESSION['member_id'];

    if (isset($game_id)) {
        global $link;

        $sql_lucky_game = "SELECT games_id, games_name, games_interval, games_lucky_choices
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
                '/games/lucky/' . $game_id => $game_name,
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
                if (isset($_POST['lucky_choice'])) {
                    $lucky_choice = mysqli_real_escape_string($link, $_POST['lucky_choice']);
                    $random_choice = mt_rand(1, 6);

                    if ($random_choice == 1 || $random_choice == 4) {
                        alert_box(
                            TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($lucky_choice).
                            '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_lost'].'...'
                            , 'danger');
                        insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, TRANSLATIONS[$GLOBALS['language']]['games']['text_game_lost'], $member_id);
                    } elseif ($random_choice == 2 || $random_choice == 5) {
                        insert_cards($member_id, 2);
                        $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_2_cards'].': '.implode(', ', $_SESSION['insert_cards']);
                        insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                        alert_box(
                            TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($lucky_choice).
                            '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_win'].'!<br />2 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].': '.implode(', ',$_SESSION['insert_cards']).
                            '<br />'.
                            $_SESSION['insert_cards_images']
                            , 'success');
                    } elseif ($random_choice == 3 || $random_choice == 6) {
                        if (TCG_CURRENCY_USE) {
                            $amount_currency = 50;
                            insert_currency($member_id, $amount_currency);
                            $inserted_currency_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win'] . ': '.$amount_currency.' '.TCG_CURRENCY;
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_currency_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($lucky_choice).
                                '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_win'].'!<br />'.$amount_currency.' '.TCG_CURRENCY
                                , 'success');
                        } else {
                            insert_cards($member_id, 1);
                            $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_1_card'] . ': ' . implode(', ', $_SESSION['insert_cards']);
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($lucky_choice).
                                '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_win'].'!<br />1 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_card'].': '.implode(', ',$_SESSION['insert_cards']).
                                '<br />'.
                                $_SESSION['insert_cards_images']
                                , 'success');
                        }
                    }

                    insert_game_played($member_id, $game_id);
                } else {
                    ?>
                    <form action="<?php echo HOST_URL; ?>/games/lucky/<?php echo $game_id; ?>" method="post">
                        <div class="row mb-5 games-lucky-container text-center">
                            <div class="col col-12 mb-3 text-center">
                                <?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_lucky_game']; ?>
                            </div>
                            <div class="col col-12 mb-2 text-center">
                                <?php
                                $choices = explode(';', $row_lucky_game['games_lucky_choices']);
                                for ($i = 0; $i < count($choices); $i++) { ?>
                                    <button class="btn btn-secondary mb-1" type="submit" name="lucky_choice"
                                            id="<?php echo $i; ?>"
                                            value="<?php echo $choices[$i]; ?>">
                                        <?php echo $choices[$i]; ?>
                                    </button>
                                <?php } ?>
                            </div>
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
    show_no_access_message_with_breadcrumb();
}
?>