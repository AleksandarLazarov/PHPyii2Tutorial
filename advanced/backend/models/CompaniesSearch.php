<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Companies;

/**
 * CompaniesSearch represents the model behind the search form about `backend\models\Companies`.
 */
class CompaniesSearch extends Companies
{
    /**
     * @inheritdoc
     */
    public $globalSearch; //Проперти зза глобално търсене

    public function rules()
    {
        return [
            [['company_id'], 'integer'],
            [['company_name', 'globalSearch', 'company_email', 'company_address', 'company_created_date', 'company_status'], 'safe'],
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
        $query = Companies::find();

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
/*Това беше преди и търси по GRID Search
//        $query->orFilterWhere([
//            'company_id'=>$this->company_id,
//            'company_create_date'=>$this->company_create_date,
//        ]);

//        $query->orFilterWhere(['like', 'company_name', $this->company_name])
//            ->orFilterWhere(['like', 'company_email', $this->company_email])
//            ->orFilterWhere(['like', 'company_address', $this->company_address])
//            ->orFilterWhere(['like', 'company_status', $this->company_status]);
Тук свършва старото
*/
        /*Това е $query-то за globalSearch*/
        $query->orFilterWhere(['like', 'company_name', $this->globalSearch])
            ->orFilterWhere(['like', 'company_email', $this->globalSearch])
            ->orFilterWhere(['like', 'company_address', $this->globalSearch])
            ->orFilterWhere(['like', 'company_status', $this->globalSearch]);

        return $dataProvider;
    }
}
