<?php
if (isset($_SESSION['member_rank'])) {
    global $link;
    $breadcrumb = array(
        '/' => 'Home',
        '/memberarea' => TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea'],
    );
    breadcrumb($breadcrumb);
    title(TRANSLATIONS[$GLOBALS['language']]['general']['text_memberarea']);
    ?>
    <div class="row mb-5 memberarea">
        <div class="col col-12 col-md-6 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <i class="fas fa-address-card fa-2x mr-3"></i>
                        <div class="media-body">
                           <a href="<?php echo HOST_URL; ?>/member/<?php echo $_SESSION['member_id']; ?>"><?php echo TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_view']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <i class="fas fa-user-cog fa-2x mr-3"></i>
                        <div class="media-body">
                           <a href="<?php echo HOST_URL; ?>/memberarea/changeprofile"><?php echo TRANSLATIONS[$GLOBALS['language']]['member']['text_profile_change']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <i class="fas fa-list fa-2x mr-3"></i>
                        <div class="media-body">
                            <a href="<?php echo HOST_URL; ?>/memberarea/log"><?php echo TRANSLATIONS[$GLOBALS['language']]['member']['text_view_log']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <i class="fas fa-search fa-2x mr-3"></i>
                        <div class="media-body">
                            <a href="<?php echo HOST_URL; ?>/memberarea/search"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_cardsearch']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <i class="fas fa-store-alt fa-2x mr-3"></i>
                        <div class="media-body">
                            <a href="<?php echo HOST_URL; ?>/memberarea/shop"><?php echo TRANSLATIONS[$GLOBALS['language']]['member']['text_shop']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6 mb-2">
            <?php
            $sql_update = "SELECT cardupdate_id
                           FROM cardupdate
                           LIMIT 1";
            $result_update = mysqli_query($link, $sql_update) OR die(mysqli_error($link));
            $row_update = mysqli_fetch_assoc($result_update);
            if (mysqli_num_rows($result_update)) {
                ?>
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <i class="fas fa-gifts fa-2x mr-3"></i>
                            <div class="media-body">
                                <a href="<?php echo HOST_URL; ?>/memberarea/update"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_cardupdate']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
} else {
    show_no_access_message_with_breadcrumb();
}
?>