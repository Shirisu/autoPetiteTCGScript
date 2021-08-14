<?php
date_default_timezone_set('Europe/London'); // choose your timezone: https://www.php.net/manual/en/timezones.php

$database_host = 'localhost'; //location of the mysql - default is localhost
$database_user = 'dageeks-geeks'; //user name for logging into mysql
$database_user_password = 'qE)FL{7hhuMD'; //password of user for logging into mysql
$database_name   = 'tcg'; //name of your database

$link = mysqli_connect($database_host, $database_user, $database_user_password, $database_name) OR die(mysqli_error($link));
?>
