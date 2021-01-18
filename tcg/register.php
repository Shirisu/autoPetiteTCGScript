<div class="row">
    <div class="col col-12">
        <?php
        title(TRANSLATIONS[$GLOBALS['language']]['general']['text_register']);
        ?>
    </div>

    <div class="col col-12">
        <?php
        if(isset($_SESSION['member_id'])) {
            echo TRANSLATIONS[$GLOBALS['language']]['register']['already_registered'];
        } else {
            if(isset($_POST['nick'])) {

            } else {
            ?>
                <form method="post" action="register.php">
                    <div class="row">
                        <div class="col col-12 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" id="nickname" aria-describedby="nicknameHelp" placeholder="<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_nickname']; ?>" />
                                <small id="nicknameHelp" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['nickname_hint']; ?></small>
                            </div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" id="email" aria-describedby="emailHelp" placeholder="<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_email']; ?>" />
                                <small id="emailHelp" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['email_hint']; ?></small>
                            </div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="form-group">
                                <input type="password" class="form-control" id="password" aria-describedby="passwordHelp" placeholder="<?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_password']; ?>" />
                                <small id="passwordHelp" class="form-text text-muted">
                                    <?php echo TRANSLATIONS[$GLOBALS['language']]['register']['password_hint']; ?>
                                    <ul>
                                        <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['password_hint_rule_1']; ?></li>
                                        <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['password_hint_rule_2']; ?></li>
                                        <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['password_hint_rule_3']; ?></li>
                                        <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['password_hint_rule_4']; ?></li>
                                        <li><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['password_hint_rule_5']; ?></li>
                                    </ul>
                                </small>
                            </div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="form-group">
                                <input type="password" class="form-control" id="password2" aria-describedby="password2Help" placeholder="<?php echo TRANSLATIONS[$GLOBALS['language']]['register']['password_repeat']; ?>" />
                                <small id="password2Help" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['password2_hint']; ?></small>
                            </div>
                        </div>
                        <div class="col col-12 col-md-12">
                            <div class="form-group">
                                <label for="dateofbirth" aria-describedby="dateofbirthHelp"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_dateofbirth']; ?></label>
                                <div class="row">
                                    <div class="col col-4">
                                        <div class="input-group">
                                            <select class="custom-select" id="dateofbirth_day">
                                                <option selected disabled><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_day']; ?></option>
                                                <?php
                                                for ($i = 1; $i <= 31; $i++) {
                                                    ?>
                                                    <option><?php echo $i; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col col-4">
                                        <div class="input-group">
                                            <select class="custom-select" id="dateofbirth_month">
                                                <option selected disabled><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_month']; ?></option>
                                                <?php
                                                for ($i = 1; $i <= 12; $i++) {
                                                    ?>
                                                    <option><?php echo $i; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col col-4">
                                        <div class="input-group">
                                            <select class="custom-select" id="dateofbirth_year">
                                                <option selected disabled><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_year']; ?></option>
                                                <?php
                                                for ($i = 1940; $i <= date('Y', time()); $i++) {
                                                    ?>
                                                    <option><?php echo $i; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <small id="dateofbirthHelp" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['dateofbirth_hint']; ?></small>
                            </div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <select class="custom-select" id="gender" aria-describedby="genderHelp">
                                        <option selected disabled><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_gender']; ?></option>
                                        <option value="2"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_diverse']; ?></option>
                                        <option value="0"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_female']; ?></option>
                                        <option value="1"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['text_male']; ?></option>
                                    </select>
                                </div>
                                <small id="genderHelp" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['gender_hint']; ?></small>
                            </div>
                        </div>
                        <div class="col col-12 col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <select class="custom-select" id="language" aria-describedby="languageHelp">
                                        <option selected disabled><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['language_text']; ?></option>
                                        <option value="en"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['language_en_text']; ?></option>
                                        <option value="de"><?php echo TRANSLATIONS[$GLOBALS['language']]['general']['language_de_text']; ?></option>
                                    </select>
                                </div>
                                <small id="languageHelp" class="form-text text-muted"><?php echo TRANSLATIONS[$GLOBALS['language']]['register']['language_hint']; ?></small>
                            </div>
                        </div>
                    </div>
                </form>
            <?php
            }
        }
        ?>
    </div>
</div>
