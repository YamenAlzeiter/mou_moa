<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Agreement;

/**
 * AgreementSearch represents the model behind the search form of `common\models\Agreement`.
 */
class AgreementSearch extends Agreement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['col_organization', 'col_name', 'col_address', 'col_contact_details', 'col_collaborators_name', 'col_wire_up', 'col_phone_number', 'col_email', 'pi_name', 'pi_kulliyyah', 'pi_phone_number', 'pi_email', 'project_title', 'grant_fund', 'sign_date', 'end_date', 'member', 'proposal', 'ssm', 'company_profile', 'mcom_date', 'meeting_link', 'doc_applicant', 'doc_draft', 'doc_newer_draft', 'doc_re_draft', 'doc_final', 'doc_extra', 'reason', 'transfer_to', 'agreement_type'], 'safe'],
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
            'status' => $this->status,
            'mcom_date' => $this->mcom_date,
        ]);

        $query->andFilterWhere(['ilike', 'col_organization', $this->col_organization])
            ->andFilterWhere(['ilike', 'col_name', $this->col_name])
            ->andFilterWhere(['ilike', 'col_address', $this->col_address])
            ->andFilterWhere(['ilike', 'col_contact_details', $this->col_contact_details])
            ->andFilterWhere(['ilike', 'col_collaborators_name', $this->col_collaborators_name])
            ->andFilterWhere(['ilike', 'col_wire_up', $this->col_wire_up])
            ->andFilterWhere(['ilike', 'col_phone_number', $this->col_phone_number])
            ->andFilterWhere(['ilike', 'col_email', $this->col_email])
            ->andFilterWhere(['ilike', 'pi_name', $this->pi_name])
            ->andFilterWhere(['ilike', 'pi_kulliyyah', $this->pi_kulliyyah])
            ->andFilterWhere(['ilike', 'pi_phone_number', $this->pi_phone_number])
            ->andFilterWhere(['ilike', 'pi_email', $this->pi_email])
            ->andFilterWhere(['ilike', 'project_title', $this->project_title])
            ->andFilterWhere(['ilike', 'grant_fund', $this->grant_fund])
            ->andFilterWhere(['ilike', 'member', $this->member])
            ->andFilterWhere(['ilike', 'proposal', $this->proposal])
            ->andFilterWhere(['ilike', 'ssm', $this->ssm])
            ->andFilterWhere(['ilike', 'company_profile', $this->company_profile])
            ->andFilterWhere(['ilike', 'meeting_link', $this->meeting_link])
            ->andFilterWhere(['ilike', 'doc_applicant', $this->doc_applicant])
            ->andFilterWhere(['ilike', 'doc_draft', $this->doc_draft])
            ->andFilterWhere(['ilike', 'doc_newer_draft', $this->doc_newer_draft])
            ->andFilterWhere(['ilike', 'doc_re_draft', $this->doc_re_draft])
            ->andFilterWhere(['ilike', 'doc_final', $this->doc_final])
            ->andFilterWhere(['ilike', 'doc_extra', $this->doc_extra])
            ->andFilterWhere(['ilike', 'reason', $this->reason])
            ->andFilterWhere(['ilike', 'transfer_to', $this->transfer_to])
            ->andFilterWhere(['ilike', 'agreement_type', $this->agreement_type]);

        return $dataProvider;
    }
}
