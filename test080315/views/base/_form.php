<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\JsExpression;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\library\BaseModel */
/* @var $form yii\widgets\ActiveForm */

/**
 * Для Авторов и Читателей даем возможность создать связь с книгами
 */
if(
    $model instanceof \app\models\library\Authors ||
    $model instanceof \app\models\library\Readers
){
    $attr = 'postBooks';
    $placeholder = 'Select book ...';
    $url = Url::to(['/ajax/getbooks']);
    $selected = $model->getBooksList();
    // Хак для корректного отображения значений по умолчанию
    $model->$attr = -1;
}
?>

<div class="authors-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>

    <?php
    if(isset($attr) && isset($url))
        echo $form->field($model, $attr)->widget(Select2::classname(), [
    'options' => ['placeholder' => $placeholder, 'multiple' => true],
    'pluginOptions' => [
        'tags' => true,
        'allowClear' => true,
        'maximumInputLength' => 10,
        'minimumInputLength' => 3,
        'initSelection' => new JsExpression("function (element, callback) {
            callback(".Json::encode($selected).");
        }"),
        'ajax' => [
            'url' => $url,
            'dataType' => 'json',
            'delay' => 500,
            'data' => new JsExpression("function (term,page) {
                return {
                    term: term,
                    page: page
                };
            }"),
            'results' => new JsExpression("function (data, page, query) {
                return {
                    results: data.results,
                    more: data.more
                };
            }"),
            'cache' => true,
        ],
    ],
]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
