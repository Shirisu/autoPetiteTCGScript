<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <button class="btn btn-dark" id="menu-toggle"><i class="fas fa-angle-double-left"></i></button>

    <span class="d-md-none d-lg-none d-xl-none">
        <a href="<?php echo HOST_URL; ?>"><?php echo TCG_NAME; ?></a>
    </span>

    <button class="btn btn-dark collapsed d-lg-none d-xl-none" id="top-menu-toggle" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLanguage" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language']; ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownLanguage">
                        <?php
                        navlink_language(TRANSLATIONS[$GLOBALS['language']]['general']['text_language_en'],'en');
                        navlink_language(TRANSLATIONS[$GLOBALS['language']]['general']['text_language_de'],'de');
                        ?>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</nav>