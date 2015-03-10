<?php

namespace app\models\library;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\library\BaseModel;
use app\models\library\BooksTrait;

/**
 * This is the model class for table "{{%authors}}".
 *
 * @property AuthorsBooks[] $authorsBooks
 * @property Books[] $books
 */
class Authors extends BaseModel
{
    use BooksTrait;

    public $postBooks = '';

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            [['postBooks'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%authors}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorsBooks()
    {
        return $this->hasMany(AuthorsBooks::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::className(),['id' => 'book_id'])->via('authorsBooks');
    }
}
