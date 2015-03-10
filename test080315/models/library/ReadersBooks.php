<?php

namespace app\models\library;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%readers_books}}".
 *
 * @property integer $id
 * @property integer $reader_id
 * @property integer $book_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Books $book
 * @property Readers $reader
 */
class ReadersBooks extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%readers_books}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reader_id', 'book_id'], 'required'],
            [['reader_id', 'book_id', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reader_id' => 'Reader ID',
            'book_id' => 'Book ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::className(), ['id' => 'book_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReader()
    {
        return $this->hasOne(Readers::className(), ['id' => 'reader_id']);
    }
}
