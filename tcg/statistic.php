<?php
global $link;

$breadcrumb = array(
    '/' => 'Home',
    '/statistic' => TRANSLATIONS[$GLOBALS['language']]['general']['text_statistic'],
);
breadcrumb($breadcrumb);

title(TRANSLATIONS[$GLOBALS['language']]['general']['text_statistic']);

$sql_carddecks = "SELECT carddeck_id
                  FROM carddeck
                  WHERE carddeck_active = 1";
$result_carddecks = mysqli_query($link, $sql_carddecks) OR die(mysqli_error($link));
$count_carddecks = mysqli_num_rows($result_carddecks);

$sql_master = "SELECT member_master_id
               FROM member_master
               JOIN member ON member_id = member_master_member_id
               WHERE member_active = 1";
$result_master = mysqli_query($link, $sql_master) OR die(mysqli_error($link));
$count_master = mysqli_num_rows($result_master);

$sql_cards = "SELECT member_cards_id
              FROM member_cards
              JOIN member ON member_id = member_cards_member_id
              WHERE member_active = 1";
$result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
$count_cards = mysqli_num_rows($result_cards);

$sql_member = "SELECT member_id
               FROM member";
$result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
$count_member = mysqli_num_rows($result_member);

$sql_active_member = "SELECT member_id
                      FROM member
                      WHERE member_active = 1";
$result_active_member = mysqli_query($link, $sql_active_member) OR die(mysqli_error($link));
$count_active_member = mysqli_num_rows($result_active_member);
?>
    <div class="row">
        <div class="col-sm-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_cards']; ?>
                    <span class="badge rounded-pill bg-secondary"><?php echo $count_carddecks * TCG_CARDDECK_MAX_CARDS; ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_carddecks']; ?>
                    <span class="badge rounded-pill bg-secondary"><?php echo $count_carddecks; ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    Master
                    <span class="badge rounded-pill bg-secondary"><?php echo $count_master; ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_cards_in_circulation']; ?>
                    <span class="badge rounded-pill bg-secondary"><?php echo $count_cards + ($count_master * TCG_CARDDECK_MAX_CARDS); ?></span>
            </div>
            </div>
        </div>
        <div class="col-sm-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_registered_member']; ?>
                    <span class="badge rounded-pill bg-secondary"><?php echo $count_member; ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mb-4">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_active_member']; ?>
                    <span class="badge rounded-pill bg-secondary"><?php echo $count_active_member; ?></span>
                </div>
            </div>
        </div>
    </div>

<?php
$sql_carddecks_main_categories = "SELECT carddeck_cat_id, carddeck_cat_name,
                                        (SELECT COUNT(carddeck_id)
                                         FROM carddeck
                                         WHERE carddeck_active = 1
                                           AND carddeck_cat = carddeck_cat_id) AS carddecks_in_main_categorie
                                  FROM carddeck_cat
                                  ORDER BY carddeck_cat_name ASC";
$result_carddecks_main_categories = mysqli_query($link, $sql_carddecks_main_categories) OR die(mysqli_error($link));
$count_carddecks_main_categories = mysqli_num_rows($result_carddecks_main_categories);

if ($count_carddecks_main_categories) {
    title_small(TRANSLATIONS[$GLOBALS['language']]['general']['text_statistic_carddecks_overview']);
    ?>
    <div class="row">
        <?php
        while ($row_carddecks_main_categories = mysqli_fetch_assoc($result_carddecks_main_categories)) {
            $sql_carddecks_sub_categories = "SELECT carddeck_sub_cat_name,
                                                (SELECT COUNT(carddeck_id)
                                                 FROM carddeck
                                                 WHERE carddeck_active = 1
                                                   AND carddeck_sub_cat = carddeck_sub_cat_id) AS carddecks_in_sub_categorie
                                             FROM carddeck_sub_cat
                                             WHERE carddeck_sub_cat_main_cat_id = '".$row_carddecks_main_categories['carddeck_cat_id']."'
                                             ORDER BY carddeck_sub_cat_name ASC";
            $result_carddecks_sub_categories = mysqli_query($link, $sql_carddecks_sub_categories) OR die(mysqli_error($link));
            $count_carddecks_sub_categories = mysqli_num_rows($result_carddecks_sub_categories);
            ?>
            <div class="col-sm-4 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <?php echo $row_carddecks_main_categories['carddeck_cat_name']; ?>
                        <span class="badge rounded-pill bg-secondary"><?php echo $row_carddecks_main_categories['carddecks_in_main_categorie']; ?></span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <?php
                        if ($count_carddecks_sub_categories) {
                            ?>
                            <ul class="list-group list-group-flush w-100">
                                <?php
                                while ($row_carddecks_sub_categories = mysqli_fetch_assoc($result_carddecks_sub_categories)) {
                                    ?>
                                    <li class="list-group-item d-flex px-0 justify-content-between align-items-center">
                                        <?php echo $row_carddecks_sub_categories['carddeck_sub_cat_name']; ?>
                                        <span class="badge rounded-pill bg-light text-dark"><?php echo $row_carddecks_sub_categories['carddecks_in_sub_categorie']; ?></span>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>