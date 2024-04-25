<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Agreement;

/**
 * AgreementSearch represents the model behind the search form of `common\models\Agreement`.
 */
class   AgreementSearch extends Agreement
{
    public $full_info;
    public $applications;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['col_organization', 'col_name', 'country','col_address', 'col_contact_details', 'col_collaborators_name', 'col_wire_up', 'col_phone_number', 'col_email', 'pi_name', 'pi_kulliyyah', 'pi_phone_number', 'pi_email', 'project_title', 'grant_fund', 'sign_date', 'end_date', 'member', 'proposal', 'ssm', 'company_profile', 'mcom_date', 'meeting_link', 'doc_applicant', 'doc_draft', 'doc_newer_draft', 'doc_re_draft', 'doc_final', 'doc_extra', 'reason', 'transfer_to', 'agreement_type', 'full_info', 'type',
                'applications'], 'safe'],
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
        $query = Agreement::find();

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
            'sign_date' => $this->sign_date,
            'end_date' => $this->end_date,
            'mcom_date' => $this->mcom_date,
        ]);



        $query->orFilterWhere(['ilike', 'col_organization', $this->full_info])
            ->orFilterWhere(['ilike', 'col_name', $this->full_info])
            ->orFilterWhere(['ilike', 'col_address', $this->full_info])
            ->orFilterWhere(['ilike', 'col_contact_details', $this->full_info])
            ->orFilterWhere(['ilike', 'col_collaborators_name', $this->full_info])
            ->orFilterWhere(['ilike', 'col_wire_up', $this->full_info])
            ->orFilterWhere(['ilike', 'col_phone_number', $this->full_info])
            ->orFilterWhere(['ilike', 'col_email', $this->full_info])
            ->orFilterWhere(['ilike', 'pi_name', $this->full_info])
            ->orFilterWhere(['ilike', 'pi_kulliyyah', $this->full_info])
            ->orFilterWhere(['ilike', 'pi_phone_number', $this->full_info])
            ->orFilterWhere(['ilike', 'pi_email', $this->full_info])
            ->orFilterWhere(['ilike', 'project_title', $this->full_info])
            ->orFilterWhere(['ilike', 'grant_fund', $this->full_info])
            ->orFilterWhere(['ilike', 'member', $this->full_info])
            ->orFilterWhere(['ilike', 'proposal', $this->full_info])
            ->orFilterWhere(['ilike', 'ssm', $this->full_info])
            ->orFilterWhere(['ilike', 'company_profile', $this->full_info])
            ->orFilterWhere(['ilike', 'meeting_link', $this->full_info])
            ->orFilterWhere(['ilike', 'doc_applicant', $this->full_info])
            ->orFilterWhere(['ilike', 'doc_draft', $this->full_info])
            ->orFilterWhere(['ilike', 'doc_newer_draft', $this->full_info])
            ->orFilterWhere(['ilike', 'doc_re_draft', $this->full_info])
            ->orFilterWhere(['ilike', 'doc_final', $this->full_info])
            ->orFilterWhere(['ilike', 'doc_extra', $this->full_info])
            ->orFilterWhere(['ilike', 'reason', $this->full_info])
            ->orFilterWhere(['ilike', 'country', $this->full_info])
            ->andFilterWhere(['ilike','agreement_type',$this->agreement_type]);


        if ($this->applications === 'new_applications') {
            $query->andWhere(['not in', 'status', [100, 102, 91, 92]]);
    } elseif ($this->applications === 'active_applications') {
            $query->andWhere(['OR', ['status' => 100], ['status' => 91]]);
        } elseif($this->applications === 'expired_applications'){
            $query->andWhere(['OR', ['status' => 102], ['status' => 92]]);
        }
        return $dataProvider;
    }


}
