<?php
while($row_member_online = mysqli_fetch_assoc($result_member_online)) {
    echo member_rank_online($row_member_online['member_id'], true);
}

if((($_SESSION['member_id'] == 1 || $_SESSION['member_id'] == 3)) && mysqli_num_rows($result_member_invi)) {
    while($row_member_invi = mysqli_fetch_assoc($result_member_invi)) {
        echo member_rank_online($row_member_invi['member_id'],true);
    }
}
?>