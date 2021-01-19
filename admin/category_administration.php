<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/admin/categoryadministration' => 'Admin - '.TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline']);
    ?>
    <div class="row">
        <div class="col col-12 col-md-6">
            <a href="/admin/addcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['category_add_headline']; ?></a>
        </div>
        <div class="col col-12 col-md-6">
            <a href="/admin/editcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['category_edit_headline']; ?></a>
        </div>
        <div class="col col-12 col-md-6">
            <a href="/admin/addsubcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_add_headline']; ?></a>
        </div>
        <div class="col col-12 col-md-6">
            <a href="/admin/editsubcategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_edit_headline']; ?></a>
        </div>
    </div>
    <?php
}
?>
