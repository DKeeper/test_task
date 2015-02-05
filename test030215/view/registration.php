<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 9:33
 * Created by JetBrains PhpStorm.
 */

/** @var $user User */

$err = $user->getErrors();
?>
<html lang="en-US"><head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= i18n::t('New User registration') ?></title>
    <link href="/css/css.css" rel="stylesheet">
</head>
<body>
<div class="container" id="registration-wrapper">
    <div class="row">
        <div class="panel-wrapper">
            <div class="panel-heading">
                <h3 class="panel-title"><?= i18n::t('New User registration') ?></h3>
                <select id="language" onchange="helpers.switchLanguage(this)">
                    <option value="en" <?= i18n::$language=="en" ? "selected" : "" ?>><?= i18n::t('English') ?></option>
                    <option value="ru" <?= i18n::$language=="ru" ? "selected" : "" ?>><?= i18n::t('Russian') ?></option>
                </select>
            </div>
            <div class="panel-body">
                <form id="registration-form" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="form-group field-registrationform-login required">
                        <input onchange="helpers.validateAttribute('login')" type="text" id="registrationform-login" class="form-control <?= isset($err['login']) ? 'has-error' : '' ?>" name="RegistrationForm[login]" placeholder="<?= i18n::t('Login') ?>" autocomplete="off" value="<?= $user->getAttribute('login') ?>">
                        <p class="help-block help-block-error"><?= isset($err['login']) ? $err['login'] : '' ?></p>
                    </div>
                    <div class="form-group field-registrationform-password required">
                        <input onchange="helpers.validateAttribute('password')" type="password" id="registrationform-password" class="form-control <?= isset($err['password']) ? 'has-error' : '' ?>" name="RegistrationForm[password]" placeholder="<?= i18n::t('Password') ?>" autocomplete="off" value="<?= $user->getAttribute('password') ?>">
                        <p class="help-block help-block-error"><?= isset($err['password']) ? $err['password'] : '' ?></p>
                    </div>
                    <div class="form-group field-registrationform-email">
                        <input onchange="helpers.validateAttribute('email')" type="text" id="registrationform-email" class="form-control <?= isset($err['email']) ? 'has-error' : '' ?>" name="RegistrationForm[email]" placeholder="<?= i18n::t('Email') ?>" autocomplete="off" value="<?= $user->getAttribute('email') ?>">
                        <p class="help-block help-block-error"><?= isset($err['email']) ? $err['email'] : '' ?></p>
                    </div>
                    <div class="form-group field-registrationform-phone">
                        <input onchange="helpers.validateAttribute('phone')" type="text" id="registrationform-phone" class="form-control <?= isset($err['phone']) ? 'has-error' : '' ?>" name="RegistrationForm[phone]" placeholder="<?= i18n::t('Phone') ?>" autocomplete="off" value="<?= $user->getAttribute('phone') ?>">
                        <p class="help-block help-block-error"><?= isset($err['phone']) ? $err['phone'] : '' ?></p>
                    </div>
                    <div class="form-group field-registrationform-first_name">
                        <input onchange="helpers.validateAttribute('first_name')" type="text" id="registrationform-first_name" class="form-control <?= isset($err['first_name']) ? 'has-error' : '' ?>" name="RegistrationForm[first_name]" placeholder="<?= i18n::t('First name') ?>" autocomplete="off" value="<?= $user->getAttribute('first_name') ?>">
                        <p class="help-block help-block-error"><?= isset($err['first_name']) ? $err['first_name'] : '' ?></p>
                    </div>
                    <div class="form-group field-registrationform-last_name">
                        <input onchange="helpers.validateAttribute('last_name')" type="text" id="registrationform-last_name" class="form-control <?= isset($err['last_name']) ? 'has-error' : '' ?>" name="RegistrationForm[last_name]" placeholder="<?= i18n::t('Last name') ?>" autocomplete="off" value="<?= $user->getAttribute('last_name') ?>">
                        <p class="help-block help-block-error"><?= isset($err['last_name']) ? $err['last_name'] : '' ?></p>
                    </div>
                    <div class="form-group field-registrationform-last_name">
                        <a class="btn btn-primary btn-block" onclick="helpers.selectAvatar()"><?= i18n::t('Avatar') ?></a>
                        <input onchange="helpers.validateAttribute('avatar')" style="display: none;" type="file" id="registrationform-avatar" class="form-control <?= isset($err['avatar']) ? 'has-error' : '' ?>" name="RegistrationForm[avatar]">
                        <p class="help-block help-block-error"><?= isset($err['avatar']) ? $err['avatar'] : '' ?></p>
                    </div>
                    <button type="submit" onclick="return helpers.validateForm();" class="btn btn-primary btn-block"><?= i18n::t('Create') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/js/js.js"></script>
<script>
    window.onload = function(event){
        helpers.init({rules:<?= json_encode($user->getRules()) ?>,formID:'registration-form'});
    };
</script>
</body></html>