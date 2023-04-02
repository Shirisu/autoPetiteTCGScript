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
                if (isset($_POST['tictactoe']) && $_POST['tictactoe'] == 'prizes') {
                    $random_choice = mt_rand(1, 3);

                    if ($random_choice == 1) {
                        insert_cards($member_id, 2);
                        $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_2_cards'].': '.implode(', ' ,$_SESSION['insert_cards']);
                        insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                        alert_box(
                            TRANSLATIONS[$GLOBALS['language']]['games']['text_game_win'].'!<br />2 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].': '.implode(', ',$_SESSION['insert_cards']).
                            '<br />'.
                            $_SESSION['insert_cards_images']
                            , 'success');
                    } elseif ($random_choice == 2) {
                        insert_cards($member_id, 3);
                        $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_3_cards'].': '.implode(', ', $_SESSION['insert_cards']);
                        insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                        alert_box(
                            TRANSLATIONS[$GLOBALS['language']]['games']['text_game_win'].'!<br />3 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].': '.implode(', ',$_SESSION['insert_cards']).
                            '<br />'.
                            $_SESSION['insert_cards_images']
                            , 'success');
                    } elseif ($random_choice == 3) {
                        insert_cards($member_id, 1);
                        $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win_1_card'].': '.implode(', ', $_SESSION['insert_cards']);
                        insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                        alert_box(
                            TRANSLATIONS[$GLOBALS['language']]['games']['text_game_win'].'!<br />1 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_card'].': '.implode(', ',$_SESSION['insert_cards']).
                            '<br />'.
                            $_SESSION['insert_cards_images']
                            , 'success');
                    }

                    insert_game_played($member_id, $game_id);
                } else if (isset($_POST['tictactoe']) && $_POST['tictactoe'] == 'lost') {
                    alert_box(
                        TRANSLATIONS[$GLOBALS['language']]['games']['text_game_lost'].'...'
                        , 'danger');
                    insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, TRANSLATIONS[$GLOBALS['language']]['games']['text_game_lost'], $member_id);
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
                            <?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_tic_game']; ?>
                        </div>
                        <div class="col col-12 mb-3 text-center">
                            <script language="javascript">
                                /*
                                Code Written by Cory Fogliani (Email: cory@ijustdontcare.com)
                                Testers: Cory Fogliani, Chris Gordon
                                Featured on JavaScript Kit (http://javascriptkit.com)
                                For this and over 400+ free scripts, visit http://javascriptkit.com
                                */

                                //if IE4/NS6, apply style
                                if (document.all||document.getElementById){
                                    document.write('<style>.tictac{')
                                    document.write('width:50px;height:50px;')
                                    document.write('}</style>')
                                }

                                var sqr1
                                var sqr2
                                var sqr3
                                var sqr4
                                var sqr5
                                var sqr6
                                var sqr7
                                var sqr8
                                var sqr9
                                var sqr1T = 0
                                var sqr2T = 0
                                var sqr3T = 0
                                var sqr4T = 0
                                var sqr5T = 0
                                var sqr6T = 0
                                var sqr7T = 0
                                var sqr8T = 0
                                var sqr9T = 0
                                var moveCount = 0
                                var turn = 0
                                var mode = 1

                                function vari()
                                {
                                    sqr1 = document.tic.sqr1.value
                                    sqr2 = document.tic.sqr2.value
                                    sqr3 = document.tic.sqr3.value
                                    sqr4 = document.tic.sqr4.value
                                    sqr5 = document.tic.sqr5.value
                                    sqr6 = document.tic.sqr6.value
                                    sqr7 = document.tic.sqr7.value
                                    sqr8 = document.tic.sqr8.value
                                    sqr9 = document.tic.sqr9.value
                                }
                                function check()
                                {
                                    if(sqr1 == " X " && sqr2 == " X " && sqr3 == " X ")
                                    {
                                        alert("You Win!")
                                        document.tic.tictactoe.value = 'prizes';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr4 == " X " && sqr5 == " X " && sqr6 == " X ")
                                    {
                                        alert("You Win!")
                                        document.tic.tictactoe.value = 'prizes';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr7 == " X " && sqr8 == " X " && sqr9 == " X ")
                                    {
                                        alert("You Win!")
                                        document.tic.tictactoe.value = 'prizes';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " X " && sqr5 == " X " && sqr9 == " X ")
                                    {
                                        alert("You Win!")
                                        document.tic.tictactoe.value = 'prizes';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " X " && sqr4 == " X " && sqr7 == " X ")
                                    {
                                        alert("You Win!")
                                        document.tic.tictactoe.value = 'prizes';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr2 == " X " && sqr5 == " X " && sqr8 == " X ")
                                    {
                                        alert("You Win!")
                                        document.tic.tictactoe.value = 'prizes';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr3 == " X " && sqr6 == " X " && sqr9 == " X ")
                                    {
                                        alert("You Win!")
                                        document.tic.tictactoe.value = 'prizes';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " X " && sqr5 == " X " && sqr9 == " X ")
                                    {
                                        alert("You Win!")
                                        document.tic.tictactoe.value = 'prizes';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr3 == " X " && sqr5 == " X " && sqr7 == " X ")
                                    {
                                        alert("You Win!")
                                        document.tic.tictactoe.value = 'prizes';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else
                                    {
                                        winCheck()
                                        check2()
                                        drawCheck()
                                    }
                                }

                                function check2()
                                {
                                    vari()
                                    drawCheck()
                                    if(sqr1 == " O " && sqr2 == " O " && sqr3 == " O ")
                                    {
                                        alert("You Lose!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr4 == " O " && sqr5 == " O " && sqr6 == " O ")
                                    {
                                        alert("You Lose!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr7 == " O " && sqr8 == " O " && sqr9 == " O ")
                                    {
                                        alert("You Lose!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " O " && sqr5 == " O " && sqr9 == " O ")
                                    {
                                        alert("You Lose!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " O " && sqr4 == " O " && sqr7 == " O ")
                                    {
                                        alert("You Lose!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr2 == " O " && sqr5 == " O " && sqr8 == " O ")
                                    {
                                        alert("You Lose!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr3 == " O " && sqr6 == " O " && sqr9 == " O ")
                                    {
                                        alert("You Lose!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " O " && sqr5 == " O " && sqr9 == " O ")
                                    {
                                        alert("You Lose!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr3 == " O " && sqr5 == " O " && sqr7 == " O ")
                                    {
                                        alert("You Lose!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                }

                                function player1Check()
                                {
                                    if(sqr1 == " X " && sqr2 == " X " && sqr3 == " X ")
                                    {
                                        alert("Player 1 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr4 == " X " && sqr5 == " X " && sqr6 == " X ")
                                    {
                                        alert("Player 1 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr7 == " X " && sqr8 == " X " && sqr9 == " X ")
                                    {
                                        alert("Player 1 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " X " && sqr5 == " X " && sqr9 == " X ")
                                    {
                                        alert("Player 1 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " X " && sqr4 == " X " && sqr7 == " X ")
                                    {
                                        alert("Player 1 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr2 == " X " && sqr5 == " X " && sqr8 == " X ")
                                    {
                                        alert("Player 1 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr3 == " X " && sqr6 == " X " && sqr9 == " X ")
                                    {
                                        alert("Player 1 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " X " && sqr5 == " X " && sqr9 == " X ")
                                    {
                                        alert("Player 1 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr3 == " X " && sqr5 == " X " && sqr7 == " X ")
                                    {
                                        alert("Player 1 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else
                                    {
                                        player2Check()
                                        drawCheck()
                                    }
                                }

                                function player2Check()
                                {
                                    vari()
                                    drawCheck()
                                    if(sqr1 == " O " && sqr2 == " O " && sqr3 == " O ")
                                    {
                                        alert("Player 2 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr4 == " O " && sqr5 == " O " && sqr6 == " O ")
                                    {
                                        alert("Player 2 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr7 == " O " && sqr8 == " O " && sqr9 == " O ")
                                    {
                                        alert("Player 2 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " O " && sqr5 == " O " && sqr9 == " O ")
                                    {
                                        alert("Player 2 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " O " && sqr4 == " O " && sqr7 == " O ")
                                    {
                                        alert("Player 2 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr2 == " O " && sqr5 == " O " && sqr8 == " O ")
                                    {
                                        alert("Player 2 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr3 == " O " && sqr6 == " O " && sqr9 == " O ")
                                    {
                                        alert("Player 2 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr1 == " O " && sqr5 == " O " && sqr9 == " O ")
                                    {
                                        alert("Player 2 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                    else if(sqr3 == " O " && sqr5 == " O " && sqr7 == " O ")
                                    {
                                        alert("Player 2 wins!")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                }

                                function drawCheck()
                                {
                                    vari()
                                    moveCount = sqr1T + sqr2T + sqr3T + sqr4T + sqr5T + sqr6T + sqr7T + sqr8T + sqr9T
                                    if(moveCount == 9)
                                    {
                                        alert("Draw")
                                        document.tic.tictactoe.value = 'lost';
                                        document.tic.submit();
                                        reset();
                                    }
                                }

                                function winCheck()
                                {
                                    check2()
                                    if(sqr1 == " O " && sqr2 == " O " && sqr3T == 0 && turn == 1)
                                    {
                                        document.tic.sqr3.value = " O "
                                        sqr3T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr2 == " O " && sqr3 == " O " && sqr1T == 0 && turn == 1)
                                    {
                                        document.tic.sqr1.value = " O "
                                        sqr1T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr4 == " O " && sqr5 == " O " && sqr6T == 0 && turn == 1)
                                    {
                                        document.tic.sqr6.value = " O "
                                        sqr6T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr5 == " O " && sqr6 == " O " && sqr4T == 0 && turn == 1)
                                    {
                                        document.tic.sqr4.value = " O "
                                        sqr4T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr7 == " O " && sqr8 == " O " && sqr9T == 0 && turn == 1)
                                    {
                                        document.tic.sqr9.value = " O "
                                        sqr9T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr8 == " O " && sqr9 == " O " && sqr7T == 0 && turn == 1)
                                    {
                                        document.tic.sqr7.value = " O "
                                        sqr7T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " O " && sqr5 == " O " && sqr9T == 0 && turn == 1)
                                    {
                                        document.tic.sqr9.value = " O "
                                        sqr9T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr5 == " O " && sqr9 == " O " && sqr1T == 0 && turn == 1)
                                    {
                                        document.tic.sqr1.value = " O "
                                        sqr1T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr3 == " O " && sqr5 == " O " && sqr7T == 0 && turn == 1)
                                    {
                                        document.tic.sqr7.value = " O "
                                        sqr7T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr7 == " O " && sqr5 == " O " && sqr3T == 0 && turn == 1)
                                    {
                                        document.tic.sqr3.value = " O "
                                        sqr3T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " O " && sqr3 == " O " && sqr2T == 0 && turn == 1)
                                    {
                                        document.tic.sqr2.value = " O "
                                        sqr2T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr4 == " O " && sqr6 == " O " && sqr5T == 0 && turn == 1)
                                    {
                                        document.tic.sqr5.value = " O "
                                        sqr5T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr7 == " O " && sqr9 == " O " && sqr8T == 0 && turn == 1)
                                    {
                                        document.tic.sqr8.value = " O "
                                        sqr8T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " O " && sqr7 == " O " && sqr4T == 0 && turn == 1)
                                    {
                                        document.tic.sqr4.value = " O "
                                        sqr4T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr2 == " O " && sqr8 == " O " && sqr5T == 0 && turn == 1)
                                    {
                                        document.tic.sqr5.value = " O "
                                        sqr5T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr3 == " O " && sqr9 == " O " && sqr6T == 0 && turn == 1)
                                    {
                                        document.tic.sqr6.value = " O "
                                        sqr6T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " O " && sqr5 == " O " && sqr9T == 0 && turn == 1)
                                    {
                                        document.tic.sqr9.value = " O "
                                        sqr9T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr4 == " O " && sqr7 == " O " && sqr1T == 0 && turn == 1)
                                    {
                                        document.tic.sqr1.value = " O "
                                        sqr1T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr5 == " O " && sqr8 == " O " && sqr2T == 0 && turn == 1)
                                    {
                                        document.tic.sqr2.value = " O "
                                        sqr2T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr6 == " O " && sqr9 == " O " && sqr3T == 0 && turn == 1)
                                    {
                                        document.tic.sqr3.value = " O "
                                        sqr3T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " O " && sqr4 == " O " && sqr7T == 0 && turn == 1)
                                    {
                                        document.tic.sqr7.value = " O "
                                        sqr7T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr2 == " O " && sqr5 == " O " && sqr8T == 0 && turn == 1)
                                    {
                                        document.tic.sqr8.value = " O "
                                        sqr8T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr3 == " O " && sqr6 == " O " && sqr9T == 0 && turn == 1)
                                    {
                                        document.tic.sqr9.value = " O "
                                        sqr9T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " O " && sqr9 == " O " && sqr5T == 0 && turn == 1)
                                    {
                                        document.tic.sqr5.value = " O "
                                        sqr5T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr3 == " O " && sqr7 == " O " && sqr5T == 0 && turn == 1)
                                    {
                                        document.tic.sqr5.value = " O "
                                        sqr5T = 1;
                                        turn = 0;
                                    }
                                    else
                                    {
                                        computer()
                                    }
                                    check2()
                                }
                                function computer()
                                {
                                    check2()
                                    if(sqr1 == " X " && sqr2 == " X " && sqr3T == 0 && turn == 1)
                                    {
                                        document.tic.sqr3.value = " O "
                                        sqr3T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr2 == " X " && sqr3 == " X " && sqr1T == 0 && turn == 1)
                                    {
                                        document.tic.sqr1.value = " O "
                                        sqr1T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr4 == " X " && sqr5 == " X " && sqr6T == 0 && turn == 1)
                                    {
                                        document.tic.sqr6.value = " O "
                                        sqr6T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr5 == " X " && sqr6 == " X " && sqr4T == 0 && turn == 1)
                                    {
                                        document.tic.sqr4.value = " O "
                                        sqr4T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr7 == " X " && sqr8 == " X " && sqr9T == 0 && turn == 1)
                                    {
                                        document.tic.sqr9.value = " O "
                                        sqr9T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr8 == " X " && sqr9 == " X " && sqr7T == 0 && turn == 1)
                                    {
                                        document.tic.sqr7.value = " O "
                                        sqr7T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " X " && sqr5 == " X " && sqr9T == 0 && turn == 1)
                                    {
                                        document.tic.sqr9.value = " O "
                                        sqr9T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr5 == " X " && sqr9 == " X " && sqr1T == 0 && turn == 1)
                                    {
                                        document.tic.sqr1.value = " O "
                                        sqr1T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr3 == " X " && sqr5 == " X " && sqr7T == 0 && turn == 1)
                                    {
                                        document.tic.sqr7.value = " O "
                                        sqr7T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr7 == " X " && sqr5 == " X " && sqr3T == 0 && turn == 1)
                                    {
                                        document.tic.sqr3.value = " O "
                                        sqr3T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " X " && sqr3 == " X " && sqr2T == 0 && turn == 1)
                                    {
                                        document.tic.sqr2.value = " O "
                                        sqr2T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr4 == " X " && sqr6 == " X " && sqr5T == 0 && turn == 1)
                                    {
                                        document.tic.sqr5.value = " O "
                                        sqr5T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr7 == " X " && sqr9 == " X " && sqr8T == 0 && turn == 1)
                                    {
                                        document.tic.sqr8.value = " O "
                                        sqr8T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " X " && sqr7 == " X " && sqr4T == 0 && turn == 1)
                                    {
                                        document.tic.sqr4.value = " O "
                                        sqr4T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr2 == " X " && sqr8 == " X " && sqr5T == 0 && turn == 1)
                                    {
                                        document.tic.sqr5.value = " O "
                                        sqr5T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr3 == " X " && sqr9 == " X " && sqr6T == 0 && turn == 1)
                                    {
                                        document.tic.sqr6.value = " O "
                                        sqr6T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " X " && sqr5 == " X " && sqr9T == 0 && turn == 1)
                                    {
                                        document.tic.sqr9.value = " O "
                                        sqr9T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr4 == " X " && sqr7 == " X " && sqr1T == 0 && turn == 1)
                                    {
                                        document.tic.sqr1.value = " O "
                                        sqr1T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr5 == " X " && sqr8 == " X " && sqr2T == 0 && turn == 1)
                                    {
                                        document.tic.sqr2.value = " O "
                                        sqr2T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr6 == " X " && sqr9 == " X " && sqr3T == 0 && turn == 1)
                                    {
                                        document.tic.sqr3.value = " O "
                                        sqr3T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " X " && sqr4 == " X " && sqr7T == 0 && turn == 1)
                                    {
                                        document.tic.sqr7.value = " O "
                                        sqr7T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr2 == " X " && sqr5 == " X " && sqr8T == 0 && turn == 1)
                                    {
                                        document.tic.sqr8.value = " O "
                                        sqr8T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr3 == " X " && sqr6 == " X " && sqr9T == 0 && turn == 1)
                                    {
                                        document.tic.sqr9.value = " O "
                                        sqr9T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr1 == " X " && sqr9 == " X " && sqr5T == 0 && turn == 1)
                                    {
                                        document.tic.sqr5.value = " O "
                                        sqr5T = 1;
                                        turn = 0;
                                    }
                                    else if(sqr3 == " X " && sqr7 == " X " && sqr5T == 0 && turn == 1)
                                    {
                                        document.tic.sqr5.value = " O "
                                        sqr5T = 1;
                                        turn = 0;
                                    }
                                    else
                                    {
                                        AI()
                                    }
                                    check2()
                                }

                                function AI()
                                {
                                    vari()
                                    if(document.tic.sqr5.value == "     " && turn == 1)
                                    {
                                        document.tic.sqr5.value = " O "
                                        turn = 0
                                        sqr5T = 1
                                    }
                                    else if(document.tic.sqr1.value == "     " && turn == 1)
                                    {
                                        document.tic.sqr1.value = " O "
                                        turn = 0
                                        sqr1T = 1
                                    }
                                    else if(document.tic.sqr9.value == "     " && turn == 1)
                                    {
                                        document.tic.sqr9.value = " O "
                                        turn = 0
                                        sqr9T = 1
                                    }
                                    else if(document.tic.sqr6.value == "     " && turn == 1)
                                    {
                                        document.tic.sqr6.value = " O "
                                        turn = 0
                                        sqr6T = 1
                                    }
                                    else if(document.tic.sqr2.value == "     " && turn == 1)
                                    {
                                        document.tic.sqr2.value = " O "
                                        turn = 0
                                        sqr2T = 1
                                    }
                                    else if(document.tic.sqr8.value == "     " && turn == 1)
                                    {
                                        document.tic.sqr8.value = " O "
                                        turn = 0
                                        sqr8T = 1
                                    }
                                    else if(document.tic.sqr3.value == "     " && turn == 1)
                                    {
                                        document.tic.sqr3.value = " O "
                                        turn = 0
                                        sqr3T = 1
                                    }
                                    else if(document.tic.sqr7.value == "     " && turn == 1)
                                    {
                                        document.tic.sqr7.value = " O "
                                        turn = 0
                                        sqr7T = 1
                                    }
                                    else if(document.tic.sqr4.value == "     " && turn == 1)
                                    {
                                        document.tic.sqr4.value = " O "
                                        turn = 0
                                        sqr4T = 1
                                    }
                                    check2()
                                }

                                function reset()
                                {
                                    document.tic.sqr1.value = "     "
                                    document.tic.sqr2.value = "     "
                                    document.tic.sqr3.value = "     "
                                    document.tic.sqr4.value = "     "
                                    document.tic.sqr5.value = "     "
                                    document.tic.sqr6.value = "     "
                                    document.tic.sqr7.value = "     "
                                    document.tic.sqr8.value = "     "
                                    document.tic.sqr9.value = "     "
                                    sqr1T = 0
                                    sqr2T = 0
                                    sqr3T = 0
                                    sqr4T = 0
                                    sqr5T = 0
                                    sqr6T = 0
                                    sqr7T = 0
                                    sqr8T = 0
                                    sqr9T = 0
                                    vari()
                                    turn = 0
                                    moveCount = 0
                                    document.cookie = 'tictactoe=';
                                }

                                function resetter()
                                {
                                    reset()
                                }
                            </script>
                        </div>
                        <div class="col col-12 col-md-12 mb-3 text-center">
                            <table class="optional">
                                <form name="tic" action="<?php echo HOST_URL; ?>/games/skill/<?php echo $game_id; ?>" method="post">
                                    <INPUT TYPE="button" NAME="sqr1" class="tictac" value="     " onClick="if(document.tic.sqr1.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr1.value = ' X '; sqr1T = 1; turn = 1; vari(); check();} else if(document.tic.sqr1.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr1.value = ' X '; sqr1T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr1.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr1.value = ' O '; sqr1T = 1; turn = 1; vari(); player1Check()} drawCheck()">
                                    <INPUT TYPE="button" NAME="sqr2" class="tictac" value="     " onClick="if(document.tic.sqr2.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr2.value = ' X '; sqr2T = 1; turn = 1; vari(); check();} else if(document.tic.sqr2.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr2.value = ' X '; sqr2T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr2.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr2.value = ' O '; sqr2T = 1; turn = 1; vari(); player1Check()} drawCheck()">
                                    <INPUT TYPE="button" NAME="sqr3" class="tictac" value="     " onClick="if(document.tic.sqr3.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr3.value = ' X '; sqr3T = 1; turn = 1; vari(); check();} else if(document.tic.sqr3.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr3.value = ' X '; sqr3T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr3.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr3.value = ' O '; sqr3T = 1; turn = 1; vari(); player1Check()} drawCheck()"><br />
                                    <INPUT TYPE="button" NAME="sqr4" class="tictac" value="     " onClick="if(document.tic.sqr4.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr4.value = ' X '; sqr4T = 1; turn = 1; vari(); check();} else if(document.tic.sqr4.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr4.value = ' X '; sqr4T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr4.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr4.value = ' O '; sqr4T = 1; turn = 1; vari(); player1Check()} drawCheck()">
                                    <INPUT TYPE="button" NAME="sqr5" class="tictac" value="     " onClick="if(document.tic.sqr5.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr5.value = ' X '; sqr5T = 1; turn = 1; vari(); check();} else if(document.tic.sqr5.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr5.value = ' X '; sqr5T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr5.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr5.value = ' O '; sqr5T = 1; turn = 1; vari(); player1Check()} drawCheck()">
                                    <INPUT TYPE="button" NAME="sqr6" class="tictac" value="     " onClick="if(document.tic.sqr6.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr6.value = ' X '; sqr6T = 1; turn = 1; vari(); check();} else if(document.tic.sqr6.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr6.value = ' X '; sqr6T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr6.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr6.value = ' O '; sqr6T = 1; turn = 1; vari(); player1Check()} drawCheck()"><br />
                                    <INPUT TYPE="button" NAME="sqr7" class="tictac" value="     " onClick="if(document.tic.sqr7.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr7.value = ' X '; sqr7T = 1; turn = 1; vari(); check();} else if(document.tic.sqr7.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr7.value = ' X '; sqr7T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr7.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr7.value = ' O '; sqr7T = 1; turn = 1; vari(); player1Check()} drawCheck()">
                                    <INPUT TYPE="button" NAME="sqr8" class="tictac" value="     " onClick="if(document.tic.sqr8.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr8.value = ' X '; sqr8T = 1; turn = 1; vari(); check();} else if(document.tic.sqr8.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr8.value = ' X '; sqr8T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr8.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr8.value = ' O '; sqr8T = 1; turn = 1; vari(); player1Check()} drawCheck()">
                                    <INPUT TYPE="button" NAME="sqr9" class="tictac" value="     " onClick="if(document.tic.sqr9.value == '     ' && turn == 0 && mode == 1) {document.tic.sqr9.value = ' X '; sqr9T = 1; turn = 1; vari(); check();} else if(document.tic.sqr9.value == '     ' && turn == 1 && mode == 2) {document.tic.sqr9.value = ' X '; sqr9T = 1; turn = 0; vari(); player1Check()} else if(document.tic.sqr9.value == '     ' && turn == 0 && mode == 2) {document.tic.sqr9.value = ' O '; sqr9T = 1; turn = 1; vari(); player1Check()} drawCheck()">
                                    <input type="hidden" name="tictactoe" value="" />
                                </form>
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