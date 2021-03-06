<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 8:51
 * Created by JetBrains PhpStorm.
 */

/** @var $user LoginForm */

$err = $user->getErrors();
?>
<html lang="en-US"><head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= i18n::t('Authorization') ?></title>
    <link href="<?= TEST_BASE_URL ?>css/css.css" rel="stylesheet">
</head>
<body>
<div class="container" id="login-wrapper">
    <div class="row">
        <div class="panel-wrapper">
            <div class="panel-heading">
                <h3 class="panel-title"><?= i18n::t('Authorization') ?></h3>
                <select id="language" onchange="helpers.switchLanguage(this)">
                    <option value="en" <?= i18n::$language=="en" ? "selected" : "" ?>><?= i18n::t('English') ?></option>
                    <option value="ru" <?= i18n::$language=="ru" ? "selected" : "" ?>><?= i18n::t('Russian') ?></option>
                </select>
            </div>
            <div class="panel-body">
                <div class="row summary-error" style="<?= isset($err['summary']) ? '' : 'display:none;' ?>"><?= isset($err['summary']) ? $err['summary']:'' ?></div>
                <form id="login-form" method="post" autocomplete="off">
                    <div class="form-group field-loginform-login required">
                        <input onchange="helpers.validateAttribute('login')" type="text" id="loginform-login" class="form-control <?= isset($err['login']) ? 'has-error' : '' ?>" name="LoginForm[login]" placeholder="<?= i18n::t('Login') ?>" autocomplete="off" value="<?= $user->getAttribute('login') ?>">
                        <p class="help-block help-block-error"><?= isset($err['login']) ? $err['login'] : '' ?></p>
                    </div>
                    <div class="form-group field-loginform-password required">
                        <input onchange="helpers.validateAttribute('password')" type="password" id="loginform-password" class="form-control <?= isset($err['password']) ? 'has-error' : '' ?>" name="LoginForm[password]" placeholder="<?= i18n::t('Password') ?>" autocomplete="off" value="<?= $user->getAttribute('password') ?>">
                        <p class="help-block help-block-error"><?= isset($err['password']) ? $err['password'] : '' ?></p>
                    </div>
                    <button type="submit" onclick="return helpers.validateForm();" class="btn btn-primary btn-block"><?= i18n::t('Sign in') ?></button>
                </form>
                <a href="<?= TEST_BASE_URL ?>test030215/registration.php"><?= i18n::t('Registration') ?></a>
            </div>
        </div>
    </div>
</div>
<script src="<?= TEST_BASE_URL ?>js/js.js"></script>
<script>
    window.onload = function(event){
        helpers.init({rules:<?= json_encode($user->getRules()) ?>,formID:'login-form'});
    };
</script>
</body></html>