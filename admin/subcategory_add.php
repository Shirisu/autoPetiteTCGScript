<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/addsubcategory' => TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_add_headline'],
    );
    breadcrumb($breadcrumb);

    title(TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_add_headline']);

    if (isset($_POST['subcategory_name']) && isset($_POST['main_category_id'])) {
        $subcategory_name = mysqli_real_escape_string($link, strip_tags(trim($_POST['subcategory_name'])));

        $main_category_id = mysqli_real_escape_string($link, trim($_POST['main_category_id']));

        $sql = "SELECT carddeck_sub_cat_name
                FROM carddeck_sub_cat
                WHERE carddeck_sub_cat_name = '".$subcategory_name."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_subcategory_name_exists'], 'danger');
        } else {
            $sql_cat_exists = "SELECT carddeck_cat_id
                               FROM carddeck_cat
                               WHERE carddeck_cat_id = '".$main_category_id."'
                               LIMIT 1";
            $result_cat_exists = mysqli_query($link, $sql_cat_exists) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_cat_exists)) {
                mysqli_query($link, "
                INSERT INTO carddeck_sub_cat
                (carddeck_sub_cat_main_cat_id, carddeck_sub_cat_name)
                VALUES
                ('".$main_category_id."','".$subcategory_name."')")
                OR die(mysqli_error($link));

                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_add'], 'success');
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_category_dont_exists'], 'danger');
            }
        }
    } elseif (isset($_POST['subcategory_name']) && !isset($_POST['main_category_id'])) {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_no_category_selected'], 'danger');
    }

    $sql_cat = "SELECT carddeck_cat_id, carddeck_cat_name
                FROM carddeck_cat
                ORDER BY carddeck_cat_name ASC";
    $result_cat = mysqli_query($link, $sql_cat) OR die(mysqli_error($link));
    ?>
    <form action="<?php echo HOST_URL; ?>/administration/addsubcategory" method="post">
        <div class="row align-items-center">
            <div class="form-group col col-12 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyName">Name</span>
                    </div>
                    <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" aria-describedby="ariaDescribedbyName" maxlength="255" value="" required />
                </div>
            </div>
            <div class="form-group col col-12 mb-2">
                <?php
                if (mysqli_num_rows($result_cat)) {
                    ?>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyCategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category']; ?></span>
                        </div>
                        <select class="custom-select" id="main_category_id" name="main_category_id" aria-describedby="ariaDescribedbyCategory" required>
                            <option selected disabled hidden value=""></option>
                            <?php
                            while ($row_cat = mysqli_fetch_assoc($result_cat)) {
                                ?>
                                <option value="<?php echo $row_cat['carddeck_cat_id']; ?>"><?php echo $row_cat['carddeck_cat_name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                <?php
                } else {
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_category_yet'], 'danger');
                }
                ?>
            </div>
            <div class="form-group col col-12">
                <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_add']; ?></button>
            </div>
        </div>
    </form>
    <?php
} else {
    show_no_access_message();
}
?>