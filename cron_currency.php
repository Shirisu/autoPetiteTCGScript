<?php

/**
 * This file will be called via a crontab entry daily using something
 * similar to 'curl -s https://<URL>/dist_currency.php'
 * This script is a simple SQL Query and then a loop over all "active"
 * users to reward them with a configurable Daily amount of Currancy.
 *
 * don't forget to adjust the ../inc/constants.php file to set the amount.
 */

// set up database connection
require_once("./inc/connection.php");
require_once("./inc/constants.php");
require_once("./inc/function.php");

global $link;

if (!$link) {
    echo "Error: could not connect to MySQL." . PHP_EOL;
    echo "Debug error number: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debug error message: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

  if (TCG_CURRENCY_USE == true) {
        $sql = "SELECT member_id, member_nick, member_active
                FROM member
                WHERE member_active = '1'";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
        while ($row = mysqli_fetch_assoc($result)) {

        $member_id = $row['member_id'];
        $quantity = TCG_DAILY_REWARD;
        $topic = "Daily ".TCG_CURRENCY." Reward";
        $language = get_member_language($member_id);

        insert_currency($member_id, $quantity);
        $inserted_currency_text = "You have been awarded <B>$quantity ".TCG_CURRENCY."</B> for being an Active User";

        insert_log($topic, $inserted_currency_text, $member_id);
        $text = $topic.' - '.$inserted_currency_text;
        insert_message($member_id, $member_id, $topic, $text, 1);

         }
        }
  }
# echo 'All went fine - Currancy Distributed.';

mysqli_close($link);
?>
