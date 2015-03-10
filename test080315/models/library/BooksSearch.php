<?php

namespace app\models\library;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\library\BaseModelSearch;

/**
 * BooksSearch represents the model behind the search form about `app\models\library\Books`.
 */
class BooksSearch extends BaseModelSearch
{
    public $searchModel = "Books";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%books}}';
    }

    public function searchWithReaders($params)
    {
        /** @var $model Books */
        $model = Yii::createObject($this->searchModel);
        $query = $model::find();
        $subquery = $model::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
                'pageParam' => 'books-page',
                'pageSizeParam' => 'books-per-page',
            ],
        ]);

        $subquery->joinWith('authorsBooks',true,'JOIN')->groupBy([Books::tableName().'.id'])->having('COUNT( '.AuthorsBooks::tableName().'.id ) >= :ab',[':ab'=>3]);

        $query->from(['book'=>$subquery])->joinWith('readersBooks',true,'JOIN')->groupBy(['book.id']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'book.id' => $this->id,
            'book.created_at' => $this->created_at,
            'book.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'book.name', $this->name]);

        return $dataProvider;
    }
}
