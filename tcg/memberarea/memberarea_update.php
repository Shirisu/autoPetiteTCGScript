<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/memberarea' => TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'],
        '/memberarea/update' => TRANSLATIONS[$GLOBALS['language']]['general']['text_cardupdate'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['general']['text_cardupdate']);

    $sql_cardupdate = "SELECT cardupdate_id, cardupdate_carddeck_id, cardupdate_count_cards
                       FROM cardupdate
                       ORDER BY cardupdate_id DESC
                       LIMIT 1";
    $result_cardupdate = mysqli_query($link, $sql_cardupdate) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_cardupdate)) {
        $row_cardupdate = mysqli_fetch_assoc($result_cardupdate);
        $updatedecks = $row_cardupdate['cardupdate_carddeck_id'];
        $updatecarddecks_array = explode(';', $updatedecks);
        $count_decks = sizeof($updatecarddecks_array);
        $count_decks_update = $count_decks;
        $quantity = $row_cardupdate['cardupdate_count_cards'];

        $sql_member_cardupdate = "SELECT member_update_carddeck_id, member_update_cards_count
                                  FROM member_update
                                  WHERE member_update_member_id = '".$_SESSION['member_id']."'
                                     AND member_update_cardupdate_id = '".$row_cardupdate['cardupdate_id']."'
                                  LIMIT 1";
        $result_member_cardupdate = mysqli_query($link, $sql_member_cardupdate) OR die(mysqli_error($link));
        $count_member_cardupdate = mysqli_num_rows($result_member_cardupdate);
        if ($count_member_cardupdate) {
            $row_member_cardupdate = mysqli_fetch_assoc($result_member_cardupdate);
            $updatedecks = $row_member_cardupdate['member_update_carddeck_id'];
            $updatecarddecks_array = explode(';', $updatedecks);
            $count_decks = sizeof($updatecarddecks_array);

            $quantity = $row_member_cardupdate['member_update_cards_count'];
        }
        $updatecarddecks_array = explode(';', $updatedecks);

        if (isset($_POST['carddeck_id']) && $quantity > 0) {
            $carddeck_id = mysqli_real_escape_string($link, trim($_POST['carddeck_id']));
            $card_number = mt_rand(1, TCG_CARDDECK_MAX_CARDS);

            if (in_array($carddeck_id, $updatecarddecks_array)) {
                $pos = array_search($carddeck_id, $updatecarddecks_array);
                unset($updatecarddecks_array[$pos]);

                $updatecarddecks_array = array_values(array_filter($updatecarddecks_array));
                $updatecarddecks_array_new = implode(";",$updatecarddecks_array);

            }

            $count_decks = sizeof($updatecarddecks_array);

            $quantity = $quantity - 1;

            $sql_carddeck = "SELECT carddeck_name
                             FROM carddeck
                             WHERE carddeck_id = '" . $carddeck_id . "'
                             LIMIT 1";
            $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_carddeck)) {
                $row_carddeck = mysqli_fetch_assoc($result_carddeck);

                insert_specific_cards($_SESSION['member_id'], $carddeck_id, $card_number);
                $inserted_card_text = TRANSLATIONS[$GLOBALS['language']]['cardupdate']['text_you_got'] . ': ' . $row_carddeck['carddeck_name'] . sprintf('%02d', $card_number);
                insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_cardupdate'].' #'.sprintf('%03d', $row_cardupdate['cardupdate_id']), $inserted_card_text, $_SESSION['member_id']);
                $query = "INSERT INTO member_update
                          (member_update_cardupdate_id,member_update_carddeck_id,member_update_member_id,member_update_cards_count)
                          VALUES
                          ('".$row_cardupdate['cardupdate_id']."', '".$updatecarddecks_array_new."','".$_SESSION['member_id']."','".$quantity."')
                          ON DUPLICATE KEY UPDATE
                          member_update_carddeck_id = '".$updatecarddecks_array_new."',
                          member_update_cards_count = '".$quantity."'
                          ;";
                mysqli_query($link, $query) or die(mysqli_error($link));
            }
        }

        ?>
        <div class="row update-container">
            <?php
            if ($quantity > 0) {
                ?>
                <div class="col col-12 mb-4 text-left text-md-center">
                    <span
                        class="font-weight-bold"><?php echo $count_decks_update . ' ' . TRANSLATIONS[$GLOBALS['language']]['cardupdate']['text_new_carddecks']; ?></span>
                    - <?php echo TRANSLATIONS[$GLOBALS['language']]['cardupdate']['text_get_cards_part_1'] . ' ' . $quantity . ' ' . ($quantity == 1 ? TRANSLATIONS[$GLOBALS['language']]['cardupdate']['text_get_cards_part_2_single'] : TRANSLATIONS[$GLOBALS['language']]['cardupdate']['text_get_cards_part_2']); ?>
                </div>
                <div class="col col-12 mb-2 text-left text-md-center">
                    <div class="row">
                        <?php
                        for ($i = 0; $i < $count_decks; $i++) {
                            $sql_carddeck = "SELECT carddeck_id, carddeck_name, carddeck_series
                                             FROM carddeck
                                             WHERE carddeck_id = '" . $updatecarddecks_array[$i] . "'
                                             LIMIT 1";
                            $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
                            if (mysqli_num_rows($result_carddeck)) {
                                $row_carddeck = mysqli_fetch_assoc($result_carddeck);
                                ?>
                                <div class="col col-12 col-md-6 mb-2 text-center">
                                    <div class="card">
                                        <h5 class="card-header"><?php echo '[' . strtoupper($row_carddeck['carddeck_name']) . ']<br />' . $row_carddeck['carddeck_series']; ?></h5>
                                        <div class="card-body">
                                            <?php for ($j = 1; $j <= TCG_CARDDECK_MAX_CARDS; $j++) { ?><?php echo get_card($row_carddeck['carddeck_id'], $j); ?><?php } ?>
                                        </div>
                                        <div class="card-footer">
                                            <form action="<?php echo HOST_URL; ?>/memberarea/update" method="post">
                                                <input type="hidden" class="form-control" id="carddeck_id"
                                                       name="carddeck_id"
                                                       value="<?php echo $row_carddeck['carddeck_id']; ?>"/>
                                                <button
                                                    class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['cardupdate']['text_take_card']; ?></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="col col-12">
                    <?php
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['cardupdate']['hint_already_taken'], 'danger');
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_update_yet'], 'danger');
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>