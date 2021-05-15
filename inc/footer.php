    <?php
    $filename = "templates/".TCG_TEMPLATE."/footer.php";
    if (file_exists($filename)) {
        require_once($filename);
    } else {
        require_once("templates/1/footer.php");
    }
    ?>

    <div id="toast-container" aria-live="polite" aria-atomic="true">
        <div id="toast-wrapper">
        </div>
    </div>

    <script>
        var tcgHostUrl = '<?php echo HOST_URL ?>';
    </script>
    <script src="<?php echo HOST_URL; ?>/assets/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo HOST_URL; ?>/assets/js/popper.min.js" type="text/javascript"></script>
    <script src="<?php echo HOST_URL; ?>/assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo HOST_URL; ?>/assets/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?php echo HOST_URL; ?>/assets/js/bootstrap-table.min.js" type="text/javascript"></script>
    <script src="<?php echo HOST_URL; ?>/assets/js/bootstrap-table-mobile.min.js" type="text/javascript"></script>
    <script src="<?php echo HOST_URL; ?>/assets/js/script.js" type="text/javascript"></script>

    <?php
    $filename = "templates/".TCG_TEMPLATE."/_include_scripts.php";
    if (file_exists($filename)) {
        require_once($filename);
    }
    ?>
    </body>
</html>