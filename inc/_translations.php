<?php
$translations = array(
    'en' => array(
        'language_text' => 'Language',
        'language_de_text' => 'german',
        'language_en_text' => 'english',

        'play_now' => 'now',
        'play_date_format' => 'h:i a',
        'play_date_text' => '',
        
        'text_password' => 'Password',
        'text_lostpassword' => 'Lost Password?',
        'text_register' => 'Register',
        'text_error_login' => 'Wrong data - please try again.',
        'text_memberarea' => 'Member Area',
        'text_pm' => 'PM',
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

        'text_lostpassword_headline' => 'Lost password',
        'text_lostpassword_intro' => 'Forgotten your password? Then request a new one!',
        'text_lostpassword_hint_success' => 'You\'ve gotten <b>a new password</b> via email!<br />
            Please also check your <b>spam folder</b>.<br /><br />
            If the email has not arrived after 10 minutes, please write an <a href="mailto:'.TCG_META_OWNER.'?Subject=Lost password"><b>email to the admin</b></a>.',
        'text_lostpassword_hint_notmatched' => 'The nickname and email do not match!',
        'text_lostpassword_hint_empty' => 'You must enter your nickname + email!',
        'text_lostpassword_button' => 'Request new password!',
        'text_lostpassword_hint_info' => '<b>P.S.:</b> It may be that at hotmail addresses (@hotmail or @live) an error occurs.<br />
            If this happens, please write an <a href="mailto:'.TCG_META_OWNER.'?Subject=Lost password"><b>email to the admin</b></a>!',
        'text_lostpassword_mail_subject' => 'Password request on '.TCG_NAME,
        'text_lostpassword_mail_part_1' => 'You have requested a new password '.HOST_URL_PLAIN.'
            Your new password: ',
        'text_lostpassword_mail_part_2' => 'You can now login with your username and the new password.
Please remember to change your password immediately afterwards!

Greetings,
your '.TCG_NAME.' team

'.HOST_URL_PLAIN.'
trading card game

** This message has been generated automatically! **',
    ),
    'de' => array(
        'language_text' => 'Sprache',
        'language_de_text' => 'deutsch',
        'language_en_text' => 'english',

        'play_now' => 'jetzt',
        'play_date_format' => 'H:i',
        'play_date_text' => ' Uhr',
        
        'text_password' => 'Passwort',
        'text_lostpassword' => 'Passwort vergessen?',
        'text_register' => 'Registrierung',
        'text_error_login' => 'Falsche Daten - bitte versuche es noch einmal.',
        'text_memberarea' => 'Memberarea',
        'text_pm' => 'PN',
        'text_cards' => 'Karten',
        'text_statistic' => 'Statistik',
        'text_linkstuff' => 'Linkstuff',
        'text_userarea' => 'Userarea',
        'text_collectlist' => 'Collectlist',
        'text_ownlog' => 'Eigener log',
        'text_contactlist' => 'Kontaktliste',
        'text_personalwishlist' => 'Pers. Wunschliste',
        'text_rules' => 'Regeln',
        'text_carddecks' => 'Carddecks',
        'text_cardsearch' => 'Cardsearch',
        'text_exchangeoffice' => 'Wechselstube',
        'text_cardupdate' => 'Cardupdate',

        'text_lostpassword_headline' => 'Passwort vergessen',
        'text_lostpassword_intro' => 'Du hast dein Passwort vergessen? Dann lasse dir ein neues zuschicken!',
        'text_lostpassword_hint_success' => 'Dir wurde ein <b>neues Passwort</b> zugeschickt!<br />
            Bitte &uuml;berpr&uuml;fe auch deinen <b>Spam-Ordner</b>.<br /><br />
            Sollte die Email nach <b>10 Minuten</b> immer noch nicht ankommen sein, dann schreib bitte eine <a href="mailto:'.TCG_META_OWNER.'?Subject=Passwort vergessen"><b>email an den Admin</b></a>.',
        'text_lostpassword_hint_notmatched' => 'Der Nickname und die email stimmen nicht &uuml;berein!',
        'text_lostpassword_hint_empty' => 'Du musst deinen Nicknamen + email eingeben!',
        'text_lostpassword_button' => 'Neues Passwort anfordern!',
        'text_lostpassword_hint_info' => '<b>P.S.:</b> Es kann sein, dass bei Hotmail-Adressen (@hotmail bzw @live) ein Fehler auftritt.<br />
            Sollte dies passieren, dann schreib bitte eine <a href="mailto:'.TCG_META_OWNER.'?Subject=Passwort vergessen"><b>Email an den Admin</b></a>!',
        'text_lostpassword_mail_subject' => 'Passwort Anforderung auf '.TCG_NAME,
        'text_lostpassword_mail_part_1' => 'Du hast ein neues Passwort auf '.HOST_URL_PLAIN.' angefordert
            Dein neues Passwort: ',
        'text_lostpassword_mail_part_2' => 'Du kannst dich nun mit deinem Nicknamen und neuem Passwort einloggen.
Bitte denke daran, dein Passwort danach sofort zu &auml;ndern!

Liebe Gr&uuml;&szlig;e,
das '.TCG_NAME.'-Team

'.HOST_URL_PLAIN.'
trading card game

** Diese Mail wurde automatisch erzeugt! **',
    )
);

define('TRANSLATIONS', $translations);
?>