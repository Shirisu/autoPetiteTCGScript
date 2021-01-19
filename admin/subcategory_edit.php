<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    global $link;

    if (isset($subcategoryId)) {
        $sql = "SELECT carddeck_sub_cat_id, carddeck_sub_cat_name, carddeck_sub_cat_main_cat_id
                FROM carddeck_sub_cat
                WHERE carddeck_sub_cat_id = '".$subcategoryId."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $row = mysqli_fetch_assoc($result);

        $subcategory_name = $row['carddeck_sub_cat_name'];
        $main_category_id = $row['carddeck_sub_cat_main_cat_id'];
        if (isset($_POST['subcategory_id']) && isset($_POST['main_category_id'])) {
            $subcategory_name = mysqli_real_escape_string($link, $_POST['subcategory_name']);
            $main_category_id = mysqli_real_escape_string($link, $_POST['main_category_id']);

            mysqli_query($link, "UPDATE carddeck_sub_cat
                         SET carddeck_sub_cat_name = '".$subcategory_name."',
                             carddeck_sub_cat_main_cat_id = '".$main_category_id."'
                         WHERE carddeck_sub_cat_id = ".$subcategoryId."
                         LIMIT 1") OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_save'], 'success');
        } elseif(isset($_POST['subcategory_name']) && !isset($_POST['main_category_id'])) {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_no_category_selected'], 'danger');
        }

        $breadcrumb = array(
            '/' => 'Home',
            '/admin/categoryadministration' => 'Admin - '.TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'],
            '/admin/editsubcategory' => TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_edit_headline'],
            '/admin/editsubcategory/'.$subcategoryId => $subcategory_name,
        );
        breadcrumb($breadcrumb);

        title(TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_edit_headline']);

        $sql_cat = "SELECT carddeck_cat_id, carddeck_cat_name
                    FROM carddeck_cat
                    ORDER BY carddeck_cat_name ASC";
        $result_cat = mysqli_query($link, $sql_cat) OR die(mysqli_error($link));
        ?>
        <form action="/admin/editsubcategory/<?php echo $subcategoryId; ?>" method="post">
            <div class="row align-items-center">
                <div class="form-group col col-12 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyID">ID</span>
                        </div>
                        <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyID" required value="<?php echo $row['carddeck_sub_cat_id']; ?>" />
                    </div>
                    <input type="hidden" class="form-control" id="subcategory_id" name="subcategory_id" value="<?php echo $row['carddeck_sub_cat_id']; ?>" />
                </div>
                <div class="form-group col col-12 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyName">Name</span>
                        </div>
                        <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" aria-describedby="ariaDescribedbyName" pattern="[a-zA-Z 0-9]*" maxlength="255" value="<?php echo $subcategory_name; ?>" required />
                    </div>
                    <small id="ariaDescribedbyName" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_only_letter_numbers_and_spaces']; ?></small>
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
                        alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_no_category_yet'], 'danger');
                    }
                    ?>
                </div>
                <div class="form-group col col-12">
                    <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_save']; ?></button>
                </div>
            </div>
        </form>
        <?php
    } else {
        $breadcrumb = array(
            '/' => 'Home',
            '/admin/categoryadministration' => 'Admin - '.TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'],
            '/admin/editsubcategory' => TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_edit_headline'],
        );
        breadcrumb($breadcrumb);

        title(TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_edit_headline']);

        $sql = "SELECT carddeck_sub_cat_id, carddeck_sub_cat_name, carddeck_cat_name
                FROM carddeck_sub_cat, carddeck_cat
                WHERE carddeck_sub_cat_main_cat_id = carddeck_cat_id
                ORDER BY carddeck_sub_cat_name";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $count = mysqli_num_rows($result);
        if ($count) {
            ?>
            <div class="row">
                <div class="col">
                    <table id="admin_member_edit_table" data-mobile-responsive="true">
                        <thead>
                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="name">Name</th>
                            <th data-field="maincategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category_main']; ?></th>
                            <th data-field="options"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['carddeck_sub_cat_id']; ?></td>
                                <td><?php echo $row['carddeck_sub_cat_name']; ?></td>
                                <td><?php echo $row['carddeck_cat_name']; ?></td>
                                <td><a href="/admin/editsubcategory/<?php echo $row['carddeck_sub_cat_id']; ?>">Edit</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="form-group col mt-2">
                    <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_nodata'], 'danger'); ?>
                </div>
            </div>
            <?php
        }
    }
}
?>