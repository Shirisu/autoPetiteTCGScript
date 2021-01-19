<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <button class="btn btn-dark" id="menu-toggle"><i class="fas fa-angle-double-left"></i></button>

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
                if(isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rang'] == 4)) {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAdmin" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Admin
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownAdmin">
                            <?php
                            require_once("header_admin.php");
                            ?>
                        </div>
                    </li>
                    <?php
                }
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
                        <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language']; ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMemberarea">
                        <?php
                        navlink_language(TRANSLATIONS[$GLOBALS['language']]['general']['text_language_en'],'en');
                        navlink_language(TRANSLATIONS[$GLOBALS['language']]['general']['text_language_de'],'de');
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
</nav>