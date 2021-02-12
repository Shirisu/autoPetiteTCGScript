<?php
// Include router class
require_once("inc/class.Route.php");

session_start();
require_once("inc/connection.php");
require_once("inc/constants.php");
require_once("inc/function.php");
require_once("inc/_translations.php");
require_once("inc/_language.php");
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

Route::add("/news/([0-9+].*)",function($news_id) {
    require_once("main/news.php");
});
Route::add("/carddecks",function() {
    require_once("tcg/carddecks.php");
});
Route::add("/carddecks/all",function() {
    require_once("tcg/carddecks.php");
});
Route::add("/carddecks/([0-9+].*)",function($category_id) {
    require_once("tcg/carddecks.php");
});
Route::add("/carddeck/([a-z0-9+].*)",function($carddeck_name) {
    require_once("tcg/carddeck_page.php");
});
Route::add("/member",function() {
    require_once("tcg/member.php");
});
Route::add("/member/([0-9+].*)/([a-z+].*)",function($member_id, $category) {
    if ($category == 'trade') {
        require_once("tcg/member_page_trade.php");
    } elseif ($category == 'collect') {
        require_once("tcg/member_page_collect.php");
    } elseif ($category == 'master') {
        require_once("tcg/member_page_master.php");
    } elseif ($category == 'wishlist') {
        require_once("tcg/member_page_wishlist.php");
    } else {
        require_once("tcg/member_page.php");
    }
});
Route::add("/member/([0-9+].*)",function($member_id) {
    require_once("tcg/member_page.php");
});
Route::add("/memberarea/changeprofile",function() {
    require_once("tcg/memberarea_changeprofile.php");
});
Route::add("/memberarea/changeprofile",function() {
    require_once("tcg/memberarea_changeprofile.php");
}, "post");
Route::add("/memberarea/log",function() {
    require_once("tcg/memberarea_log.php");
});
Route::add("/memberarea/search",function() {
    require_once("tcg/memberarea_search.php");
});
Route::add("/memberarea",function() {
    require_once("tcg/memberarea.php");
});

Route::add("/lostpassword",function() {
    require_once("tcg/lostpassword.php");
});
Route::add("/lostpassword",function() {
    require_once("tcg/lostpassword.php");
});
// Route for submitting the form
Route::add("/lostpassword",function() {
    require_once("tcg/lostpassword.php");
}, "post");

Route::add("/register",function() {
    require_once("tcg/register.php");
});
// Route for submitting the form
Route::add("/register",function() {
    require_once("tcg/register.php");
}, "post");
// Route for account activation
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


Route::run("/");

require_once("inc/footer.php");
?>