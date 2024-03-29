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
    echo '<li><a class="'.$classname.'" href="'.HOST_URL.'/'.$url.'">'.$name.'</a></li>';
}

function navlink_language($name, $language) {
    echo '<li><a class="dropdown-item switch-language" href="#" data-language="'.$language.'">'.$name.'</a></li>';
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

        $masterclass = '';
        if ($card_number == 'master') {
            $card_number = 'master';
            $masterclass = ' mastercard';
        } else {
            $card_number = sprintf('%02d', $card_number);
        }

        if ($show_only_url == true) {
            return HOST_URL.TCG_CARDS_FOLDER.'/'.$carddeck_name.'/'.$carddeck_name.$card_number.'.'.TCG_CARDS_FILE_TYPE;
        } else {
            $filename = get_card($carddeck_id, $card_number, true, $show_inactive);
            if (file_exists('.' . substr($filename, strlen(HOST_URL)))) {
                return '<img class="card-wrapper'.$masterclass.'" src="'.HOST_URL.TCG_CARDS_FOLDER.'/'.$carddeck_name.'/'.$carddeck_name.$card_number.'.'.TCG_CARDS_FILE_TYPE.'" alt="'.$carddeck_name.$card_number.'" />';
            } else {
                return '<img class="card-wrapper'.$masterclass.'" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="'.$carddeck_name.$card_number.'" />';
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

function get_card_filter_class($carddeck_id, $card_number, $own_member_id = 0, $other_member_id = 0, $category = 0) {
    global $link;

    if ($own_member_id === 0 && $other_member_id === 0) {
        $own_member_id = $_SESSION['member_id'];
        $other_member_id = $_SESSION['member_id'];
    }

    $sql_cards = "SELECT 
                    EXISTS (SELECT member_cards_id
                      FROM member_cards
                      WHERE member_cards_member_id = '" . $own_member_id . "'
                        AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                        AND member_cards_cat = '" . MEMBER_CARDS_COLLECT . "'
                        AND member_cards_active = 1
                      GROUP BY member_cards_carddeck_id) as carddeck_in_collect,
                     EXISTS (SELECT member_cards_id
                      FROM member_cards
                      WHERE member_cards_member_id = '" . $own_member_id . "'
                        AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                        AND member_cards_number = '".$card_number."'
                        AND member_cards_cat = '" . MEMBER_CARDS_COLLECT . "'
                        AND member_cards_active = 1
                      GROUP BY member_cards_carddeck_id, member_cards_number) as card_already_in_collect,
                     EXISTS (SELECT member_cards_id
                      FROM member_cards
                      WHERE member_cards_member_id = '" . $own_member_id . "'
                        AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                        AND member_cards_cat = '" . MEMBER_CARDS_KEEP . "'
                        AND member_cards_active = 1
                      GROUP BY member_cards_carddeck_id) as carddeck_in_keep,
                     EXISTS (SELECT member_cards_id
                      FROM member_cards
                      WHERE member_cards_member_id = '" . $own_member_id . "'
                        AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                        AND member_cards_number = '".$card_number."'
                        AND member_cards_cat = '" . MEMBER_CARDS_KEEP . "'
                        AND member_cards_active = 1
                      GROUP BY member_cards_carddeck_id, member_cards_number) as card_already_in_keep,
                     EXISTS (SELECT member_cards_id
                      FROM member_cards
                      WHERE member_cards_member_id = '" . $own_member_id . "'
                        AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                        AND member_cards_cat = '" . MEMBER_CARDS_TRADE . "'
                        AND member_cards_active = 1
                      GROUP BY member_cards_carddeck_id) as carddeck_in_trade,
                     EXISTS (SELECT member_cards_id
                      FROM member_cards
                      WHERE member_cards_member_id = '" . $own_member_id . "'
                        AND mc.member_cards_carddeck_id = member_cards_carddeck_id
                        AND member_cards_number = '".$card_number."'
                        AND member_cards_cat = '" . MEMBER_CARDS_TRADE . "'
                        AND member_cards_active = 1
                      GROUP BY member_cards_carddeck_id, member_cards_number) as card_already_in_trade,
                     EXISTS (SELECT member_wishlist_member_id
                      FROM member_wishlist
                      WHERE member_wishlist_member_id = '" . $own_member_id . "'
                        AND mc.member_cards_carddeck_id = member_wishlist_carddeck_id
                      GROUP BY member_wishlist_carddeck_id) as carddeck_on_wishlist,
                     EXISTS (SELECT member_master_id
                      FROM member_master
                      WHERE member_master_member_id = '" . $own_member_id . "'
                        AND mc.member_cards_carddeck_id = member_master_carddeck_id
                      GROUP BY member_master_carddeck_id) as carddeck_already_mastered
                    FROM member_cards mc
                    WHERE member_cards_member_id = '" . $other_member_id . "'
                      AND member_cards_carddeck_id = '" . $carddeck_id . "'
                      AND member_cards_active = 1
                    LIMIT 1";
    $result_cards = mysqli_query($link, $sql_cards) OR die(mysqli_error($link));
    $count_cards = mysqli_num_rows($result_cards);
    if (!$count_cards) {
        $sql_wishlist = "SELECT member_wishlist_member_id
                         FROM member_wishlist
                         WHERE member_wishlist_member_id = '" . $own_member_id . "'
                           AND member_wishlist_carddeck_id = '" . $carddeck_id . "'
                         LIMIT 1";
        $result_wishlist = mysqli_query($link, $sql_wishlist) OR die(mysqli_error($link));
        $count_wishlist = mysqli_num_rows($result_wishlist);
        if (!$count_wishlist) {
            return '';
        }

        return ' needed wishlist';
    }

    $row_cards = mysqli_fetch_assoc($result_cards);

    $carddeck_in_collect = $row_cards['carddeck_in_collect'];
    $card_already_in_collect = $row_cards['card_already_in_collect'];
    $carddeck_in_keep = $row_cards['carddeck_in_keep'];
    $card_already_in_keep = $row_cards['card_already_in_keep'];
    $carddeck_in_trade = $row_cards['carddeck_in_trade'];
    $card_already_in_trade = $row_cards['card_already_in_trade'];
    $carddeck_on_wishlist = $row_cards['carddeck_on_wishlist'];
    $carddeck_already_mastered = $row_cards['carddeck_already_mastered'];

    if ($carddeck_already_mastered == 1 && !TCG_MULTI_MASTER) {
        $filterclass = ' deck-mastered';
    } elseif ($carddeck_in_collect == 1 && $card_already_in_collect == 1) {
        $filterclass = ' already-in-collect';
    } elseif (
    ($carddeck_in_collect == 1 && $card_already_in_collect == 0)
    ) {
        $filterclass = ' needed collect';
    } elseif (
    ($carddeck_in_keep == 1 && $card_already_in_keep == 0)
    ) {
        $filterclass = TCG_CATEGORY_KEEP_USE ? ' needed keep' : '';
    } elseif ($category === MEMBER_CARDS_NEW && $carddeck_in_keep == 1 && $card_already_in_keep == 1) {
        $filterclass = ' already-in-keep';
    } elseif ($category === MEMBER_CARDS_NEW && $carddeck_in_trade == 1 && $card_already_in_trade == 1) {
        $filterclass = ' already-in-trade';
    } elseif (
    ($carddeck_on_wishlist == 1 && $carddeck_in_collect == 0 && $carddeck_in_keep == 0)
    ) {
        $filterclass = ' needed wishlist';
    } else {
        $filterclass = '';
    }

    return $filterclass;
}

function get_card_highlight_legend() {
    echo '<div class="container">
            <div class="row gx-4">
                <div class="col col-12 mb-2 pt-2 border-top"><b>'.TRANSLATIONS[$GLOBALS['language']]['general']['text_legend'].':</b></div>
                <div class="col col-12 col-md-'.(TCG_CATEGORY_KEEP_USE ? '3' : '4').' mb-2">
                    <div class="needed collect">Collect</div>
                </div>';
        if (TCG_CATEGORY_KEEP_USE) {
            echo '<div class="col col-12 col-md-3 mb-2">
                    <div class="needed keep">Keep</div>
                </div>';
        }
          echo '<div class="col col-12 col-md-'.(TCG_CATEGORY_KEEP_USE ? '3' : '4').' mb-2">
                    <div class="needed wishlist">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist'].'</div>
                </div>
                <div class="col col-12 col-md-'.(TCG_CATEGORY_KEEP_USE ? '3' : '4').' mb-2">
                    <div class="deck-mastered">'.TRANSLATIONS[$GLOBALS['language']]['general']['text_mastered'].'</div>
                </div>
            </div>
          </div>';
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
        }
    }

    if ($updateCount) {
        mysqli_query($link, "UPDATE member SET member_cards = member_cards + '" . $quantity . "' WHERE member_id = '" . $member_id . "' LIMIT 1") OR die(mysqli_error($link));
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

    if (get_member_wish($member_id) > 0) {
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

function get_member_menu($member_id, $active_menu) {
?>
    <div class="row justify-content-center">
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'profile' ? ' active' : ''; ?>"><i class="fas fa-user"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_profile']; ?></a>
        </div>
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/trade" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'trade' ? ' active' : ''; ?>"><i class="fas fa-exchange-alt"></i> Trade</a>
        </div>
        <?php if (TCG_CATEGORY_KEEP_USE) { ?>
            <div class="d-grid col col-6 col-md-2 mb-2">
                <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/keep" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'keep' ? ' active' : ''; ?>"><i class="fas fa-lock"></i> Keep</a>
            </div>
        <?php } ?>
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/collect" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'collect' ? ' active' : ''; ?>"><i class="fas fa-heart"></i> Collect</a>
        </div>
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/master" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'master' ? ' active' : ''; ?>"><i class="fas fa-award"></i> Master</a>
        </div>
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/member/<?php echo $member_id; ?>/wishlist" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'wishlist' ? ' active' : ''; ?>"><i class="fas fa-star"></i> <?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_wishlist']; ?></a>
        </div>
    </div>
<?php
}

function get_cards_menu($active_menu) {
?>
    <div class="row justify-content-center">
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/cards/new" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'new' ? ' active' : ''; ?>"><i class="fas fa-user"></i> New</a>
        </div>
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/cards/trade" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'trade' ? ' active' : ''; ?>"><i class="fas fa-exchange-alt"></i> Trade</a>
        </div>
        <?php if (TCG_CATEGORY_KEEP_USE) { ?>
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/cards/keep" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'keep' ? ' active' : ''; ?>"><i class="fas fa-lock"></i> Keep</a>
        </div>
        <?php } ?>
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/cards/collect" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'collect' ? ' active' : ''; ?>"><i class="fas fa-heart"></i> Collect</a>
        </div>
        <div class="d-grid col col-6 col-md-2 mb-2">
            <a href="<?php echo HOST_URL; ?>/cards/master" class="btn btn-outline-info btn-sm<?php echo $active_menu == 'master' ? ' active' : ''; ?>"><i class="fas fa-award"></i> Master</a>
        </div>
    </div>
<?php
}

function include_game_file($game_id) {
    global $link;
    $sql_games = "SELECT games_id, games_name, games_file, games_interval, games_type, games_is_lucky_category_game
                  FROM games
                  WHERE games_status = '1'
                    AND games_id = '".$game_id."'
                  ORDER BY games_interval, games_name ASC";
    $result_games = mysqli_query($link, $sql_games) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_games)) {
        $row_games = mysqli_fetch_assoc($result_games);
        $filename = "tcg/games/" . $row_games['games_file'];
        if (file_exists($filename)) {
            require_once($filename);
        } else {
            require_once("tcg/games/games.php");
        }
    } else {
        require_once("tcg/games/games.php");
    }
}

function check_shop_update() {
    global $link;

    $sql_shop = "SELECT table_last_update_date
                 FROM table_last_update
                 WHERE table_last_update_name = 'shop'
                 LIMIT 1";
    $result_shop = mysqli_query($link, $sql_shop) OR DIE(mysqli_error($link));
    if (mysqli_num_rows($result_shop)) {
        $row_shop = mysqli_fetch_assoc($result_shop);
        $last_update = $row_shop['table_last_update_date'];
        $date_now = time();
        if ($last_update <= $date_now - (60 * 60 * 24)) {
            reset_shop();
            refill_shop(TCG_SHOP_MAX_CARDS);
        }
    } else {
        reset_shop();
        refill_shop(TCG_SHOP_MAX_CARDS);
    }
}

function reset_shop() {
    global $link;

    mysqli_query($link, "TRUNCATE shop")
    OR DIE(mysqli_error($link));

    mysqli_query($link, "DELETE FROM table_last_update WHERE table_last_update_name = 'shop' LIMIT 1")
    OR DIE(mysqli_error($link));
}
function refill_shop($card_quantity) {
    global $link;

    for ($i = 1; $i <= $card_quantity; $i++) {
        $sql = "SELECT carddeck_id, carddeck_name
                FROM carddeck
                WHERE carddeck_active = 1
                ORDER BY RAND()
                LIMIT 1";
        $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
        if (mysqli_num_rows($result)) {
            mysqli_query($link,
                "INSERT INTO table_last_update 
                       (table_last_update_name) 
                       VALUES 
                       ('shop')
                       ON DUPLICATE KEY UPDATE
                       table_last_update_date = '".time()."'"
            ) OR die(mysqli_error($link));

            while ($row = mysqli_fetch_assoc($result)) {
                $cardnumber = mt_rand(1, TCG_CARDDECK_MAX_CARDS);
                $price = mt_rand(TCG_SHOP_CURRENCY_FOR_CARD_RANGE_MIN, TCG_SHOP_CURRENCY_FOR_CARD_RANGE_MAX);
                mysqli_query($link,
                    "INSERT INTO shop 
                           (shop_carddeck_name, shop_carddeck_id, shop_card_number, shop_price) 
                           VALUES 
                           ('" . $row['carddeck_name'] . "','" . $row['carddeck_id'] . "','" . $cardnumber . "','" . $price . "')"
                ) OR die(mysqli_error($link));
            }
        }
    }
}

function buy_card($member_id, $shop_id) {
    global $link;
    $sql = "SELECT shop_id, shop_carddeck_name, shop_carddeck_id, shop_card_number, shop_price
            FROM shop
            WHERE shop_id = '".$shop_id."'
            LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);
        $carddeck_id = $row['shop_carddeck_id'];
        $carddeck_name = $row['shop_carddeck_name'];
        $cardnumber_plain = $row['shop_card_number'];
        $cardnumber = sprintf("%'.02d", $cardnumber_plain);
        $card_price = $row['shop_price'];

        if (get_member_currency($member_id) >= $card_price) {
            mysqli_query($link, "UPDATE member SET member_currency = member_currency - '".$card_price."' WHERE member_id = '".$member_id."' LIMIT 1") OR die(mysqli_error($link));
            insert_specific_cards($member_id, $carddeck_id, $cardnumber_plain);
            $inserted_card_text = TRANSLATIONS[$GLOBALS['language']]['shop']['text_you_bought'] . ': ' . $carddeck_name . sprintf('%02d', $cardnumber) . '. ' . TRANSLATIONS[$GLOBALS['language']]['shop']['text_you_spent'] . ': ' . $card_price . ' '.TCG_CURRENCY;
            alert_box($inserted_card_text, 'success');
            insert_log(TRANSLATIONS[$GLOBALS['language']]['member']['text_shop'], $inserted_card_text, $member_id);
            mysqli_query($link, "DELETE FROM shop WHERE shop_id = '".$shop_id."' LIMIT 1") OR die(mysqli_error($link));
            refill_shop(1);
        } else {
            return alert_box(TRANSLATIONS[$GLOBALS['language']]['shop']['hint_not_enough_currency'], 'danger');
        }
    }
}

?>