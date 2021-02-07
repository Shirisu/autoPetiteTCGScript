<?php
if (isset($_SESSION['language'])) {
    $GLOBALS['language'] = $_SESSION['language'];
}
if (isset($_COOKIE['language']) && !isset($_SESSION['language'])) {
    $GLOBALS['language'] = $_COOKIE['language'];
}
if (!isset($GLOBALS['language'])) {
    $GLOBALS['language'] = TCG_MAIN_LANGUAGE;
}
?>