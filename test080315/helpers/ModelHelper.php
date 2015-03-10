<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 09.03.15
 * @time 15:25
 * Created by JetBrains PhpStorm.
 */

namespace app\helpers;

class ModelHelper
{
    /**
     * @param $query \yii\db\ActiveQuery
     * @param int $min
     * @param int $max
     * @return \app\models\library\BaseModel[]
     */
    public static function getRandomModelList($query,$min=1,$max=10){
        $count = $query->count();
        $res = [];
        $i = mt_rand($min,$max);
        for(;$i>0;$i--){
            $res[] = $query->offset(mt_rand(0,$count))->limit(1)->one();
        }
        return $res;
    }
}
