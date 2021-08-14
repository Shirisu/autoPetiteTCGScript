<?php

/**
 * This file will be called via a crontab entry daily using something
 * similar to 'curl -s https://<URL>/dist_cardpack.php'
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

  if (TCG_CARDPACK_REWARD == true) {
        $sql = "SELECT member_id, member_nick, member_active
                FROM member
                WHERE member_active = '1'";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
        while ($row = mysqli_fetch_assoc($result)) {

        $member_id = $row['member_id'];
        $quantity = TCG_CARDPACK_QUANTITY;
        $topic = "Card Pack Award";

        insert_cards($member_id, $quantity);
        $inserted_cardpack_text = "You have been awarded <B>$quantity Cards</B> in a Card Pack By the Site Admins";

        insert_log($topic, $inserted_cardpack_text, $member_id);
        $text = $topic.' - '.$inserted_cardpack_text;
        insert_message($member_id, $member_id, $topic, $text, 1);

         }
        }
  }

mysqli_close($link);
?>
