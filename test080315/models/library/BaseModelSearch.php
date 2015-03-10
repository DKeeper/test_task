<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 08.03.15
 * @time 16:43
 * Created by JetBrains PhpStorm.
 */
namespace app\models\library;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\base\InvalidConfigException;
use app\models\library\BaseModel;

class BaseModelSearch extends BaseModel
{
    public $searchModel;

    public function init()
    {
        if(!isset($this->searchModel)){
            throw new InvalidConfigException("Property 'searchModel' must be set.");
        }
        $this->searchModel = "app\\models\\library\\".$this->searchModel;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        /** @var $model BaseModel */
        $model = Yii::createObject($this->searchModel);
        $query = $model::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
