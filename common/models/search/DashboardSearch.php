<?php
namespace common\models\search;

use common\models\Agreement;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DashboardSearch extends Model
{
    public $full_info;
    public $year;
    public $status;
    public $agreement_type;
    public $transfer_to;
    public $country;
    public $chartType;
    public $kcdio;

    public function rules()
    {
        return [
            [['year', 'status', 'agreement_type', 'transfer_to', 'country', 'chartType', 'kcdio', 'full_info'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Agreement::find()
            ->joinWith(['agreementPoc', 'collaboration']);

        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if ($this->status === 'Active') {
            $query->andWhere(['OR', ['status' => 100], ['status' => 91]]);
        }elseif ($this->status === 'Expired') {
            $query->andWhere(['OR', ['status' => 102], ['status' => 92]]);
        }

        $query->andFilterWhere([
            'agreement.agreement_type' => $this->agreement_type,
            'agreement.transfer_to' => $this->transfer_to,
            'agreement.expiration_date' => $this->year,
            'agreement_poc.pi_kcdio' => $this->kcdio
        ]);

        $query->andFilterWhere([
            'agreement_poc.pi_kcdio' => $this->kcdio,
            'agreement_poc.pi_is_primary' => true,
        ]);

        $query
            ->orFilterWhere(['ilike', 'collaboration.col_organization', $this->full_info])
            ->orFilterWhere(['ilike', 'collaboration.col_name', $this->full_info])
            ->orFilterWhere(['ilike', 'collaboration.col_address', $this->full_info])
            ->orFilterWhere(['ilike', 'collaboration.col_contact_details', $this->full_info])
            ->orFilterWhere(['ilike', 'collaboration.col_collaborators_name', $this->full_info])
            ->orFilterWhere(['ilike', 'collaboration.col_wire_up', $this->full_info])
            ->orFilterWhere(['ilike', 'collaboration.col_phone_number', $this->full_info])
            ->orFilterWhere(['ilike', 'collaboration.col_email', $this->full_info])
            ->orFilterWhere(['ilike', 'collaboration.country', $this->full_info])
            ->orFilterWhere(['ilike', 'agreement.project_title', $this->full_info])
            ->orFilterWhere(['ilike', 'agreement.grant_fund', $this->full_info])
            ->orFilterWhere(['ilike', 'agreement.member', $this->full_info])
            ->orFilterWhere(['ilike', 'agreement.proposal', $this->full_info])
            ->orFilterWhere(['ilike', 'agreement.ssm', $this->full_info])
            ->orFilterWhere(['ilike', 'agreement.company_profile', $this->full_info])
            ->orFilterWhere(['ilike', 'agreement.reason', $this->full_info])
            ->orFilterWhere(['ilike', 'agreement.agreement_type', $this->full_info])
            ->orFilterWhere(['ilike','agreement_poc.pi_name', $this->full_info]);

        return $dataProvider;
    }


    public function getChartData()
    {
        $query = Agreement::find()
            ->joinWith(['agreementPoc', 'collaboration']);

        // Apply the same filters used in the search method
        $query->andFilterWhere([
            'agreement.agreement_type' => $this->agreement_type,
            'agreement.transfer_to' => $this->transfer_to,
            'agreement.expiration_date' => $this->year,
        ]);

        $query->andFilterWhere([
            'agreement_poc.pi_kcdio' => $this->kcdio,
            'agreement_poc.pi_is_primary' => true,
        ]);

        if ($this->status === 'Active') {
            $query->andWhere(['OR', ['status' => 100], ['status' => 91]]);
        }elseif ($this->status === 'Expired') {
            $query->andWhere(['OR', ['status' => 102], ['status' => 92]]);
        }

        $query
            ->andFilterWhere([
                'or',
                ['ilike', 'collaboration.col_organization', $this->full_info],
                ['ilike', 'collaboration.col_name', $this->full_info],
                ['ilike', 'collaboration.col_address', $this->full_info],
                ['ilike', 'collaboration.col_contact_details', $this->full_info],
                ['ilike', 'collaboration.col_collaborators_name', $this->full_info],
                ['ilike', 'collaboration.col_wire_up', $this->full_info],
                ['ilike', 'collaboration.col_phone_number', $this->full_info],
                ['ilike', 'collaboration.col_email', $this->full_info],
                ['ilike', 'collaboration.country', $this->full_info],
                ['ilike', 'agreement.project_title', $this->full_info],
                ['ilike', 'agreement.grant_fund', $this->full_info],
                ['ilike', 'agreement.member', $this->full_info],
                ['ilike', 'agreement.proposal', $this->full_info],
                ['ilike', 'agreement.ssm', $this->full_info],
                ['ilike', 'agreement.company_profile', $this->full_info],
                ['ilike', 'agreement.reason', $this->full_info],
                ['ilike', 'agreement.agreement_type', $this->full_info],
                ['ilike', 'agreement_poc.pi_name', $this->full_info],
            ]);



        switch ($this->chartType) {
            case 'KCDIO':
                $query->select(['agreement_poc.pi_kcdio AS label', 'COUNT(*) AS count'])
                    ->andWhere(['agreement_poc.pi_is_primary' => true])
                    ->groupBy('agreement_poc.pi_kcdio');
                break;
            case 'agreement_type':
                $query->select(['agreement.agreement_type AS label', 'COUNT(*) AS count'])
                    ->groupBy('agreement.agreement_type');
                break;

            case 'country':
                $query->select(['collaboration.country AS label', 'COUNT(*) AS count'])
                    ->groupBy('collaboration.country');
                break;

            case 'transfer_to':
                $query->select(['agreement.transfer_to AS label', 'COUNT(*) AS count'])
                    ->groupBy('agreement.transfer_to');
                break;

            default:
                return [
                    'categories' => [],
                    'series' => [],
                    'fullData' => [],
                ];
        }

        $chartData = $query->asArray()->all();

        $categories = [];
        $seriesData = [];

        foreach ($chartData as $data) {
            $categories[] = $data['label'];
            $seriesData[] = (int)$data['count'];
        }

        return [
            'categories' => $categories,
            'series' => $seriesData,
        ];
    }

}
