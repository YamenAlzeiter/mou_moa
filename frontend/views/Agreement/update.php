<?php

use common\models\McomDate;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = 'Update Agreement: '.$model->id;
$templateFileInput = '<div class="col align-items-center"><div class="col-md-2 col-form-label">{label}</div><div class="col-md">{input}</div>{error}</div>';

$status = [
    2 => 15,
    12 => 15,
    11 => 21,
    33 => 15,
    43 => 15,
    81 => 91,
];

$currentDate = date('Y-m-d'); // Get the current date in the format 'YYYY-MM-DD'


?>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],
    ],
]); ?>


<?php if ($model->status == 2 || $model->status == 12 || $model->status == 33 || $model->status == 43): ?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'agreement_type')->dropDownList(['MOU (Academic)' => 'MOU (Academic)', 'MOU (Non-Academic)' => 'MOU (Non-Academic)', 'MOA (Academic)' => 'MOA (Academic)', 'MOA (Non-Academic)' => 'MOA (Non-Academic)'], ['prompt' => 'Select Type']) ?>

    </div>
    <div class="col-md-8">
        <?= $form->field($model, 'col_organization')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>


<div class = "row">
    <div class = "col-md">
        <?= $form->field($model, 'col_name')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true, 'placeholder'=>'']) ?>
    </div>
    <div class = "col-md">
        <?= $form->field($model, 'col_address')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        <?= $form->field($model, 'col_email')->textInput(['type => email', 'maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>
<?= $form->field($model, 'col_collaborators_name')->textarea(['maxlength' => true, 'placeholder' => '', 'rows' => 6]) ?>


<div class="row">
    <div class="col-md-8">
        <?= $form->field($model, 'col_wire_up')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'country')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>
<h4>Person In Charge Details</h4>

<div class = "row">
    <div class = "col-md">
        <?= $form->field($model, 'pi_name')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false)?>
        <?= $form->field($model, 'pi_kulliyyah')->hiddenInput(['value' => Yii::$app->user->identity->type])->label(false)?>
        <?= $form->field($model, 'pi_email')->hiddenInput(['value' => Yii::$app->user->identity->email])->label(false)?>
        <?= $form->field($model, 'pi_phone_number')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
    <?= $form->field($model, 'project_title')->textarea(['rows' => 6]) ?>
    <div class = "row">
        <div class = "col-md">
            <?= $form->field($model, 'grant_fund')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        </div>

        <div class = "col-md">
            <?= $form->field($model, 'member')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        </div>
        <div class = "col-md">
            <?= $form->field($model, 'transfer_to')->dropDownList(['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'], ['prompt' => 'select OSC']) ?>
        </div>
    </div>

    <?= $form->field($model, 'proposal')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fileUpload', ['template' => $templateFileInput])->fileInput()->label('Document') ?>

    <?php elseif ($model->status == 11): ?>

        <?= $form->field($model, 'mcom_date')->dropDownList(ArrayHelper::map(McomDate::find()->where([
                '<', 'counter', 20
            ])->andWhere(['>', 'date', $currentDate])->all(), 'date', function ($model) {
            return 'Date: '.' '.$model->date.', available: '.' '.(20 - $model->counter);
        }), ['prompt' => 'Select a Date']) ?>

    <?php elseif ($model->status == 81): ?>
        <div class = "row">
            <div class = "col-md"><?= $form->field($model, 'sign_date')->textInput(['type' => 'date']) ?></div>
            <div class = "col-md"><?= $form->field($model, 'end_date')->textInput(['type' => 'date']) ?></div>
        </div>
        <div class = "row">
            <div class = "col-md"><?= $form->field($model, 'ssm')->textInput([
                    'maxlength' => true, 'placeholder' => ''
                ]) ?></div>
            <div class = "col-md"><?= $form->field($model, 'company_profile')->textInput([
                    'maxlength' => true, 'placeholder' => ''
                ]) ?></div>
        </div>
        <?= $form->field($model, 'executedAgreement',
            ['template' => $templateFileInput])->fileInput()->label('Document') ?>
    <?php elseif ($model->status == 110): ?>
        <h4>Do You want to Extend the Agreement?</h4>
        <div class = "mb-2">
            <?= $form->field($model, 'status')->radioList(['91' => 'Yes', '92' => 'No'], [
                'class' => 'gap-2 row', // Use flexbox
                'item' => function ($index, $label, $name, $checked, $value) {
                    return '<label class=" col-md  border-dark-light px-4 py-5 border rounded-4 text-nowrap fs-4">'.Html::radio($name,
                            $checked, ['id' => "is".$value, 'value' => $value, 'class' => 'mx-2']).$label.'</label>';
                }
            ])->label(false); ?>
            <div class="end_date d-none">
                <div class = "col-md"><?= $form->field($model, 'end_date')->textInput(['type' => 'date']) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($model->status == 110):?>
    <div class = "modal-footer p-0">
        <?= Html::submitButton('Submit',
            ['class' => 'btn btn-success', 'name' => 'checked']) ?>
        <?php ActiveForm::end(); ?>
    </div>
    <?php else:?>
    <div class = "modal-footer p-0">
        <?= Html::submitButton('Submit',
            ['class' => 'btn btn-success', 'name' => 'checked', 'value' => $status[$model->status]]) ?>
        <?php ActiveForm::end(); ?>
    </div>
    <?php endif;?>
    <script>
        $("#is91").on("change", function () {

            if (this.checked) {
                $(".end_date").removeClass('d-none');
            }
        });
        $("#is92").on("change", function () {

            if (this.checked) {
                $(".end_date").addClass('d-none');
            }
        });
    </script>