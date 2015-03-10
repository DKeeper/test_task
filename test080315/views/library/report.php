<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 10.03.15
 * @time 7:42
 * Created by JetBrains PhpStorm.
 */

use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $bookSearchModel \app\models\library\Books */
/* @var $authorSearchModel \app\models\library\Authors */
/* @var $bookDataProvider yii\data\ActiveDataProvider */
/* @var $authorDataProvider yii\data\ActiveDataProvider */
/* @var $randomDataProvider yii\data\ArrayDataProvider */

?>

<?= Tabs::widget([
    'items' => [
        [
            'label' => 'Books in the hands of',
            'content' => $this->render('books',[
                'searchModel' => $bookSearchModel,
                'dataProvider' => $bookDataProvider,
            ]),
        ],
        [
            'label' => 'Popular authors',
            'content' => $this->render('authors',[
                'searchModel' => $authorSearchModel,
                'dataProvider' => $authorDataProvider,
            ])
        ],
        [
            'label' => 'Random books',
            'content' => $this->render('books',[
                'searchModel' => null,
                'dataProvider' => $randomDataProvider,
            ]),
        ],
    ]
]);
?>