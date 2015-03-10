<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 08.03.15
 * @time 13:49
 * Created by JetBrains PhpStorm.
 */
namespace app\controllers;

use Yii;
use app\models\library\BaseModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use yii\filters\VerbFilter;

class BaseController extends Controller
{
    public $modelClassName;

    public $searchModelClassName;

    public function init()
    {
        if(!isset($this->modelClassName)){
            throw new InvalidConfigException("Property 'modelClassName' must be set.");
        }
        $this->modelClassName = "app\\models\\library\\".$this->modelClassName;
        if(!isset($this->searchModelClassName))
            $this->searchModelClassName = $this->modelClassName."Search";
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all BaseModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new $this->searchModelClassName;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@app/views/base/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BaseModel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('@app/views/base/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BaseModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var $model BaseModel */
        $model = new $this->modelClassName;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('@app/views/base/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BaseModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('@app/views/base/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BaseModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Readers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BaseModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var $model BaseModel */
        $model = Yii::createObject($this->modelClassName);
        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
