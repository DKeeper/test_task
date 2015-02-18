<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\icons\Icon;
use app\models\Mfr;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */

Icon::map($this, Icon::WHHG);

$mfrList = ArrayHelper::map(Mfr::find()->orderBy('name')->asArray()->all(),'id','name');
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'mfr_id')->widget(Select2::classname(), [
        'data' => $mfrList,
        'options' => ['placeholder' => 'Select a MFR type ...'],
        'addon' => [
            'prepend' => [
                'content' => Icon::show('factory', [], Icon::WHHG),
            ],
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
