<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property integer $mfr_id
 *
 * @property Mfr $mfr
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'mfr_id'], 'required'],
            [['mfr_id'], 'integer'],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'mfr_id' => Yii::t('app', 'Mfr ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMfr()
    {
        return $this->hasOne(Mfr::className(), ['id' => 'mfr_id']);
    }
}
