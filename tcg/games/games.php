<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    $breadcrumb = array(
        '/' => 'Home',
        '/games' => TRANSLATIONS[$GLOBALS['language']]['general']['text_games'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['general']['text_games']);

    $sql_games = "SELECT games_id, games_name, games_intervall, games_icon, games_is_lucky_category_game
                  FROM games
                  ORDER BY games_name ASC";
    $result_games = mysqli_query($link, $sql_games) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_games)) {
        ?>
        <div class="row mb-5 games-container">
            <?php
            while ($row_games = mysqli_fetch_assoc($result_games)) {
                if ($row_games['games_is_lucky_category_game'] == '1') {
                    $sql_cat = "SELECT carddeck_cat_id, carddeck_cat_name
                                FROM carddeck_cat, carddeck
                                WHERE carddeck_cat_id = carddeck_cat
                                GROUP BY carddeck_cat_id
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
                                $next_game_time = $row_last_played['member_game_played_last_played'] + $row_games['games_intervall'];

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
                                            <i class="fas fa-<?php echo $row_games['games_icon']; ?> fa-2x mr-3"></i>
                                            <div class="media-body">
                                                <?php
                                                if ($can_play) {
                                                    ?>
                                                    <span class="font-weight-bold"><?php echo $row_games['games_name'].' ' . $row_cat['carddeck_cat_name']; ?></span><br />
                                                    <a href="<?php echo HOST_URL; ?>/games/lucky/<?php echo $row_cat['carddeck_cat_id']; ?>">Jetzt spielen</a>
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
                        $next_game_time = $row_last_played['member_game_played_last_played'] + $row_games['games_intervall'];

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
                                    <i class="fas fa-<?php echo $row_games['games_icon']; ?> fa-2x mr-3"></i>
                                    <div class="media-body">
                                        <?php
                                        if ($can_play) {
                                            ?>
                                            <span class="font-weight-bold"><?php echo $row_games['games_name']; ?></span><br />
                                            <a href="<?php echo HOST_URL; ?>/games/<?php echo $row_games['games_id']; ?>">Jetzt spielen</a>
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
    show_no_access_message();
}
?>