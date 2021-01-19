<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/admin/memberadministration' => 'Admin - '.TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline']);
    ?>
    <div class="row">
        <div class="col col-12 col-md-6">
            <a href="/admin/editmember/all"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['member_edit_headline']; ?></a>
        </div>
    </div>
    <?php
}
?>
