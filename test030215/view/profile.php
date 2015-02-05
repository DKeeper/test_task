<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 9:01
 * Created by JetBrains PhpStorm.
 */

/** @var $user LoginForm */

$avatar = $user->getAttribute('avatar');
?>
<html lang="en-US"><head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= i18n::t('View profile') ?></title>
    <link href="<?= TEST_BASE_URL ?>css/css.css" rel="stylesheet">
</head>
<body>
<div class="container" id="profile-wrapper">
    <div class="row">
        <div class="panel-wrapper">
            <div class="panel-heading">
                <h3 class="panel-title"><?= i18n::t('View') ?> <?= $user->getAttribute('login') ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-50"><?= i18n::t('Login') ?></div>
                    <div class="col-50"><?= $user->getAttribute('login') ?></div>
                </div>
                <div class="row">
                    <div class="col-50"><?= i18n::t('Email') ?></div>
                    <div class="col-50"><?= $user->getAttribute('email') ?></div>
                </div>
                <div class="row">
                    <div class="col-50"><?= i18n::t('Phone') ?></div>
                    <div class="col-50"><?= $user->getAttribute('phone') ?></div>
                </div>
                <div class="row">
                    <div class="col-50"><?= i18n::t('First name') ?></div>
                    <div class="col-50"><?= $user->getAttribute('first_name') ?></div>
                </div>
                <div class="row">
                    <div class="col-50"><?= i18n::t('Last name') ?></div>
                    <div class="col-50"><?= $user->getAttribute('last_name') ?></div>
                </div>
                <div class="row" style="height: 125px">
                    <div class="col-50"><?= i18n::t('Avatar') ?></div>
                    <div class="col-50"><?= !empty($avatar) ? "<img width='150px' src='".$avatar."'/>":"No avatar" ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= TEST_BASE_URL ?>js/js.js"></script>
</body></html>