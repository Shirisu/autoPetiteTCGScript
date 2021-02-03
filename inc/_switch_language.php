<?php
require_once('function.php');
if (isset($_POST['language'])) {
    $language = trim($_POST['language']);
    if ($language != 'en' && $language != 'de') {
        $language = TCG_MAIN_LANGUAGE;
    }
    set_cookie('language', $language);
    $GLOBALS['language'] = $language;

    echo 'switch';
}
?>