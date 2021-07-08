<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Repairs;

/**
 * RepairsSearch represents the model behind the search form of `app\models\Repairs`.
 */
class RepairsSearch extends Repairs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'receipt', 'money'], 'integer'],
            [['date', 'client', 'phone', 'service_name', 'equipment', 'serial_id', 'facilities', 'problem', 'username', 'result_name'], 'safe'],
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
        $query = Repairs::find();

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
            'receipt' => $this->receipt,
            'date' => $this->date,
            'money' => $this->money,
        ]);

        $query->andFilterWhere(['ilike', 'client', $this->client])
            ->andFilterWhere(['ilike', 'phone', $this->phone])
            ->andFilterWhere(['ilike', 'service_name', $this->service_name])
            ->andFilterWhere(['ilike', 'equipment', $this->equipment])
            ->andFilterWhere(['ilike', 'serial_id', $this->serial_id])
            ->andFilterWhere(['ilike', 'facilities', $this->facilities])
            ->andFilterWhere(['ilike', 'problem', $this->problem])
            ->andFilterWhere(['ilike', 'username', $this->username])
            ->andFilterWhere(['ilike', 'result_name', $this->result_name]);

        return $dataProvider;
    }
}
