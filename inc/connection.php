<?php
date_default_timezone_set('Europe/Berlin'); // choose your timezone: https://www.php.net/manual/en/timezones.php

$database_host = 'localhost'; //location of the mysql - default is localhost
$database_user = 'root'; //user name for logging into mysql
$database_user_password = ''; //password of user for logging into mysql
$database_name   = 'autopetitetcgscript'; //name of your database

$link = mysqli_connect($database_host, $database_user, $database_user_password, $database_name) OR die(mysqli_error($link));
?>
