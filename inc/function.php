<?php
function redirect($link) {
    header('Location: '.$link);
}

function ip() {
    return getenv("REMOTE_ADDR");
}

if (!function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return null;
        }
        return array_keys($array)[count($array) - 1];
    }
}

function passwordgenerator() {
    $password = "";
    $signs = "qwertzupasdfghkyxcvbnm";
    $signs .= "123456789";
    $signs .= "WERTZUPLKJHGFDSAYXCVBNM";

    srand((double)microtime()*1000000);

    for ($i = 0; $i < 20; $i++) {
        $password .= substr($signs,(rand()%(strlen ($signs))), 1);
    }

    return $password;
}

function set_cookie($name, $value, $expire = null, $path = null) {
    if ($expire == null) {
        $expire = time()+60*60*24*30;
    }
    if ($path == null) {
        $path = '/';
    }

    setcookie($name, $value, $expire, $path);
}

function shorten_text($text, $length) {
    if (strlen($text) >= $length) {
        return preg_replace('/\s+?(\S+)?$/', '', substr($text, 0, $length)).'...';
    } else {
        return $text;
    }
}

function show_no_access_message_with_breadcrumb() {
    $breadcrumb = array(
        '/' => 'Home',
    );
    breadcrumb($breadcrumb);
    alert_box(TRANSLATIONS[$GLOBALS['language']]['general']['hint_no_access'], 'danger');
}

function title($text) {
    echo '<h2 class="mb-3">'.$text.'</h2>';
}

function title_small($text) {
    echo '<h3 class="mb-3">'.$text.'</h3>';
}

function alert_box($text, $type = 'secondary') {
    echo '<div class="alert alert-'.$type.'" role="alert">
            '.$text.'
          </div>';
}

function navlink($name, $url, $classname = 'dropdown-item') {
    echo '<a class="'.$classname.'" href="'.HOST_URL.'/'.$url.'">'.$name.'</a>';
}

function navlink_language($name, $language) {
    echo '<a class="dropdown-item switch-language" href="#" data-language="'.$language.'">'.$name.'</a>';
}

function navilink($name, $url, $classname = 'list-group-item list-group-item-action bg-light', $icon = null) {
    echo '<a class="'.$classname.'" href="'.HOST_URL.'/'.$url.'">'.($icon ? '<i class="'.$icon.'"></i> ' : '').''.$name.'</a>';
}

function breadcrumb($breadcrumb_array) {
    ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php
            foreach ($breadcrumb_array as $link => $text) {
                if ($link === array_key_last($breadcrumb_array)) {
                    $activelinkclass = ' active';
                } else {
                    $activelinkclass = '';
                }
                ?>
                <li class="breadcrumb-item<?php echo $activelinkclass; ?>"><a href="<?php echo HOST_URL.$link; ?>"><?php echo $text; ?></a></li>
                <?php
            }
            ?>
        </ol>
    </nav>
    <?php
}

function member_level_up($member_id) {
    global $link;

    $sql_cards_count = "SELECT member_cards_id
	      					 FROM member_cards
	      					 WHERE member_cards_member_id = '".$member_id."'";
    $result_cards_count = mysqli_query($link, $sql_cards_count) OR die(mysqli_error($link));
    $count_cards = mysqli_num_rows($result_cards_count);

    $sql_count_master = "SELECT member_master_id
                            FROM member_master
                            JOIN carddeck ON carddeck_id = member_master_carddeck_id
                            WHERE member_master_member_id = '".$member_id."';";
    $result_count_master = mysqli_query($link, $sql_count_master) OR die(mysqli_error($link));
    $count_master = mysqli_num_rows($result_count_master);
    $count_master_with_cards_count = $count_master * TCG_CARDDECK_MAX_CARDS;
    $cards_count_total = ($count_cards + $count_master_with_cards_count);

    $sql_level = "SELECT member_level_id, member_level_name FROM member_level WHERE '".$cards_count_total."' BETWEEN member_level_from AND member_level_to LIMIT 1";
    $result_level = mysqli_query($link, $sql_level) OR die(mysqli_error($link));
    $row_level = mysqli_fetch_assoc($result_level);

    $sql_member = "SELECT member_level FROM member WHERE member_id = '".$member_id."' LIMIT 1";
    $result_member = mysqli_query($link, $sql_member) OR die(mysqli_error($link));
    $row_member = mysqli_fetch_assoc($result_member);

    if(($row_member['member_level'] != $row_level['member_level_id']) && ($row_member['member_level'] < $row_level['member_level_id'])) {
        insert_cards($member_id, TCG_LEVEL_UP_CARD_REWARD);
        $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['general']['text_level_up_reward'].': '.implode(', ', $_SESSION['insert_cards']);
        insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_level_up'].' - '.sprintf('%02d', $row_level['member_level_id']), $inserted_cards_text, $member_id);

        mysqli_query($link, "UPDATE member SET member_level = '".$row_level['member_level_id']."' WHERE member_id = '".$member_id."' LIMIT 1");

        alert_box(
            TRANSLATIONS[$GLOBALS['language']]['general']['text_level_up'].': '.TRANSLATIONS[$GLOBALS['language']]['general']['text_level_up_reward'].': '.implode(', ', $_SESSION['insert_cards']).
            '<br />'.
            $_SESSION['insert_cards_images']
            , 'success');
    }
}

function member_check_card_count($member_id) {
    global $link;

    $sql = "SELECT member_cards
            FROM member
            WHERE member_id = '".$member_id."'
            LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));

    if(mysqli_num_rows($result)) {
        while ($row = mysqli_fetch_assoc($result)) {

            $sql_count_cards = "SELECT member_cards_id
                             FROM member_cards
                             WHERE member_cards_member_id = '" . $member_id . "';";
            $result_count_cards = mysqli_query($link, $sql_count_cards) OR die(mysqli_error($link));
            $count_cards = mysqli_num_rows($result_count_cards);

            $sql_count_master = "SELECT member_master_id
                              FROM member_master
                              WHERE member_master_member_id = '" . $member_id . "';";
            $result_count_master = mysqli_query($link, $sql_count_master) OR die(mysqli_error($link));
            $count_master = mysqli_num_rows($result_count_master);

            $count_all_cards = (($count_cards) + ($count_master * TCG_CARDDECK_MAX_CARDS));

            $sql_level = "SELECT *
                          FROM member_level
                          WHERE " . $count_all_cards . " BETWEEN member_level_from AND member_level_to";
            $result_level = mysqli_query($link, $sql_level) OR die(mysqli_error($link));

            if ($count_all_cards != $row['member_cards']) {
                mysqli_query($link, "UPDATE member
                                     SET member_cards = " . $count_all_cards . "
                                     WHERE member_id = " . $member_id . "
                                     LIMIT 1"
                ) OR die(mysqli_error($link));
            }
        }
    }
}

function get_active_status($status) {
    if ($status == 1) {
        $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_active'];
    } elseif ($status == 2) {
        $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_blocked'];
    } elseif ($status == 3) {
        $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_not_activated_yet'];
    } elseif ($status == 4) {
        $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_deleted'];
    } elseif ($status == 0) {
        $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive'];
    } else {
        $status = 'unkown';
    }

    return $status;
}

function get_online_status($member_id) {
    global $link;

    $sql_member_online = "SELECT member_online_member_id
                          FROM member_online
                          WHERE member_online_member_id = '".$member_id."'
                          LIMIT 1;";
    $result_member_online = mysqli_query($link, $sql_member_online) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_member_online)) {
        return '<span class="online">online</span>';
    } else {
        return '<span class="offline">offline</span>';
    }
}

function get_member_link($member_id, $custom_link_class = '', $show_with_rank = false) {
    global $link;
    $sql = "SELECT member_id, member_rank_name, member_nick, member_rank
          FROM member
          INNER JOIN member_rank ON member_rank = member_rank_id
          WHERE member_id = '".$member_id."'
          LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);

        if ($show_with_rank) {
            if ($row['member_rank'] == 1) {
                $rankclass = 'admin';
            } elseif ($row['member_rank'] == 2) {
                $rankclass = 'coadmin';
            } elseif ($row['member_rank'] == 3) {
                $rankclass = 'cm';
            } elseif ($row['member_rank'] == 4) {
                $rankclass = 'mod';
            } else {
                $rankclass = 'member';
            }

            return '<a ' . ($custom_link_class ? 'class="' . $custom_link_class . '"' : '') . ' href="' . HOST_URL . '/member/' . $row['member_id'] . '"
                    title="' . $row['member_rank_name'] . '">
                    <span class="' . $rankclass . '">' . $row['member_nick'] . '</span>
                </a>';
        } else {
            return '<a ' . ($custom_link_class ? 'class="' . $custom_link_class . '"' : '') . ' href="' . HOST_URL . '/member/' . $row['member_id'] . '">' . $row['member_nick'] . '</a>';
        }
    }

    return 'unkown';
}

function get_member_nick_plain($member_id) {
    global $link;
    $sql = "SELECT member_nick
          FROM member
          WHERE member_id = '".$member_id."'
          LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);

        return $row['member_nick'];
    }

    return 'unkown';
}

function get_card($carddeck_id = 0, $card_number = 0, $show_only_url = false, $show_inactive = false) {
    global $link;

    $active_query_string = ($show_inactive ? '' : 'AND carddeck_active = 1');
    $sql_carddeck = "SELECT carddeck_name
                     FROM carddeck
                     WHERE carddeck_id = '".$carddeck_id."'
                       ".$active_query_string."
                     LIMIT 1";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck)) {
        $row_carddeck = mysqli_fetch_assoc($result_carddeck);
        $carddeck_name = $row_carddeck['carddeck_name'];

        if ($card_number == 'master') {
            $card_number = 'master';
        } else {
            $card_number = sprintf('%02d', $card_number);
        }

        if ($show_only_url == true) {
            return HOST_URL.TCG_CARDS_FOLDER.'/'.$carddeck_name.'/'.$carddeck_name.$card_number.'.'.TCG_CARDS_FILE_TYPE;
        } else {
            $filename = get_card($carddeck_id, $card_number, true, $show_inactive);
            if (file_exists('.' . substr($filename, strlen(HOST_URL)))) {
                return '<img src="'.HOST_URL.TCG_CARDS_FOLDER.'/'.$carddeck_name.'/'.$carddeck_name.$card_number.'.'.TCG_CARDS_FILE_TYPE.'" alt="'.$carddeck_name.$card_number.'" />';
            } else {
                return '<img class="card-wrapper" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="'.$carddeck_name.$card_number.'" />';
            }
        }
    } else {
        $masterclass = '';
        if ($card_number == 'master') {
            $masterclass = ' mastercard';
        }
        return '<img class="card-wrapper'.$masterclass.'" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="" />';
    }
}

function get_card_path_without_number($carddeck_id) {
    global $link;

    $sql_carddeck = "SELECT carddeck_name
                     FROM carddeck
                     WHERE carddeck_id = '".$carddeck_id."'
                     LIMIT 1";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck)) {
        $row_carddeck = mysqli_fetch_assoc($result_carddeck);
        $carddeck_name = $row_carddeck['carddeck_name'];

        return HOST_URL.TCG_CARDS_FOLDER.'/'.$carddeck_name.'/'.$carddeck_name;
    }
}

function get_carddeck_id_from_member_cards_id($member_card_id) {
    global $link;

    $sql_carddeck = "SELECT member_cards_carddeck_id
                     FROM member_cards
                     WHERE member_cards_id = '".$member_card_id."'
                     LIMIT 1";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck)) {
        $row_carddeck = mysqli_fetch_assoc($result_carddeck);
        return $row_carddeck['member_cards_carddeck_id'];
    }
}

function get_carddeck_name_from_member_cards_id($member_card_id) {
    global $link;

    $sql_carddeck = "SELECT carddeck_name
                     FROM member_cards
                     INNER JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                     WHERE member_cards_id = '".$member_card_id."'
                     LIMIT 1";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck)) {
        $row_carddeck = mysqli_fetch_assoc($result_carddeck);
        return $row_carddeck['carddeck_name'];
    }
}

function get_carddeck_name_from_carddeck_id($carddeck_id) {
    global $link;

    $sql_carddeck = "SELECT carddeck_name
                     FROM carddeck
                     WHERE carddeck_id = '".$carddeck_id."'
                     LIMIT 1";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck)) {
        $row_carddeck = mysqli_fetch_assoc($result_carddeck);
        return $row_carddeck['carddeck_name'];
    }
}

function get_carddeck_link($carddeck_id, $extra_link_text = '') {
    global $link;
    $sql = "SELECT carddeck_name
            FROM carddeck
            WHERE carddeck_id = '".$carddeck_id."'
            LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);

        return '<a href="' . HOST_URL . '/carddeck/' . $row['carddeck_name'] . '">' . ($extra_link_text ? $extra_link_text : $row['carddeck_name']) . '</a>';
    }

    return 'unkown';
}

function get_card_number_from_member_cards_id($member_card_id) {
    global $link;

    $sql_carddeck = "SELECT member_cards_number
                     FROM member_cards
                     WHERE member_cards_id = '".$member_card_id."'
                     LIMIT 1";
    $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_carddeck)) {
        $row_carddeck = mysqli_fetch_assoc($result_carddeck);
        return $row_carddeck['member_cards_number'];
    }
}

function get_card_filter_class($carddeck_id, $card_number) {
    global $link;

    $member_id = $_SESSION['member_id'];

    $sql_cards = "SELECT EXISTS (SELECT member_cards_id
                          FROM member_cards
                          WHERE member_cards_member_id = '" . $member_id . "'
                            AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                            AND member_cards_cat = '" . MEMBER_CARDS_COLLECT . "'
                            AND member_cards_active = 1
                          GROUP BY member_cards_carddeck_id) as carddeck_in_collect,
                         EXISTS (SELECT member_cards_id
                          FROM member_cards
                          WHERE member_cards_member_id = '" . $member_id . "'
                            AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                            AND member_cards_number = '".$card_number."'
                            AND member_cards_cat = '" . MEMBER_CARDS_COLLECT . "'
                            AND member_cards_active = 1
                          GROUP BY member_cards_carddeck_id, member_cards_number) as card_already_in_collect,
                         EXISTS (SELECT member_wishlist_member_id
                          FROM member_wishlist
                          WHERE member_wishlist_member_id = '" . $member_id . "'
                            AND mc.member_cards_carddeck_id = member_wishlist_carddeck_id
                          GROUP BY member_wishlist_carddeck_id) as carddeck_on_wishlist,
                         EXISTS (SELECT member_master_id
                          FROM member_master
                          WHERE member_master_member_id = '" . $member_id . "'
                            AND mc.member_cards_carddeck_id = member_master_carddeck_id
                          GROUP BY member_master_carddeck_id) as carddeck_already_mastered
                    FROM member_cards mc
                    WHERE member_cards_member_id = '" . $member_id . "'
                      AND member_cards_carddeck_id = '" . $carddeck_id . "'
                      AND member_cards_active = 1
                    LIMIT 1";
    $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
    $count_cards = mysqli_num_rows($result_cards);
    if (!$count_cards) {
        return '';
    }

    $row_cards = mysqli_fetch_assoc($result_cards);

    $carddeck_in_collect = $row_cards['carddeck_in_collect'];
    $card_already_in_collect = $row_cards['card_already_in_collect'];
    $carddeck_on_wishlist = $row_cards['carddeck_on_wishlist'];
    $carddeck_already_mastered = $row_cards['carddeck_already_mastered'];

    if ($carddeck_already_mastered == 1) {
        $filterclass = ' mastered';
    } elseif ($carddeck_in_collect == 1 && $card_already_in_collect == 1) {
        $filterclass = '';
    } elseif (
    ($carddeck_in_collect == 1 && $card_already_in_collect == 0)
    ) {
        $filterclass = ' needed collect';
    } elseif (
    ($carddeck_on_wishlist == 1 && $carddeck_in_collect == 0)
    ) {
        $filterclass = ' needed wishlist';
    } else {
        $filterclass = '';
    }

    return $filterclass;
}

function get_member_language($member_id) {
    global $link;

    $sql_language = "SELECT member_language
                     FROM member
                     WHERE member_id = '".$member_id."'
                     LIMIT 1";
    $result_language = mysqli_query($link, $sql_language) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_language)) {
        $row_language = mysqli_fetch_assoc($result_language);

        return $row_language['member_language'];
    }

    return 'en';
}

function get_member_currency($member_id) {
    global $link;

    $sql = "SELECT member_currency
            FROM member
            WHERE member_id = '".$member_id."'
            LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);

        return $row['member_currency'];
    }

    return 0;
}

function get_member_wish($member_id) {
    global $link;

    $sql = "SELECT member_wish
            FROM member
            WHERE member_id = '".$member_id."'
            LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);

        return $row['member_wish'];
    }

    return 0;
}

function insert_cards($member_id, $quantity, $updateCount = true) {
    global $link;
    unset($_SESSION['insert_cards']);
    unset($_SESSION['insert_cards_images']);

    $_SESSION['insert_cards_images'] = '';
    $_SESSION['insert_cards'] = '';
    $cardarray = array();
    $cardarray_infos = array();
    for ($count = 1; $count <= $quantity; $count++) {
        $sql = "SELECT carddeck_id, carddeck_name
            FROM carddeck
            WHERE carddeck_active = 1
            ORDER BY RAND()
            LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $cardnumber = mt_rand(1, TCG_CARDDECK_MAX_CARDS);
                mysqli_query($link, "INSERT INTO member_cards (member_cards_carddeck_id, member_cards_number, member_cards_member_id) VALUES ('" . $row['carddeck_id'] . "','" . $cardnumber . "','" . $member_id . "')") OR die(mysqli_error($link));
                array_push($cardarray, $row['carddeck_name'] . sprintf("%02d", $cardnumber));
                $cardarray_infos[$count] = array(
                    'id' => $row['carddeck_id'],
                    'number' => $cardnumber
                );
            }
            $_SESSION['insert_cards'] = $cardarray;

            if ($count != 0) {
                $_SESSION['insert_cards_images'] .= ' ';
            }
            $_SESSION['insert_cards_images'] .= get_card($cardarray_infos[$count]['id'], $cardarray_infos[$count]['number']);

            if ($updateCount == true) {
                mysqli_query($link, "UPDATE member SET member_cards = member_cards + '" . $quantity . "' WHERE member_id = '" . $member_id . "' LIMIT 1") OR die(mysqli_error($link));
            }
        }
    }
}

function insert_specific_cards($member_id, $carddeck_id, $card_number) {
    global $link;

    $sql = "SELECT carddeck_name
            FROM carddeck
            WHERE carddeck_id = '".$carddeck_id."'
            LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));

    if (mysqli_num_rows($result)) {
        mysqli_query($link, "INSERT INTO member_cards (member_cards_carddeck_id, member_cards_number, member_cards_member_id) VALUES ('" . $carddeck_id . "','" . $card_number . "','" . $member_id . "')") OR die(mysqli_error($link));
        mysqli_query($link, "UPDATE member SET member_cards = member_cards + 1 WHERE member_id = '" . $member_id . "' LIMIT 1") OR die(mysqli_error($link));
    }
}

function insert_wish($member_id, $quantity) {
    global $link;

    mysqli_query($link, "UPDATE member SET member_wish = member_wish + '".$quantity."' WHERE member_id = '".$member_id."' LIMIT 1") OR die(mysqli_error($link));
}

function insert_currency($member_id, $quantity) {
    global $link;

    mysqli_query($link, "UPDATE member SET member_currency = member_currency + '".$quantity."' WHERE member_id = '".$member_id."' LIMIT 1") OR die(mysqli_error($link));
}

function insert_log($topic, $text, $member_id) {
    global $link;

    mysqli_query($link, "INSERT INTO member_log
               (member_log_member_id,member_log_date,member_log_cat,member_log_text)
               VALUES
               ('".$member_id."','".time()."','".$topic."','".$text."')
               ")
    OR DIE(mysqli_error($link));
}

function insert_shop_random($member_id, $quantity) {
    global $link;

    if (get_member_currency($_SESSION['member_id']) / TCG_SHOP_CURRENCY_FOR_RANDOM >= $quantity) {
        $currency_spent = $quantity * TCG_SHOP_CURRENCY_FOR_RANDOM;

        mysqli_query($link, "UPDATE member SET member_currency = member_currency - '".$currency_spent."' WHERE member_id = '".$member_id."' LIMIT 1") OR die(mysqli_error($link));
        insert_cards($member_id, $quantity);
        $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['shop']['text_you_spent'].' '.$currency_spent.' '.TCG_CURRENCY.' '.TRANSLATIONS[$GLOBALS['language']]['shop']['text_and_got_following'].': '.implode(', ',$_SESSION['insert_cards']);
        insert_log('Shop', $inserted_cards_text, $member_id);
        return alert_box($inserted_cards_text.'<br />'.$_SESSION['insert_cards_images'], 'success');
    } else {
        return alert_box(TRANSLATIONS[$GLOBALS['language']]['shop']['hint_not_enough_currency'], 'danger');
    }
}

function insert_shop_card($member_id, $carddeck_id, $card_number) {
    global $link;

    if (get_member_wish($_SESSION['member_id']) > 0) {
        $sql_carddeck = "SELECT carddeck_name
                         FROM carddeck
                         WHERE carddeck_id = '".$carddeck_id."'
                         LIMIT 1";
        $result_carddeck = mysqli_query($link, $sql_carddeck) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_carddeck)) {
            $row_carddeck = mysqli_fetch_assoc($result_carddeck);
            $carddeck_name = $row_carddeck['carddeck_name'];
            mysqli_query($link, "UPDATE member SET member_wish = member_wish - 1 WHERE member_id = '".$member_id."' LIMIT 1") OR die(mysqli_error($link));
            insert_specific_cards($member_id, $carddeck_id, $card_number);
            $inserted_card_text = TRANSLATIONS[$GLOBALS['language']]['shop']['text_you_spent'] . ' 1 ' . TCG_WISH . ' ' . TRANSLATIONS[$GLOBALS['language']]['shop']['text_and_got_following'] . ': ' . $carddeck_name . sprintf('%02d', $card_number);
            insert_log('Shop', $inserted_card_text, $member_id);
            return alert_box($inserted_card_text.'<br />'.get_card($carddeck_id, $card_number), 'success');
        }
    } else {
        return alert_box(TRANSLATIONS[$GLOBALS['language']]['shop']['hint_not_enough_wish'], 'danger');
    }
}

function insert_lucky_game_played($member_id, $game_id, $lucky_game_id) {
    global $link;

    $sql_last_played = "SELECT member_game_played_last_played
                        FROM member_game_played
                        WHERE member_game_played_member_id = '" . $member_id . "'
                          AND member_game_played_game_id = '" . $game_id . "'
                          AND member_game_played_lucky_category_id = '" . $lucky_game_id . "'
                        ORDER BY member_game_played_id DESC
                        LIMIT 1";
    $result_last_played = mysqli_query($link, $sql_last_played) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_last_played)) {
        $query = "UPDATE member_game_played
              SET member_game_played_last_played = '".time()."'
              WHERE member_game_played_member_id = '".$member_id."'
                AND member_game_played_game_id = '".$game_id."'
                AND member_game_played_lucky_category_id = '".$lucky_game_id."'
              LIMIT 1
              ;";
        mysqli_query($link, $query) or die(mysqli_error($link));
    } else {
        $query = "INSERT INTO member_game_played
              (member_game_played_member_id, member_game_played_game_id, member_game_played_lucky_category_id, member_game_played_last_played)
              VALUES
              ('".$member_id."', '".$game_id."', '".$lucky_game_id."', '".time()."')
              ;";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}

function insert_game_played($member_id, $game_id) {
    global $link;

    $sql_last_played = "SELECT member_game_played_last_played
                        FROM member_game_played
                        WHERE member_game_played_member_id = '" . $member_id . "'
                          AND member_game_played_game_id = '" . $game_id . "'
                        ORDER BY member_game_played_id DESC
                        LIMIT 1";
    $result_last_played = mysqli_query($link, $sql_last_played) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_last_played)) {
        $query = "UPDATE member_game_played
              SET member_game_played_last_played = '".time()."'
              WHERE member_game_played_member_id = '".$member_id."'
                AND member_game_played_game_id = '".$game_id."'
              LIMIT 1
              ;";
        mysqli_query($link, $query) or die(mysqli_error($link));
    } else {
        $query = "INSERT INTO member_game_played
              (member_game_played_member_id, member_game_played_game_id, member_game_played_last_played)
              VALUES
              ('".$member_id."', '".$game_id."', '".time()."')
              ;";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}

function insert_tradein($member_id) {
    global $link;

    $sql_last_played = "SELECT member_tradein_last_tradein
                        FROM member_tradein
                        WHERE member_tradein_member_id = '" . $member_id . "'
                        ORDER BY member_tradein_id DESC
                        LIMIT 1";
    $result_last_played = mysqli_query($link, $sql_last_played) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_last_played)) {
        $query = "UPDATE member_tradein
                  SET member_tradein_last_tradein = '".time()."'
                  WHERE member_tradein_member_id = '".$member_id."'
                  LIMIT 1
                  ;";
        mysqli_query($link, $query) or die(mysqli_error($link));
    } else {
        $query = "INSERT INTO member_tradein
              (member_tradein_member_id, member_tradein_last_tradein)
              VALUES
              ('".$member_id."', '".time()."')
              ;";
        mysqli_query($link, $query) or die(mysqli_error($link));
    }
}

function insert_message($sender, $receiver, $subject, $text, $message_system = 0) {
    global $link;

    mysqli_query($link, "INSERT INTO message
               (message_sender_member_id, message_receiver_member_id, message_subject, message_text, message_date, message_system)
               VALUES
               ('".$sender."', '".$receiver."', '".$subject."', '".$text."', '".time()."', '".$message_system."')
               ")
    OR DIE(mysqli_error($link));
}

function card_tradein($card_id, $tradein_card_deck_name, $tradein_card_number) {
    global $link;

    $member_id = $_SESSION['member_id'];

    $sql_duplicate_card = "SELECT
                              (SELECT COUNT(member_cards_id)
                               FROM member_cards
                               WHERE member_cards_id = '".$card_id."'
                                 AND member_cards_member_id = '".$member_id."'
                                 AND member_cards_active = 1
                               LIMIT 1) as own_card
                           FROM member_cards
                           JOIN carddeck ON carddeck_id = member_cards_carddeck_id
                           WHERE member_cards_member_id = '".$member_id."'
                             AND member_cards_cat = '".MEMBER_CARDS_TRADE."'
                             AND member_cards_active = 1
                           GROUP BY member_cards_number, member_cards_carddeck_id
                           HAVING COUNT(member_cards_id) > 1";
    $result_duplicate_card = mysqli_query($link, $sql_duplicate_card) OR die(mysqli_error($link));
    $row_duplicate_card = mysqli_fetch_assoc($result_duplicate_card);
    if (mysqli_num_rows($result_duplicate_card) && $row_duplicate_card['own_card'] == 1) {
        // delete duplicate card
        mysqli_query($link, "DELETE FROM member_cards
                             WHERE member_cards_id = '".$card_id."'
                               AND member_cards_member_id = '".$member_id."'
                               AND member_cards_cat = '".MEMBER_CARDS_TRADE."'
                               AND member_cards_active = 1
                             LIMIT 1")
        OR die(mysqli_error($link));

        // insert new card
        insert_cards($member_id, 1, false);
        $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['tradein']['text_changed_card'].' '.$tradein_card_deck_name.$tradein_card_number.' '.TRANSLATIONS[$GLOBALS['language']]['tradein']['text_and_got_card'].' '.implode(', ', $_SESSION['insert_cards']);
        insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_tradein'], $inserted_cards_text, $member_id);
        insert_tradein($member_id);
        return alert_box($inserted_cards_text.'<br />'.$_SESSION['insert_cards_images'], 'success');
    } else {
        return alert_box(TRANSLATIONS[$GLOBALS['language']]['tradein']['hint_card_dont_exists'], 'danger');
    }
}

?>