<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 15.02.15
 * @time 10:05
 * Created by JetBrains PhpStorm.
 */

use yii\helpers\Html;
use kartik\icons\Icon;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $elasticsearch boolean */
/* @var $active boolean */
/* @var $err string */
/* @var $term string */
/* @var $data yii\data\ArrayDataProvider */
/* @var $searchData yii\data\ArrayDataProvider */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Offers';
$this->params['breadcrumbs'][] = $this->title;

Icon::map($this, Icon::WHHG);

?>
<style>
    .site-offers{
        background-color: #fff;
        color: #000;
        padding: 10px;
    }
    .search-box{
        height: 36px;
    }
    .document-search-index-form{
        margin: 10px 0;
    }
</style>
<div class="site-offers">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if($elasticsearch){
        ?>
        Elasticsearch component status : <?= Icon::show($active?'ok':'remove', [], Icon::WHHG) ?> <?= $active?'active':'not active'?>
        <?= empty($err) ? "" : "<code>".$err."</code>"; ?><br>
        <?php
        if($active){
            ?>
            <div class="document-search-index-form">
                <?php $form = ActiveForm::begin(); ?>
                <div class="input-group">
                    <?= Html::input('text','term',$term,['class'=>'form-control search-box']) ?>
                    <span class="input-group-btn"><?= Html::submitButton(Icon::show('search', [], Icon::WHHG), ['class' => 'btn btn-default']) ?></span>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <?php
            if(isset($searchData)){
                echo GridView::widget([
                    'dataProvider' => $searchData,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'name',
                        'ext',
                        'size:shortsize',
                    ],
                ]);
            }
            ?>
            <?php
        }
        ?>
        <?= GridView::widget([
            'dataProvider' => $data,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'ext',
                'size:shortsize',
                [
                    'class' => DataColumn::className(),
                    'attribute' => 'indexed',
                    'format' => 'html',
                    'label' => 'Indexed',
                    'value' => function($model){
                        return Icon::show($model['indexed']?'ok':'remove', [], Icon::WHHG);
                    }
                ],
            ],
        ]); ?>
        <?php
            if($active){
                ?>
                <div class="document-update-index-form">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= Html::hiddenInput('updateIndex') ?>
                    <div class="form-group">
                        <?= Html::submitButton('Update index', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="document-clear-index-form">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= Html::hiddenInput('clearIndex') ?>
                    <div class="form-group">
                        <?= Html::submitButton('Clear index', ['class' => 'btn btn-danger']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <?php
            }
    } else {
        ?>
        <?= Html::a('elasticserach','https://github.com/yiisoft/yii2-elasticsearch') ?> component can not be found.
        <?php
    }
    ?>
</div>