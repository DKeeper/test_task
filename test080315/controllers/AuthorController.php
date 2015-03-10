<?php

namespace app\controllers;

use Yii;
use app\controllers\BaseController;
use yii\helpers\ArrayHelper;
use app\models\library\AuthorsBooks;

/**
 * AuthorController implements the CRUD actions for Authors model.
 */
class AuthorController extends BaseController
{
    public $modelClassName = "Authors";

    public function actionCreate(){

        if(!Yii::$app->request->isPost)
            return parent::actionCreate();

        /** @var $model \app\models\library\Authors */
        $model = new $this->modelClassName;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $books = explode(',',$model->postBooks);
            foreach($books as $book){
                if($book==-1) continue;
                $rel = new AuthorsBooks;
                $rel->author_id = $model->id;
                $rel->book_id = $book;
                $rel->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('@app/views/base/update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        if(!Yii::$app->request->isPost)
            return parent::actionUpdate($id);

        /** @var $model \app\models\library\Authors */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $bookIDs = array_keys(ArrayHelper::map($model->getAuthorsBooks()->select('book_id')->asArray()->all(),'book_id',function(){return '';}));
            $postBooks = explode(',',$model->postBooks);
            foreach($postBooks as $i => $id){
                if($id==-1) {
                    unset($postBooks[$i]);
                    continue;
                }
                if(!in_array($id,$bookIDs)){
                    $rel = new AuthorsBooks;
                    $rel->author_id = $model->id;
                    $rel->book_id = $id;
                    $rel->save();
                } else {
                    unset($postBooks[$i]);
                    unset($bookIDs[array_search($id,$bookIDs)]);
                }
            }
            foreach($bookIDs as $id){
                $model->getAuthorsBooks()->where('book_id=:i',[':i'=>$id])->one()->delete();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('@app/views/base/update', [
                'model' => $model,
            ]);
        }
    }
}
