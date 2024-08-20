<?php

namespace common\models\search;

use Carbon\Carbon;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Agreement;
use yii\db\Expression;

/**
 * AgreementSearch represents the model behind the search form of `common\models\Agreement`.
 */
class   AgreementSearch extends Agreement
{
    public $full_info;
    public $applications;
    public $endDate;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [[['id', 'status'], 'integer'], [['col_organization', 'col_name', 'country', 'col_address', 'col_contact_details', 'col_collaborators_name', 'col_wire_up', 'col_phone_number', 'col_email', 'pi_name', 'pi_kulliyyah', 'pi_phone_number', 'pi_email', 'project_title', 'grant_fund', 'agreement_sign_date', 'agreement_expiration_date', 'member', 'proposal', 'ssm', 'company_profile', 'mcom_date', 'doc_applicant', 'doc_draft', 'doc_newer_draft', 'doc_re_draft', 'doc_final', 'doc_extra', 'reason', 'transfer_to', 'agreement_type', 'full_info', 'type', 'endDate', 'applications'], 'safe'],];
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
        $query = Agreement::find()
            ->joinWith(['agreementPoc', 'collaboration']);

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        // Calculate target ranges
        $today = Carbon::now();
        $oneYearFromNow = $today->copy()->addYear()->startOfYear();
        $sixMonthsFromNow = $today->copy()->addMonths(6)->startOfMonth();
        $threeMonthsFromNow = $today->copy()->addMonths(3)->startOfMonth();
        $twoMonthsFromNow = $today->copy()->addMonths(2)->startOfMonth();
        $oneMonthFromNow = $today->copy()->addMonth()->startOfMonth();

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Grid filtering conditions
        $query->andFilterWhere([
            'agreement.agreement_sign_date' => $this->agreement_sign_date,
            'agreement.agreement_expiration_date' => $this->agreement_expiration_date,
            'agreement.mcom_date' => $this->mcom_date,
            'agreement.agreement_type' => $this->agreement_type
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
            ->orFilterWhere(['agreement_poc.pi_name' => $this->full_info])
            ->orFilterWhere(['agreement_poc.pi_kcdio' => $this->full_info])
            ->andFilterWhere(['ilike', 'transfer_to', $this->transfer_to])
            ->andFilterWhere(['agreement.id' => $this->id]);

        if ($this->applications === 'new_applications') {
            $query->andWhere(['not in', 'status', [100, 102, 91, 92]]);
        } elseif ($this->applications === 'active_applications') {
            $query->andWhere(['OR', ['status' => 100], ['status' => 91]]);
        } elseif ($this->applications === 'expired_applications') {
            $query->andWhere(['OR', ['status' => 102], ['status' => 92]]);
        }

        if ($this->endDate === '1 Year') {
            $query->andWhere(['>=', 'agreement_expiration_date', $oneYearFromNow->toDateString()])
                ->andWhere(['<=', 'agreement_expiration_date', $oneYearFromNow->addYear()->startOfYear()->toDateString()]);
        } elseif ($this->endDate === '6 Month') {
            $query->andWhere(['>=', 'agreement_expiration_date', $sixMonthsFromNow->toDateString()])
                ->andWhere(['<=', 'agreement_expiration_date', $sixMonthsFromNow->addMonth()->startOfMonth()->toDateString()]);
        } elseif ($this->endDate === '3 Month') {
            $query->andWhere(['>=', 'agreement_expiration_date', $threeMonthsFromNow->toDateString()])
                ->andWhere(['<=', 'agreement_expiration_date', $threeMonthsFromNow->addMonth()->startOfMonth()->toDateString()]);
        } elseif ($this->endDate === '2 Month') {
            $query->andWhere(['>=', 'agreement_expiration_date', $twoMonthsFromNow->toDateString()])
                ->andWhere(['<=', 'agreement_expiration_date', $twoMonthsFromNow->addMonth()->startOfMonth()->toDateString()]);
        } elseif ($this->endDate === '1 Month') {
            $query->andWhere(['>=', 'agreement_expiration_date', $oneMonthFromNow->toDateString()])
                ->andWhere(['<=', 'agreement_expiration_date', $oneMonthFromNow->addMonth()->startOfMonth()->toDateString()]);
        }

        // Aggregate data to count agreement types
        $agreementCounts = Agreement::find()
            ->select(['agreement_type', 'COUNT(*) AS count'])
            ->groupBy('agreement_type')
            ->asArray()
            ->all();

        // Prepare data for the chart
        $chartData = [
            'categories' => [],
            'series' => [],
        ];

        foreach ($agreementCounts as $data) {
            $chartData['categories'][] = $data['agreement_type'];
            $chartData['series'][] = (int) $data['count'];
        }

        // Pass the chart data to the view
        Yii::$app->params['chartData'] = $chartData;

        return $dataProvider;
    }
    public function getAgreementCountsByCountry()
    {
        return Agreement::find()
            ->select(['country', 'COUNT(*) AS count'])
            ->groupBy('country')
            ->asArray()
            ->all();
    }

    public function getExecutedAgreementsCount()
    {
        return Agreement::find()
            ->where(['status' => [91, 100]])
            ->count();
    }

    public function getExpiredAgreementsCount()
    {
        return Agreement::find()
            ->where(['status' => [92, 102]])
            ->count();
    }


}
