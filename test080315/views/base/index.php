<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\BaseInflector;

/* @var $this yii\web\View */
/* @var $searchModel app\models\library\BaseModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = BaseInflector::titleize($this->context->id);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authors-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create '.$this->title, ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'layout' => "{summary}\n{pager}\n{items}\n{pager}",
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'maxButtonCount' => 15,
            'firstPageLabel' => '&laquo;&laquo;',
            'lastPageLabel' => '&raquo;&raquo;',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
