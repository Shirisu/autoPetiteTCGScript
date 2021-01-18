<?php
$translations['de'] = array(
    'general' => array(
        'language_text' => 'Sprache',
        'language_de_text' => 'deutsch',
        'language_en_text' => 'english',

        'play_now' => 'jetzt',
        'play_date_format' => 'H:i',
        'play_date_text' => ' Uhr',

        'text_dateofbirth' => 'Geburtstag',
        'text_day' => 'Tag',
        'text_month' => 'Monat',
        'text_year' => 'Jahr',
        'text_gender' => 'Geschlecht',
        'text_male' => 'm&auml;nnlich',
        'text_female' => 'weiblich',
        'text_diverse' => 'divers',

        'text_password' => 'Passwort',
        'text_lostpassword' => 'Passwort vergessen?',
        'text_register' => 'Registrierung',
        'text_error_login' => 'Falsche Daten - bitte versuche es noch einmal.',
        'text_memberarea' => 'Memberarea',
        'text_pm' => 'PN',
        'text_trade' => 'Trade',
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

        'text_active' => 'aktiv',
        'text_inactive' => 'inaktiv',
        'text_rank' => 'Rang',
        'text_status' => 'Status',
        'text_main_language' => 'Muttersprache',

        'text_nickname' => 'Nickname',
        'text_email' => 'Email',
    ),
    'lostpassword' => array(
        'headline' => 'Passwort vergessen',
        'intro' => 'Du hast dein Passwort vergessen? Dann lasse dir ein neues zuschicken!',
        'hint_success' => 'Dir wurde ein <b>neues Passwort</b> zugeschickt!<br />
            Bitte &uuml;berpr&uuml;fe auch deinen <b>Spam-Ordner</b>.<br /><br />
            Sollte die Email nach <b>10 Minuten</b> immer noch nicht ankommen sein, dann schreib bitte eine <a href="mailto:'.TCG_META_OWNER.'?Subject=Passwort vergessen"><b>email an den Admin</b></a>.',
        'hint_notmatched' => 'Der Nickname und die email stimmen nicht &uuml;berein!',
        'hint_empty' => 'Du musst deinen Nicknamen + email eingeben!',
        'button' => 'Neues Passwort anfordern!',
        'hint_info' => '<b>P.S.:</b> Es kann sein, dass bei Hotmail-Adressen (@hotmail bzw @live) ein Fehler auftritt.<br />
            Sollte dies passieren, dann schreib bitte eine <a href="mailto:'.TCG_META_OWNER.'?Subject=Passwort vergessen"><b>Email an den Admin</b></a>!',
        'mail_subject' => 'Passwort Anforderung auf '.TCG_NAME,
        'mail_part_1' => 'Du hast ein neues Passwort auf '.HOST_URL_PLAIN.' angefordert
            Dein neues Passwort: ',
        'mail_part_2' => 'Du kannst dich nun mit deinem Nicknamen und neuem Passwort einloggen.
Bitte denke daran, dein Passwort danach sofort zu &auml;ndern!

Liebe Gr&uuml;&szlig;e,
das '.TCG_NAME.'-Team

'.HOST_URL_PLAIN.'
trading card game

** Diese Mail wurde automatisch erzeugt! **',
    ),
    'register' => array(
        'already_registered' => 'Du bist bereits angemeldet!<br />Mehrfachanmeldungen sind nicht gestattet!!',
        'nickname_hint' => 'Du kannst Buchstaben und Zahlen verwenden, keine Sonder- oder Leerzeichen.',
        'email_hint' => 'Bitte beachte, dass du eine <b>g&uuml;ltige Email-Adresse</b> verwendest, da du damit deinen <b>Account freischalten</b> musst!<br />Vermeide <i>hotmail, live und outlook</i> Adressen.',
        'password_hint' => 'Bitte w&auml;hle ein sicheres Passwort!',
        'password_hint_rule_1' => 'mindestens einen Gro&szlig;buchtsabe',
        'password_hint_rule_2' => 'mindestens einen Kleinbuchstaben',
        'password_hint_rule_3' => 'mindestens eine Zahl',
        'password_hint_rule_4' => 'mindestens ein Sonderzeichen (!, ?, +, -, _, #, *, &, $, &sect;, %)',
        'password_hint_rule_5' => 'mindestens 8 Zeichen',
        'password_repeat' => 'Password wiederholen',
        'password2_hint' => 'Bitte pr&uuml;fe, ob du dich nicht vertippt hast!',
        'dateofbirth_hint' => 'Kann sp&auml;ter deaktiviert werden! Nicht &auml;nderbar!',
        'gender_hint' => 'Nicht &auml;nderbar!',
        'language_hint' => 'Deine präferierte oder Muttersprache',
        'mistake_text' => 'Solltest du dich vertippt haben, so kontaktiere <a href="mailto:'.TCG_META_OWNER.'?Subject='.TCG_NAME.' - Bei Registrierung vertippt">uns</a> bitte!',
    ),
);
?>