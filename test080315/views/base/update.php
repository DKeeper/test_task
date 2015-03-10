<?php

use yii\helpers\Html;
use yii\helpers\BaseInflector;

/* @var $this yii\web\View */
/* @var $model app\models\library\BaseModel */

$this->title = 'Update '.BaseInflector::titleize($this->context->id).': ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => BaseInflector::titleize($this->context->id), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="authors-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
