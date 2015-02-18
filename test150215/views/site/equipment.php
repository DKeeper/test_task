<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 15.02.15
 * @time 10:05
 * Created by JetBrains PhpStorm.
 */

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use kartik\icons\Icon;
use app\models\Mfr;

/* @var $this yii\web\View */
$this->title = 'Equipment';
$this->params['breadcrumbs'][] = $this->title;

Icon::map($this, Icon::WHHG);

?>
<div class="row site-equipment">
    <div class="col-md-6">
        <?= Select2::widget([
        'name' => 'mfr_select',
        'id' => 'mfr_select',
        'data' => ArrayHelper::map(Mfr::find()->orderBy('name')->asArray()->all(),'id','name'),
        'options' => ['placeholder' => 'Select a MFR type ...'],
        'addon' => [
            'prepend' => [
                'content' => Icon::show('factory', [], Icon::WHHG),
            ],
        ],
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= DepDrop::widget([
        'type'=>DepDrop::TYPE_SELECT2,
        'name' => 'product_select',
        'options'=>['id'=>'product_select', 'placeholder'=>'Select ...'],
        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
        'pluginOptions'=>[
            'depends'=>['mfr_select'],
            'url'=>Url::to(['/ajax/getproducts']),
        ]
        ]) ?>
    </div>
</div>