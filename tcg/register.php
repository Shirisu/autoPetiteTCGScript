<?php
title(TRANSLATIONS[$GLOBALS['language']]['general']['text_register']);
?>

<div class="row">
    <?php
    if(isset($_SESSION['member_id'])) {
        if(isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
            echo 'You\'re already registered!<br />
            Multiple accounts are not permitted!!';
        } else {
            echo 'Du bist bereits angemeldet!<br />
            Mehrfachanmeldungen sind nicht gestattet!!';
        }
    } else {

    }
    ?>
</div>
