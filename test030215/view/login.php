<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 8:51
 * Created by JetBrains PhpStorm.
 */

/** @var $login string */
/** @var $pass string */
/** @var $err array */
/** @var $rules array */

?>
<html lang="en-US"><head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authorization</title>
    <link href="/css/css.css" rel="stylesheet">
</head>
<body>
<div class="container" id="login-wrapper">
    <div class="row">
        <div class="panel-wrapper">
            <div class="panel-heading">
                <h3 class="panel-title">Authorization</h3>
            </div>
            <div class="panel-body">
                <div class="row summary-error" style="<?= isset($err['summary']) ? '' : 'display:none;' ?>"><?= isset($err['summary']) ? $err['summary']:'' ?></div>
                <form id="login-form" method="post" autocomplete="off">
                    <div class="form-group field-loginform-login required">
                        <input type="text" id="loginform-login" class="form-control <?= isset($err['login']) ? 'has-error' : '' ?>" name="LoginForm[login]" placeholder="Login" autocomplete="off" value="<?= $login ?>">
                        <p class="help-block help-block-error"><?= isset($err['login']) ? $err['login'] : '' ?></p>
                    </div>
                    <div class="form-group field-loginform-password required">
                        <input type="password" id="loginform-password" class="form-control <?= isset($err['pass']) ? 'has-error' : '' ?>" name="LoginForm[password]" placeholder="Password" autocomplete="off" value="<?= $pass ?>">
                        <p class="help-block help-block-error"><?= isset($err['password']) ? $err['password'] : '' ?></p>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <a href="/test030215/registration.php">Registration</a>
            </div>
        </div>
    </div>
</div>
<script src="/js/js.js"></script>
</body></html>