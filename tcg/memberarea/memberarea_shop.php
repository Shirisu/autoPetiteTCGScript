<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/memberarea' => TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'],
        '/memberarea/shop' => TRANSLATIONS[$GLOBALS['language']]['member']['text_shop'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['member']['text_shop']);

    $sql_carddeck = "SELECT carddeck_id, carddeck_name
                     FROM carddeck
                     WHERE carddeck_active = 1
                     ORDER BY carddeck_name ASC";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));

    $member_currency = get_member_currency($_SESSION['member_id']);
    if (TCG_WISH_USE == true) {
        $member_wish = get_member_wish($_SESSION['member_id']);
    }
    $prize_single_random = TCG_SHOP_CURRENCY_FOR_RANDOM;
    $max_random = floor($member_currency / $prize_single_random);

    if (isset($_POST['random_quantity'])) {
        $random_quantity = mysqli_real_escape_string($link, trim($_POST['random_quantity']));

        insert_shop_random($_SESSION['member_id'], $random_quantity);
        $member_currency = get_member_currency($_SESSION['member_id']);
        $max_random = floor($member_currency / $prize_single_random);
    }

    if (isset($_POST['carddeck_id']) && isset($_POST['card_number'])) {
        $carddeck_id = mysqli_real_escape_string($link, trim($_POST['carddeck_id']));
        $card_number = mysqli_real_escape_string($link, trim($_POST['card_number']));

        insert_shop_card($_SESSION['member_id'], $carddeck_id, $card_number);
        if (TCG_WISH_USE == true) {
            $member_wish = get_member_wish($_SESSION['member_id']);
        }
    }

    if (isset($_POST['cardshop'])) {
        echo $_POST['cardshop'];
        buy_card($_POST['cardshop']);
    }
    ?>
    <div class="row shop-container">
        <div class="col col-12 mb-4 text-left text-md-center">
            <span class="font-weight-bold"><?php echo TRANSLATIONS[$GLOBALS['language']]['shop']['text_you_currently_own']; ?>:</span>
            <?php if (TCG_CURRENCY_USE == true) { ?><?php echo $member_currency.' '.TCG_CURRENCY; ?><?php } ?>
            <?php if (TCG_CURRENCY_USE == true && TCG_WISH_USE == true) { ?>& <?php } ?>
            <?php if (TCG_WISH_USE == true) { ?><?php echo $member_wish.' '.TCG_WISH; ?><?php } ?>
        </div>
        <?php
        if (mysqli_num_rows($result_carddeck)) {
            ?>
            <?php if (TCG_CURRENCY_USE == true) { ?>
                <div class="col col-12 <?php echo(TCG_WISH_USE == true ? 'col-md-6' : 'col-md-12'); ?>">
                    <form action="<?php echo HOST_URL; ?>/memberarea/shop" method="post">
                        <div class="row">
                            <div class="col <?php echo(TCG_WISH_USE == true ? 'col-md-12' : 'col-md-6'); ?> mb-2">
                                <?php echo TCG_SHOP_CURRENCY_FOR_RANDOM; ?> <?php echo TCG_CURRENCY; ?> = 1
                                Random <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_card']; ?>
                            </div>
                            <?php
                            if ($max_random > 0) {
                                ?>
                                <div
                                    class="form-group col <?php echo(TCG_WISH_USE == true ? 'col-12' : 'col-12 col-md-6'); ?> mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text"
                                              id="ariaDescribedbyQuantity"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_quantity']; ?></span>
                                        <select class="selectpicker input-group-btn" data-live-search="true" data-size="10" id="random_quantity" name="random_quantity"
                                                aria-describedby="ariaDescribedbyQuantity" required>
                                            <option selected disabled hidden value=""></option>
                                            <?php
                                            for ($i = 1; $i <= $max_random; $i++) {
                                                ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col col-12">
                                    <button type="submit"
                                            class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['shop']['text_button_buy_random']; ?></button>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div
                                    class="col <?php echo(TCG_WISH_USE == true ? 'col-12' : 'col-12 col-md-6'); ?> mb-2">
                                    <?php
                                    alert_box(TRANSLATIONS[$GLOBALS['language']]['shop']['hint_not_enough_currency'], 'danger');
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
                <?php
            }

            if (TCG_WISH_USE == true) { ?>
                <div class="col col-12 <?php echo (TCG_CURRENCY_USE == true ? 'col-md-6' : 'col-md-12'); ?>">
                    <form action="<?php echo HOST_URL; ?>/memberarea/shop" method="post">
                        <div class="row">
                            <div class="col col-12 mb-2">
                                1 <?php echo TCG_WISH; ?> =
                                1 <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_card']; ?>
                            </div>
                            <?php
                            if ($member_wish > 0) {
                                $sql_carddeck = "SELECT carddeck_id, carddeck_name
                                             FROM carddeck
                                             WHERE carddeck_active = 1
                                             ORDER BY carddeck_name ASC";
                                $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
                                ?>
                                <div class="form-group col col-12 mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text"
                                              id="ariaDescribedbyCarddeck"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_carddeck']; ?></span>
                                        <select class="selectpicker input-group-btn" data-live-search="true" data-size="10" id="carddeck_id" name="carddeck_id"
                                                aria-describedby="ariaDescribedbyCarddeck" required>
                                            <option selected disabled hidden value=""></option>
                                            <?php
                                            while ($row_carddeck = mysqli_fetch_assoc($result_carddeck)) {
                                                ?>
                                                <option
                                                    value="<?php echo $row_carddeck['carddeck_id']; ?>"><?php echo $row_carddeck['carddeck_name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <select class="selectpicker input-group-btn" data-live-search="true" data-size="10" id="card_number" name="card_number"
                                                aria-describedby="ariaDescribedbyNumber" required>
                                            <option selected disabled hidden value=""></option>
                                            <?php
                                            for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS; $i++) {
                                                ?>
                                                <option
                                                        value="<?php echo $i; ?>"><?php echo sprintf('%02d', $i); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col col-12">
                                    <button type="submit"
                                            class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['shop']['text_button_buy_card']; ?></button>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="col col-12 mb-2">
                                    <?php
                                    alert_box(TRANSLATIONS[$GLOBALS['language']]['shop']['hint_not_enough_wish'], 'danger');
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="col col-12 mb-2">
                <?php
                alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_carddeck_yet'], 'danger');
                ?>
            </div>
            <?php
        }
        ?>

        <div class="col col-12 my-3">
            <?php
            $sql_shop = "SELECT shop_id, shop_carddeck_name, shop_carddeck_id, shop_card_number, shop_price
                         FROM shop
                         ORDER BY shop_carddeck_name, shop_card_number
                         LIMIT ".TCG_SHOP_MAX_CARDS."";
            $result_shop = mysqli_query($link, $sql_shop) OR die(mysqli_error($link));
            if (mysqli_num_rows($result_shop)) {
                ?>
                <div class="row">
                    <div class="col col-12 mb-2"><?php echo TRANSLATIONS[$GLOBALS['language']]['shop']['text_buy_cards_headline']; ?></div>
                    <?php
                    while($row_shop = mysqli_fetch_assoc($result_shop)) {
                        $carddeck_id = $row_shop['shop_carddeck_id'];
                        $carddeck_name = $row_shop['shop_carddeck_name'];
                        $cardnumber_plain = $row_shop['shop_card_number'];
                        $cardnumber = sprintf("%'.02d", $cardnumber_plain);
                        ?>
                        <div
                            class="col col-6 col-md-3 mb-2 shop-card-wrapper">
                            <form action="<?php echo HOST_URL; ?>/memberarea/shop" method="post">
                                <?php echo get_card($carddeck_id, $cardnumber_plain); ?>
                                <a class="carddeck-link"
                                   href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>">
                                    <small><?php echo $carddeck_name . $cardnumber; ?></small>
                                </a>
                                <div>
                                    <?php echo TRANSLATIONS[$GLOBALS['language']]['shop']['text_costs']; ?>: <?php echo $row_shop['shop_price'] ?>
                                </div>
                                <div>
                                    <input type="hidden" name="cardshop" value="<?php echo $row_shop['shop_id']; ?>" />
                                    <button type="submit"
                                            class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['shop']['text_button_buy_card']; ?></button>
                                </div>
                            </form>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                alert_box(TRANSLATIONS[$GLOBALS['language']]['shop']['text_no_cards']);
            }
            ?>
        </div>
    </div>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>