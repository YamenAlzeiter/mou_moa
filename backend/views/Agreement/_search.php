<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="agreement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'col_organization') ?>

    <?= $form->field($model, 'col_name') ?>

    <?= $form->field($model, 'col_address') ?>

    <?= $form->field($model, 'col_contact_details') ?>

    <?php // echo $form->field($model, 'col_collaborators_name') ?>

    <?php // echo $form->field($model, 'col_wire_up') ?>

    <?php // echo $form->field($model, 'col_phone_number') ?>

    <?php // echo $form->field($model, 'col_email') ?>

    <?php // echo $form->field($model, 'pi_name') ?>

    <?php // echo $form->field($model, 'pi_kulliyyah') ?>

    <?php // echo $form->field($model, 'pi_phone_number') ?>

    <?php // echo $form->field($model, 'pi_email') ?>

    <?php // echo $form->field($model, 'project_title') ?>

    <?php // echo $form->field($model, 'grant_fund') ?>

    <?php // echo $form->field($model, 'sign_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'member') ?>

    <?php // echo $form->field($model, 'proposal') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'ssm') ?>

    <?php // echo $form->field($model, 'company_profile') ?>

    <?php // echo $form->field($model, 'mcom_date') ?>

    <?php // echo $form->field($model, 'meeting_link') ?>

    <?php // echo $form->field($model, 'doc_applicant') ?>

    <?php // echo $form->field($model, 'doc_draft') ?>

    <?php // echo $form->field($model, 'doc_newer_draft') ?>

    <?php // echo $form->field($model, 'doc_re_draft') ?>

    <?php // echo $form->field($model, 'doc_final') ?>

    <?php // echo $form->field($model, 'doc_extra') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'transfer_to') ?>

    <?php // echo $form->field($model, 'agreement_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
