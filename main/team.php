<div class="row">
    <div class="col col-12">
        <?php
        global $link;
        $sql_a = "SELECT member_id, member_nick, member_rank_name, member_language, member_active
                  FROM member, member_rank
                  WHERE member_rank != 5
                    AND member_rank = member_rank_id
                  ORDER BY member_rank, member_nick";
        $result_a = mysqli_query($link, $sql_a) OR die(mysqli_error($link));
        $anz_a = mysqli_num_rows($result_a);
        title('Team ('.$anz_a.')');
        ?>
    </div>
    <div class="col col-12">
        <div class="row">
            <?php
            if($anz_a) {
                while($row_a = mysqli_fetch_assoc($result_a)) {
                    if($row_a['member_active'] == 1) {
                        $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_active'];
                    } else {
                        $statuts = TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive'];
                    }
                    ?>
                    <div class="col col-4">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Nick:</th>
                                <td scope="row"><a href="/tcg/member.php?id=<?php echo $row_a['member_id']; ?>"><?php echo $row_a['member_nick']; ?></a></td>
                            </tr>
                            <tr>
                                <th><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_rank']; ?>:</th>
                                <td><?php echo $row_a['member_rank_name']; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_status']; ?>:</th>
                                <td><?php echo $status; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_main_language']; ?>:</th>
                                <td><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['language_'.$row_a['member_language'].'_text']; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>