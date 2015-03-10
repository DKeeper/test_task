<?php

namespace app\models\library;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\library\BaseModelSearch;

/**
 * AuthorsSearch represents the model behind the search form about `app\models\library\Authors`.
 */
class AuthorsSearch extends BaseModelSearch
{
    public $searchModel = "Authors";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%authors}}';
    }

    public function searchWithReaders($params)
    {
        /** @var $model Authors */
        $model = Yii::createObject($this->searchModel);
        $query = $model::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
                'pageParam' => 'authors-page',
                'pageSizeParam' => 'authors-per-page',
            ],
        ]);

        $query->joinWith('books.readersBooks',true,'JOIN')->groupBy(Authors::tableName().'.id')->having('COUNT('.Authors::tableName().'.id) > :r',[':r'=>3]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            Authors::tableName().'.id' => $this->id,
            Authors::tableName().'.created_at' => $this->created_at,
            Authors::tableName().'.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', Authors::tableName().'.name', $this->name]);

        return $dataProvider;
    }
}
