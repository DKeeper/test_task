<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 08.03.15
 * @time 13:26
 * Created by JetBrains PhpStorm.
 */

namespace app\models\library;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 */


class BaseModel extends ActiveRecord
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
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
