<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Agreements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agreement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'col_organization',
            'col_name',
            'col_address',
            'col_contact_details',
            'col_collaborators_name',
            'col_wire_up',
            'col_phone_number',
            'col_email:email',
            'country',
            'pi_name',
            'pi_kulliyyah',
            'pi_phone_number',
            'pi_email:email',
            'project_title:ntext',
            'grant_fund',
            'sign_date',
            'end_date',
            'member',
            'progress:ntext',
            'status',
            'ssm',
            'company_profile',
            'mcom_date',
            'meeting_link',
            'agreement_type',
            'transfer_to',
            'doc_applicant',
            'doc_draft',
            'doc_newer_draft',
            'doc_re_draft',
            'doc_final',
            'doc_extra',
            'reason:ntext',
            'updated_at',
            'created_at',
            'pi_details:ntext',
            'col_details:ntext',
            'collaboration_area:ntext',
            'proposal',
            'doc_executed',
            'pi_name_extra',
            'pi_kulliyyah_extra',
            'pi_phone_number_extra',
            'pi_email_extra:email',
            'pi_name_extra2',
            'pi_kulliyyah_extra2',
            'pi_phone_number_extra2',
            'pi_email_extra2:email',
        ],
    ]) ?>

</div>
