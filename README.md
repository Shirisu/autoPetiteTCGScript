# autoPetiteTCGScript
A simple and light script for a mini automated Trading Card Game (TCG).

It's written in PHP and use plain JS and CSS.

It's based on the script of "Tasty TCG" from 2016.

Actual status: Work in progress!


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


## finished features
- multilingual (english and german)
- administration
  - category administration
    - add category
    - edit category
    - add subcategory
    - edit subcategory
  - carddeck administration
    - add carddeck
    - edit carddeck
  - cardupate administration
    - add cardupdate with news
  - news administration
    - add news
    - edit news
  - member administration
    - edit member data (status, rank, language, email)
    - distribute cards
    - distribute currency
    - distribute *wish*
    - delete member
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


## up coming features
- carddeck page
  - show card deck overview
    - all
    - by main category
    - by sub category
  - show cards and details
  - show collector, trader, master
  - add to wishlist
- member profile
  - show profile
  - show cards, master, wishlist
  - change own profile
    - traderules
    - some personal stuff like language, email
- managing of cards
  - sort in categories (trade, collect)
  - master full carddecks
- games
- cardupdate
  - get new cards
- message system
- trade system
  - fully automatic
  - semi-automatic
- search
  - missing cards for member
- shop
  - change currency in cards
  - change wish in cards


## possible features in the future
- main page
  - news system
    - commenting system


## used frameworks and libraries
- Bootstrap v4.5.3 (https://getbootstrap.com/)
- jQuery v3.5.1 (https://jquery.com/)
- Font Awesome Free v5.15.2 (https://fontawesome.com/)


## credits for the script
- Shirisu (also known as Etna or Pleinar) - [Github](https://github.com/Shirisu/)