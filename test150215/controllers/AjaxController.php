<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 18.02.15
 * @time 10:58
 * Created by JetBrains PhpStorm.
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\ArrayHelper;

class AjaxController extends Controller
{
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['post'],
                ],
            ],
        ];
    }

    public function init(){
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function actionIndex(){
        if(Yii::$app->request->isAjax && isset($_POST['task'])){
            $task = $_POST['task'];
            $param = isset($_POST['param']) ? array($_POST['param']) : [];
            if(method_exists($this,$task)){
                return call_user_func_array([$this,$task],$param);
            }
        }
        return [];
    }

    public function actionGetproducts(){
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $mfr_id = $parents[0];
                $out = \app\models\Product::find()->select(['id', 'name'])->where('mfr_id = :p',[':p'=>$mfr_id])->asArray()->all();
                return ['output'=>$out, 'selected'=>''];
            }
        }
        return ['output'=>'', 'selected'=>''];
    }
}
