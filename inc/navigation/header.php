<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="<?php echo HOST_URL; ?>"><?php echo TCG_NAME; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo HOST_URL; ?>">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMain" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Main
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMain">
                        <?php
                        require_once("header_main.php");
                        ?>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTCG" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        TCG
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownTCG">
                        <?php
                        require_once("header_tcg.php");
                        ?>
                    </div>
                </li>
                <?php
                if(isset($_SESSION['member_id'])) {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownGames" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Games
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownGames">
                            <?php
                            require_once("header_games.php");
                            ?>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMemberarea" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea']; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMemberarea">
                            <?php
                            require_once("header_memberarea.php");
                            ?>
                        </div>
                    </li>
                    <?php
                    $sql = "SELECT *
                            FROM member, message
                            WHERE message_to_member_id = '".$_SESSION['member_id']."'
                             AND message_from_member_id = member_id
                             AND message_read = 0
                            ORDER BY message_id DESC";
                    $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                    $count = mysqli_num_rows($result);
                    if ($count > 0) {
                        $text_pn_count = '<b>'.TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'].' ('.$count.')</b>';
                    } else {
                        $text_pn_count = TRANSLATIONS[$GLOBALS['language']]['general']['text_pm'];
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo HOST_URL; ?>/tcg/message"><?php echo $text_pn_count; ?></a>
                    </li>

                    <?php

                    $sql = "SELECT *
          				FROM member_cards
          				WHERE member_cards_member_id = '".$_SESSION['member_id']."'
          				 AND member_cards_cat = 1
          				 AND member_cards_active = 1";
                    $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                    $count = mysqli_num_rows($result);
                    if ($count > 0) {
                        $text_cards_count = '<b>'.TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'].' ('.$count.')</b>';
                    } else {
                        $text_cards_count = TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'];
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo HOST_URL; ?>/tcg/userarea/cards/new"><?php echo $text_cards_count; ?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <ul class="navbar-nav">
                <?php
                if (isset($_SESSION['member_id'])) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo HOST_URL; ?>/logout">Logout</a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMemberarea" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['language_text']; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMemberarea">
                            <?php
                            navlink_language(TRANSLATIONS[$GLOBALS['language']]['general']['language_en_text'],'en');
                            navlink_language(TRANSLATIONS[$GLOBALS['language']]['general']['language_de_text'],'de');
                            ?>
                        </div>
                    </li>
                    <?php
                }
                ?>
                <?php
                $sql_member_online = "SELECT member.member_id, member.member_nick, member.member_rank, member_online.*
                        FROM member,member_online
                        WHERE member.member_id=member_online.member_id
                          AND member.member_on != 0
                        ORDER BY member_nick ASC;";
                $result_member_online = mysqli_query($link, $sql_member_online) OR die(mysqli_error($link));
                $count_member = mysqli_num_rows($result_member_online);
                $sql_member_invi = "SELECT member.member_id, member.member_nick, member.member_rank, member_online.*
                        FROM member,member_online
                        WHERE member.member_id=member_online.member_id
                         AND member.member_on != 1
                        ORDER BY member_nick ASC;";
                $result_member_invi = mysqli_query($link, $sql_member_invi) OR die(mysqli_error($link));
                $count_member_invi = mysqli_num_rows($result_member_invi);

                $is_anybody_online = ($count_member+$count_member_invi > 0);
                if ($is_anybody_online) {
                    $member_text = 'Members';
                } else {
                    $member_text = 'Member';
                }
                $member_text = '';
                if ($count_member_invi <= 0) {
                    $member_text_count = $count_member.' '.$member_text;
                } else {
                    $member_text_count = $count_member+$count_member_invi.' '.$member_text.' ('.$count_member_invi.' invi)';
                }

                if (!$is_anybody_online || !isset($_SESSION['member_id'])) {
                    ?>
                    <li class="nav-item">
                        <span class="nav-link">Online: <i><?php echo $member_text_count; ?></i></span>
                    </li>
                    <?php
                } else {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownOnlineMember" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Online: <i><?php echo $member_text_count; ?></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownOnlineMember">
                            <?php
                            require_once("header_onlinemember.php");
                            ?>
                        </div>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>