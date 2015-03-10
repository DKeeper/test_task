<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 09.03.15
 * @time 19:28
 * Created by JetBrains PhpStorm.
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\library\BooksSearch;

class AjaxController extends Controller
{
    public function init(){
        if(Yii::$app->request->isAjax)
            Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }

    public function actionGetbooks($term=null, $page=null){
        $out['results'] = [];

        if(!isset($term))
            return $out;

        $params = [
            'BooksSearch' => [
                'name' => $term,
            ]
        ];

        if(isset($page))
            $params['page'] = $page;

        $bookSearchModel = new BooksSearch();
        $bookDataProvider = $bookSearchModel->search($params);

        if($bookDataProvider->totalCount>0){
            foreach($bookDataProvider->models as $book){
                $out['results'][] = ['id'=>$book->id,'text'=>$book->name];
            }
        }
        if($bookDataProvider->totalCount > count($out['results'])){
            $out['more'] = true;
        }
        return $out;
    }
}
