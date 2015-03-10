<?php

namespace app\models\library;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\library\BaseModel;

/**
 * This is the model class for table "{{%readers}}".
 *
 * @property ReadersBooks[] $readersBooks
 * @property Books[] $books
 */
class Readers extends BaseModel
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
        return '{{%readers}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReadersBooks()
    {
        return $this->hasMany(ReadersBooks::className(), ['reader_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Books::className(),['id' => 'book_id'])->via('readersBooks');
    }
}
