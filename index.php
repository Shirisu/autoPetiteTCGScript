<?php
// Include router class
require_once("inc/class.Route.php");

session_start();
require_once("inc/connection.php");
require_once("inc/constants.php");
require_once("inc/function.php");
require_once("inc/_translations.php");
require_once("inc/_language.php");
require_once("inc/_member_online_status.php");
require_once("inc/header.php");

/**
 * add base route (startpage) - don't change it
 */
Route::add("/",function() {
    require_once("main/home.php");
});

/**
 * routes you shouldn't change
 */
// administration
Route::add("/administration",function() {
    require_once("admin/administration.php");
});
Route::add("/administration/editmember/([a-z+].*)",function($rank) {
    require_once("admin/member_edit.php");
});
Route::add("/administration/editmember/([0-9+].*)",function($member_id) {
    require_once("admin/member_edit.php");
});
Route::add("/administration/editmember/([0-9+].*)",function($member_id) {
    require_once("admin/member_edit.php");
}, "post");
Route::add("/administration/deletemember/([0-9+].*)",function($member_id) {
    require_once("admin/member_delete.php");
});
Route::add("/administration/deletemember/([0-9+].*)",function($member_id) {
    require_once("admin/member_delete.php");
}, "post");
Route::add("/administration/distributecards",function() {
    require_once("admin/member_card_distribute.php");
});
Route::add("/administration/distributecards",function() {
    require_once("admin/member_card_distribute.php");
}, "post");
Route::add("/administration/distributecurrency",function() {
    require_once("admin/member_currency_distribute.php");
});
Route::add("/administration/distributecurrency",function() {
    require_once("admin/member_currency_distribute.php");
}, "post");
Route::add("/administration/distributewish",function() {
    require_once("admin/member_wish_distribute.php");
});
Route::add("/administration/distributewish",function() {
    require_once("admin/member_wish_distribute.php");
}, "post");
Route::add("/administration/addcategory",function() {
    require_once("admin/category_add.php");
});
Route::add("/administration/addcategory",function() {
    require_once("admin/category_add.php");
}, "post");
Route::add("/administration/editcategory",function() {
    require_once("admin/category_edit.php");
});
Route::add("/administration/editcategory",function() {
    require_once("admin/category_edit.php");
}, "post");
Route::add("/administration/editcategory/([0-9+].*)",function($category_id) {
    require_once("admin/category_edit.php");
});
Route::add("/administration/editcategory/([0-9+].*)",function($category_id) {
    require_once("admin/category_edit.php");
}, "post");
Route::add("/administration/addsubcategory",function() {
    require_once("admin/subcategory_add.php");
});
Route::add("/administration/addsubcategory",function() {
    require_once("admin/subcategory_add.php");
}, "post");
Route::add("/administration/editsubcategory",function() {
    require_once("admin/subcategory_edit.php");
});
Route::add("/administration/editsubcategory",function() {
    require_once("admin/subcategory_edit.php");
}, "post");
Route::add("/administration/editsubcategory/([0-9+].*)",function($subcategory_id) {
    require_once("admin/subcategory_edit.php");
});
Route::add("/administration/editsubcategory/([0-9+].*)",function($subcategory_id) {
    require_once("admin/subcategory_edit.php");
}, "post");
Route::add("/administration/deletesubcategory/([0-9+].*)",function($subcategory_id) {
    require_once("admin/subcategory_delete.php");
});
Route::add("/administration/deletesubcategory/([0-9+].*)",function($subcategory_id) {
    require_once("admin/subcategory_delete.php");
}, "post");
Route::add("/administration/addcarddeck",function() {
    require_once("admin/carddeck_add.php");
});
Route::add("/administration/addcarddeck",function() {
    require_once("admin/carddeck_add.php");
}, "post");
Route::add("/administration/editcarddeck",function() {
    require_once("admin/carddeck_edit.php");
});
Route::add("/administration/editcarddeck",function() {
    require_once("admin/carddeck_edit.php");
}, "post");
Route::add("/administration/editcarddeck/([0-9+].*)",function($carddeck_id) {
    require_once("admin/carddeck_edit.php");
});
Route::add("/administration/editcarddeck/([0-9+].*)",function($carddeck_id) {
    require_once("admin/carddeck_edit.php");
}, "post");
Route::add("/administration/addnews",function() {
    require_once("admin/news_add.php");
});
Route::add("/administration/addnews",function() {
    require_once("admin/news_add.php");
}, "post");
Route::add("/administration/editnews",function() {
    require_once("admin/news_edit.php");
});
Route::add("/administration/editnews",function() {
    require_once("admin/news_edit.php");
}, "post");
Route::add("/administration/editnews/([0-9+].*)",function($news_id) {
    require_once("admin/news_edit.php");
});
Route::add("/administration/editnews/([0-9+].*)",function($news_id) {
    require_once("admin/news_edit.php");
}, "post");
Route::add("/administration/addcardupdate",function() {
    require_once("admin/cardupdate_add.php");
});
Route::add("/administration/addcardupdate",function() {
    require_once("admin/cardupdate_add.php");
}, "post");
Route::add("/administration/addgame",function() {
    require_once("admin/games_add.php");
});
Route::add("/administration/addgame",function() {
    require_once("admin/games_add.php");
}, "post");
Route::add("/administration/editgame",function() {
    require_once("admin/games_edit.php");
});
Route::add("/administration/editgame",function() {
    require_once("admin/games_edit.php");
}, "post");
Route::add("/administration/editgame/([0-9+].*)",function($games_id) {
    require_once("admin/games_edit.php");
});
Route::add("/administration/editgame/([0-9+].*)",function($games_id) {
    require_once("admin/games_edit.php");
}, "post");
Route::add("/administration/addlevel",function() {
    require_once("admin/level_add.php");
});
Route::add("/administration/addlevel",function() {
    require_once("admin/level_add.php");
}, "post");
Route::add("/administration/editlevel",function() {
    require_once("admin/level_edit.php");
});
Route::add("/administration/editlevel",function() {
    require_once("admin/level_edit.php");
}, "post");
Route::add("/administration/editlevel/([0-9+].*)",function($level_id) {
    require_once("admin/level_edit.php");
});
Route::add("/administration/editlevel/([0-9+].*)",function($level_id) {
    require_once("admin/level_edit.php");
}, "post");

// news
Route::add("/news/([0-9+].*)",function($news_id) {
    require_once("main/news.php");
});

// carddecks
Route::add("/carddecks",function() {
    require_once("tcg/carddeck/carddecks.php");
});
Route::add("/carddecks/all",function() {
    require_once("tcg/carddeck/carddecks.php");
});
Route::add("/carddecks/([0-9+].*)/([0-9+].*)",function($category_id, $sub_category_id) {
    require_once("tcg/carddeck/carddecks.php");
});
Route::add("/carddecks/([0-9+].*)",function($category_id) {
    require_once("tcg/carddeck/carddecks.php");
});
Route::add("/carddeck/([a-z0-9+].*)",function($carddeck_name) {
    require_once("tcg/carddeck/carddeck_page.php");
});

// member
Route::add("/member",function() {
    require_once("tcg/member/member.php");
});
Route::add("/member/([0-9+].*)/([a-z+].*)",function($member_id, $category) {
    if ($category == 'trade') {
        require_once("tcg/member/member_page_trade.php");
    } elseif ($category == 'collect') {
        require_once("tcg/member/member_page_collect.php");
    } elseif ($category == 'master') {
        require_once("tcg/member/member_page_master.php");
    } elseif ($category == 'wishlist') {
        require_once("tcg/member/member_page_wishlist.php");
    } else {
        require_once("tcg/member/member_page.php");
    }
});
Route::add("/member/([0-9+].*)",function($member_id) {
    require_once("tcg/member/member_page.php");
});
Route::add("/memberarea/changeprofile",function() {
    require_once("tcg/memberarea/memberarea_changeprofile.php");
});
Route::add("/memberarea/changeprofile",function() {
    require_once("tcg/memberarea/memberarea_changeprofile.php");
}, "post");
Route::add("/memberarea/log",function() {
    require_once("tcg/memberarea/memberarea_log.php");
});
Route::add("/memberarea/search",function() {
    require_once("tcg/memberarea/memberarea_search.php");
});
Route::add("/memberarea/shop",function() {
    require_once("tcg/memberarea/memberarea_shop.php");
});
Route::add("/memberarea/shop",function() {
    require_once("tcg/memberarea/memberarea_shop.php");
}, "post");
Route::add("/memberarea/update",function() {
    require_once("tcg/memberarea/memberarea_update.php");
});
Route::add("/memberarea/update",function() {
    require_once("tcg/memberarea/memberarea_update.php");
}, "post");
Route::add("/memberarea/tradein",function() {
    require_once("tcg/memberarea/memberarea_tradein.php");
});
Route::add("/memberarea/tradein",function() {
    require_once("tcg/memberarea/memberarea_tradein.php");
}, "post");
Route::add("/memberarea",function() {
    require_once("tcg/memberarea/memberarea.php");
});
Route::add("/cards/([a-z+].*)",function($category) {
    if ($category == 'new') {
        require_once("tcg/cards/cards_new.php");
    } elseif ($category == 'trade') {
        require_once("tcg/cards/cards_trade.php");
    } elseif ($category == 'collect') {
        require_once("tcg/cards/cards_collect.php");
    } elseif ($category == 'master') {
        require_once("tcg/cards/cards_master.php");
    } else {
        require_once("tcg/cards/cards_new.php");
    }
});
Route::add("/cards/([a-z+].*)",function($category) {
    if ($category == 'new') {
        require_once("tcg/cards/cards_new.php");
    } elseif ($category == 'trade') {
        require_once("tcg/cards/cards_trade.php");
    } elseif ($category == 'collect') {
        require_once("tcg/cards/cards_collect.php");
    } elseif ($category == 'master') {
        require_once("tcg/cards/cards_master.php");
    } else {
        require_once("tcg/cards/cards_new.php");
    }
}, "post");
Route::add("/cards",function() {
    require_once("tcg/cards/cards_new.php");
});
Route::add("/message/delete/allsystemmessages",function() {
    $action = 'delete_all_systemmessages';
    $message_box_type = 'inbox';
    require_once("tcg/message/message.php");
});
Route::add("/message/delete/([0-9+].*)",function($message_id) {
    $action = 'delete';
    $message_box_type = 'inbox';
    require_once("tcg/message/message.php");
});
Route::add("/message/reply/([0-9+].*)",function($message_id) {
    require_once("tcg/message/message_write.php");
});
Route::add("/message/reply/([0-9+].*)",function($message_id) {
    require_once("tcg/message/message_write.php");
}, "post");
Route::add("/message/write/([0-9+].*)",function($receiver_id) {
    require_once("tcg/message/message_write.php");
});
Route::add("/message/write",function() {
    require_once("tcg/message/message_write.php");
});
Route::add("/message/write",function() {
    require_once("tcg/message/message_write.php");
}, "post");
Route::add("/message/([0-9+].*)",function($message_id) {
    require_once("tcg/message/message.php");
});
Route::add("/message",function() {
    $message_box_type = 'inbox';
    require_once("tcg/message/message.php");
});
Route::add("/message/inbox",function() {
    $message_box_type = 'inbox';
    require_once("tcg/message/message.php");
});
Route::add("/message/outbox",function() {
    $message_box_type = 'outbox';
    require_once("tcg/message/message.php");
});
Route::add("/trade",function() {
    $trade_box_type = 'inbox';
    require_once("tcg/trade/trade.php");
});
Route::add("/trade/inbox",function() {
    $trade_box_type = 'inbox';
    require_once("tcg/trade/trade.php");
});
Route::add("/trade/outbox",function() {
    $trade_box_type = 'outbox';
    require_once("tcg/trade/trade.php");
});
Route::add("/trade/([0-9+].*)/([0-9+].*)",function($trade_member_id, $card_id) {
    require_once("tcg/trade/trade_offer.php");
});
Route::add("/trade/([0-9+].*)/([0-9+].*)",function($trade_member_id, $card_id) {
    require_once("tcg/trade/trade_offer.php");
}, "post");
Route::add("/trade/inbox/([0-9+].*)",function($trade_id) {
    $trade_box_type = 'inbox';
    require_once("tcg/trade/trade.php");
}, "post");
Route::add("/trade/([0-9+].*)/withdraw",function($trade_id) {
    $action = 'withdraw';
    $trade_box_type = 'outbox';
    require_once("tcg/trade/trade.php");
});

// games
Route::add("/games",function() {
    require_once("tcg/games/games.php");
});
Route::add("/games/lucky_cat/([0-9+].*)",function($lucky_cat_game_id) {
    require_once("tcg/games/lucky_cat.php");
});
Route::add("/games/lucky_cat/([0-9+].*)",function($lucky_cat_game_id) {
    require_once("tcg/games/lucky_cat.php");
}, "post");
Route::add("/games/lucky/([0-9+].*)",function($game_id) {
    includeGameFile($game_id);
});
Route::add("/games/lucky/([0-9+].*)",function($game_id) {
    includeGameFile($game_id);
}, "post");
Route::add("/games/skill/([0-9+].*)",function($game_id) {
    includeGameFile($game_id);
});
Route::add("/games/skill/([0-9+].*)",function($game_id) {
    includeGameFile($game_id);
}, "post");

// lost password and register
Route::add("/lostpassword",function() {
    require_once("tcg/lostpassword.php");
});
Route::add("/lostpassword",function() {
    require_once("tcg/lostpassword.php");
}, "post");
Route::add("/register",function() {
    require_once("tcg/register.php");
});
Route::add("/register",function() {
    require_once("tcg/register.php");
}, "post");
// account activation
Route::add("/activation/([a-zA-Z0-9]*)",function($activation_code) {
    require_once("tcg/activation.php");
});


/**
 * routes you can change and add more
 */
Route::add("/team",function() {
    require_once("main/team.php");
});
Route::add("/linkin",function() {
    require_once("main/linkin.php");
});
Route::add("/linkout",function() {
    require_once("main/linkout.php");
});
Route::add("/faq",function() {
    require_once("tcg/faq.php");
});
Route::add("/rules",function() {
    require_once("tcg/rules.php");
});
Route::add("/statistic",function() {
    require_once("tcg/statistic.php");
});


Route::run("/");

require_once("inc/footer.php");
?>