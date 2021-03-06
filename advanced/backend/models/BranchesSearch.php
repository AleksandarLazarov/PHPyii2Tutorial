<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Branches;

/**
 * BranchesSearch represents the model behind the search form about `backend\models\Branches`.
 */
class BranchesSearch extends Branches
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id'], 'integer'],        //companies_company_id beshe tuk no trqbva da e dolu
                                                //Poletata ot GRID-a koito sa integer sa gore, STRING-ovete se mestqt dolu
            [['branch_name', 'companies_company_id', 'branch_address', 'branch_created_date', 'branch_status'], 'safe'],
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
        $query = Branches::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('companiesCompany');    //Dobavqme uslovie v zaqvkata za da mojem da tarsim

        $dataProvider->setSort([
            'attributes'=>[
                'branch_name',
                'branch_status',
                'companies_company_id'=>[
                    'asc'=>['companies.company_name'=>SORT_ASC],
                    'desc'=>['companies.company_name'=>SORT_DESC],
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'branch_id' => $this->branch_id,
//            'companies_company_id' => $this->companies_company_id,    //Tova tarsi po ID,ako iskame po dugo pole vij DOLU
            'branch_created_date' => $this->branch_created_date,
        ]);

        $query->andFilterWhere(['like', 'branch_name', $this->branch_name])
            ->andFilterWhere(['like', 'branch_address', $this->branch_address])
            ->andFilterWhere(['like', 'branch_status', $this->branch_status])
            ->andFilterWhere(['like', 'companies.company_name', $this->companies_company_id]);  //Tova pozvolqva da tarsim po NAME
                                //companies.company_name e poleto po koeto tarsim()
        return $dataProvider;
    }
}
