<?php
session_start();
require_once("connection.php");
require_once("constants.php");
require_once("function.php");
require_once("_translations.php");
require_once("_language.php");

global $link;
if (isset($_POST['action']) && isset($_POST['carddeck_id'])) {
    $action = mysqli_real_escape_string($link, trim($_POST['action']));
    $carddeck_id = mysqli_real_escape_string($link, trim($_POST['carddeck_id']));
    $member_id = $_SESSION['member_id'];

    $toast_data = [
        'id' => 'toast-wishlist-error',
        'icon' => 'star',
        'title' => TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist'],
        'text' => TRANSLATIONS[$GLOBALS['language']]['general']['text_error'],
    ];

    $sql_carddeck = "SELECT carddeck_name
                     FROM carddeck
                     WHERE carddeck_id = '".$carddeck_id."'
                     LIMIT 1";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck)) {
        $row_carddeck = mysqli_fetch_assoc($result_carddeck);

        $sql_on_wishlist = "SELECT member_wishlist_carddeck_id
                            FROM member_wishlist
                            WHERE member_wishlist_carddeck_id = '".$carddeck_id."'
                              AND member_wishlist_member_id = '".$member_id."'
                            LIMIT 1";
        $result_on_wishlist = mysqli_query($link, $sql_on_wishlist) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_on_wishlist)) {
            if ($action == 'add') {
                $toast_data = [
                    'id' => 'toast-add-to-wishlist-' . $carddeck_id,
                    'icon' => 'star',
                    'title' => TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist'],
                    'text' => '<span class="font-weight-bold">' . $row_carddeck['carddeck_name'] . '</span> ' . TRANSLATIONS[$GLOBALS['language']]['wishlist']['text_already_on_wishlist'],
                ];
            } elseif ($action == 'remove') {
                mysqli_query($link, "DELETE FROM member_wishlist
                                     WHERE member_wishlist_member_id = '".$member_id."'
                                       AND member_wishlist_carddeck_id = '".$carddeck_id."'
                                     LIMIT 1
               ")
                OR DIE(mysqli_error($link));

                $toast_data = [
                    'id' => 'toast-remove-from-wishlist-' . $carddeck_id,
                    'icon' => 'star',
                    'title' => TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist'],
                    'text' => '<span class="font-weight-bold">' . $row_carddeck['carddeck_name'] . '</span> ' . TRANSLATIONS[$GLOBALS['language']]['wishlist']['text_removed_from_wishlist'],
                ];
            }
        } else {
            if ($action == 'add') {
                mysqli_query($link, "INSERT INTO member_wishlist
                                     (member_wishlist_member_id,member_wishlist_carddeck_id,member_wishlist_date)
                                     VALUES
                                     ('" . $member_id . "','" . $carddeck_id . "','" . time() . "')
               ")
                OR DIE(mysqli_error($link));

                $toast_data = [
                    'id' => 'toast-add-to-wishlist-' . $carddeck_id,
                    'icon' => 'star',
                    'title' => TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist'],
                    'text' => '<span class="font-weight-bold">' . $row_carddeck['carddeck_name'] . '</span> ' . TRANSLATIONS[$GLOBALS['language']]['wishlist']['text_added_to_wishlist'],
                ];
            } elseif ($action == 'remove') {
                $toast_data = [
                    'id' => 'toast-remove-from-wishlist-' . $carddeck_id,
                    'icon' => 'star',
                    'title' => TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist'],
                    'text' => '<span class="font-weight-bold">' . $row_carddeck['carddeck_name'] . '</span> ' . TRANSLATIONS[$GLOBALS['language']]['wishlist']['text_not_on_wishlist'],
                ];
            }
        }
    } else {
        $toast_data = [
            'id' => 'toast-wishlist-error',
            'icon' => 'star',
            'title' => TRANSLATIONS[$GLOBALS['language']]['wishlist']['text_wishlist'],
            'text' => TRANSLATIONS[$GLOBALS['language']]['general']['hint_carddeck_dont_exists'],
        ];
    }
    echo json_encode($toast_data);
}

?>