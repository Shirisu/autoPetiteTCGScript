<?php
title(TRANSLATIONS[$GLOBALS['language']]['activation']['headline']);
?>

<div class="row">
    <div class="col col-12">
        <?php
        if(isset($activation_code)) {
            //insert_cards($new_member_id, TCG_CARDS_START_PACKAGE);
            //insert_log(TRANSLATIONS[$GLOBALS['language']]['register']['start_package'].' '.$_SESSION['insert_cards'], $text, $new_member_id);
        } else {
            alert_box(TRANSLATIONS[$GLOBALS['language']]['activation']['hint_nocode'], 'danger');
        }
        ?>
    </div>
</div>
