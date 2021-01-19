<?php
date_default_timezone_set('Europe/Berlin'); // choose your timezone: https://www.php.net/manual/en/timezones.php

error_reporting(E_ALL);

$host = 'localhost'; //location of the mysql
$user_db = 'USERNAME'; //user name for logging into mys
$pass = 'PASSWORD'; //password for logging into mysql
$db   = 'DATABASENAME'; //database

$link = mysqli_connect($host, $user_db, $pass, $db) OR die(mysqli_error($link));
?>
