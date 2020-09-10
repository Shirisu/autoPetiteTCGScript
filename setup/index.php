<?php

/**
 * Setup for automated petite TCG Script
 *
 * don't forget to adjust the ../inc/connection.php file
 */

// set up database connection
require_once '../inc/connection.php';

if (!$link) {
    echo "Error: could not connect to MySQL." . PHP_EOL;
    echo "Debug error number: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debug error message: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$query = '';
$sqlScript = file("database-structure.sql");
foreach ($sqlScript as $line)	{
    $startWith = substr(trim($line), 0 ,2);
    $endWith = substr(trim($line), -1 ,1);

    if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
        continue;
    }

    $query = $query . $line;
    if ($endWith == ';') {
        mysqli_query($link,$query) or die("Problem in executing the SQL query <b>" . $query. "</b>");
        $query= '';
    }
}

echo "All went fine - database structure is imported.";

mysqli_close($link);
?>