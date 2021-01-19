<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/admin/categoryadministration' => 'Admin - '.TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'],
        '/admin/addcategory' => TRANSLATIONS[$GLOBALS['language']]['admin']['category_add_headline'],
    );
    breadcrumb($breadcrumb);

    title(TRANSLATIONS[$GLOBALS['language']]['admin']['category_add_headline']);

    if (isset($_POST['category_name'])) {
        $category_name = mysqli_real_escape_string($link, trim($_POST['category_name']));

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
    <form action="/admin/addcategory" method="post">
        <div class="row align-items-center">
            <div class="form-group col mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyName">Name</span>
                    </div>
                    <input type="email" class="form-control" id="email" name="email" pattern="^[a-zA-Z0-9_\+-]+(\.[a-zA-Z0-9_\+-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.([a-zA-Z]{2,4})$" autocomplete="off" aria-describedby="ariaDescribedbyEmail" required />
                </div>
                <small id="ariaDescribedbyName" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_only_letter_numbers_and_spaces']; ?></small>
            </div>
            <div class="form-group col col-12">
                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_add']; ?></button>
            </div>
        </div>
    </form>
    <?php
}
?>