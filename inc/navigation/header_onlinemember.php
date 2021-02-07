<?php
while ($row_member_online = mysqli_fetch_assoc($result_member_online)) {
    echo member_link($row_member_online['member_id'], "useron list-group-item list-group-item-action bg-light", true);
}
?>