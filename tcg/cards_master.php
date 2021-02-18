<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/cards' => TRANSLATIONS[$GLOBALS['language']]['general']['text_cards'],
        '/cards/trade' => 'Master',
    );
    breadcrumb($breadcrumb);
    title('Master');

    $member_id = $_SESSION['member_id'];
    ?>
    <div class="row cards-sorting">
        <div class="col col-12 mb-3">
            <div class="row">
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/new" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-fire"></i> New</a>
                </div>
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/trade" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-exchange-alt"></i> Trade</a>
                </div>
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/collect" class="btn btn-outline-info btn-sm btn-block"><i class="fas fa-heart"></i> Collect</a>
                </div>
                <div class="col col-6 col-md-3 mb-2 mb-md-0">
                    <a href="<?php echo HOST_URL; ?>/cards/master" class="btn btn-outline-info btn-sm btn-block active"><i class="fas fa-award"></i> Master</a>
                </div>
            </div>
        </div>
        <div class="col col-12 mb-3 cards-sorting-container">
            <?php
            $sql_cards = "SELECT carddeck_id, carddeck_name, member_master_date
                          FROM member_master, carddeck
                          WHERE member_master_member_id = '".$member_id."'
                            AND member_master_carddeck_id = carddeck_id
                          ORDER BY carddeck_name ASC";
            $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
            $count_cards = mysqli_num_rows($result_cards);
            if ($count_cards) {
                title_small($count_cards.' Master');
                ?>
                <div class="row">
                    <div class="col col-12">
                        <table class="optional cards-sorting-table master-cards" data-mobile-responsive="true">
                            <thead>
                            <tr>
                                <th><?php echo title_small($count_cards.' Master'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row_cards = mysqli_fetch_assoc($result_cards)) {
                                $carddeck_id = $row_cards['carddeck_id'];
                                $carddeck_name = $row_cards['carddeck_name'];
                                $master_date = $row_cards['member_master_date'];
                                ?>
                                <tr>
                                    <td>
                                        <div class="cards-sorting-wrapper">
                                            <a class="carddeck-link" href="<?php echo HOST_URL; ?>/carddeck/<?php echo $carddeck_name; ?>"><?php echo show_card($carddeck_id, 'master'); ?></a><br />
                                            <small><span class="mastered"><i class="fas fa-medal"></i></span> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_mastered_on']; ?> <?php echo date(TRANSLATIONS[$GLOBALS['language']]['general']['date_format_date'], $master_date); ?></small>
                                        </div>
                                    </td>
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
                title_small('0 Master');
                alert_box(TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_no_cards_in_category'], 'danger');
            }
            ?>
        </div>
    </div>
    <?php
} else {
    show_no_access_message();
}
?>
