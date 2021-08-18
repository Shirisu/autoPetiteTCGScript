<?php
if (isset($_SESSION['member_rank'])) {
    $member_id = $_SESSION['member_id'];

    if (isset($game_id)) {
        global $link;

        $sql_mining_game = "SELECT games_id, games_name, games_interval
                           FROM games
                           WHERE games_id = '".$game_id."'
                             AND games_status = '1'
                           LIMIT 1";
        $result_mining_game = mysqli_query($link, $sql_mining_game) OR die(mysqli_error($link));
        if (mysqli_num_rows($result_mining_game)) {
            $row_mining_game = mysqli_fetch_assoc($result_mining_game);
            $game_id = $row_mining_game['games_id'];
            $game_name = $row_mining_game['games_name'];

            $breadcrumb = array(
                '/' => 'Home',
                '/games' => TRANSLATIONS[$GLOBALS['language']]['general']['text_games'],
                '/games/mining/' . $game_id => $game_name,
            );
            breadcrumb($breadcrumb);
            title($game_name);

            $can_play = true;
            $sql_last_played = "SELECT member_game_played_last_played
                                FROM member_game_played
                                WHERE member_game_played_member_id = '" . $member_id . "'
                                  AND member_game_played_game_id = '" . $game_id . "'
                                ORDER BY member_game_played_id DESC
                                LIMIT 1";
            $result_last_played = mysqli_query($link, $sql_last_played) OR die(mysqli_error($link));
            $row_last_played = mysqli_fetch_assoc($result_last_played);
            if (mysqli_num_rows($result_last_played)) {
                $next_game_time = $row_last_played['member_game_played_last_played'] + $row_mining_game['games_interval'];

                if ($next_game_time <= time()) {
                    $can_play = true;
                } else {
                    $can_play = false;
                }
            } else {
                $can_play = true;
            }
            ?>
            <?php
            if ($can_play) {
                if (isset($_POST['nonce'])) {
                    $nonce = mysqli_real_escape_string($link, $_POST['nonce']);
                    $cards = mysqli_real_escape_string($link, $_POST['cards']);

                        if (TCG_CURRENCY_USE) {
                            $amount_currency = $nonce;
                            insert_currency($member_id, $amount_currency);
                            $inserted_currency_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win'] . ': '.$amount_currency.' '.TCG_CURRENCY;
                            insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_currency_text, $member_id);

                            alert_box(
                                TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($lucky_choice).
                                '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_win'].'!<br />'.$amount_currency.' '.TCG_CURRENCY
                                , 'success');
                            if ($cards > 0) {
                              insert_cards($member_id, $cards);
                              $inserted_cards_text = TRANSLATIONS[$GLOBALS['language']]['games']['text_game_log_win'] . ': ' . implode(', ', $_SESSION['insert_cards']);
                              insert_log(TRANSLATIONS[$GLOBALS['language']]['general']['text_games'].' - '.$game_name, $inserted_cards_text, $member_id);

                              alert_box(
                                  TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice'].': '.strtoupper($lucky_choice).
                                  '. '.TRANSLATIONS[$GLOBALS['language']]['games']['text_game_choice_win'].'!<br />1 '.TRANSLATIONS[$GLOBALS['language']]['general']['text_card'].': '.implode(', ',$_SESSION['insert_cards']).
                                  '<br />'.
                                  $_SESSION['insert_cards_images']
                                  , 'success');
                            }
                        }

                    insert_game_played($member_id, $game_id);
                } else {
                    ?>
                        <div class="row mb-5 games-lucky-container text-center">
                            <div class="col col-12 mb-3 text-center">
                                <?php echo TRANSLATIONS[$GLOBALS['language']]['games']['text_lucky_game']; ?>
                            </div>
                            <div class="col col-12 mb-2 text-center">



<hr>
<p>Click on the 'Start Mining' button to start the mining simulation</p>
<div style="border:1px solid blue; width:200px; height:100px; text-align:center;padding:0"><h1 id="dice" style="font-size:300%">1</h1></div><Br>
<div style="border:1px solid blue; width:300px; height:100px; text-align:center;padding:0"><h2 id="nonce" style="font-size:150%">0 <?php echo TCG_CURRENCY ?> Mined</h2></div>
<br>
<!-- <div style="border:1px solid blue; width:300px; height:50px; text-align:center;padding:0"><h1 id="msg" style="font-size:100%">Mining Waiting</h1></div><br> -->
<br>
<button onclick="StartMine()">Start Mining</button>
<button onclick="StopMine()">Stop Mining</button>
<hr>
<script>
const target=12999
var nonce
var cards

nonce=0;
cards=5;

function StartMine()
{
// document.getElementById("msg").innerHTML="Miner Running";
 document.getElementById("nonce").innerHTML="Calculating...";
MyVar=setInterval(mining,150)
}

function mining()
{
var ranNum = Math.floor( 1 + Math.random() * 1000000 );
nonce=nonce+1;
document.getElementById("dice").innerHTML = ranNum;
	 if (ranNum<target)
	 
	 {  StopMine();
           if (cards<0){
             cards=0;
           } else {
             cards=cards-1;
           };

	 document.getElementById("nonce").innerHTML ='Mined Value'+'='+nonce;
         document.mining.nonce.value = nonce;	
         document.mining.cards.value = cards;
	 document.getElementById("details").innerHTML ='Claim '+nonce+' <?php echo TCG_CURRENCY ?> and '+cards+' Cards';}
}
function StopMine()
{clearInterval(MyVar);}

</script>


                    <form name=mining action="<?php echo HOST_URL; ?>/games/mining/<?php echo $game_id; ?>" method="post">
                                    <button type="submit" name="submit">
                                           <h3 id=details>No Mined <?php echo TCG_CURRENCY ?> Yet</h3>
                                    </button>
                                    <input type="hidden" name="nonce" value="0">
                                    <input type="hidden" name="cards" value="0">
                            </div>
                        </div>
                    </form>

<?php
}
}
}
}
}
?>
