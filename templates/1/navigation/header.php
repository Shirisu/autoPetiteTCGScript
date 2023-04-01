<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <button class="btn btn-dark" id="menu-toggle"><i class="fas fa-angle-double-left"></i></button>

        <span class="d-md-none d-lg-none d-xl-none">
            <a href="<?php echo HOST_URL; ?>"><?php echo TCG_NAME; ?></a>
        </span>

        <button class="btn btn-dark collapsed d-lg-none d-xl-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTCG" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        TCG
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownTCG">
                        <?php
                        navlink('F.A.Q.','faq');
                        navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_rules'],'rules');
                        navlink(TRANSLATIONS[$GLOBALS['language']]['general']['text_statistic'],'statistic');
                        ?>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php
                if (isset($_SESSION['member_rank'])) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo HOST_URL; ?>/logout">Logout</a>
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
</nav>