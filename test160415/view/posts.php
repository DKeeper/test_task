<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 16.04.15
 * @time 9:10
 * Created by JetBrains PhpStorm.
 */

/** @var $model Post */
/** @var $user User|null */
/** @var $posts Post[] */
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
    if(isset($_SESSION['addPostSuccess'])){
        ?>
        <div class="row notification notification-success"><?=$_SESSION['addPostSuccess']?></div>
        <?php
        unset($_SESSION['addPostSuccess']);
    }
    if(empty($posts)){
        ?>
        <div class="row">Нет записей</div>
        <?php
    } else {
        foreach($posts as $post){
            echo viewPhpFile(getViewPath().'post.php',[
                'post' => $post,
                'asAdmin' => isset($user),
            ]);
        }
    }
?>
    <h2>Оставить сообщение</h2>
<?php
    renderPostForm($model,$user);
?>
</div>
</body></html>