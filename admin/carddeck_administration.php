<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/admin/carddeckadministration' => 'Admin - '.TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_administration_headline'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_administration_headline']);
    ?>
    <div class="row">
        <div class="col col-12 col-md-6">
            <a href="/admin/addcarddeck"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_add_headline']; ?></a>
        </div>
        <div class="col col-12 col-md-6">
            <a href="/admin/editcarddeck"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_edit_headline']; ?></a>
        </div>
    </div>
    <?php
}
?>
