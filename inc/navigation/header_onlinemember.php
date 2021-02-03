<?php
while($row_member_online = mysqli_fetch_assoc($result_member_online)) {
    echo member_rank_online($row_member_online['member_id'], true);
}
?>