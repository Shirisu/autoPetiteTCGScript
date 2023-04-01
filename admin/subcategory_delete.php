<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2)) {
    global $link;
    if (isset($subcategory_id)) {
        $sql = "SELECT carddeck_sub_cat_id, carddeck_sub_cat_name
                FROM carddeck_sub_cat
                WHERE carddeck_sub_cat_id = '" . $subcategory_id . "'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);

            $breadcrumb = array(
                '/' => 'Home',
                '/administration' => 'Administration',
                '/administration/editsubcategory' => TRANSLATIONS[$GLOBALS['language']]['admin']['category_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_edit_headline'],
                '/administration/deletesubcategory/'.$subcategory_id => TRANSLATIONS[$GLOBALS['language']]['admin']['member_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_delete_headline'],
            );
            breadcrumb($breadcrumb);
            title(TRANSLATIONS[$GLOBALS['language']]['admin']['subcategory_delete_headline']);

            if (isset($_POST['carddeck_sub_cat_id'])) {
                $sql_subcat_have_carddecks = "SELECT carddeck_id
                                              FROM carddeck
                                              WHERE carddeck_sub_cat = '".$subcategory_id."'
                                              LIMIT 1";
                $result_subcat_have_carddecks = mysqli_query($link, $sql_subcat_have_carddecks) OR die(mysqli_error($link));
                if (!mysqli_num_rows($result_subcat_have_carddecks)) {
                    $can_delete_subcategory = false;
                    // delete subcategory
                    mysqli_query($link, "DELETE FROM carddeck_sub_cat
                                         WHERE carddeck_sub_cat_id = '".$subcategory_id."'")
                    OR die(mysqli_error($link));
                }

                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_subcategory_deleted'], 'success');
            } else {
                ?>
                <div class="row">
                    <div class="col col-12 mt-2">
                        <?php alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_irreversible'] . ' ' . TRANSLATIONS[$GLOBALS['language']]['general']['hint_delete_all_data'], 'warning'); ?>
                    </div>
                    <div class="col col-12 mt-2">
                        <form action="<?php echo HOST_URL; ?>/administration/deletesubcategory/<?php echo $subcategory_id; ?>" method="post">
                            <div class="row">
                                <div class="form-group col col-12 mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text"
                                              id="ariaDescribedbyName">Name</span>
                                        <input type="text" disabled class="form-control"
                                               aria-describedby="ariaDescribedbyName" required
                                               value="<?php echo $row['carddeck_sub_cat_name']; ?>"/>
                                        <input type="hidden" class="form-control" id="carddeck_sub_cat_id" name="carddeck_sub_cat_id"
                                               value="<?php echo $subcategory_id; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group col col-12 mb-2">
                                    <button type="submit"
                                            class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_delete_subcategory']; ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_data'], 'danger');
        }
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>