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
Route::add("/admin/memberadministration",function() {
    require_once("admin/member_administration.php");
});
Route::add("/admin/editmember/([a-z]*)",function($rank) {
    require_once("admin/member_edit.php");
});
Route::add("/admin/editmember/([1-9]*)",function($memberId) {
    require_once("admin/member_edit.php");
});
Route::add("/admin/editmember/([1-9]*)",function($memberId) {
    require_once("admin/member_edit.php");
}, "post");

Route::add("/admin/categoryadministration",function() {
    require_once("admin/category_administration.php");
});
Route::add("/admin/addcategory",function() {
    require_once("admin/category_add.php");
});
Route::add("/admin/addcategory",function() {
    require_once("admin/category_add.php");
}, "post");
Route::add("/admin/editcategory",function() {
    require_once("admin/category_edit.php");
});
Route::add("/admin/editcategory",function() {
    require_once("admin/category_edit.php");
}, "post");
Route::add("/admin/editcategory/([1-9]*)",function($categoryId) {
    require_once("admin/category_edit.php");
});
Route::add("/admin/editcategory/([1-9]*)",function($categoryId) {
    require_once("admin/category_edit.php");
}, "post");
Route::add("/admin/addsubcategory",function() {
    require_once("admin/subcategory_add.php");
});
Route::add("/admin/addsubcategory",function() {
    require_once("admin/subcategory_add.php");
}, "post");
Route::add("/admin/editsubcategory",function() {
    require_once("admin/subcategory_edit.php");
});
Route::add("/admin/editsubcategory",function() {
    require_once("admin/subcategory_edit.php");
}, "post");
Route::add("/admin/editsubcategory/([1-9]*)",function($subcategoryId) {
    require_once("admin/subcategory_edit.php");
});
Route::add("/admin/editsubcategory/([1-9]*)",function($subcategoryId) {
    require_once("admin/subcategory_edit.php");
}, "post");

Route::add("/admin/carddeckadministration",function() {
    require_once("admin/carddeck_administration.php");
});
Route::add("/admin/addcarddeck",function() {
    require_once("admin/carddeck_add.php");
});
Route::add("/admin/addcarddeck",function() {
    require_once("admin/carddeck_add.php");
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
Route::add("/activation/([a-zA-Z0-9]*)(?=.{19,21})",function($activation_code) {
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