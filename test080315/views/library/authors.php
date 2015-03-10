<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 08.03.15
 * @time 19:10
 * Created by JetBrains PhpStorm.
 */

use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel \app\models\library\Authors */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
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
        [
            'class' => DataColumn::className(),
            'format' => 'html',
            'label' => 'Books',
            'value' => function ($model, $key, $index, $column) {
                /** @var $model \app\models\library\Authors */
                return implode('<br/>',ArrayHelper::map($model->books,'id','name'));
            }
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'controller' => 'author'
        ],
    ],
]); ?>