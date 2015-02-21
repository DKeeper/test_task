<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 20.02.15
 * @time 7:49
 * Created by JetBrains PhpStorm.
 */
namespace app\models;

use yii\elasticsearch\ActiveRecord;

class EDocument extends ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
        return ['id', 'name', 'ext', 'size', 'fullPath', 'content'];
    }

    public function rules(){
        return [
            [['name', 'ext', 'size', 'fullPath', 'content'],'safe'],
        ];
    }
    /**
     * Converts the object into an array.
     *
     * @param array $fields the fields that the output array should contain. Fields not specified
     * in [[fields()]] will be ignored. If this parameter is empty, all fields as specified in [[fields()]] will be returned.
     * @param array $expand the additional fields that the output array should contain.
     * Fields not specified in [[extraFields()]] will be ignored. If this parameter is empty, no extra fields
     * will be returned.
     * @param boolean $recursive whether to recursively return array representation of embedded objects.
     * @return array the array representation of the object
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        // TODO: Implement toArray() method.
    }
}
