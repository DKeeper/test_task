<?php

namespace app\models\library;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%authors_books}}".
 *
 * @property integer $id
 * @property integer $author_id
 * @property integer $book_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Books $book
 * @property Authors $author
 */
class AuthorsBooks extends \yii\db\ActiveRecord
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
        return '{{%authors_books}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id', 'book_id'], 'required'],
            [['author_id', 'book_id', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
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
    public function getAuthor()
    {
        return $this->hasOne(Authors::className(), ['id' => 'author_id']);
    }
}
