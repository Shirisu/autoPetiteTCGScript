<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 3)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/addcarddeck' => TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_add_headline'],
    );
    breadcrumb($breadcrumb);

    title(TRANSLATIONS[$GLOBALS['language']]['admin']['carddeck_add_headline']);

    if (isset($_POST['carddeck_name'])) {
        $carddeck_name = mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_name'])));
        $carddeck_series = mysqli_real_escape_string($link, strip_tags(trim($_POST['carddeck_series'])));
        $carddeck_creator = mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_creator'])));
        $carddeck_category = explode(';', mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_category'])))); // split
        $carddeck_cat = $carddeck_category[0];
        $carddeck_sub_cat = $carddeck_category[1];
        $carddeck_is_puzzle = mysqli_real_escape_string($link, trim(strtolower($_POST['carddeck_is_puzzle'])));
        $carddeck_artist = mysqli_real_escape_string($link, strip_tags(trim($_POST['carddeck_artist'])));
        $carddeck_copyright = mysqli_real_escape_string($link, strip_tags(trim(strtolower($_POST['carddeck_copyright']))));
        $carddeck_imagesources = mysqli_real_escape_string($link, strip_tags(trim(strtolower($_POST['carddeck_imagesources']))));

        $sql = "SELECT carddeck_name
                FROM carddeck
                WHERE carddeck_name = '".$carddeck_name."'
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result)) {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_carddeck_name_exists'], 'danger');
        } else {
            $card_folder = '.'.TCG_CARDS_FOLDER;
            if (!is_dir($card_folder."/".$carddeck_name."")) {
                mkdir($card_folder."/".$carddeck_name."", 0755);
            }
            if (!empty($_FILES['masterCardFile']['name'])) {
                $file = $_FILES['masterCardFile'];
                move_uploaded_file($file['tmp_name'], $card_folder."/".$carddeck_name."/".$carddeck_name."master.".TCG_CARDS_FILE_TYPE);
                chmod($card_folder."/".$carddeck_name."/".$carddeck_name."master.".TCG_CARDS_FILE_TYPE, 0665);
            }
            for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS ; $i++) {
                $file = $_FILES['cardFile' . $i];
                if (!empty($file['name'])) {
                    move_uploaded_file($file['tmp_name'], $card_folder . "/" . $carddeck_name . "/" . $carddeck_name . sprintf("%'.02d", $i) . "." . TCG_CARDS_FILE_TYPE);
                    chmod($card_folder . "/" . $carddeck_name . "/" . $carddeck_name . sprintf("%'.02d", $i) . "." . TCG_CARDS_FILE_TYPE, 0665);
                }
            }
            mysqli_query($link, "
                INSERT INTO carddeck
                (carddeck_name, carddeck_creator, carddeck_series, carddeck_copyright,
                carddeck_artist, carddeck_imagesources, carddeck_cat, carddeck_sub_cat,
                carddeck_is_puzzle, carddeck_date)
                VALUES
                ('".$carddeck_name."', '".$carddeck_creator."', '".$carddeck_series."', '".$carddeck_copyright."',
                 '".$carddeck_artist."', '".$carddeck_imagesources."', '".$carddeck_cat."', '".$carddeck_sub_cat."',
                 '".$carddeck_is_puzzle."', '".time()."')
                ")
            OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_carddeck_add'], 'success');
        }
    }

    $sql_member = "SELECT member_id, member_nick
                   FROM member
                   WHERE member_active = 1
                   ORDER BY member_nick ASC";
    $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));

    $sql_category = "SELECT carddeck_cat_id, carddeck_cat_name
                     FROM carddeck_cat
                     ORDER BY carddeck_cat_name ASC";
    $result_category = mysqli_query($link, $sql_category) OR die(mysqli_error($link));
    ?>
    <form action="/administration/addcarddeck" method="post" enctype="multipart/form-data">
        <div class="row align-items-center">
            <div class="form-group col col-12 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyName">Name (<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_abbreviation']; ?>)</span>
                    </div>
                    <input type="text" class="form-control" id="carddeck_name" name="carddeck_name" aria-describedby="ariaDescribedbyName" pattern="[a-z0-9-_]*" maxlength="50" value="" required />
                </div>
                <small id="ariaDescribedbyName" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_only_small_letter_numbers_minus_and_underscore']; ?></small>
            </div>
            <div class="form-group col col-12 col-md-6 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbySeries"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_series']; ?></span>
                    </div>
                    <input type="text" class="form-control" id="carddeck_series" name="carddeck_series" aria-describedby="ariaDescribedbySeries" maxlength="255" value="" required />
                </div>
            </div>
            <div class="form-group col col-12 col-md-6 mb-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyIsPuzzle"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_is_puzzle']; ?></span>
                    </div>
                    <select class="custom-select" id="carddeck_is_puzzle" name="carddeck_is_puzzle" aria-describedby="ariaDescribedbyIsPuzzle" required>
                        <option selected disabled hidden value=""></option>
                        <option value="0"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_no']; ?></option>
                        <option value="1"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_yes']; ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group col col-12 col-md-6 mb-3">
                <?php
                if (mysqli_num_rows($result_member)) {
                    ?>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyCreator"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_creator']; ?></span>
                        </div>
                        <select class="custom-select" id="carddeck_creator" name="carddeck_creator" aria-describedby="ariaDescribedbyCreator" required>
                            <option selected disabled hidden value=""></option>
                            <?php
                            while ($row_member = mysqli_fetch_assoc($result_member)) {
                                ?>
                                <option value="<?php echo $row_member['member_id']; ?>"><?php echo $row_member['member_nick']; ?></option>
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
            <div class="form-group col col-12 col-md-6 mb-3">
                <?php
                if (mysqli_num_rows($result_category)) {
                    ?>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyCategory"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_category']; ?></span>
                        </div>
                        <select class="custom-select" id="carddeck_category" name="carddeck_category" aria-describedby="ariaDescribedbyCategory" required>
                            <option selected disabled hidden value=""></option>
                            <?php
                            while ($row_category = mysqli_fetch_assoc($result_category)) {
                                $sql_sub_category = "SELECT carddeck_sub_cat_id, carddeck_sub_cat_name
                                                     FROM carddeck_sub_cat
                                                     WHERE carddeck_sub_cat_main_cat_id = '".$row_category['carddeck_cat_id']."'
                                                     ORDER BY carddeck_sub_cat_name ASC";
                                $result_sub_category = mysqli_query($link, $sql_sub_category) OR die(mysqli_error($link));
                                ?>
                                <optgroup label="<?php echo $row_category['carddeck_cat_name']; ?>"></optgroup>
                                <?php
                                while ($row_sub_category = mysqli_fetch_assoc($result_sub_category)) {
                                    ?>
                                    <option value="<?php echo $row_category['carddeck_cat_id'].';'.$row_sub_category['carddeck_sub_cat_id']; ?>"><?php echo $row_sub_category['carddeck_sub_cat_name']; ?></option>
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
                                <span class="input-group-text" id="ariaDescribedbyArtist"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_artist']; ?></span>
                            </div>
                            <input type="text" class="form-control" id="carddeck_artist" name="carddeck_artist" aria-describedby="ariaDescribedbyArtist" maxlength="255" value="" />
                        </div>
                        <small id="ariaDescribedbyArtist" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_artist']; ?></small>
                    </div>
                    <div class="form-group col col-12 col-md-6 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyCopyright"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_copyright']; ?></span>
                            </div>
                            <input type="text" class="form-control" id="carddeck_copyright" name="carddeck_copyright" aria-describedby="ariaDescribedbyCopyright" maxlength="255" value="" placeholder="<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_link_without_beginning']; ?>" />
                        </div>
                        <small id="ariaDescribedbyCopyright" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_copyright']; ?></small>
                    </div>
                    <div class="form-group col col-12 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ariaDescribedbyImagesources"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_imagesources']; ?></span>
                            </div>
                            <textarea class="form-control" id="carddeck_imagesources" name="carddeck_imagesources" aria-describedby="ariaDescribedbyImagesources" maxlength="255"></textarea>
                        </div>
                        <small id="ariaDescribedbyImagesources" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_imagesources']; ?></small>
                    </div>
                </div>
            </details>
            <div class="form-group col col-12 mb-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="ariaDescribedbyMastercard">Mastercard</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="masterCardFile" name="masterCardFile" aria-describedby="ariaDescribedbyMastercard" required>
                        <label class="custom-file-label" for="masterCardFile"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_choose_file']; ?></label>
                    </div>
                </div>
            </div>
            <?php
            for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS ; $i++) {
                ?>
                <div class="form-group col col-12 col-md-6 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyCard<?php echo $i; ?>">Card <?php echo $i; ?></span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="cardFile<?php echo $i; ?>" name="cardFile<?php echo $i; ?>" aria-describedby="ariaDescribedbyCard<?php echo $i; ?>" required>
                            <label class="custom-file-label" for="cardFile<?php echo $i; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_choose_file']; ?></label>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="form-group col col-12 mb-2">
                <small id="ariaDescribedbyMastercard" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['hint_files_rename_after_upload']; ?></small>
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