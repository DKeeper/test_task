<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\FileHelper;
use yii\data\ArrayDataProvider;
use app\models\EDocument;
use yii\elasticsearch\Exception;

//use yii\filters\VerbFilter;
//use yii\filters\AccessControl;
//use app\models\LoginForm;
//use app\models\ContactForm;

class SiteController extends Controller
{
    public $layout = 'zebra';

//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['logout'],
//                'rules' => [
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

//    public function actions()
//    {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//            ],
//        ];
//    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPartner(){
        return $this->render('partner');
    }

    public function actionTariff(){
        return $this->render('tariff');
    }

    public function actionServices(){
        return $this->render('services');
    }

    public function actionOffers(){
        $_c = Yii::$app->components;
        $_esIsActive = false;
        $_esErrMessage = '';
        $fileList = [];
        $dataProvider = null;

        if(isset($_c['elasticsearch'])){
            /** @var $_es \yii\elasticsearch\Connection */
            $_es = Yii::$app->elasticsearch;
            /** test connection */
            try{
                $_es->open();
                $_esIsActive = $_es->isActive;
                $_es->close();
            }
            catch (Exception $e){
                $_esIsActive = false;
                $_esErrMessage = $e->getMessage();
            }

            if(isset($_POST) && isset($_POST['clearIndex'])){
                EDocument::deleteAll();
            }

            if(FileHelper::createDirectory(Yii::getAlias('@webroot/upload'))){
                foreach(FileHelper::findFiles(Yii::getAlias('@webroot/upload')) as $file){
                    $indexed = false;
                    if($_esIsActive){
                        $indexedDocument = EDocument::find()->where(['name' => pathinfo($file,PATHINFO_FILENAME)])->one();
                        $indexed = !is_null($indexedDocument);
                    }
                    $fileList[] = [
                        'name' => pathinfo($file,PATHINFO_FILENAME),
                        'ext' => pathinfo($file,PATHINFO_EXTENSION),
                        'size' => filesize($file),
                        'fullPath' => $file,
                        'indexed' => $indexed,
                    ];
                }
            }

            if(isset($_POST) && isset($_POST['updateIndex'])){
                foreach($fileList as $i => $file){
                    $doc = new EDocument;
                    $doc->primaryKey = Yii::$app->security->generateRandomString();
                    $doc->attributes = [
                        'name' => $file['name'],
                        'ext' => $file['ext'],
                        'size' => $file['size'],
                        'fullPath' => $file['fullPath'],
                        'content' => file_get_contents($file['fullPath']),
                    ];
                    $fileList[$i]['indexed'] = $doc->save();
                }
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $fileList,
                'sort' => [
                    'attributes' => ['name', 'ext', 'size', 'indexed'],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }



        return $this->render('offers',[
            'elasticsearch' => isset($_c['elasticsearch']),
            'active' => $_esIsActive,
            'err' => $_esErrMessage,
            'data' => $dataProvider,
        ]);
    }

    public function actionEquipment(){
        return $this->render('equipment');
    }

    /*public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }*/
}
