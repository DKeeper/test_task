<?php

use yii\helpers\Html;
use yii\helpers\BaseInflector;

/* @var $this yii\web\View */
/* @var $model app\models\library\BaseModel */

$this->title = 'Create '.BaseInflector::titleize($this->context->id);
$this->params['breadcrumbs'][] = ['label' => BaseInflector::titleize($this->context->id), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
