<?php
if (isset($_SESSION['member_rank']) && ($_SESSION['member_rank'] == 1 || $_SESSION['member_rank'] == 2 || $_SESSION['member_rank'] == 3)) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/administration' => 'Administration',
        '/administration/addcardupdate' => TRANSLATIONS[$GLOBALS['language']]['admin']['cardupdate_administration_headline'].' - '.TRANSLATIONS[$GLOBALS['language']]['admin']['cardupdate_add_headline'],
    );
    breadcrumb($breadcrumb);

    title(TRANSLATIONS[$GLOBALS['language']]['admin']['cardupdate_add_headline']);

    if (isset($_POST['carddecks'])&& isset($_POST['cardupdate_id']) && isset($_POST['quantity']) && isset($_POST['news_text'])) {
        $carddecks = mysqli_real_escape_string($link, strip_tags(trim($_POST['carddecks'])));
        $cardupdate_id = mysqli_real_escape_string($link, strip_tags(trim($_POST['cardupdate_id'])));
        $quantity = mysqli_real_escape_string($link, strip_tags(trim($_POST['quantity'])));
        $news_title = mysqli_real_escape_string($link, strip_tags(trim($_POST['news_title'])));
        $news_text = mysqli_real_escape_string($link, strip_tags(trim($_POST['news_text'])));

        $sql_check_before_insert = "SELECT cardupdate_id
                                    FROM cardupdate
                                    WHERE cardupdate_id = '".$cardupdate_id."'
                                    ORDER BY cardupdate_id DESC
                                    LIMIT 1";
        $result_check_before_insert = mysqli_query($link, $sql_check_before_insert);
        if (!mysqli_num_rows($result_check_before_insert)) {
            $carddecks_array = explode(';', $carddecks);

            for ($i = 0; $i < sizeof($carddecks_array); $i++) {
                mysqli_query($link, "UPDATE carddeck
                         SET carddeck_active = '1'
                         WHERE carddeck_id = " . $carddecks_array[$i] . "
                         LIMIT 1")
                OR die(mysqli_error($link));
            }

            mysqli_query($link, "
            INSERT INTO cardupdate
            (cardupdate_id, cardupdate_date, cardupdate_carddeck_id, cardupdate_count_cards)
            VALUES
            ('" . $cardupdate_id . "', '" . time() . "', '" . $carddecks . "', '" . $quantity . "')")
            OR die(mysqli_error($link));

            mysqli_query($link, "
            INSERT INTO news
            (news_member_id, news_title, news_text, news_date, news_cardupdate_id)
            VALUES
            ('" . $_SESSION['member_id'] . "', '" . $news_title . "', '" . $news_text . "', '" . time() . "', '" . $cardupdate_id . "')")
            OR die(mysqli_error($link));

            alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_success_cardupdate_add'], 'success');
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_duplicate_entry'], 'danger');
        }
    }

    if (isset($_POST['updatedecks'])) {
        $updatedecks = $_POST['updatedecks'];
        $count_decks = sizeof($updatedecks);
        $updatecarddecks_array = array();
        for ($i = 0; $i < $count_decks; $i++) {
            array_push($updatecarddecks_array, $updatedecks[$i]);
        }
        $updatecarddecks = implode(';', $updatecarddecks_array);
        $quantity_cards = round($count_decks/2);
    } else {
        $updatecarddecks_array = array();
        $updatecarddecks = '';
        $quantity_cards = 0;
    }

    $sql = "SELECT carddeck_id, carddeck_name, carddeck_series
            FROM carddeck
            WHERE carddeck_active = 0
            ORDER BY carddeck_id ASC;";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    $count = mysqli_num_rows($result);

    if ($count) {
        title_small($count.' '.TRANSLATIONS[$GLOBALS['language']]['admin']['cardupdate_decks_headline']);
        ?>
        <div class="row mb-4">
            <div class="col">
                <form action="<?php echo HOST_URL; ?>/administration/addcardupdate" method="post">
                    <table id="admin-member-edit-table" data-mobile-responsive="true" data-paging="no">
                        <thead>
                            <tr>
                                <th data-field="options"></th>
                                <th data-field="id">ID</th>
                                <th data-field="preview"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_preview']; ?></th>
                                <th data-field="name">Name (<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_abbreviation']; ?>)</th>
                                <th data-field="series"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_series']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="updatedecks[]" id="updatedeck<?php echo $row['carddeck_id']; ?>" value="<?php echo $row['carddeck_id']; ?>" <?php echo (in_array($row['carddeck_id'], $updatecarddecks_array) ? 'checked' : ''); ?> />
                                        <label class="custom-control-label" for="updatedeck<?php echo $row['carddeck_id']; ?>"></label>
                                    </div>
                                </td>
                                <td><?php echo $row['carddeck_id']; ?></td>
                                <td><?php echo show_card($row['carddeck_id'], 'master', false, true); ?></td>
                                <td><?php echo $row['carddeck_name']; ?></td>
                                <td><?php echo $row['carddeck_series']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary mt-2"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_add_to_cardupdate']; ?></button>
                </form>
            </div>
        </div>
        <?php
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['admin']['hint_no_carddeck_for_update_yet'], 'danger');
    }

    if (isset($_POST['updatedecks'])) {
        title_small('Karten f&uuml;r Update');

        $sql_cardupdate = "SELECT cardupdate_id
                           FROM cardupdate";
        $result_cardupdate = mysqli_query($link, $sql_cardupdate) OR die(mysqli_error($link));
        $count_cardupdate = mysqli_num_rows($result_cardupdate);
        $cardupdate_id = ($count_cardupdate + 1);

        $updatedecks = $_POST['updatedecks'];
        $count_decks = sizeof($updatedecks);
        ?>
        <form action="<?php echo HOST_URL; ?>/administration/addcardupdate" method="post">
            <div class="row align-items-center">
                <div class="form-group col col-12 col-md-6 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyCarddecks"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_carddecks_for_update']; ?></span>
                        </div>
                        <input type="text" disabled class="form-control" id="carddecks_preview" name="carddecks_preview" aria-describedby="ariaDescribedbyCarddecks" maxlength="255" value="<?php echo $updatecarddecks; ?>" />
                        <input type="hidden" class="form-control" id="carddecks" name="carddecks" value="<?php echo $updatecarddecks; ?>" />
                        <input type="hidden" class="form-control" id="cardupdate_id" name="cardupdate_id" value="<?php echo $cardupdate_id; ?>" />
                    </div>
                </div>
                <div class="form-group col col-12 col-md-6 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyQuantity"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_carddecks_quantity_for_update']; ?></span>
                        </div>
                        <input type="number" class="form-control" aria-describedby="ariaDescribedbyWish" id="quantity" name="quantity" min="1" max="<?php echo $count_decks; ?>" value="<?php echo $quantity_cards; ?>" required />
                    </div>
                </div>
                <div class="form-group col col-12 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyTitle"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_title']; ?></span>
                        </div>
                        <input type="text" class="form-control" id="news_title" name="news_title" aria-describedby="ariaDescribedbyTitle" maxlength="55" value="" required />
                    </div>
                </div>
                <div class="form-group col col-12 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="ariaDescribedbyText">Text</span>
                        </div>
                        <textarea class="form-control" id="news_text" name="news_text" aria-describedby="ariaDescribedbyText" rows="10" required></textarea>
                    </div>
                </div>
                <div class="form-group col col-12">
                    <span class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_preview_for_cardupdate']; ?></span>
                    <span class="font-italic">(<?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_preview_for_cardupdate_subtext']; ?>)</span>
                    <p class="text-center mt-2">
                        <?php
                        for ($i = 0; $i < $count_decks; $i++) {
                            $sql_carddeck = "SELECT carddeck_id, carddeck_name
                                             FROM carddeck
                                             WHERE carddeck_id = '".$updatecarddecks_array[$i]."'
                                             LIMIT 1";
                            $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
                            if (mysqli_num_rows($result_carddeck)) {
                                $row_carddeck = mysqli_fetch_assoc($result_carddeck);
                                ?>
                                    <a href="<?php echo HOST_URL; ?>/carddeck/<?php echo $row_carddeck['carddeck_name']; ?>"><?php echo show_card($row_carddeck['carddeck_id'], 1, false, true); ?></a>
                                <?php
                            }
                        }
                        ?>
                    </p>
                </div>
                <div class="form-group col col-12">
                    <button type="submit" class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_add']; ?></button>
                    <small class="text-muted"><span class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_hint']; ?>:</span> <?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['hint_cardupdate_is_activated_immediately']; ?></small>
                </div>
            </div>
        </form>
        <?php
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>