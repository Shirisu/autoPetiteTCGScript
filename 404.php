<?php
$breadcrumb = array(
    '/' => 'Home',
    '/404' => TRANSLATIONS[$GLOBALS['language']]['general']['text_notfound'],
);
breadcrumb($breadcrumb);
title(TRANSLATIONS[$GLOBALS['language']]['general']['text_notfound']);

alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['text_pagenotexist'])
?>