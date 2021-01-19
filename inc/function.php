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

    for($i = 0; $i < 20; $i++) {
        $password .= substr($signs,(rand()%(strlen ($signs))), 1);
    }

    return $password;
}

function title($text) {
    echo '<h2 class="mb-3">'.$text.'</h2>';
}

function title_small($text) {
    echo '<h3 class="mb-3">'.$text.'</h3>';
}

function member_rank_online($member_id,$showInDropdown = false,$invisible = false) {
    global $link;
    $sql = "SELECT member_id, member_rank_name, member_nick, member_rank
          FROM member, member_rank
          WHERE member_id = '".$member_id."'
            AND member_rank = member_rank_id
          LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    $row = mysqli_fetch_assoc($result);

    $nick = $row['member_nick'];
    $ranktitle = $row['member_rank_name'];
    $friendclass = '';

    if ($row['member_rank'] == 1) {
        $rankclass = 'admin';
    } elseif ($row['member_rank'] == 2) {
        $rankclass = 'coadmin';
    } elseif ($row['member_rank'] == 3) {
        $rankclass = 'cm';
    } else {
        $rankclass = 'member';
    }
    if ($invisible != false) {
        $rank = '<a class="useron'.($showInDropdown ? ' dropdown-item' : '' ).'" href="/tcg/member/'.$row['member_id'].'"
              title="'.$ranktitle.'">
              <span class="invi">
              '.$nick.'</span></a>';
    } else {
        $rank = '<a class="useron'.($showInDropdown ? ' dropdown-item' : '' ).'" href="/tcg/member/'.$row['member_id'].'"
              title="'.$ranktitle.'">
              <span class="'.$friendclass.$rankclass.'">
              '.$nick.'</span></a>';
    }

    return $rank;
}

function get_active_status($member_status) {
    if ($member_status == 1) {
        $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_active'];
    } elseif ($member_status == 2) {
        $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_blocked'];
    } elseif ($member_status == 0) {
        $status = TRANSLATIONS[$GLOBALS['language']]['general']['text_inactive'];
    } else {
        $status = 'unkown';
    }

    return $status;
}

function alert_box($text, $type = 'secondary') {
    echo '<div class="alert alert-'.$type.'" role="alert">
            '.$text.'
          </div>';
}

function navlink($name,$url) {
    echo '<a class="dropdown-item" href="'.HOST_URL.'/'.$url.'">'.$name.'</a>';
}

function navlink_language($name,$url) {
    echo '<a class="dropdown-item" href="'.HOST_URL.explode('?', $_SERVER['REQUEST_URI'], 2)[0].'?language='.$url.'">'.$name.'</a>';
}

function insert_cards($member_id, $quantity_cards) {
    global $link;

    $sql = "SELECT carddeck_id, carddeck_name, carddeck_count_cards
          FROM sets
          WHERE carddeck_active = 1
          ORDER BY RAND()
          LIMIT ".$quantity_cards."";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));

    while($row = mysqli_fetch_assoc($result)) {
        $cardnumber = mt_rand(1,$row['carddeck_count_cards']);
        mysqli_query($link, "INSERT INTO member_cards (member_cards_carddeck_id, member_cards_number, member_cards_member_id) VALUES ('".$row['carddeck_id']."','".$cardnumber."','".$member_id."')") OR die(mysqli_error($link));
        array_push($cardarray, $row['carddeck_name'].sprintf("%02d", $cardnumber));
        $_SESSION['insert_cards'] = $cardarray;
    }
    mysqli_query($link, "UPDATE member SET member_cards = member_cards + '".$quantity_cards."' WHERE member_id = '".$member_id."' LIMIT 1") OR die(mysqli_error($link));
}

function insert_log($topic, $text, $member_id) {
    global $link;

    mysqli_query($link, "INSERT INTO member_log
               (member_log_member_id,member_log_date,member_log_cat,member_log_text)
               VALUES
               ('".$member_id."','".time()."','".$topic."','".$text."')
               ") OR DIE(mysqli_error($link));
}

function breadcrumb($breadcrumb_array) {
    ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php
            foreach($breadcrumb_array as $link => $text) {
                if ($link === array_key_last($breadcrumb_array)) {
                    ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $text; ?></li>
                    <?php
                } else {
                    ?>
                    <li class="breadcrumb-item"><a href="<?php echo $link; ?>"><?php echo $text; ?></a></li>
                    <?php
                }
            }
            ?>
        </ol>
    </nav>
    <?php
}









function img_title() {
    header("Content-Type: image/png");
}

function content_header($name) {
    echo '<h2>+++ '.$name.' +++</h2>';
}
function content_headera($name) {
    echo '<h2>+++ '.$name.' +++</h2>';
}
function content_header1($name) {
    echo '<h2>+++ '.$name.' +++</h2>';
}
function content_header2($name) {
    echo '<h2>'.$name.'</h2>';
}
function content_header3($name) {
    echo '<h2 style="text-align:center;height:21px;">'.$name.'</h2>';
}
function content_header4($name) {
    echo '<h2 style="text-align:center;height:16px;">'.$name.'</h2>';
}

function navilink($name,$url) {
    echo '<div class="l"><a href="'.HOST_URL.'/'.$url.'">&raquo; '.$name.'</a></div>';
}
function navilink1($name,$url) {
    echo '<div class="l" style="width:100%;"><a href="'.HOST_URL.'/'.$url.'">&raquo; '.$name.'</a></div>';
}
function navilink2($name,$url) {
    echo '<div class="l"><a href="http://'.$url.'">&raquo; '.$name.'</a></div>';
}

function online_rank($member_id) {
    global $link;
    $sql_member_online = "SELECT member_id
                        FROM member_online
                        WHERE member_id = '".$member_id."';";
    $result_member_online  = mysqli_query($link, $sql_member_online) OR die(mysqli_error($link));
    $anz_member = mysqli_num_rows($result_member_online);

    if($anz_member) {
        return '<span style="color:#00aa00;">online</span>';
    } else {
        return '<span style="color:#aa0000;">offline</span>';
    }
}

function member_rank($member_id) {
    global $link;
    $sql = "SELECT member_id, member_nick, member_rank, member_rank_name
          FROM member, member_rank
          WHERE member_id = '".$member_id."'
            AND member_rank = member_rank_id
          LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    $row = mysqli_fetch_assoc($result);

    if ($row['member_rank'] == 1) {
        $rank = '<a class="useron" href="/tcg/member/'.$row['member_id'].'"
            title="'.$row['member_rank_name'].'">
            <span class="admin">
              '.$row['member_nick'].'</span></a>';
    } elseif ($row['member_rank'] == 2) {
        $rank = '<a class="useron" href="/tcg/member/'.$row['member_id'].'"
            title="'.$row['member_rank_name'].'">
            <span class="coadmin">
              '.$row['member_nick'].'</span></a>';
    } elseif ($row['member_rank'] == 3) {
        $rank = '<a class="useron" href="/tcg/member/'.$row['member_id'].'"
            title="'.$row['member_rank_name'].'">
            <span class="cm">
              '.$row['member_nick'].'</span></a>';
    } elseif ($row['member_rank'] == 4) {
        $rank = '<a class="useron" href="/tcg/member/'.$row['member_id'].'"
            title="'.$row['member_rank_name'].'">
            <span class="cmod">
              '.$row['member_nick'].'</span></a>';
    } elseif ($row['member_rank'] == 5) {
        $rank = '<a class="useron" href="/tcg/member/'.$row['member_id'].'"
            title="'.$row['member_rank_name'].'">
            '.$row['member_nick'].'</a>';
    } else {
        $rank = '<a class="useron" href="/tcg/member/'.$row['member_id'].'"
            title="'.$row['member_rank_name'].'">
            '.$row['member_nick'].'</a>';
    }
    return $rank;
}

function getCM($hosturl,$member_id) {
    global $link;
    $sql = "SELECT member_id, member_nick
          FROM member, member_rank
          WHERE member_id = '".$member_id."'
            AND member_rank = member_rank_id
          LIMIT 1";
    $result = mysqli_query($link, $sql) OR die(mysqli_error($link));
    $row = mysqli_fetch_assoc($result);

    return '<a href="'.HOST_URL.'/tcg/member/'.$row['member_id'].'">'.$row['member_nick'].'</a>';
}

function card($anz,$member_id,$cat) {
    global $link;
    $sql = "SELECT carddeck_id, carddeck_name, carddeck_count_cards
          FROM sets
          WHERE carddeck_active = 1
          ORDER BY RAND()
          LIMIT ".$anz."";
    $result = mysqli_query($link, $sql);

    mt_srand((double)microtime()*1000000);
    $cards = array();
    $allcards = '';
    while ($row1 = mysqli_fetch_array($result)) {
        $zahl2 = mt_rand(1,$row1['carddeck_count_cards']);
        echo '<img src="../'.$row1['carddeck_name'].','.$zahl2.'.png" alt="'.$row1['carddeck_name'].sprintf("%02s",$zahl2).'" title="'.$row1['carddeck_name'].sprintf("%02s",$zahl2).'" /> ';
        mysqli_query($link, "INSERT INTO member_cards (member_cards_carddeck_id, member_cards_number, member_cards_member_id) VALUES (".$row1['carddeck_id'].",".$zahl2.",".($member_id).")");
        array_push($cards, $row1['carddeck_name'].sprintf("%02s",$zahl2));
    }
    for ($i = 0; $i < $anz; $i++) {
        $allcards .= $cards[$i];
        if($i != ($anz-1)) {
            $allcards .= ', ';
        }
    }

    if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        if ($anz == 1) {
            $karten = 'card';
        } else {
            $karten = 'cards';
        }
        $text = $_SESSION['member_nick'].' has given you the following '.$karten.': '.$allcards;
    } else {
        if ($anz == 1) {
            $karten = 'Karte';
        } else {
            $karten = 'Karten';
        }
        $text = $_SESSION['member_nick'].' hat dir folgende '.$karten.' eingetragen: '.$allcards;
    }

    mysqli_query($link, "INSERT INTO member_log
							 (member_log_member_id,member_log_date,member_log_cat,member_log_text)
							 VALUES
							 (".$member_id.",'".time()."','".$cat."','".$text."')");
    mysqli_query($link, "UPDATE member
							 SET member_cards = member_cards + ".$anz."
							 WHERE member_id = ".$member_id."");
}

function card_tradein($anz,$member_id,$cat,$cardoldid,$cardold) {
    global $link;
    $sql = "SELECT carddeck_id, carddeck_name, carddeck_count_cards
          FROM sets
          WHERE carddeck_active = 1
          ORDER BY RAND()
          LIMIT ".$anz."";
    $result = mysqli_query($link, $sql);
    if (mysql_num_rows($result)) {
        mt_srand((double)microtime()*1000000);
        $cards = array();
        $carddeck_count_cards = 12;
        while ($row1 = mysqli_fetch_assoc($result)) {
            if ($row1['carddeck_count_cards'] > 0) {
                $carddeck_count_cards = $row1['carddeck_count_cards'];
            }
            $zahl2 = mt_rand(1,$carddeck_count_cards);
            echo '<img src="../'.$row1['carddeck_name'].','.$zahl2.'.png" alt="'.$row1['carddeck_name'].sprintf("%02s",$zahl2).'" title="'.$row1['carddeck_name'].sprintf("%02s",$zahl2).'" /> ';
            mysqli_query($link, "UPDATE member_cards
  								 SET member_cards_carddeck_id = ".$row1['carddeck_id'].",
  								 		 member_cards_number = ".$zahl2.",
  										 member_cards_cat = 1
  								 WHERE member_cards_member_id = ".$_SESSION['member_id']."
  								 	 AND member_cards_id = ".$cardoldid."
  								 LIMIT 1");
            array_push($cards, $row1['carddeck_name'].sprintf("%02s",$zahl2));
        }
        $allcards = '';
        for ($i = 0; $i < $anz; $i++) {
            $allcards .= $cards[$i];
            if ($i != ($anz-1)) {
                $allcards .= ', ';
            }
        }
        if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
            if ($anz == 1) {
                $karten = 'card';
            } else {
                $karten = 'cards';
            }
            $text = 'You changed '.$cardold.' for '.$allcards.'.';
        } else {
            if ($anz == 1) {
                $karten = 'Karte';
            } else {
                $karten = 'Karten';
            }
            $text = 'Du hast '.$cardold.' gegen '.$allcards.' eingetauscht.';
        }
        mysqli_query($link, "INSERT INTO member_log
  							 (member_log_member_id,member_log_date,member_log_cat,member_log_text)
  							 VALUES
  							 (".$member_id.",'".time()."','".$cat."','".$text."')");
    }
}

function card_master($anz,$member_id,$cat,$setname,$deck,$language) {
    global $link;
    mysqli_query($link, "DELETE FROM member_wishlist
               WHERE member_wishlist_member_id = ".$member_id."
                AND member_wishlist_carddeck_id = ".$deck."
               LIMIT 1");
    mysqli_query($link, "DELETE FROM member_cards WHERE member_cards_member_id = ".$member_id." AND member_cards_carddeck_id = ".$deck." AND member_cards_cat = 2");
    mysqli_query($link, "INSERT INTO member_master (member_master_member_id,member_master_carddeck_id,member_master_date) VALUES (".$member_id.",".$deck.",".time().")");

    if ($language == 'en') {
        $text = 'You mastered '.strtoupper($setname).' and got '.$anz.' wish';
    } else {
        $text = 'Du hast '.strtoupper($setname).' gemastert und '.$anz.' Wish erhalten';
    }
    mysqli_query($link, "INSERT INTO member_log
							 (member_log_member_id,member_log_date,member_log_cat,member_log_text)
							 VALUES
							 (".$member_id.",'".time()."','".$cat."','".$text."')");
    mysqli_query($link, "UPDATE member
							 SET member_master = member_master + 1,
                   member_wish = member_wish + ".$anz."
							 WHERE member_id = ".$member_id."
               LIMIT 1");
}

function card_lvlup($anz,$member_id,$cat) {
    global $link;
    $sql = "SELECT carddeck_id, carddeck_name, carddeck_count_cards
          FROM sets
          WHERE carddeck_active = 1
          ORDER BY RAND()
          LIMIT ".$anz."";
    $result = mysqli_query($link, $sql);

    mt_srand((double)microtime()*1000000);
    $cards = array();
    $allcards = '';

    while ($row1 = mysqli_fetch_array($result)) {
        $zahl2 = mt_rand(1,$row1['carddeck_count_cards']);
        echo '<img src="../'.$row1['carddeck_name'].','.$zahl2.'.png" alt="'.$row1['carddeck_name'].sprintf("%02s",$zahl2).'" title="'.$row1['carddeck_name'].sprintf("%02s",$zahl2).'" /> ';
        mysqli_query($link, "INSERT INTO member_cards (member_cards_carddeck_id, member_cards_number, member_cards_member_id) VALUES (".$row1['carddeck_id'].",".$zahl2.",".($member_id).")");
        array_push($cards, $row1['carddeck_name'].sprintf("%02s",$zahl2));
    }
    for ($i = 0; $i < $anz; $i++) {
        $allcards .= $cards[$i];
        if($i != ($anz-1)) {
            $allcards .= ', ';
        }
    }
    if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        if($anz == 1) {
            $karten = 'card';
        } else {
            $karten = 'cards';
        }
        $text = 'You have reached a new level and got the following '.$karten.': '.$allcards;
    } else {
        if($anz == 1) {
            $karten = 'Karte';
        } else {
            $karten = 'Karten';
        }
        $text = 'Du hast ein neues Level erreicht und folgende '.$karten.' erhalten: '.$allcards;
    }
    mysqli_query($link, "UPDATE member SET member_points = member_points + 5, member_points_max = member_points_max + 5 WHERE member_id = ".$_SESSION['member_id']."");
    mysqli_query($link, "INSERT INTO member_log
							 (member_log_member_id,member_log_date,member_log_cat,member_log_text)
							 VALUES
							 (".$member_id.",'".time()."','".$cat."','".$text."')");
    mysqli_query($link, "UPDATE member
							 SET member_cards = member_cards + ".$anz."
							 WHERE member_id = ".$member_id."");
    mysqli_query($link, "INSERT INTO member_log_points
               (member_log_points_member_id,member_log_points_date,member_log_points_cat,member_log_points_points)
               VALUES
               (".$member_id.",'".time()."','Master','5')");
}

function card_game($anz,$member_id,$cat) {
    global $link;
    $sql = "SELECT carddeck_id, carddeck_name, carddeck_count_cards
          FROM sets
          WHERE carddeck_active = 1
          ORDER BY RAND()
          LIMIT ".$anz."";
    $result = mysqli_query($link, $sql);

    mt_srand((double)microtime()*1000000);
    $cards = array();
    $allcards = '';

    while ($row1 = mysqli_fetch_array($result)) {
        $zahl = 12;
        if($row1['carddeck_count_cards'] && $row1['carddeck_count_cards'] != 0) {
            $zahl = $row1['carddeck_count_cards'];
        }
        $zahl2 = mt_rand(1,$zahl);
        echo '<img src="../'.$row1['carddeck_name'].','.$zahl2.'.png" alt="'.strtoupper($row1['carddeck_name']).sprintf("%02s",$zahl2).'" title="'.strtoupper($row1['carddeck_name']).sprintf("%02s",$zahl2).'" /> ';
        mysqli_query($link, "INSERT INTO member_cards (member_cards_carddeck_id, member_cards_number, member_cards_member_id) VALUES (".$row1['carddeck_id'].",".$zahl2.",".($member_id).")");
        array_push($cards, $row1['carddeck_name'].sprintf("%02s",$zahl2));
    }
    for ($i = 0; $i < $anz; $i++) {
        $allcards .= $cards[$i];
        if($i != ($anz-1)) {
            $allcards .= ', ';
        }
    }
    if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        if($anz == 1) {
            $karten = 'card';
        } else {
            $karten = 'cards';
        }
        $text = 'You won '.$anz.' '.$karten.': '.$allcards;
    } else {
        if($anz == 1) {
            $karten = 'Karte';
        } else {
            $karten = 'Karten';
        }
        $text = 'Du hast '.$anz.' '.$karten.' gewonnen: '.$allcards;
    }
    mysqli_query($link, "INSERT INTO member_log
							 (member_log_member_id,member_log_date,member_log_cat,member_log_text)
							 VALUES
							 (".$member_id.",'".time()."','".$cat."','".$text."')");
    mysqli_query($link, "UPDATE member
							 SET member_cards = member_cards + ".$anz."
							 WHERE member_id = ".$member_id."");
}

function card_exchange($anz,$member_id,$what_cat,$points,$cat) {
    global $link;
    if ($cat == 'random') {
        $stringcat = "";
    } else {
        $stringcat = "AND carddeck_cat = '".$cat."'";
    }

    $sql = "SELECT carddeck_id, carddeck_name, carddeck_count_cards
          FROM sets
          WHERE carddeck_active = 1
            ".$stringcat."
          ORDER BY RAND()
          LIMIT ".$anz."";
    $result = mysqli_query($link, $sql);

    $cards = array();
    $allcards = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $zahl2 = mt_rand(1,$row['carddeck_count_cards']);
        mysqli_query($link, "INSERT INTO member_cards (member_cards_carddeck_id, member_cards_number, member_cards_member_id) VALUES (".$row['carddeck_id'].",".$zahl2.",".($member_id).")");
        array_push($cards, $row['carddeck_name'].sprintf("%02s",$zahl2));
    }

    for ($i = 0; $i < $anz; $i++) {
        $allcards .= $cards[$i];
        if($i != ($anz-1)) {
            $allcards .= ', ';
        }
    }
    if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        $text = 'Exchanged cardpack for '.number_format($points, 0, '','.').' points: '.$allcards;
    } else {
        $text = 'Kartenpaket f&uuml;r '.number_format($points, 0, '','.').' Punkte ertauscht: '.$allcards;
    }
    mysqli_query($link, "INSERT INTO member_log
							 (member_log_member_id,member_log_date,member_log_cat,member_log_text)
							 VALUES
							 (".$member_id.",'".time()."','".$what_cat."','".$text."')");
    mysqli_query($link, "UPDATE member
							 SET member_cards = member_cards + ".$anz.",
                   member_points = member_points - ".$points."
							 WHERE member_id = ".$member_id."
               LIMIT 1");
}

function money($zahl,$member_id,$cat) {
    global $link;
    mysqli_query($link, "UPDATE member SET member_grind = member_grind + ".$zahl." WHERE member_id = ".$_SESSION['member_id']."");
    if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        $text = 'You won '.$zahl.' '.TCG_CURRENCY;
    } else {
        $text = 'Du hast '.$zahl.' '.TCG_CURRENCY.' gewonnen';
    }
    mysqli_query($link, "INSERT INTO member_log
							 (member_log_member_id,member_log_date,member_log_cat,member_log_text)
							 VALUES
							 (".$member_id.",'".time()."','".$cat."','".$text."')");
}

function getPoints($zahl,$member_id,$cat) {
    global $link;
    mysqli_query($link, "UPDATE member SET member_points = member_points + ".$zahl.", member_points_max = member_points_max + ".$zahl." WHERE member_id = ".$_SESSION['member_id']."");
    if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        if($zahl > 1) {
            $point_text = 'points';
        } else {
            $point_text = 'point';
        }
        $text = 'You got '.$zahl.' '.$point_text;
    } else {
        if($zahl > 1) {
            $point_text = 'Punkte';
        } else {
            $point_text = 'Punkt';
        }
        $text = 'Du hast '.$zahl.' '.$point_text.' erhalten';
    }
    mysqli_query($link, "INSERT INTO member_log
							 (member_log_member_id,member_log_date,member_log_cat,member_log_text)
							 VALUES
							 (".$member_id.",'".time()."','".$cat."','".$text."')");

    mysqli_query($link, "INSERT INTO member_log_points
               (member_log_points_member_id,member_log_points_date,member_log_points_cat,member_log_points_points)
               VALUES
               (".$member_id.",'".time()."','".$cat."','".$zahl."')");
}


function pagelog($perPage, $catname, $member_id) {
    global $link;
    if (!isset($_GET['go'])) {
        $_GET['go'] = 1;
    }
    if (isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        $firsttext = 'first';
        $lasttext = 'last';
    } else {
        $firsttext = 'erste';
        $lasttext = 'letzte';
    }
    $thema = "WHERE member_log_member_id = '".$member_id."' AND member_log_active = 1";
    $select= "SELECT * FROM member_log ".$thema."";

    $query = mysqli_query($link, $select);
    $p = "3";
    $total = mysqli_num_rows($query);
    $seiten = ceil($total / $perPage);

    // ------------------------------------------------------------------------
    if ($seiten > 1) {
        if ($_GET['go'] != "" OR !isset($_GET['go'])) {
            $go = 1;
        }
        if ($_GET['go'] <= 0 OR $_GET['go'] > $seiten) {
            $go = 1;
        } else {
            $go = mysqli_real_escape_string($link, $_GET['go']);
        }
        $links = array();
        if (($go - $p) < 1) {
            $davor = $go - 1;
        } else {
            $davor = $p;
        }
        if (($go + $p) > $seiten) {
            $danach = $seiten - $go;
        } else {
            $danach = $p;
        }
        $off = ($go - $davor);
        if ($go- $davor > 1) {
            $first = 1;
            $links[] = "<a href='?id=".$member_id."&log&go=".$first."' class=\"kommentare\">&laquo; ".$firsttext." ...</a>\n";
        }
        if ($go != 1) {
            $prev = $go-1;
        }
        for ($i = $off; $i <= ($go + $danach); $i++) {
            if ($i != $go) {
                $links[] = "<a href='?id=".$member_id."&log&go=".$i."' class=\"kommentare\">$i</a>\n";
            }
            elseif ($i == $seiten) {
                $links[] = " <span class=\"font-weight-bold\">$i</span> \n";
            }
            elseif ($i == $go) {
                $links[] = " <span class=\"font-weight-bold\">$i</span> \n";
            }
        }
        if ($go != $seiten) {
            $next = $go+1;
        }
        if ($seiten - $go - $p > 0 ) {
            $last = $seiten;
            $links[] = "<a href='?id=".$member_id."&log&go=".$last."' class=\"kommentare\">... ".$lasttext." &raquo;</a>\n";
        }
        $start = ($go-1) * $perPage;
        $link_string = implode(" ", $links);

        echo "<br><center>";
        echo $link_string;
        echo "</center><br>";
    }
}

function cardsForMemberCardsearch($g_mid) {
    global $link;
    $sql_ck = "SELECT carddeck_name, mc_me.member_cards_number, mc_me.member_cards_id, mc_me.member_cards_carddeck_id, carddeck_count_cards,
                   (SELECT COUNT(member_wishlist_carddeck_id)
                    FROM member_wishlist
                    WHERE member_wishlist_member_id = '".$_SESSION['member_id']."'
                      AND member_wishlist_carddeck_id = carddeck_id
                    ) AS haveWishAnz,
                   (SELECT COUNT(member_cards_carddeck_id)
                    FROM member_cards
                    WHERE member_cards_member_id = '".$g_mid."'
                      AND member_cards_carddeck_id = carddeck_id
                      AND (member_cards_cat = 1
                        OR member_cards_cat = 2)
                    ) AS haveCollectAnz
            FROM member_cards mc_me
            INNER JOIN member_wishlist mw_other
                    ON mc_me.member_cards_carddeck_id = mw_other.member_wishlist_carddeck_id
            JOIN sets
                    ON mc_me.member_cards_carddeck_id = carddeck_id
            WHERE mc_me.member_cards_member_id = '".$_SESSION['member_id']."'
              AND mw_other.member_wishlist_member_id = '".$g_mid."'
              AND mc_me.member_cards_cat = 3
              AND mc_me.member_cards_active = 1
              AND mw_other.member_wishlist_carddeck_id NOT IN
              (
              SELECT member_cards_carddeck_id
              FROM member_cards
              WHERE (member_cards_cat = 1
                  OR member_cards_cat = 2)
                 AND member_cards_member_id = '".$g_mid."'
                 AND member_cards_active = 1
                 AND member_cards_number = mc_me.member_cards_number
              )
              AND mc_me.member_cards_number NOT IN
              (
              SELECT member_cards_number
              FROM member_cards
              WHERE (member_cards_cat = 1
                  OR member_cards_cat = 2)
                AND member_cards_member_id = '".$g_mid."'
                AND member_cards_active = 1
                AND member_cards_carddeck_id = mc_me.member_cards_carddeck_id
              )
            GROUP BY mc_me.member_cards_id
            ORDER BY carddeck_name, mc_me.member_cards_number ASC";
    $result_ck = mysqli_query($link, $sql_ck);
    if (mysqli_num_rows($result_ck)) {
        return true;
    } else {
        return false;
    }
}

function mobile_device() {
    $tablet_browser = 0;
    $mobile_browser = 0;

    if(isset($_SERVER['HTTP_USER_AGENT'])) {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }

        if (isset($_SERVER['HTTP_ACCEPT']) && (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0 || isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']))) {
            $mobile_browser++;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');

        if (in_array($mobile_ua,$mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }
    }

    if ($tablet_browser > 0) {
        // do something for tablet devices
        //print 'is tablet';
        return 'tablet';
    } else if ($mobile_browser > 0) {
        // do something for mobile devices
        //print 'is mobile';
        return 'mobile';
    } else {
        // do something for everything else
        //print 'is desktop';
        return 'desktop';
    }
}

function paging_member($catname,$anza,$g_mid,$aa,$page,$downpage,$uppage,$letter = '') {
    $letterstring = '';
    if($letter != '') {
        $letterstring = '&amp;letter='.$letter;
    }
    if(isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        $first = 'first';
        $last = 'last';
    } else {
        $first = 'erste';
        $last = 'letzte';
    }

    echo '<div class="middle" style="padding-top:5px;">';
    echo '<a href="member/'.$g_mid.'&amp;cat='.$catname.$letterstring.'&amp;page=1">&laquo; '.$first.'</a>&nbsp;&nbsp;';
    for ($i=$downpage;$i<=$uppage;$i++) {
        if ($i==$page) {
            echo '<a class="button" href="member/'.$g_mid.'&amp;cat='.$catname.$letterstring.'&amp;page='.$i.'">&nbsp;<span class="bold">'.$i.'</span>&nbsp;</a> ';
        } else {
            echo '<a class="button" href="member/'.$g_mid.'&amp;cat='.$catname.$letterstring.'&amp;page='.$i.'">&nbsp;'.$i.'&nbsp;</a> ';
        }
    }
    echo '&nbsp;&nbsp;<a href="member/'.$g_mid.'&amp;cat='.$catname.$letterstring.'&amp;page='.$aa.'">'.$last.' &raquo;</a><br />&nbsp;<br />';
    echo '</div>';
}

function paging_carddecks($which,$anza,$aa,$page,$downpage,$uppage,$sort = '2', $show = 'normal') {
    if(isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        $first = 'first';
        $last = 'last';
    } else {
        $first = 'erste';
        $last = 'letzte';
    }
    echo '<div class="middle" style="padding-top:5px;">';
    echo '<a href="carddecks.php?which='.$which.'&amp;sort='.$sort.'&amp;show='.$show.'&amp;page=1">&laquo; '.$first.'</a>&nbsp;&nbsp;';
    for ($i=$downpage;$i<=$uppage;$i++) {
        if ($i==$page) {
            echo '<a class="button" href="carddecks.php?which='.$which.'&amp;sort='.$sort.'&amp;show='.$show.'&amp;page='.$i.'">&nbsp;<span class="bold">'.$i.'</span>&nbsp;</a> ';
        } else {
            echo '<a class="button" href="carddecks.php?which='.$which.'&amp;sort='.$sort.'&amp;show='.$show.'&amp;page='.$i.'">&nbsp;'.$i.'&nbsp;</a> ';
        }
    }
    echo '&nbsp;&nbsp;<a href="carddecks.php?which='.$which.'&amp;sort='.$sort.'&amp;show='.$show.'&amp;page='.$aa.'">'.$last.' &raquo;</a><br />&nbsp;<br />';
    echo '</div>';
}

function paging_admin_decks($anza,$aa,$page,$downpage,$uppage,$show = 'all') {
    if(isset($_SESSION['language']) && $_SESSION['language'] == 'en') {
        $first = 'first';
        $last = 'last';
    } else {
        $first = 'erste';
        $last = 'letzte';
    }
    echo '<div class="middle" style="padding-top:5px;">';
    echo '<a href="editdeck.php?show='.$show.'&amp;page=1">&laquo; '.$first.'</a>&nbsp;&nbsp;';
    for ($i=$downpage;$i<=$uppage;$i++) {
        if ($i==$page) {
            echo '<a class="button" href="editdeck.php?show='.$show.'&amp;page='.$i.'">&nbsp;<span class="bold">'.$i.'</span>&nbsp;</a> ';
        } else {
            echo '<a class="button" href="editdeck.php?show='.$show.'&amp;page='.$i.'">&nbsp;'.$i.'&nbsp;</a> ';
        }
    }
    echo '&nbsp;&nbsp;<a href="editdeck.php?show='.$show.'&amp;page='.$aa.'">'.$last.' &raquo;</a><br />&nbsp;<br />';
    echo '</div>';
}

?>
