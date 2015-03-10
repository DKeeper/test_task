<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\library\BooksSearch;
use app\models\library\AuthorsSearch;
use app\models\library\ReadersSearch;

class LibraryController extends Controller
{
    public function actionBooks()
    {
        $bookSearchModel = new BooksSearch();
        $bookDataProvider = $bookSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('books',[
            'searchModel' => $bookSearchModel,
            'dataProvider' => $bookDataProvider,
        ]);
    }

    public function actionAuthors()
    {
        $authorSearchModel = new AuthorsSearch();
        $authorDataProvider = $authorSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('authors',[
            'searchModel' => $authorSearchModel,
            'dataProvider' => $authorDataProvider,
        ]);
    }

    public function actionReaders()
    {
        $authorSearchModel = new ReadersSearch();
        $authorDataProvider = $authorSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('readers',[
            'searchModel' => $authorSearchModel,
            'dataProvider' => $authorDataProvider,
        ]);
    }

    public function actionReport()
    {
        $min = 5;
        $max = 5;

        $bookSearchModel = new BooksSearch();
        $bookDataProvider = $bookSearchModel->searchWithReaders(Yii::$app->request->queryParams);
        $authorSearchModel = new AuthorsSearch();
        $authorDataProvider = $authorSearchModel->searchWithReaders(Yii::$app->request->queryParams);

        $randomBookDataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => \app\helpers\ModelHelper::getRandomModelList(\app\models\library\Books::find(),$min,$max)
        ]);

        return $this->render('report',[
            'bookSearchModel' => $bookSearchModel,
            'bookDataProvider' => $bookDataProvider,
            'authorSearchModel' => $authorSearchModel,
            'authorDataProvider' => $authorDataProvider,
            'randomDataProvider' => $randomBookDataProvider,
        ]);
    }

    public function actionIndex()
    {
        return $this->redirect(['report']);
    }
}
