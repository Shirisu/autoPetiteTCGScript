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

echo '<ul>
        <li><a href="index.php?import">Import database structure</a></li>
        <li><a href="index.php?add_admin">Add Admin Account (your personal account with admin permissions)</a></li>
      </ul>';

if (isset($_GET['import'])) {
    $query = '';
    $sqlScript = file("database-structure.sql");
    foreach ($sqlScript as $line) {
        $startWith = substr(trim($line), 0, 2);
        $endWith = substr(trim($line), -1, 1);

        if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
            continue;
        }

        $query = $query . $line;
        if ($endWith == ';') {
            mysqli_query($link, $query) or die("Problem in executing the SQL query <b>" . $query . "</b>");
            $query = '';
        }
    }

    echo 'All went fine - database structure is imported.';
} elseif (isset($_GET['add_admin'])) {
    // Insert simple form here
    if (isset($_POST['submit'])) {
        if (
            (isset($_POST['member_nick']) && trim($_POST['member_nick']) != '') &&
            (isset($_POST['member_password']) && trim($_POST['member_password']) != '') &&
            (isset($_POST['member_email']) && trim($_POST['member_email']) != '') &&
            (isset($_POST['member_birthdate_day']) && trim($_POST['member_birthdate_day']) != '') &&
            (isset($_POST['member_birthdate_month']) && trim($_POST['member_birthdate_month']) != '') &&
            (isset($_POST['member_birthdate_year']) && trim($_POST['member_birthdate_year']) != '') &&
            (isset($_POST['member_language']) && trim($_POST['member_language']) != '')
        ) {
            $nick = mysqli_real_escape_string($link, trim($_POST['member_nick']));
            $password = mysqli_real_escape_string($link, trim($_POST['member_password']));
            require_once('../inc/class.passwordhash_tcg.php');
            $password_hashed = create_hash_for_tcg($password);
            $email = mysqli_real_escape_string($link, trim($_POST['member_email']));
            $birthdate_day = mysqli_real_escape_string($link, trim($_POST['member_birthdate_day']));
            $birthdate_month = mysqli_real_escape_string($link, trim($_POST['member_birthdate_month']));
            $birthdate_year = mysqli_real_escape_string($link, trim($_POST['member_birthdate_year']));
            $birthdate_str = $birthdate_month.'/'.$birthdate_day.'/'.$birthdate_year.' 00:00:01';
            $birthdate = strtotime($birthdate_str);
            $language = mysqli_real_escape_string($link, trim($_POST['member_language']));

            $query = "INSERT INTO member
                      (member_id, member_nick, member_password, member_register, member_rank, member_birthdate, member_email, member_cards, member_language)
                      VALUES
                      (1, '".$nick."','".$password_hashed."','".time()."','1','".$birthdate."','".$email."','".TCG_CARDS_STARTDECK."','".$language."')
                      ON DUPLICATE KEY UPDATE
                      member_nick = '".$nick."',
                      member_password = '".$password_hashed."',
                      member_birthdate = '".$birthdate."',
                      member_email = '".$email."',
                      member_language = '".$language."'
                      ;";
            mysqli_query($link, $query) or die(mysqli_error($link));

            echo 'All went fine - your personal (admin) account has been created.';
        } else {
            echo '<b>Please fill all fields.</b><br /><br />';
        }
    }
    ?>
    <form action="index.php?add_admin" method="POST">
        <label for="member_nick">Nickname:</label>
        <input type="text" id="member_nick" name="member_nick"><br />
        <label for="member_password">Password:</label>
        <input type="text" id="member_password" name="member_password"><br />
        <label for="member_email">Email:</label>
        <input type="text" id="member_email" name="member_email"><br />
        <label>Birthdate:</label>
        <select id="member_birthdate_day" name="member_birthdate_day">
            <option value="">Day</option>
            <?php
            for ($i = 1; $i <= 31; $i++) {
                echo '<option value="'.$i.'">'.$i.'</option>';
            }
            ?>
        </select>
        <select id="member_birthdate_month" name="member_birthdate_month">
            <option value="">Month</option>
            <?php
            for ($i = 1; $i <= 12; $i++) {
                echo '<option value="'.$i.'">'.$i.'</option>';
            }
            ?>
        </select>
        <select id="member_birthdate_year" name="member_birthdate_year">
            <option value="">Year</option>
            <?php
            for ($i = 1900; $i <= date('Y', time()); $i++) {
                echo '<option value="'.$i.'">'.$i.'</option>';
            }
            ?>
        </select>
        <br />
        <label for="member_language">Language:</label>
        <select id="member_language" name="member_language">
            <option value="en">english</option>
            <option value="de">german</option>
        </select>
        <br />
        <button type="submit" name="submit">Create account</button>
    </form>
    <?php
}

    mysqli_close($link);
?>