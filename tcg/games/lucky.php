<?php
if (isset($_SESSION['member_rank'])) {
    $member_id = $_SESSION['member_id'];

    if (isset($lucky_game_id)) {
        global $link;

        $sql_lucky_game = "SELECT carddeck_cat_id, carddeck_cat_name, games_id, games_name, games_interval
                           FROM games
                           JOIN carddeck_cat ON carddeck_cat_id = '".$lucky_game_id."'
                           WHERE games_is_lucky_category_game = '1'
                             AND games_status = '1'
                           LIMIT 1";
        $result_lucky_game = mysqli_query($link, $sql_lucky_game) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_lucky_game)) {
            $row_lucky_game = mysqli_fetch_assoc($result_lucky_game);
            $game_id = $row_lucky_game['games_id'];
            $lucky_game_name = $row_lucky_game['carddeck_cat_name'];

            $breadcrumb = array(
                '/' => 'Home',
                '/games' => TRANSLATIONS[$GLOBALS['language']]['general']['text_games'],
                '/games/lucky/' . $lucky_game_id => 'Lucky '.$lucky_game_name,
            );
            breadcrumb($breadcrumb);
            title('Lucky '.$lucky_game_name);

            $can_play = true;
            $sql_last_played = "SELECT member_game_played_last_played
                                FROM member_game_played
                                WHERE member_game_played_member_id = '" . $member_id . "'
                                  AND member_game_played_lucky_category_id = '" . $row_lucky_game['carddeck_cat_id'] . "'
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
                    if (isset($_POST['lucky'])) {
                        $lucky_choice = mysqli_real_escape_string($link, $_POST['lucky']);
                        $random_choice = mt_rand(1, 3);

                        if ($random_choice == 1) {
                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($lucky_choice).
                                '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_lost'].'...'
                                , 'danger');
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - Lucky '.$lucky_game_name, TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_lost'], $member_id);
                        } elseif ($random_choice == 2) {
                            insert_cards($member_id, 2);
                            $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_2_cards'].': '.implode(', ',$_SESSION['insert_cards']);
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - Lucky '.$lucky_game_name, $inserted_cards_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($lucky_choice).
                                '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_win'].'!<br />2 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].': '.implode(', ',$_SESSION['insert_cards'])
                                , 'success');
                        } elseif ($random_choice == 3) {
                            insert_cards($member_id, 1);
                            $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_1_card'].': '.implode(', ',$_SESSION['insert_cards']);
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - Lucky '.$lucky_game_name, $inserted_cards_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($lucky_choice).
                                '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_win'].'!<br />1 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_card'].': '.implode(', ',$_SESSION['insert_cards'])
                                , 'success');
                        }

                        insert_lucky_game_played($member_id, $game_id, $lucky_game_id);
                    } else {
                        $sql_carddeck = "SELECT carddeck_id, carddeck_name
                                         FROM carddeck
                                         WHERE carddeck_cat = '".$lucky_game_id."'
                                           AND carddeck_active = 1
                                         ORDER BY RAND()
                                         LIMIT 3";
                        $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
                        if (mysqli_num_rows($result_carddeck)) {
                            ?>
                            <form action="<?php echo HOST_URL; ?>/games/lucky/<?php echo $lucky_game_id; ?>" method="post">
                                <div class="row mb-5 games-lucky-container">
                                    <div class="col col-12 mb-3 text-center">
                                        <?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_lucky_game']; ?>
                                    </div>
                                    <?php while ($row_carddeck = mysqli_fetch_assoc($result_carddeck)) { ?>
                                        <div class="col col-12 col-md-4 mb-2 text-center">
                                            <button class="btn btn-secondary" type="submit" name="lucky"
                                                    id="<?php echo $row_carddeck['carddeck_name']; ?>"
                                                    value="<?php echo $row_carddeck['carddeck_name']; ?>">
                                                <?php echo get_card($row_carddeck['carddeck_id'], rand(1, TCG_CARDDECK_MAX_CARDS)); ?>
                                            </button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </form>
                            <?php
                        }
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