# Documentation - how to use autoPetiteTCGScript

## Table of contents

* [Constants.php](#constantsphp)
* [Add new page](#add-new-page)
* [Add new translations](#add-new-translations)
* [How to run in subfolder](#how-to-run-in-subfolder)
* [Frequently asked questions](#frequently-asked-questions)


## Constants.php
You can define some settings in the [constants.php](https://github.com/Shirisu/autoPetiteTCGScript/blob/master/inc/constants.php)

- **TCG_CARDS_FILE_TYPE** - default `'png'`
  - file type of cards
  - accepted values: `'gif'`, `'jpg'`, `'png'`
  - use only one!
- **TCG_CARDS_FILLER_NAME** - default: `'filler'`
  - name of the filler/search card - should be placed in the the folder "/assets/cards/"
  - will be used for missing cards in the member profile and cards page
- **TCG_CARDS_HEIGHT** - default: `100`
  - height of a single card
  - accepted values: from `1` to whatever you like
  - all cards have the same height
- **TCG_CARDS_PER_ROW** - default: `4`
  - how many cards are in one row
  - accepted values: from `1` to whatever you like
  - will be used on card deck pages and in member profile "collect"
- **TCG_CARDS_WIDTH** - default: `100`
  - width of a single card
  - accepted values: from `1` to whatever you like
  - all cards have the same width
- **TCG_CARDS_START_PACKAGE** - default `12`
  - card amount of start package after successful registration with account activation
  - accepted values: from `1` to whatever you like
- **TCG_CARDDECK_MAX_CARDS** - default: `12`
  - card amount of a card deck
  - accepted values: from `1` to whatever you like
  - all card decks have the same amount of cards
- **TCG_CURRENCY** - default: `'Dollar'`
  - currency name
  - accepted values: whatever you like
  - will be used for the shop to buy cards
- **TCG_CURRENCY_USE** - default: `true`
  - will you use the currency?
  - accepted values: `true` (means yes), `false` (means no)
- **TCG_LEVEL_UP_CARD_REWARD** - default `3`
  - reward card amount for level up
- **TCG_MAIN_LANGUAGE** - default: `'en'`
  - main language of the TCG
  - accepted values: `'en'` and `'de'`
- **TCG_MASTER_CARD_REWARD** - default: `3`
  - reward card amount for mastering a card deck
- **TCG_MASTERCARDS_HEIGHT** - default: `100`
  - height of a single master card
  - accepted values: from `1` to whatever you like
  - all master cards have the same height
- **TCG_MASTERCARDS_WIDTH** - default: `100`
  - width of a single master card
  - accepted values: from `1` to whatever you like
  - all master cards have the same width
- **TCG_META_AUTHOR**
  - name of the TCG owner
  - meta tags are used for search engine pages like google, bing, ...
- **TCG_META_DESC**
  - short description of your TCG
  - meta tags are used for search engine pages like google, bing, ...
- **TCG_META_KEYWORDS**
  - keywords for your TCG
  - meta tags are used for search engine pages like google, bing, ...
- **TCG_META_TITLE**
  - title-tag & meta title
  - meta tags are used for search engine pages like google, bing, ...
- **TCG_META_OWNER**
  - email address to contact the TCG owner and for mails (forget password, questions, ...)
  - meta tags are used for search engine pages like google, bing, ...
  - will be used for all mailings (contact admin)
- **TCG_NAME**
  - for title-attribute & meta title
  - the name of your TCG
- **TCG_SHOP_CURRENCY_FOR_RANDOM** - default `100`
  - how much currency do 1 random cost?
  - accepted values: from `1` to whatever you like
- **TCG_SLOGAN**
  - for title-attribute & meta title
- **TCG_TEMPLATE** - default: `1`
  - switch template
  - accepted values: `1`, `2`
  - which template you want to use
- **TCG_WISH** - default: `'Wish'`
  - wish name
  - accepted values: whatever you like
  - will be used for the shop to change into specific card
- **TCG_WISH_USE** - default: `true`
  - will you use wish?
  - accepted values: `true` (means yes), `false` (means no)


## Add new page
To add a new page you must follow these steps
- open the file `index.php`
    - search for `* routes you can change and add more`
    - paste the following code after the last `Route::add` block
```
Route::add("/PAGEURL",function() {
    require_once("PATHTOFILE/FILENAME.php");
});
```
    - change `PAGEURL` - something which should be in the browser url bar - example `about` (don't delete the `/`!)
    - change `PATHTOFILE/FILENAME` - the place where you put the file - example `main/about.php`
- create your PHP-file in the defined path from above - example file `about.php` in `/main/`
- open your created PHP-file - example `/main/about.php`
    - paste the following code
```
<?php
$breadcrumb = array(
    '/' => 'Home',
    '/PAGEURL' => 'PAGENAME',
);
breadcrumb($breadcrumb);

title('PAGENAME');
?>

<div class="row">
    <div class="col">
        TEXT
    </div>
</div>
```
- change `PAGEURL` - the defined string form above - example `about`
- change `PAGENAME` - the subject of your created page - example `About the site`
- change `TEXT` - here you can add your text/code of your new page
- open the file `/inc/navigation/header.php`
    - search for `<div class="dropdown-menu" aria-labelledby="navbarDropdownMain">`
    - paste the following code after the last `navlink()` line
```
navlink('PAGENAME','PAGEURL');
```
    - change `PAGENAME` - the defined subject from above - example `About the site`
    - change `PAGEURL` - the defined string form above - example `about`

Done.
You can now open your TCG and hover in the top menu over `Main` - you should see the created page link `About the site`.
After clicking on it you will be redirected to the new created page.


## Add new translations
To add new translations you must follow these steps
- open the file `/inc/translations/en.php`
    - you can create a new block for your new texts
    - go to the end of the file and paste the following code
```
'newtexts' => array(
  'KEY' => 'VALUE',
),
```
    - change `KEY` - example `text_about_headline`
    - change `VALUE` - example `About the site`
- do the same with the other translation file `/inc/translations/de.php`
    - `KEY` must be the same like in the `en.php` file
    - `VALUE` should be the translated text in german - example `Über die Seite` (_hint:_ use HTML Codes for umlauts - example `&Uuml;` for `Ü`)

To use your added translations use following code
```
TRANSLATIONS[$GLOBALS['language']]['newtexts']['KEY']
```
in our example 
```
TRANSLATIONS[$GLOBALS['language']]['newtexts']['text_about_headline']
```

## How to run in subfolder
To run the script in subfolder you must follow there steps
- open the file `/inc/constants.php`
    - fill `HOST_URL_SUBFOLDER` with your subfolder name - example `tcg`
- open the file `/.htaccess`
    - search for `RewriteBase /`
    - add the subfolder name after `/` - example `RewriteBase /tcg`
    - search for `RewriteRule ^(login|logout)/?$ /inc/$1.php [L]`
    - add the subfolder name before `/inc` - example `RewriteRule ^(login|logout)/?$ /tcg/inc/$1.php [L]`


## Frequently asked questions
1) Why can't I select a category at a card deck?
- You must add at least 1 main and 1 sub category then you can select a category
2) Why don't I see any active games?
- You must add at least 3 card decks to see the active games
3) Why can't I activate my account using the link in the activation email?
- You must add at least the amount of card decks you specify in `TCG_CARDS_START_PACKAGE` in the `constants.php`