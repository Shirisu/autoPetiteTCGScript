# autoPetiteTCGScript
A simple and light script for a mini automated Trading Card Game (TCG).

It's written in PHP and uses jQuery and plain CSS.

It's based on the concept and script of "Tasty TCG" from 2016 but it's almost completely reworked.

Current version: 2.6.0

[Documentation - how to use autoPetiteTCGScript](https://github.com/Shirisu/autoPetiteTCGScript/blob/master/DOCUMENTATION.md)

![GitHub commit activity](https://img.shields.io/github/commit-activity/w/Shirisu/autoPetiteTCGScript)
![Lines of code](https://img.shields.io/tokei/lines/github/Shirisu/autoPetiteTCGScript)


## Table of contents

* [how to setup](#how-to-setup)
* [features](#features)
* [possible features in the future](#possible-features-in-the-future)
* [requirements](#requirements)
* [used frameworks, libraries and others](#used-frameworks-libraries-and-others)
* [credits for the script](#credits-for-the-script)
* [sites that use the script](#sites-that-use-the-script)


## how to setup
- download the files
- go to `inc/connection.php` and adjust the database settings (you need MySQLi)
- go to `inc/constants.php` and adjust the constants to your needs
- upload the files to your webspace
- create database with the name you set for `$database_name` in `inc/connection.php`
- run *urltoyourpage.com/setup*
  - click the link *Import database structure*
  - click the link *Add Admin Account* and fill the form with your data
  - click the link *Back*
- delete the `setup` folder!


## features
- multilingual (english and german)
- multiple templates to use
- administration
  - category administration
    - add category
    - edit category
    - add subcategory
    - edit subcategory
    - delete subcategory
  - carddeck administration
    - add carddeck
    - edit carddeck
  - cardupate administration
    - add cardupdate with news
  - news administration
    - add news
    - edit news
  - level administration
    - add level
    - edit level
  - member administration
    - edit member data (status, rank, language, email, reset password)
    - distribute cards
    - distribute currency
    - distribute *wish*
    - delete member
  - game administration
    - add game
    - edit game
- registration (activation via link in email)
- login
- logout
- lost password
- some sample pages with lorem ipsum text
  - team
  - link in
  - link out
  - faq
  - rules
- online list
- main page
  - news system
- carddeck page
  - show card deck overview
    - all
    - by main category
    - functionality for add/remove wishlist
  - show cards and details
  - add to wishlist
  - show collector, trader, master
- member overview
- member profile
  - show profile
  - show cards
    - show only needed cards filter
    - show all cards filter
  - master, wishlist
- memberarea
  - overview
  - change own profile
    - traderules
    - some personal stuff like language, email
  - show log
  - search
    - missing cards for members
  - shop
    - change currency in cards
    - change wish in cards
  - cardupdate
    - get new cards
  - trade in
- managing of cards
  - sort in categories (trade, collect)
  - master full carddecks
    - multiple mastering
- message system
- games
- trade system
  - semi-automatic
- level up system

## possible features in the future
- main page
  - news system
    - commenting system
- bbcodes
- trade system
  - fully automatic


# requirements
- MySQL 5.7 or higher
- PHP 7.2 or higher


## used frameworks, libraries and others
- Bootstrap v5.2.3 (https://getbootstrap.com)
- BootstrapSelect v1.14.0-beta3 (https://developer.snapappointments.com/bootstrap-select/)
- BootstrapTable v1.21.4 (https://bootstrap-table.com)
- jQuery v3.6.4 (https://jquery.com)
- Font Awesome Free v5.15.2 (https://fontawesome.com)
- PHP Routing (https://steampixel.de/einfaches-und-elegantes-url-routing-mit-php/)


## credits for the script
- Shirisu (also known as Etna or Pleinar) - [Github](https://github.com/Shirisu/)


## sites that use the script
- [Witch Heaven TCG](https://tcg.jadestaub.de)
- [Alohomora TCG](https://alohomora.arctic-rose.net)
