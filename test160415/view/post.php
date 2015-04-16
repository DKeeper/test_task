<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 16.04.15
 * @time 9:27
 * Created by JetBrains PhpStorm.
 */

/** @var $post Post */
/** @var $asAdmin boolean */

$extClass = '';
if($asAdmin){
    if(!$post->getAttribute('published')){
        $extClass .= 'unpublished';
    }
}
?>

<div class="row single-post <?=$extClass?>">
    <div class="user-block col-10">
        <?= $post->getAttribute('user_name') ?><br/>
        <?= $post->getAttribute('created_at') ?>
        <?php
        if($asAdmin){
            $editUrl = TEST_BASE_URL."test160415/?q=editpost&id=".$post->getAttribute($post->getPk());
            $deleteUrl = TEST_BASE_URL."test160415/?q=delpost&id=".$post->getAttribute($post->getPk());
            ?>
            <br>
            <a class="btn btn-primary" href="<?=$editUrl?>" title="Редактировать">U</a>
            <a class="btn btn-primary" href="<?=$deleteUrl?>" title="Удалить">D</a>
            <?php
        }
        ?>
    </div>
    <div class="message-block">
        <?= $post->getAttribute('message') ?>
        <?php
        if($asAdmin){
            $replyUrl = TEST_BASE_URL."test160415/?q=replypost&id=".$post->getAttribute($post->getPk());
            ?>
            <br>
            <a class="btn btn-primary" href="<?=$replyUrl?>" title="Ответить">Ответить</a>
            <?php
        }
        ?>
    </div>
</div>
<?php
$answers = $post->getReplys($asAdmin);
if(!empty($answers)){
    foreach($answers as $answer){
        echo viewPhpFile(getViewPath().'answer.php',[
            'post' => $answer,
            'asAdmin' => $asAdmin,
        ]);
    }
}
?>