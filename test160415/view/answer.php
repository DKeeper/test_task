<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 16.04.15
 * @time 14:48
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

<div class="row reply single-post <?=$extClass?>">
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
    </div>
</div>