<?php

namespace app\models\library;

use Yii;
use app\models\library\BaseModel;

/**
 * This is the model class for table "{{%books}}".
 *
 * @property AuthorsBooks[] $authorsBooks
 * @property ReadersBooks[] $readersBooks
 * @property Authors[] $authors
 * @property Readers[] $readers
 */
class Books extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%books}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorsBooks()
    {
        return $this->hasMany(AuthorsBooks::className(), ['book_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReadersBooks()
    {
        return $this->hasMany(ReadersBooks::className(), ['book_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Authors::className(),['id' => 'author_id'])->via('authorsBooks');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReaders()
    {
        return $this->hasMany(Readers::className(),['id' => 'reader_id'])->via('readersBooks');
    }
}
