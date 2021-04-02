<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 3)) {
    global $link;

    if (isset($carddeck_id)) {
        $sql = "SELECT *
                FROM carddeck, carddeck_cat, carddeck_sub_cat
                WHERE carddeck_id = '".$carddeck_id."'
                  AND carddeck_cat = carddeck_cat_id
                  AND carddeck_sub_cat = carddeck_sub_cat_id
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            $remove_host = ['https://', 'https//', 'http://', 'http//', '://', '//'];

            $row = mysqli_fetch_assoc($result);

            $carddeck_name = $row['carddeck_name'];
            $carddeck_series = $row['carddeck_series'];
            $carddeck_creator = $row['carddeck_creator'];
            $carddeck_category = $row['carddeck_cat'] . ';' . $row['carddeck_sub_cat'];
            $carddeck_is_puzzle = $row['carddeck_is_puzzle'];
            $carddeck_active = $row['carddeck_active'];
            $carddeck_artist = $row['carddeck_artist'];
            $carddeck_copyright = str_replace($remove_host, '', $row['carddeck_copyright']);
            $carddeck_imagesources = str_replace($remove_host, '', $row['carddeck_imagesources']);
            if (isset($_POST['carddeck_name']) && isset($_POST['carddeck_series'])) {
                $carddeck_name = mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_name'])));
                $carddeck_series = mysqli_real_escape_string($link, strip_tags(trim($_POST['carddeck_series'])));
                $carddeck_creator = mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_creator'])));
                $carddeck_category = mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_category'])));
                $carddeck_category_split = explode(';', mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_category'])))); // split
                $carddeck_cat = $carddeck_category_split[0];
                $carddeck_sub_cat = $carddeck_category_split[1];
                $carddeck_is_puzzle = mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_is_puzzle'])));
                $carddeck_active = mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_active'])));
                $carddeck_artist = mysqli_real_escape_string($link, strip_tags(trim($_POST['carddeck_artist'])));
                $carddeck_copyright = mysqli_real_escape_string($link, strip_tags(trim(strtolower($_POST['carddeck_copyright']))));
                $carddeck_copyright = str_replace($remove_host, '', $carddeck_copyright);
                $carddeck_imagesources = str_replace($remove_host, '', mysqli_real_escape_string($link, strip_tags(trim(strtolower($_POST['carddeck_imagesources'])))));

                $card_folder = '.' . TCG_CARDS_FOLDER;
                if (!is_dir($card_folder . "/" . $carddeck_name . "")) {
                    mkdir($card_folder . "/" . $carddeck_name . "", 0755);
                }
                if (!empty($_FILES['masterCardFile']['name'])) {
                    $file = $_FILES['masterCardFile'];
                    move_uploaded_file($file['tmp_name'], $card_folder . "/" . $carddeck_name . "/" . $carddeck_name . "master." . TCG_CARDS_FILE_TYPE);
                    chmod($card_folder . "/" . $carddeck_name . "/" . $carddeck_name . "master." . TCG_CARDS_FILE_TYPE, 0665);
                }
                for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS; $i++) {
                    $file = $_FILES['cardFile' . $i];
                    if (!empty($file['name'])) {
                        move_uploaded_file($file['tmp_name'], $card_folder . "/" . $carddeck_name . "/" . $carddeck_name . sprintf("%'.02d", $i) . "." . TCG_CARDS_FILE_TYPE);
                        chmod($card_folder . "/" . $carddeck_name . "/" . $carddeck_name . sprintf("%'.02d", $i) . "." . TCG_CARDS_FILE_TYPE, 0665);
                    }
                }

                mysqli_query($link, "UPDATE carddeck
                    SET carddeck_creator = '" . $carddeck_creator . "',
                        carddeck_series = '" . $carddeck_series . "',
                        carddeck_copyright = '" . $carddeck_copyright . "',
                        carddeck_artist = '" . $carddeck_artist . "',
                        carddeck_imagesources = '" . $carddeck_imagesources . "',
                        carddeck_cat = '" . $carddeck_cat . "',
                        carddeck_sub_cat = '" . $carddeck_sub_cat . "',
                        carddeck_is_puzzle = '" . $carddeck_is_puzzle . "',
                        carddeck_active = '" . $carddeck_active . "'
                    WHERE carddeck_id = " . $carddeck_id . "
                    LIMIT 1")
                OR die(mysqli_error($link));

                alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_carddeck_edit'], 'success');
            }

            $breadcrumb = array(
                '/' => 'Home',
                '/administration' => 'Administration',
                '/administration/editcarddeck' => TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_administration_headline'] . ' - ' . TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_edit_headline'],
                '/administration/editcarddeck/' . $carddeck_id => $carddeck_name,
            );
            breadcrumb($breadcrumb);

            title(TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_edit_headline']);

            $sql_member = "SELECT member_id, member_nick
                           FROM member
                           WHERE member_active != 3
                           ORDER BY member_nick ASC";
            $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));

            $sql_category = "SELECT carddeck_cat_id, carddeck_cat_name
                             FROM carddeck_cat
                             ORDER BY carddeck_cat_name ASC";
            $result_category = mysqli_query($link, $sql_category) OR die(mysqli_error($link));
            ?>
            <form action="<?php echo HOST_URL; ?>/administration/editcarddeck/<?php echo $carddeck_id; ?>" method="post"
                  enctype="multipart/form-data">
                <div class="row align-items-center">
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyID">ID</span>
                            </div>
                            <input type="text" disabled class="form-control" aria-describedby="ariaDescribedbyID"
                                   required value="<?php echo $row['carddeck_id']; ?>"/>
                        </div>
                        <input type="hidden" class="form-control" id="carddeck_id" name="carddeck_id"
                               value="<?php echo $row['carddeck_id']; ?>"/>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                      id="ariaDescribedbyName">Name (<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_abbreviation']; ?>
                                    )</span>
                            </div>
                            <input type="text" disabled class="form-control" id="carddeck_name" name="carddeck_name"
                                   aria-describedby="ariaDescribedbyName" pattern="[a-z0-9-_]*" maxlength="50"
                                   value="<?php echo $carddeck_name; ?>" required/>
                        </div>
                        <input type="hidden" class="form-control" id="carddeck_name" name="carddeck_name"
                               value="<?php echo $row['carddeck_name']; ?>"/>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                      id="ariaDescribedbySeries"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_series']; ?></span>
                            </div>
                            <input type="text" class="form-control" id="carddeck_series" name="carddeck_series"
                                   aria-describedby="ariaDescribedbySeries" maxlength="255"
                                   value="<?php echo $carddeck_series; ?>" required/>
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                      id="ariaDescribedbyIsPuzzle"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_is_puzzle']; ?></span>
                            </div>
                            <select class="custom-select" id="carddeck_is_puzzle" name="carddeck_is_puzzle"
                                    aria-describedby="ariaDescribedbyIsPuzzle" required>
                                <option selected disabled hidden value=""></option>
                                <option
                                    value="0" <?php echo($carddeck_is_puzzle == 0 ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_no']; ?></option>
                                <option
                                    value="1" <?php echo($carddeck_is_puzzle == 1 ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_yes']; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <?php
                        if (mysqli_num_rows($result_member)) {
                            ?>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"
                                          id="ariaDescribedbyCreator"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_creator']; ?></span>
                                </div>
                                <select class="custom-select" id="carddeck_creator" name="carddeck_creator"
                                        aria-describedby="ariaDescribedbyCreator" required>
                                    <option selected disabled hidden value=""></option>
                                    <?php
                                    while ($row_member = mysqli_fetch_assoc($result_member)) {
                                        ?>
                                        <option
                                            value="<?php echo $row_member['member_id']; ?>" <?php echo($carddeck_creator == $row_member['member_id'] ? 'selected' : ''); ?>><?php echo $row_member['member_nick']; ?></option>
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
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                      id="ariaDescribedbyStatus"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?></span>
                            </div>
                            <select class="custom-select" id="carddeck_active" name="carddeck_active"
                                    aria-describedby="ariaDescribedbyStatus" required>
                                <option selected disabled hidden value=""></option>
                                <option
                                    value="0" <?php echo($carddeck_active == 0 ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive']; ?></option>
                                <option
                                    value="1" <?php echo($carddeck_active == 1 ? 'selected' : ''); ?>><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_active']; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-3">
                        <?php
                        if (mysqli_num_rows($result_category)) {
                            ?>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"
                                          id="ariaDescribedbyCategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category']; ?></span>
                                </div>
                                <select class="custom-select" id="carddeck_category" name="carddeck_category"
                                        aria-describedby="ariaDescribedbyCategory" required>
                                    <option selected disabled hidden value=""></option>
                                    <?php
                                    while ($row_category = mysqli_fetch_assoc($result_category)) {
                                        $sql_sub_category = "SELECT carddeck_sub_cat_id, carddeck_sub_cat_name
                                                             FROM carddeck_sub_cat
                                                             WHERE carddeck_sub_cat_main_cat_id = '" . $row_category['carddeck_cat_id'] . "'
                                                             ORDER BY carddeck_sub_cat_name ASC";
                                        $result_sub_category = mysqli_query($link, $sql_sub_category) OR die(mysqli_error($link));
                                        ?>
                                        <optgroup label="<?php echo $row_category['carddeck_cat_name']; ?>"></optgroup>
                                        <?php
                                        while ($row_sub_category = mysqli_fetch_assoc($result_sub_category)) {
                                            ?>
                                            <option
                                                value="<?php echo $row_category['carddeck_cat_id'] . ';' . $row_sub_category['carddeck_sub_cat_id']; ?>" <?php echo($carddeck_category == $row_category['carddeck_cat_id'] . ';' . $row_sub_category['carddeck_sub_cat_id'] ? 'selected' : ''); ?>><?php echo $row_sub_category['carddeck_sub_cat_name']; ?></option>
                                            <?php
                                        }
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
                    <details class="col col-12 mb-2">
                        <summary><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_optional_information']; ?></summary>
                        <div class="row">
                            <div class="form-group col col-12 col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                              id="ariaDescribedbyArtist"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_artist']; ?></span>
                                    </div>
                                    <input type="text" class="form-control" id="carddeck_artist" name="carddeck_artist"
                                           aria-describedby="ariaDescribedbyArtist" maxlength="255"
                                           value="<?php echo $carddeck_artist; ?>"/>
                                </div>
                                <small id="ariaDescribedbyArtist"
                                       class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_artist']; ?></small>
                            </div>
                            <div class="form-group col col-12 col-md-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                              id="ariaDescribedbyCopyright"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_copyright']; ?></span>
                                    </div>
                                    <input type="text" class="form-control" id="carddeck_copyright"
                                           name="carddeck_copyright" aria-describedby="ariaDescribedbyCopyright"
                                           maxlength="255" value="<?php echo $carddeck_copyright; ?>"
                                           placeholder="<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_link_without_beginning']; ?>"/>
                                </div>
                                <small id="ariaDescribedbyCopyright"
                                       class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_copyright']; ?></small>
                            </div>
                            <div class="form-group col col-12 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                              id="ariaDescribedbyImagesources"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_imagesources']; ?></span>
                                    </div>
                                    <textarea class="form-control" id="carddeck_imagesources"
                                              name="carddeck_imagesources"
                                              aria-describedby="ariaDescribedbyImagesources"><?php echo $carddeck_imagesources; ?></textarea>
                                </div>
                                <small id="ariaDescribedbyImagesources"
                                       class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_imagesources']; ?></small>
                            </div>
                        </div>
                    </details>

                    <details class="col col-12 mb-2">
                        <summary><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_only_if_should_be_changed']; ?></summary>
                        <div class="row">
                            <div class="form-group col col-12 mb-2">
                                <div class="mb-1">
                                    <?php echo get_card($row['carddeck_id'], 'master', false, true); ?>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="ariaDescribedbyMastercard">Mastercard</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="masterCardFile"
                                               name="masterCardFile" aria-describedby="ariaDescribedbyMastercard">
                                        <label class="custom-file-label"
                                               for="masterCardFile"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_choose_file']; ?></label>
                                    </div>
                                </div>
                            </div>
                            <?php
                            for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS; $i++) {
                                ?>
                                <div class="form-group col col-12 col-md-6 mb-2">
                                    <div class="mb-1">
                                        <?php echo get_card($row['carddeck_id'], $i, false, true); ?>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="ariaDescribedbyCard<?php echo $i; ?>">Card <?php echo $i; ?></span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="cardFile<?php echo $i; ?>"
                                                   name="cardFile<?php echo $i; ?>"
                                                   aria-describedby="ariaDescribedbyCard<?php echo $i; ?>">
                                            <label class="custom-file-label"
                                                   for="cardFile<?php echo $i; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_choose_file']; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </details>

                    <div class="form-group col col-12">
                        <button type="submit"
                                class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_save']; ?></button>
                    </div>
                </div>
            </form>
            <?php
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/administration' => 'Administration',
                '/administration/editcarddeck' => TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_edit_headline'],
            );
            breadcrumb($breadcrumb);

            title(TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_edit_headline']);
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_valid_id'], 'danger');
        }
    } else {
        $breadcrumb = array(
            '/' => 'Home',
            '/administration' => 'Administration',
            '/administration/editcarddeck' => TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_edit_headline'],
        );
        breadcrumb($breadcrumb);

        title(TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_edit_headline']);

        $sql = "SELECT carddeck_id, carddeck_name, carddeck_active, carddeck_cat_name, carddeck_sub_cat_name
                FROM carddeck
                JOIN carddeck_cat ON carddeck_cat = carddeck_cat_id
                JOIN carddeck_sub_cat ON carddeck_sub_cat = carddeck_sub_cat_id
                ORDER BY carddeck_name";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $count = mysqli_num_rows($result);
        if ($count) {
            ?>
            <div class="row">
                <div class="col">
                    <table id="admin-member-edit-table" data-mobile-responsive="true">
                        <thead>
                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="name">Name (<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_abbreviation']; ?>)</th>
                            <th data-field="category"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category']; ?></th>
                            <th data-field="status"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?></th>
                            <th data-field="options"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['carddeck_id']; ?></td>
                                <td><?php echo $row['carddeck_name']; ?></td>
                                <td><?php echo $row['carddeck_cat_name']; ?> <i class="fas fa-angle-right"></i> <?php echo $row['carddeck_sub_cat_name']; ?></td>
                                <td><?php echo get_active_status($row['carddeck_active']); ?></td>
                                <td><a href="<?php echo HOST_URL; ?>/administration/editcarddeck/<?php echo $row['carddeck_id']; ?>">Edit</a></td>
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
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_data'], 'danger');
        }
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>