<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="agreement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'col_organization')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_contact_details')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_collaborators_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_wire_up')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'col_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pi_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pi_kulliyyah')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pi_phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pi_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_title')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'grant_fund')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sign_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <?= $form->field($model, 'member')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proposal')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'ssm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_profile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mcom_date')->textInput() ?>

    <?= $form->field($model, 'meeting_link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_applicant')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_draft')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_newer_draft')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_re_draft')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_final')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_extra')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reason')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
