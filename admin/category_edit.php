<?php
if($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2) {
    global $link;

    if (isset($categoryId)) {
        $sql = "SELECT carddeck_cat_id, carddeck_cat_name
                FROM carddeck_cat
                WHERE carddeck_cat_id = '".$categoryId."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $row = mysqli_fetch_assoc($result);

        $category_name = $row['carddeck_cat_name'];
        if (isset($_POST['category_id'])) {
            $category_name = mysqli_real_escape_string($link, strip_tags(trim($_POST['category_name'])));

            mysqli_query($link, "UPDATE carddeck_cat
                         SET carddeck_cat_name = '".$category_name."'
                         WHERE carddeck_cat_id = ".$categoryId."
                         LIMIT 1") OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_save'], 'success');
        }

        $breadcrumb = array(
            '/' => 'Home',
            '/administration' => 'Administration',
            '/administration/editcategory' => TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['category_edit_headline'],
            '/administration/editcategory/'.$categoryId => $category_name,
        );
        breadcrumb($breadcrumb);

        title(TRANSLATIONS[$GLOBALS['language']]['admin']['category_edit_headline']);
        ?>
        <form action="/administration/editcategory/<?php echo $categoryId; ?>" method="post">
            <div class="row align-items-center">
                <div class="form-group col col-12 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyID">ID</span>
                        </div>
                        <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyID" required value="<?php echo $row['carddeck_cat_id']; ?>" />
                    </div>
                    <input type="hidden" class="form-control" id="category_id" name="category_id" value="<?php echo $row['carddeck_cat_id']; ?>" />
                </div>
                <div class="form-group col col-12 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyName">Name</span>
                        </div>
                        <input type="text" class="form-control" id="category_name" name="category_name" aria-describedby="ariaDescribedbyName" maxlength="255" value="<?php echo $category_name; ?>" required />
                    </div>
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
            '/administration' => 'Administration',
            '/administration/editcategory' => TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['category_edit_headline'],
        );
        breadcrumb($breadcrumb);

        title(TRANSLATIONS[$GLOBALS['language']]['admin']['category_edit_headline']);

        $sql = "SELECT carddeck_cat_id, carddeck_cat_name
                FROM carddeck_cat
                ORDER BY carddeck_cat_name ASC";
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
                            <th data-field="options"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['carddeck_cat_id']; ?></td>
                                <td><?php echo $row['carddeck_cat_name']; ?></td>
                                <td><a href="/administration/editcategory/<?php echo $row['carddeck_cat_id']; ?>">Edit</a></td>
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
                <div class="col mt-2">
                    <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_nodata'], 'danger'); ?>
                </div>
            </div>
            <?php
        }
    }
}
?>