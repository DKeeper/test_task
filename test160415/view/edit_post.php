<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 16.04.15
 * @time 12:38
 * Created by JetBrains PhpStorm.
 */

/** @var $model Post */
/** @var $user User|null */

?>
<html lang="en-US"><head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= 'Posts' ?></title>
    <link href="<?= TEST_BASE_URL ?>css/css_160415.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <?php
    $parent = $model->getParent();
    if(isset($parent)){
        ?>
        <label>Ваш ответ на</label>
        <?php
        echo viewPhpFile(getViewPath().'post.php',[
            'post' => $parent,
            'asAdmin' => isset($user),
        ]);
    }
    renderPostForm($model,$user);
    ?>
</div>
</body></html>