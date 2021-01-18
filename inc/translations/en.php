<?php
$translations['en'] = array(
    'general' => array(
        'language_text' => 'Language',
        'language_de_text' => 'german',
        'language_en_text' => 'english',

        'play_now' => 'now',
        'play_date_format' => 'h:i a',
        'play_date_text' => '',

        'text_dateofbirth' => 'Date of birth',
        'text_day' => 'Day',
        'text_month' => 'Month',
        'text_year' => 'Year',
        'text_gender' => 'Gender',
        'text_male' => 'male',
        'text_female' => 'female',
        'text_diverse' => 'diverse',

        'text_password' => 'Password',
        'text_lostpassword' => 'Lost Password?',
        'text_register' => 'Register',
        'text_error_login' => 'Wrong data - please try again.',
        'text_memberarea' => 'Member Area',
        'text_pm' => 'PM',
        'text_trade' => 'Trade',
        'text_cards' => 'Cards',
        'text_statistic' => 'Statistic',
        'text_linkstuff' => 'Link stuff',
        'text_userarea' => 'User area',
        'text_collectlist' => 'Collect list',
        'text_ownlog' => 'Own log',
        'text_contactlist' => 'Contact List',
        'text_personalwishlist' => 'Personal Wishlist',
        'text_rules' => 'Rules',
        'text_carddecks' => 'Card decks',
        'text_cardsearch' => 'Card search',
        'text_exchangeoffice' => 'Exchange office',
        'text_cardupdate' => 'Card update',

        'text_active' => 'active',
        'text_inactive' => 'inactive',
        'text_rank' => 'Rank',
        'text_status' => 'Status',
        'text_main_language' => 'Main language',

        'text_nickname' => 'Nickname',
        'text_email' => 'Email',
    ),
    'lostpassword' => array(
        'headline' => 'Lost password',
        'intro' => 'Forgotten your password? Then request a new one!',
        'hint_success' => 'You\'ve gotten <b>a new password</b> via email!<br />
            Please also check your <b>spam folder</b>.<br /><br />
            If the email has not arrived after 10 minutes, please write an <a href="mailto:'.TCG_META_OWNER.'?Subject=Lost password"><b>email to the admin</b></a>.',
        'hint_notmatched' => 'The nickname and email do not match!',
        'hint_empty' => 'You must enter your nickname + email!',
        'button' => 'Request new password!',
        'hint_info' => '<b>P.S.:</b> It may be that at hotmail addresses (@hotmail or @live) an error occurs.<br />
            If this happens, please write an <a href="mailto:'.TCG_META_OWNER.'?Subject=Lost password"><b>email to the admin</b></a>!',
        'mail_subject' => 'Password request on '.TCG_NAME,
        'mail_part_1' => 'You have requested a new password '.HOST_URL_PLAIN.'
            Your new password: ',
        'mail_part_2' => 'You can now login with your username and the new password.
Please remember to change your password immediately afterwards!

Greetings,
your '.TCG_NAME.' team

'.HOST_URL_PLAIN.'
trading card game

** This message has been generated automatically! **',
    ),
    'register' => array(
        'already_registered' => 'You\'re already registered!<br />Multiple accounts are not permitted!!',
        'nickname_hint' => 'You can use letters and numbers, no special characters or spaces.',
        'email_hint' => 'Please make sure you use a <b>valid email address</b>, as you will need to use it to activate your <b>account</b>!<br />Avoid <i>hotmail, live and outlook</i> addresses.',
        'password_hint' => 'Please choose a strong password!',
        'password_hint_rule_1' => 'at least one capital letter',
        'password_hint_rule_2' => 'at least one lowercase letter',
        'password_hint_rule_3' => 'at least one number',
        'password_hint_rule_4' => 'at least one special character (!, ?, +, -, _, #, *, &, $, &sect;, %)',
        'password_hint_rule_5' => 'at least 8 characters',
        'password_repeat' => 'Repeat password',
        'password2_hint' => 'Please check if you have not mistyped!',
        'dateofbirth_hint' => 'Can be deactivated later! Can not be changed!',
        'gender_hint' => 'Can not be changed!',
        'language_hint' => 'Your preferred or native language',
        'mistake_text' => 'Made a mistake? Then please contact <a href="mailto:'.TCG_META_OWNER.'?Subject='.TCG_NAME.' - Register mistake">us</a>!',
   ),
);
?>