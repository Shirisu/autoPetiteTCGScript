<div class="d-flex" id="wrapper">
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">
            <a href="<?php echo HOST_URL; ?>"><?php echo TCG_NAME; ?></a>
        </div>
        <div class="list-group list-group-flush">
            <?php
            // sidebar navigation
            require_once("navigation/sidebar.php");
            ?>
        </div>
    </div>

    <div id="page-content-wrapper">
        <?php
        // top navigation
        require_once("navigation/header.php");
        ?>
        <div class="container-fluid mt-3">