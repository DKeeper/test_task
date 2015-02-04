<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 04.02.15
 * @time 9:01
 * Created by JetBrains PhpStorm.
 */

/** @var $user array */
?>
<html lang="en-US"><head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View profile</title>
    <link href="/css/css.css" rel="stylesheet">
</head>
<body>
<div class="container" id="profile-wrapper">
    <div class="row">
        <div class="panel-wrapper">
            <div class="panel-heading">
                <h3 class="panel-title">View <?= $user['login'] ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-50">Username</div>
                    <div class="col-50"><?= $user['login'] ?></div>
                </div>
                <div class="row">
                    <div class="col-50">Email</div>
                    <div class="col-50"><?= $user['email'] ?></div>
                </div>
                <div class="row">
                    <div class="col-50">Phone</div>
                    <div class="col-50"><?= $user['phone'] ?></div>
                </div>
                <div class="row">
                    <div class="col-50">First name</div>
                    <div class="col-50"><?= $user['first_name'] ?></div>
                </div>
                <div class="row">
                    <div class="col-50">Last name</div>
                    <div class="col-50"><?= $user['last_name'] ?></div>
                </div>
                <div class="row" style="height: 125px">
                    <div class="col-50">Avatar</div>
                    <div class="col-50"><?= isset($user['avatar']) ? "<img width='150px' src='".$user['avatar']."'/>":"No avatar" ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/js.js"></script>
</body></html>