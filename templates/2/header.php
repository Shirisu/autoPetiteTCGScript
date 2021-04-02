<div class="wrapper">
    <?php
    /**
     * Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"
     * Tip 2: you can also add an image using data-image tag
     **/
    ?>
    <div class="sidebar" data-color="purple" data-image="<?php echo HOST_URL; ?>/templates/2/assets/img/sidebar-1.jpg">
        <div class="sidebar-wrapper">
            <div class="logo">
                <span class="simple-text">Sidebar</span>
            </div>
            <ul class="nav">
                <?php
                // sidebar navigation
                require_once("navigation/sidebar.php");
                ?>
            </ul>
        </div>
    </div>
    <div class="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg " color-on-scroll="500">
            <?php
            // top navigation
            require_once("navigation/header.php");
            ?>
        </nav>
        <!-- End Navbar -->
        <div class="content">
            <div class="container-fluid">