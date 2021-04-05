<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    $breadcrumb = array(
        '/' => 'Home',
        '/games' => TRANSLATIONS[$GLOBALS['language']]['general']['text_games'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['general']['text_games']);

    $sql_can_access_games = "SELECT 1
                             FROM carddeck
                             WHERE carddeck_active = 1
                             HAVING COUNT(carddeck_id) >= 3";
    $result_can_access_games = mysqli_query($link, $sql_can_access_games) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_can_access_games)) {
        $sql_games = "SELECT games_id, games_name, games_interval, games_type, games_is_lucky_category_game
                      FROM games
                      WHERE games_status = '1'
                      ORDER BY games_interval, games_name ASC";
        $result_games = mysqli_query($link, $sql_games) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_games)) {
            ?>
            <div class="row mb-5 games-container">
                <?php
                while ($row_games = mysqli_fetch_assoc($result_games)) {
                    if ($row_games['games_is_lucky_category_game'] == '1') {
                        $sql_cat = "SELECT carddeck_cat_id, carddeck_cat_name
                                    FROM carddeck_cat
                                    JOIN carddeck ON carddeck_cat = carddeck_cat_id
                                    WHERE carddeck_active = 1
                                    GROUP BY carddeck_cat_id
                                    HAVING COUNT(carddeck_id) >= 3
                                    ORDER BY carddeck_cat_name";
                        $result_cat = mysqli_query($link, $sql_cat) OR die(mysqli_error($link));
                        if (mysqli_num_rows($result_cat)) {
                            while ($row_cat = mysqli_fetch_assoc($result_cat)) {
                                $sql_last_played = "SELECT member_game_played_last_played
                                                    FROM member_game_played
                                                    WHERE member_game_played_member_id = '" . $_SESSION['member_id'] . "'
                                                      AND member_game_played_game_id = '" . $row_games['games_id'] . "'
                                                      AND member_game_played_lucky_category_id = '" . $row_cat['carddeck_cat_id'] . "'
                                                    ORDER BY member_game_played_id DESC
                                                    LIMIT 1";
                                $result_last_played = mysqli_query($link, $sql_last_played) OR die(mysqli_error($link));
                                $row_last_played = mysqli_fetch_assoc($result_last_played);
                                if (mysqli_num_rows($result_last_played)) {
                                    $next_game_time = $row_last_played['member_game_played_last_played'] + $row_games['games_interval'];

                                    if ($next_game_time <= time()) {
                                        $can_play = true;
                                    } else {
                                        $can_play = false;
                                    }
                                } else {
                                    $can_play = true;
                                }
                                ?>
                                <div class="col col-12 col-md-6 mb-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="media">
                                                <i class="fas fa-dice fa-2x mr-3"></i>
                                                <div class="media-body">
                                                    <?php
                                                    if ($can_play) {
                                                        ?>
                                                        <span class="font-weight-bold"><?php echo $row_games['games_name'].' ' . $row_cat['carddeck_cat_name']; ?></span><br />
                                                        <a href="<?php echo HOST_URL; ?>/games/lucky_cat/<?php echo $row_cat['carddeck_cat_id']; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['play_now'] ?></a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <span class="font-weight-bold"><?php echo $row_games['games_name'].' ' . $row_cat['carddeck_cat_name']; ?></span><br />
                                                        <span class="text-muted">
                                                            <?php echo TRANSLATIONS[$GLOBALS['language']]['games']['hint_next_round']; ?>:
                                                            <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_time'], $next_game_time); ?>
                                                        </span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    } else {
                        $sql_last_played = "SELECT member_game_played_last_played
                                            FROM member_game_played
                                            WHERE member_game_played_member_id = '" . $_SESSION['member_id'] . "'
                                              AND member_game_played_game_id = '" . $row_games['games_id'] . "'
                                            ORDER BY member_game_played_id DESC
                                            LIMIT 1";
                        $result_last_played = mysqli_query($link, $sql_last_played) OR die(mysqli_error($link));
                        $row_last_played = mysqli_fetch_assoc($result_last_played);
                        if (mysqli_num_rows($result_last_played)) {
                            $next_game_time = $row_last_played['member_game_played_last_played'] + $row_games['games_interval'];

                            if ($next_game_time <= time()) {
                                $can_play = true;
                            } else {
                                $can_play = false;
                            }
                        } else {
                            $can_play = true;
                        }
                        ?>
                        <div class="col col-12 col-md-6 mb-2">
                            <div class="card">
                                <div class="card-body">
                                    <div class="media">
                                        <i class="fas fa-<?php echo (($row_games['games_type'] == '1' || $row_games['games_is_lucky_category_game'] == 1) ? 'dice' : 'puzzle-piece'); ?> fa-2x mr-3"></i>
                                        <div class="media-body">
                                            <?php
                                            if ($can_play) {
                                                ?>
                                                <span class="font-weight-bold"><?php echo $row_games['games_name']; ?></span><br />
                                                <a href="<?php echo HOST_URL; ?>/games/<?php echo ($row_games['games_type'] == 1 ? 'lucky' : 'skill'); ?>/<?php echo $row_games['games_id']; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['play_now'] ?></a>
                                                <?php
                                            } else {
                                                ?>
                                                <span class="font-weight-bold"><?php echo $row_games['games_name']; ?></span><br />
                                                <span class="text-muted">
                                                            <?php echo TRANSLATIONS[$GLOBALS['language']]['games']['hint_next_round']; ?>:
                                                    <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_time'], $next_game_time); ?>
                                                        </span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_carddeck_yet'], 'danger');
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>