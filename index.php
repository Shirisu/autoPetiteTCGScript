<?php
// Include router class
require_once("inc/class.Route.php");

session_start();
require_once("inc/connection.php");
require_once("inc/function.php");
require_once("inc/_translations.php");
require_once("inc/header.php");

// Add base route (startpage)
Route::add("/",function() {
    require_once("main/home.php");
});


/**
 * routes in folder "main"
 */
Route::add("/main/team",function() {
    require_once("main/team.php");
});
Route::add("/main/partner",function() {
    require_once("main/partner.php");
});
Route::add("/main/linkstuff",function() {
    require_once("main/linkstuff.php");
});
Route::add("/main/statistic",function() {
    require_once("main/statistic.php");
});

/**
 * routes in folder "tcg"
 */
Route::add("/tcg/lostpassword",function() {
    require_once("tcg/lostpassword.php");
});
// Route for submitting the form
Route::add("/tcg/lostpassword",function() {
    require_once("tcg/lostpassword.php");
}, "post");
Route::add("/tcg/register",function() {
    require_once("tcg/register.php");
});
// Route for submitting the form
Route::add("/tcg/register",function() {
    require_once("tcg/register.php");
}, "post");


// Accept only numbers as parameter. Other characters will result in a 404 error
Route::add("/foo/([0-9]*)/bar",function($var1) {
    echo $var1." is a great number!";
});

Route::run("/");

require_once("inc/footer.php");
?>