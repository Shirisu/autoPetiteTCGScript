<?php
// online member
$sql_member_online = "SELECT member_id, member_nick, member_rank, member_online.*
                      FROM member_online
                      JOIN member ON member_id = member_online_member_id
                      ORDER BY member_nick ASC;";
$result_member_online = mysqli_query($link, $sql_member_online) OR die(mysqli_error($link));
$count_member = mysqli_num_rows($result_member_online);
?>
<ul class="footer-menu">
    <li>
        <span class="nav-link">Online: <?php echo $count_member; ?></span>
    </li>
    <?php
    if (isset($_SESSION['member_rank'])) {
        while ($row_member_online = mysqli_fetch_assoc($result_member_online)) {
            ?>
            <li>
                <?php echo get_member_link($row_member_online['member_id'], "nav-link", true); ?>
            </li>
            <?php
        }
    }
    ?>
</ul>