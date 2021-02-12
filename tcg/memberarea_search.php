<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/memberarea' => TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'],
        '/memberarea/search' => TRANSLATIONS[$GLOBALS['language']]['general']['text_cardsearch'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['general']['text_cardsearch']);


    $carddeck_id = '';
    $card_number = '';
    if (isset($_GET['carddeck_id']) && isset($_GET['card_number'])) {
        $carddeck_id = mysqli_real_escape_string($link, trim($_GET['carddeck_id']));
        $card_number = mysqli_real_escape_string($link, trim($_GET['card_number']));
    }

    $sql_carddeck = "SELECT carddeck_id, carddeck_name
                     FROM carddeck
                     WHERE carddeck_active = 1
                     ORDER BY carddeck_name ASC";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck)) {
        ?>
        <div class="row">
            <div class="col">
                <form action="<?php echo HOST_URL; ?>/memberarea/search" method="get">
                    <div class="row align-items-center">
                        <div class="form-group col col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ariaDescribedbyCarddeck"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_carddeck']; ?></span>
                                </div>
                                <select class="custom-select" id="carddeck_id" name="carddeck_id" aria-describedby="ariaDescribedbyCarddeck" required>
                                    <option selected disabled hidden value=""></option>
                                    <?php
                                    while ($row_carddeck = mysqli_fetch_assoc($result_carddeck)) {
                                        ?>
                                        <option value="<?php echo $row_carddeck['carddeck_id']; ?>" <?php echo ($carddeck_id == $row_carddeck['carddeck_id'] ? 'selected' : ''); ?>><?php echo $row_carddeck['carddeck_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col col-12 col-md-6 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="ariaDescribedbyNumber"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_number']; ?></span>
                                </div>
                                <select class="custom-select" id="card_number" name="card_number" aria-describedby="ariaDescribedbyNumber" required>
                                    <option selected disabled hidden value=""></option>
                                    <?php
                                    for ($i = 1; $i <= TCG_CARDDECK_MAX_CARDS; $i++) {
                                        ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($card_number == $i ? 'selected' : ''); ?>><?php echo sprintf('%02d', $i); ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col col-12">
                            <button type="submit"
                                    class="btn btn-primary"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_search_button']; ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    } else {
        alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_carddeck_yet'], 'danger');
    }

    if (isset($_GET['carddeck_id']) && isset($_GET['card_number'])) {
        $sql_search = "SELECT member_id, member_last_login, member_cards_id
                       FROM member_cards, member
                       WHERE member_cards_carddeck_id = '".$carddeck_id."'
                         AND member_cards_number = '".$card_number."'
                         AND member_cards_member_id = member_id
                         AND member_cards_cat = 3
                       ORDER BY member_nick ASC";
        $result_search = mysqli_query($link, $sql_search);
        $count_search = mysqli_num_rows($result_search);
        ?>
        <div class="row">
            <div class="col col-12 text-center mb-2">
                <?php echo show_card($carddeck_id, $card_number); ?>
            </div>
            <div class="col col-12 member-search-container">
                <?php
                if ($count_search) {
                    ?>
                    <table id="member-search-table" data-mobile-responsive="true">
                        <thead>
                        <tr>
                            <th data-field="member" data-sortable="true">Member</th>
                            <th data-field="lastlogin" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_lastlogin']; ?></th>
                            <th data-field="online" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?></th>
                            <th data-field="text"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row_search = mysqli_fetch_assoc($result_search)) {
                            ?>
                            <tr>
                                <td><?php echo member_link($row_search['member_id'], '', true); ?></td>
                                <td><?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_fulldatetime'], $row_search['member_last_login']); ?></td>
                                <td><?php echo get_online_status($row_search['member_id']); ?></td>
                                <td><?php echo ($row_search['member_id'] != $_SESSION['member_id'] ? '<a href="'.HOST_URL.'/trade/'.$row_search['member_cards_id'].'">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_start_trade'].'</a>' : '-'); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_nobody_has_card_in_trade'], 'danger');
                }
                ?>
            </div>
        </div>
        <?php
    }
} else {
    show_no_access_message();
}
?>