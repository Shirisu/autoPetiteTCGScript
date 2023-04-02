<div class="container-fluid">
    <a class="navbar-brand" href="<?php echo HOST_URL; ?>"> <?php echo TCG_NAME; ?> </a>
    <button class="btn btn-dark collapsed navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-bar burger-lines"></span>
        <span class="navbar-toggler-bar burger-lines"></span>
        <span class="navbar-toggler-bar burger-lines"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navigation">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?php echo HOST_URL; ?>">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMain" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Main
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMain">
                    <?php
                    navlink('Team','team');
                    navlink('Link in','linkin');
                    navlink('Link out','linkout');
                    ?>
                </ul>
            </li>
            <?php
            if (isset($_SESSION['member_rank'])) {
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTCG" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>TCG</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownTCG">
                        <?php
                        navlink('F.A.Q.','faq');
                        navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_rules'],'rules');
                        navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_statistic'],'statistic');
                        ?>
                    </ul>
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLanguage" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_language']; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownLanguage">
                        <?php
                        navlink_language(TRANSLATIONS[$GLOBALS['language']]['general']['text_language_en'],'en');
                        navlink_language(TRANSLATIONS[$GLOBALS['language']]['general']['text_language_de'],'de');
                        ?>
                    </ul>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>