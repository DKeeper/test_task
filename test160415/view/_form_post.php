<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 16.04.15
 * @time 10:45
 * Created by JetBrains PhpStorm.
 */

/** @var $model Post */
/** @var $asAdmin boolean */

$err = $model->getErrors();

?>
<form id="post-form" method="post" autocomplete="off">
    <input type="hidden" name="PostForm[user_name]" value="<?= $model->getAttribute('parent') ?>">
    <div class="row summary-error" style="display:none;"></div>
    <div class="form-group field-postform-user_name required">
        <label for="postform-user_name">Ваше имя</label>
        <input onchange="helpers.validateAttribute('user_name')" type="text" id="postform-user_name" class="form-control <?= isset($err['user_name']) ? 'has-error' : '' ?>" name="PostForm[user_name]" autocomplete="off" value="<?= $model->getAttribute('user_name') ?>">
        <p class="help-block help-block-error"><?= isset($err['user_name']) ? $err['user_name'] : '' ?></p>
    </div>
    <div class="form-group field-postform-message required">
        <label for="postform-message">Текст сообщения</label>
        <textarea onchange="helpers.validateAttribute('message')" id="postform-message" class="form-control <?= isset($err['message']) ? 'has-error' : '' ?>" name="PostForm[message]" style="height: 100px;">
            <?= $model->getAttribute('message') ?>
        </textarea>
        <p class="help-block help-block-error"><?= isset($err['message']) ? $err['message'] : '' ?></p>
    </div>
<?php
    if($asAdmin){
        ?>
        <div class="form-group field-postform-published">
            <label for="postform-published">Опубликовано</label>
            <select onchange="helpers.validateAttribute('published')" id="postform-published" class="form-control <?= isset($err['published']) ? 'has-error' : '' ?>" name="PostForm[published]">
                <option value="0" <?= $model->getAttribute("published")==0 ? "selected" : "" ?>>Нет</option>
                <option value="1" <?= $model->getAttribute("published")==1 ? "selected" : "" ?>>Да</option>
            </select>
        </div>
        <?php
    }
?>
    <button type="submit" onclick="return helpers.validateForm();" class="btn btn-primary btn-block"><?= 'Send message' ?></button>
</form>

<script src="<?= TEST_BASE_URL ?>js/js.js"></script>
<script>
    window.onload = function(event){
        helpers.init({rules:<?= json_encode($model->getRules()) ?>,formID:'post-form'});
    };
</script>