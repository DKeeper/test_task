<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mfr */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Mfr',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mfrs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mfr-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
