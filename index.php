<?php
// Include router class
require_once("inc/class.Route.php");

session_start();
require_once("inc/connection.php");
require_once("inc/constants.php");
require_once("inc/function.php");
require_once("inc/_translations.php");
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
Route::add("/administration/editmember/([a-z]*)",function($rank) {
    require_once("admin/member_edit.php");
});
Route::add("/administration/editmember/([1-9]*)",function($memberId) {
    require_once("admin/member_edit.php");
});
Route::add("/administration/editmember/([1-9]*)",function($memberId) {
    require_once("admin/member_edit.php");
}, "post");
Route::add("/administration/deletemember/([1-9]*)",function($memberId) {
    require_once("admin/member_delete.php");
});
Route::add("/administration/deletemember/([1-9]*)",function($memberId) {
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
Route::add("/administration/editcategory/([1-9]*)",function($categoryId) {
    require_once("admin/category_edit.php");
});
Route::add("/administration/editcategory/([1-9]*)",function($categoryId) {
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
Route::add("/administration/editsubcategory/([1-9]*)",function($subcategoryId) {
    require_once("admin/subcategory_edit.php");
});
Route::add("/administration/editsubcategory/([1-9]*)",function($subcategoryId) {
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
Route::add("/administration/editcarddeck/([1-9]*)",function($carddeckId) {
    require_once("admin/carddeck_edit.php");
});
Route::add("/administration/editcarddeck/([1-9]*)",function($carddeckId) {
    require_once("admin/carddeck_edit.php");
}, "post");
Route::add("/administration/addnews",function() {
    require_once("admin/add_news.php");
});
Route::add("/administration/addnews",function() {
    require_once("admin/add_news.php");
}, "post");
Route::add("/administration/editnews",function() {
    require_once("admin/edit_news.php");
});
Route::add("/administration/editnews",function() {
    require_once("admin/edit_news.php");
}, "post");
Route::add("/administration/editnews/([1-9]*)",function($newsId) {
    require_once("admin/edit_news.php");
});
Route::add("/administration/editnews/([1-9]*)",function($newsId) {
    require_once("admin/edit_news.php");
}, "post");
Route::add("/administration/addcardupdate",function() {
    require_once("admin/cardupdate_add.php");
});
Route::add("/administration/addcardupdate",function() {
    require_once("admin/cardupdate_add.php");
}, "post");


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