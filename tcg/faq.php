<?php
$breadcrumb = array(
    '/' => 'Home',
    '/faq' => 'F.A.Q.',
);
breadcrumb($breadcrumb);

title('F.A.Q.');

title_small(TRANSLATIONS[$GLOBALS['language']]['general']['text_level_overview']);

global $link;
$sql = "SELECT member_level_id, member_level_name, member_level_from, member_level_to
        FROM member_level
        ORDER BY member_level_id ASC";
$result = mysqli_query($link, $sql) OR die(mysqli_error($link));
$count = mysqli_num_rows($result);
if ($count) {
    ?>
    <div class="row">
        <div class="col">
            <table class="faq-table" data-mobile-responsive="true">
                <thead>
                <tr>
                    <th data-field="id" data-sortable="true">ID</th>
                    <th data-field="name" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_name']; ?></th>
                    <th data-field="from" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_level_from']; ?></th>
                    <th data-field="to" data-sortable="true"><?php echo TRANSLATIONS[$GLOBALS['language']]['admin']['text_level_to']; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $row['member_level_id']; ?></td>
                        <td><?php echo $row['member_level_name']; ?></td>
                        <td><?php echo $row['member_level_from']; ?></td>
                        <td><?php echo $row['member_level_to']; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>