<?php

namespace app\models\library;

use app\models\library\BaseModelSearch;

/**
 * ReadersSearch represents the model behind the search form about `app\models\library\Readers`.
 */
class ReadersSearch extends BaseModelSearch
{
    public $searchModel = "Readers";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%readers}}';
    }
}
