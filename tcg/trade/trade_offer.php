<?php
if (isset($_SESSION['member_rank'])) {
    global $link;

    $member_id = $_SESSION['member_id'];

    if (isset($trade_member_id) && isset($card_id)) {
        $sql_trade_card = "SELECT member_cards_id, member_cards_carddeck_id, carddeck_name, member_cards_number
                           FROM member_cards
                           INNER JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                           WHERE member_cards_id = '" . $card_id . "'
                             AND member_cards_member_id = '".$trade_member_id."'
                             AND member_cards_cat = 3
                             AND member_cards_active = 1
                           LIMIT 1";
        $result_trade_card = mysqli_query($link, $sql_trade_card) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_trade_card)) {
            $row_trade_card = mysqli_fetch_assoc($result_trade_card);
            $trade_card_carddeck_id = $row_trade_card['member_cards_carddeck_id'];
            $trade_card_name = $row_trade_card['carddeck_name'];
            $trade_card_number = $row_trade_card['member_cards_number'];

            if (isset($_POST['trade_own_card']) && isset($_POST['trade_card_id']) && isset($_POST['trade_message'])) {
                $breadcrumb = array(
                    '/' => 'Home',
                    '/trade' => TRANSLATIONS[$GLOBALS['language']]['general']['text_trade'],
                    '/trade/' . $trade_member_id.'/'. $card_id => TRANSLATIONS[$GLOBALS['language']]['trade']['text_offer_sent'],
                );
                breadcrumb($breadcrumb);
                title(TRANSLATIONS[$GLOBALS['language']]['trade']['text_offer_sent']);

                $trade_own_card = mysqli_real_escape_string($link, trim($_POST['trade_own_card']));
                $trade_own_card_array = explode(';;', $trade_own_card);
                $trade_own_card_id = $trade_own_card_array[0];
                $trade_card_id = mysqli_real_escape_string($link, trim($_POST['trade_card_id']));
                $trade_message = mysqli_real_escape_string($link, trim($_POST['trade_message']));

                // check if card from trade member is available
                $sql_trade_card = "SELECT member_cards_carddeck_id, carddeck_name, member_cards_number
                                   FROM member_cards
                                   INNER JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                                   WHERE member_cards_id = '" . $trade_card_id . "'
                                     AND member_cards_member_id = '".$trade_member_id."'
                                     AND member_cards_cat = 3
                                     AND member_cards_active = 1
                                   LIMIT 1";
                $result_trade_card = mysqli_query($link, $sql_trade_card) OR die(mysqli_error($link));

                // check if own card is available
                $sql_own_card = "SELECT member_cards_carddeck_id, carddeck_name, member_cards_number
                                   FROM member_cards
                                   INNER JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                                   WHERE member_cards_id = '" . $trade_own_card_id . "'
                                     AND member_cards_member_id = '".$member_id."'
                                     AND member_cards_cat = 3
                                     AND member_cards_active = 1
                                   LIMIT 1";
                $result_own_card = mysqli_query($link, $sql_own_card) OR die(mysqli_error($link));

                if (mysqli_num_rows($result_trade_card) && mysqli_num_rows($result_own_card)) {
                    $row_trade_card = mysqli_fetch_assoc($result_trade_card);
                    $row_own_card = mysqli_fetch_assoc($result_own_card);
                    // set trade card inactive
                    mysqli_query($link, "UPDATE member_cards
                                         SET member_cards_active = 0
                                         WHERE member_cards_id = '".$trade_card_id."'
                                           AND member_cards_member_id = '".$trade_member_id."'
                                         LIMIT 1")
                    OR die(mysqli_error($link));

                    // set own card inactive
                    mysqli_query($link, "UPDATE member_cards
                                         SET member_cards_active = 0
                                         WHERE member_cards_id = '".$trade_own_card_id."'
                                           AND member_cards_member_id = '".$member_id."'
                                         LIMIT 1")
                    OR die(mysqli_error($link));

                    // insert trade in table
                    mysqli_query($link, "INSERT INTO trade
                                         (trade_from_member_id, trade_from_member_card_id, trade_to_member_id, trade_to_member_card_id, trade_text, trade_date)
                                         VALUES
                                         ('".$member_id."', '".$trade_own_card_id."', '".$trade_member_id."', '".$trade_card_id."', '".$trade_message."', '".time()."')")
                    OR die(mysqli_error($link));

                    alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_offer_sent'], 'success');
                } else {
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_offer_error'], 'danger');
                }

            } else {
                $breadcrumb = array(
                    '/' => 'Home',
                    '/trade' => TRANSLATIONS[$GLOBALS['language']]['general']['text_trade'],
                    '/trade/' . $trade_member_id.'/'. $card_id => TRANSLATIONS[$GLOBALS['language']]['trade']['text_create_offer'],
                );
                breadcrumb($breadcrumb);
                title(TRANSLATIONS[$GLOBALS['language']]['trade']['text_create_offer']);

                $sql_own_cards = "SELECT member_cards_id, member_cards_carddeck_id, carddeck_name, member_cards_number,
                                      COUNT(*) AS card_count
                           FROM member_cards
                           INNER JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                           WHERE member_cards_member_id = '" . $member_id . "'
                             AND member_cards_cat = 3
                             AND member_cards_active = 1
                           GROUP BY carddeck_name, member_cards_number
                           ORDER BY carddeck_name, member_cards_number ASC";
                $result_own_cards = mysqli_query($link, $sql_own_cards) OR die(mysqli_error($link));
                ?>
                <form action="<?php echo HOST_URL; ?>/trade/<?php echo $trade_member_id; ?>/<?php echo $card_id; ?>" method="post">
                    <div class="row trade-container">
                        <div class="col col-12 col-md-4 order-1 order-md-1">
                            <div class="row">
                                <div class="col col-12 text-center">
                                    <?php
                                    $filename_filler = TCG_CARDS_FOLDER . '/'.TCG_CARDS_FILLER_NAME.'.' . TCG_CARDS_FILE_TYPE;
                                    ?>
                                    <span class="card-wrapper own-card" <?php echo(file_exists('.' . $filename_filler) ? 'style="background-image:url(' . HOST_URL.$filename_filler . ');"' : ''); ?>></span>
                                </div>
                                <div class="form-group col col-12">
                                    <?php if (mysqli_num_rows($result_own_cards)) { ?>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"
                                                      id="ariaDescribedbyOwnCard"><?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_own_card']; ?></span>
                                            </div>
                                            <select id="trade_own_card" name="trade_own_card" class="custom-select" aria-describedby="ariaDescribedbyOwnCard"
                                                    required>
                                                <option selected disabled hidden value=""></option>
                                                <?php
                                                while ($row_own_cards = mysqli_fetch_assoc($result_own_cards)) {
                                                    $card_count = ($row_own_cards['card_count'] > 1 ? ' ('.$row_own_cards['card_count'].'x)' : '');
                                                    $own_carddeck_name = $row_own_cards['carddeck_name'];
                                                    $own_card_number_plain = $row_own_cards['member_cards_number'];
                                                    $own_card_number = sprintf("%'.02d", $own_card_number_plain);
                                                    ?>
                                                    <option
                                                        value="<?php echo $row_own_cards['member_cards_id']; ?>;;<?php echo $own_carddeck_name; ?>;;<?php echo $own_card_number; ?>"><?php echo $own_carddeck_name.$own_card_number.$card_count; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                    } else {
                                        alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_no_cards_to_trade'], 'danger');
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col col-12 col-md-4 order-2 order-md-3">
                            <div class="row">
                                <div class="col col-12 text-center">
                                    <?php
                                    $filename = show_card($trade_card_carddeck_id, $trade_card_number, true);
                                    ?>
                                    <span class="card-wrapper trade-card" <?php echo(file_exists('.' . substr($filename, strlen(HOST_URL))) ? 'style="background-image:url(' . $filename . ');"' : ''); ?>></span>
                                </div>
                                <div class="form-group col col-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="ariaDescribedbyTradeCard"><?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_trade_card']; ?></span>
                                        </div>
                                        <input type="text" class="form-control" aria-describedby="ariaDescribedbyTradeCard"
                                               value="<?php echo $trade_card_name.sprintf("%'.02d", $trade_card_number); ?>" disabled required />
                                        <input type="hidden" class="form-control" id="trade_card_id"
                                               name="trade_card_id"
                                               value="<?php echo $card_id; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-12 col-md-4 order-3 order-md-2 text-center">
                            <div class="row">
                                <div class="form-group col col-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="ariaDescribedbyTradeText"><?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_trade_message']; ?></span>
                                        </div>
                                        <textarea class="form-control" id="trade_message" name="trade_message" aria-describedby="ariaDescribedbyTradeText" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group col col-12">
                                    <button type="submit"
                                            class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['trade']['text_send_offer']; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <script>
                    document.querySelector('#trade_own_card').addEventListener('change', function(e) {
                        var own_card = e.target.value.split(';;');
                        var own_card_carddeck_name = own_card[1];
                        var own_card_number = own_card[2];

                        document.querySelector('.own-card').style.backgroundImage = 'url(<?php echo HOST_URL; ?>/assets/cards/'+ own_card_carddeck_name +'/'+ own_card_carddeck_name+own_card_number +'.<?php echo TCG_CARDS_FILE_TYPE; ?>)';
                    });
                </script>
                <?php
            }
        } else {
            $breadcrumb = array(
                '/' => 'Home',
                '/trade' => TRANSLATIONS[$GLOBALS['language']]['general']['text_trade'],
            );
            breadcrumb($breadcrumb);
            title(TRANSLATIONS[$GLOBALS['language']]['general']['text_trade']);

            alert_box(TRANSLATIONS[$GLOBALS['language']]['trade']['hint_card_dont_exists'], 'danger');
        }
    }
} else {
    show_no_access_message_with_breadcrumb();
}
?>