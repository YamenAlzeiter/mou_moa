<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Testing;

/**
 * TestingSearch represents the model behind the search form of `common\models\Testing`.
 */
class TestingSearch extends Testing
{
    public $fullinfo;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['first_name', 'last_name', 'email', 'email1', 'fullinfo'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Testing::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->orFilterWhere(['ilike', 'first_name', $this->fullinfo])
            ->orFilterWhere(['ilike', 'last_name', $this->fullinfo])
            ->orFilterWhere(['ilike', 'email', $this->fullinfo])
            ->orFilterWhere(['ilike', 'email1', $this->fullinfo]);

        return $dataProvider;
    }
}
