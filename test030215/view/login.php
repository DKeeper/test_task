<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 8:51
 * Created by JetBrains PhpStorm.
 */

/** @var $user string */
/** @var $pass string */
/** @var $err array */

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
                    <div class="form-group field-loginform-username required">
                        <input type="text" id="loginform-username" class="form-control <?= isset($err['user']) ? 'has-error' : '' ?>" name="LoginForm[username]" placeholder="Login" autocomplete="off" value="<?= $user ?>">
                        <p class="help-block help-block-error"><?= isset($err['user']) ? $err['user'] : '' ?></p>
                    </div>
                    <div class="form-group field-loginform-password required">
                        <input type="password" id="loginform-password" class="form-control <?= isset($err['pass']) ? 'has-error' : '' ?>" name="LoginForm[password]" placeholder="Password" autocomplete="off" value="<?= $pass ?>">
                        <p class="help-block help-block-error"><?= isset($err['pass']) ? $err['pass'] : '' ?></p>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/js/js.js"></script>
</body></html>