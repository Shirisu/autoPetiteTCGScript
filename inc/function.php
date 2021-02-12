<?php
function redirect($link) {
    header('Location: '.$link);
}

function ip() {
    $ip = getenv("REMOTE_ADDR");
    return $ip;
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

function show_no_access_message() {
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

    $sql_member_online = "SELECT member_id
                          FROM member_online
                          WHERE member_id = '".$member_id."'
                          LIMIT 1;";
    $result_member_online = mysqli_query($link, $sql_member_online) OR die(mysqli_error($link));
    if (mysqli_num_rows($result_member_online)) {
        return '<span class="online">online</span>';
    } else {
        return '<span class="offline">offline</span>';
    }
}

function alert_box($text, $type = 'secondary') {
    echo '<div class="alert alert-'.$type.'" role="alert">
            '.$text.'
          </div>';
}

function navlink($name,$url) {
    echo '<a class="dropdown-item" href="'.HOST_URL.'/'.$url.'">'.$name.'</a>';
}

function navlink_language($name,$language) {
    echo '<a class="dropdown-item switch-language" href="#" data-language="'.$language.'">'.$name.'</a>';
}

function navilink($name,$url,$icon = null) {
    echo '<a class="list-group-item list-group-item-action bg-light" href="'.HOST_URL.'/'.$url.'">'.($icon ? '<i class="fas fa-'.$icon.'"></i> ' : '').''.$name.'</a>';
}

function member_link($member_id, $custom_link_class = '', $show_with_rank = false) {
    global $link;
    $sql = "SELECT member_id, member_rank_name, member_nick, member_rank
          FROM member, member_rank
          WHERE member_id = '".$member_id."'
            AND member_rank = member_rank_id
          LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
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

        return $rank = '<a '.($custom_link_class ? 'class="'.$custom_link_class.'"' : '').' href="'.HOST_URL.'/member/'.$row['member_id'].'"
                        title="'.$row['member_rank_name'].'">
                        <span class="'.$rankclass.'">'.$row['member_nick'].'</span>
                    </a>';
    } else {
        return $rank = '<a '.($custom_link_class ? 'class="'.$custom_link_class.'"' : '').' href="'.HOST_URL.'/member/'.$row['member_id'].'">'.$row['member_nick'].'</a>';
    }
}

function breadcrumb($breadcrumb_array) {
    ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php
            foreach ($breadcrumb_array as $link => $text) {
                if ($link === array_key_last($breadcrumb_array)) {
                    ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $text; ?></li>
                    <?php
                } else {
                    ?>
                    <li class="breadcrumb-item"><a href="<?php echo HOST_URL.$link; ?>"><?php echo $text; ?></a></li>
                    <?php
                }
            }
            ?>
        </ol>
    </nav>
    <?php
}

function insert_cards($member_id, $quantity) {
    global $link;

    $sql = "SELECT carddeck_id, carddeck_name
            FROM carddeck
            WHERE carddeck_active = 1
            ORDER BY RAND()
            LIMIT ".$quantity."";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));

    $cardarray = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $cardnumber = mt_rand(1, TCG_CARDDECK_MAX_CARDS );
        mysqli_query($link, "INSERT INTO member_cards (member_cards_carddeck_id, member_cards_number, member_cards_member_id) VALUES ('".$row['carddeck_id']."','".$cardnumber."','".$member_id."')") OR die(mysqli_error($link));
        array_push($cardarray, $row['carddeck_name'].sprintf("%02d", $cardnumber));
        $_SESSION['insert_cards'] = $cardarray;
    }
    mysqli_query($link, "UPDATE member SET member_cards = member_cards + '".$quantity."' WHERE member_id = '".$member_id."' LIMIT 1") OR die(mysqli_error($link));
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

function send_message($sender, $receiver, $subject, $text) {
    global $link;

    mysqli_query($link, "INSERT INTO message
               (message_from_member_id, message_to_member_id, message_subject, message_text, message_date)
               VALUES
               ('".$sender."', '".$receiver."', '".$subject."', '".$text."', '".time()."')
               ")
    OR DIE(mysqli_error($link));
}

function shorten_text($text, $length) {
    if (strlen($text) >= $length) {
        return preg_replace('/\s+?(\S+)?$/', '', substr($text, 0, $length)).'...';
    } else {
        return $text;
    }
}

function get_card($carddeck_id, $card_number, $show_only_url = false, $show_inactive = false) {
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
            return '<img src="'.HOST_URL.TCG_CARDS_FOLDER.'/'.$carddeck_name.'/'.$carddeck_name.$card_number.'.'.TCG_CARDS_FILE_TYPE.'" alt="'.$carddeck_name.$card_number.'" />';
        }
    }
}




?>
