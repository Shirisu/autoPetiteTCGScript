<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/addcategory' => TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['category_add_headline'],
    );
    breadcrumb($breadcrumb);

    title(TRANSLATIONS[$GLOBALS['language']]['admin']['category_add_headline']);

    if (isset($_POST['category_name'])) {
        $category_name = mysqli_real_escape_string($link, strip_tags(trim($_POST['category_name'])));

        $sql = "SELECT carddeck_cat_name
                FROM carddeck_cat
                WHERE carddeck_cat_name = '".$category_name."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result)) {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_category_name_exists'], 'danger');
        } else {
            mysqli_query($link, "
                INSERT INTO carddeck_cat
                (carddeck_cat_name)
                VALUES
                ('".$category_name."')")
            OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_add'], 'success');
        }
    }

    ?>
    <form action="<?php echo HOST_URL; ?>/administration/addcategory" method="post">
        <div class="row align-items-center">
            <div class="form-group col mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyName">Name</span>
                    </div>
                    <input type="text" class="form-control" id="category_name" name="category_name" aria-describedby="ariaDescribedbyName" maxlength="255" value="" required />
                </div>
            </div>
            <div class="form-group col col-12">
                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_add']; ?></button>
            </div>
        </div>
    </form>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>