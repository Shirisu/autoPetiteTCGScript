<div class="container-fluid">
    <a class="navbar-brand" href="<?php echo HOST_URL; ?>"> <?php echo TCG_NAME; ?> </a>
    <button href="" class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-bar burger-lines"></span>
        <span class="navbar-toggler-bar burger-lines"></span>
        <span class="navbar-toggler-bar burger-lines"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navigation">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMain" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>Main</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMain">
                    <?php
                    navlink('Team','team');
                    navlink('Link in','linkin');
                    navlink('Link out','linkout');
                    ?>
                </div>
            </li>
            <?php
            if (isset($_SESSION['member_rank'])) {
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTCG" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>TCG</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownTCG">
                        <?php
                        navlink('F.A.Q.','faq');
                        navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_rules'],'rules');
                        ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo HOST_URL; ?>/logout">
                        <span>Logout</span>
                    </a>
                </li>
                <?php
            } else {
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="no-icon"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language']; ?></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
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
</div>