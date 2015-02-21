<?php
/**
 * Контроллер по умолчанию.
 * Закомментированы стандартные действия, добавлены новые в соответствии с заданием
 */

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
        $searchDataProvider = null;
        $term = '';

        /**
         * Проверяем наличие компонента elasticsearch
         */
        if(isset($_c['elasticsearch'])){
            /** @var $_es \yii\elasticsearch\Connection */
            $_es = Yii::$app->elasticsearch;
            /** test connection */
            try{
                /** Проверяем соединение, в случае ошибки ловим и выводим сообщение о причинах сбоя */
                $_es->open();
                $_esIsActive = $_es->isActive;
                $_es->close();
            }
            catch (Exception $e){
                $_esIsActive = false;
                $_esErrMessage = $e->getMessage();
            }

            /**
             * Если пришел запрос на поиск в индексе
             */
            if(isset($_POST) && isset($_POST['term'])){
                $term = $_POST['term'];
                $qes = ["query"=>["match"=>["content"=>$term]]];
                /**
                 * Получаем результаты запроса для термина, введенного пользователем
                 */
                $result = EDocument::find()->createCommand()->db->get([EDocument::index(),EDocument::type(),'_search'],[],\yii\helpers\Json::encode($qes));
                $models=[];
                /**
                 * Если что-то нашли, заполняем модели и получаем массив данных для дальнейшего вывода в таблице
                 */
                if($result['hits']['total']>0){
                    foreach($result['hits']['hits'] as $doc){
                        /** @var $model EDocument */
                        $model = EDocument::instantiate($doc);
                        EDocument::populateRecord($model, $doc);
                        $models[] = $model->getAttributes(['name','ext','size']);
                    }
                }

                /**
                 * Создаем провайдер данных для найденных результатов
                 */
                $searchDataProvider = new ArrayDataProvider([
                    'allModels' => $models,
                    'sort' => [
                        'attributes' => ['name', 'ext', 'size'],
                    ],
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]);
            }

            /**
             * Если пришел запрос на удаление индекса - удаляем все имеющиеся документы
             */
            if(isset($_POST) && isset($_POST['clearIndex'])){
                EDocument::deleteAll();
            }

            /**
             * Сканируем папку, где находятся загруженные документы
             */
            if(FileHelper::createDirectory(Yii::getAlias('@webroot/upload'))){
                /**
                 * Каждый найденный файл, если компонент активен, ищем в индексе и помечаем состояние - добавлен или нет
                 */
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

            /**
             * Если пришел запрос на обновление индекса, перебираем найденные файлы и если они есть - обновляем
             * иначе вставляем новый документ
             */
            if(isset($_POST) && isset($_POST['updateIndex'])){
                foreach($fileList as $i => $file){
                    $doc = EDocument::find()->where(['name' => $file['name']])->one();
                    if(is_null($doc)){
                        $doc = new EDocument;
                        $doc->primaryKey = Yii::$app->security->generateRandomString();
                    }
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

            /**
             * Провайдер данных для документов, найденных в папке
             */
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
            'searchData' => $searchDataProvider,
            'term' => $term,
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
