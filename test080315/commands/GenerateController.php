<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 09.03.15
 * @time 5:41
 * Created by JetBrains PhpStorm.
 */
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\BaseInflector;

class GenerateController extends Controller
{
    public function actionIndex()
    {
        $this->stdout("Available commands:\n");
        $this->stdout(" yii generate/createauthors [count=1000] [out=false]\n");
        $this->stdout(" yii generate/createreaders [count=1000] [out=false]\n");
        $this->stdout(" yii generate/createbooks [count=1000] [out=false]\n");
        $this->stdout(" yii generate/duplicate fromTable=authors toTable=readers\n");
        $this->stdout(" yii generate/createbooksauthors [maxAuthors=5] [offsetBooks=0] [limitBooks=-1] [out=false]\n");
        $this->stdout(" yii generate/createbooksreaders [maxReaderss=5] [offsetBooks=0] [limitBooks=-1] [out=false]\n");
    }

    public function actionCreatebooksauthors($max=5,$offset=0,$limit=-1,$out=false){
        if(!is_numeric($max) || !is_numeric($offset) || !is_numeric($limit)) return;

        $this->stdout("Start generate relations\n");

        $query = \app\models\library\Books::find();

        if($offset>0)
            $query->offset($offset);

        if($limit>-1){
            $query->limit($limit);
            $count = $limit;
        } else {
            $count = $query->count();
        }

        $i = 0;

        foreach($query->each() as $book){
            $i++;
            /** @var $book \app\models\library\Books */
            /** @var $authors \app\models\library\Authors[] */
            $authors = \app\helpers\ModelHelper::getRandomModelList(\app\models\library\Authors::find(),1,$max);
            foreach($authors as $author){
                $model = new \app\models\library\AuthorsBooks;
                $model->author_id = $author->id;
                $model->book_id = $book->id;
                $model->save();
            }
            if($out){
                $this->stdout("Create relations (".Yii::$app->formatter->asPercent($i/$count,2).") for book[id:$book->id] - ".count($authors)." authors\n");
            }
        }

        $this->stdout("Complete!\n");
    }

    public function actionCreatebooksreaders($max=5,$offset=0,$limit=-1,$out=false){
        if(!is_numeric($max) || !is_numeric($offset) || !is_numeric($limit)) return;

        $this->stdout("Start generate relations\n");

        $query = \app\models\library\Books::find();

        if($offset>0)
            $query->offset($offset);

        if($limit>-1){
            $query->limit($limit);
            $count = $limit;
        } else {
            $count = $query->count();
        }

        $i = 0;

        foreach($query->each() as $book){
            $i++;
            /** @var $book \app\models\library\Books */
            /** @var $readers \app\models\library\Readers[] */
            $readers = \app\helpers\ModelHelper::getRandomModelList(\app\models\library\Readers::find(),1,$max);
            foreach($readers as $reader){
                $model = new \app\models\library\ReadersBooks();
                $model->reader_id = $reader->id;
                $model->book_id = $book->id;
                $model->save();
            }
            if($out){
                $this->stdout("Create relations (".Yii::$app->formatter->asPercent($i/$count,2).") for book[id:$book->id] - ".count($readers)." readers\n");
            }
        }

        $this->stdout("Complete!\n");
    }

    public function actionDuplicate($fromTable,$toTable)
    {
        if(!isset($fromTable) || !isset($toTable)) return;

        $sql = "INSERT INTO " .
            Yii::$app->db->getSchema()->quoteTableName("{{%$fromTable}}") .
            " (`id`, `name`, `created_at`, `updated_at`) SELECT * FROM " .
            Yii::$app->db->getSchema()->quoteTableName("{{%$toTable}}");

        $this->stdout("Start duplicate data from $fromTable to $toTable\n");

        Yii::$app->db->createCommand($sql)->execute();

        $this->stdout("Complete!\n");
    }

    public function actionCreateauthors($count=1000,$out=false)
    {
        if($count<0 || !is_numeric($count)) return;

        $this->stdout("Start generate authors\n");

        $className = \app\models\library\Authors::className();

        $this->generateModel($className,$count,$out);
    }

    public function actionCreatereaders($count=1000,$out=false)
    {
        if($count<0 || !is_numeric($count)) return;

        $this->stdout("Start generate readers\n");

        $className = \app\models\library\Readers::className();

        $this->generateModel($className,$count,$out);
    }

    public function actionCreatebooks($count=1000,$out=false)
    {
        if($count<0 || !is_numeric($count)) return;

        $this->stdout("Start generate books\n");

        $className = \app\models\library\Books::className();

        $this->generateModel($className,$count,$out);
    }

    private function generateModel($modelClassName,$count,$out)
    {
        if($modelClassName!=="app\\models\\library\\Books"){
            $names = file(Yii::getAlias('@app/data/names.txt'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $namesCount = count($names)-1;
            $surnames = file(Yii::getAlias('@app/data/surnames.txt'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $surnamesCount = count($surnames)-1;

            for($i=0;$i<$count;$i++){
                /** @var $model \app\models\library\BaseModel */
                $model = new $modelClassName;
                $model->name = BaseInflector::titleize($surnames[mt_rand(1,$surnamesCount)] . " " . $names[mt_rand(1,$namesCount)],true);
                $model->save();
                if($out){
                    $this->stdout("Generate model (".Yii::$app->formatter->asPercent($i/$count,2)."): " . $model->name . "\n");
                }
            }
        } else {
            $words = file(Yii::getAlias('@app/data/words.txt'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $wordsCount = count($words)-1;

            for($i=0;$i<$count;$i++){
                $numWords = mt_rand(1,5);
                $bookName = "";
                for($j=0;$j<$numWords;$j++){
                    $bookName .= $words[mt_rand(1,$wordsCount)]." ";
                }
                /** @var $model \app\models\library\BaseModel */
                $model = new $modelClassName;
                $model->name = BaseInflector::titleize(trim($bookName));
                $model->save();
                if($out){
                    $this->stdout("Generate model (".Yii::$app->formatter->asPercent($i/$count,2)."): " . $model->name . "\n");
                }
            }
        }
        $this->stdout("Complete!\n");
    }
}
