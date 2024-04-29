<?php

use common\models\Kcdio;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = 'Create';
$templateFileInput = '<div class="col-md align-items-center"><div class="col-md-md-2 col-md-form-label">{label}</div><div class="col-md-md">{input}</div>{error}</div>';
?>

<?php $form = ActiveForm::begin([
    'id' => 'create-form',
    'fieldConfig' => [
        'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>",
        'labelOptions' => ['class' => ''],
    ],
]); ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'agreement_type')->dropDownList(
            [
                'MOU (Academic)' => 'MOU (Academic)',
                'MOU (Non-Academic)' => 'MOU (Non-Academic)',
                'MOA (Academic)' => 'MOA (Academic)',
                'MOA (Non-Academic)' => 'MOA (Non-Academic)'
            ],
            [
                'prompt' => 'Select Type',
                'options' => [
                    'MOU (Academic)' => ['selected' => true]
                ]
            ]
        ) ?>

    </div>
    <div class="col-md-8">
        <?= $form->field($model, 'col_organization')->textInput([
            'maxlength' => true,
            'placeholder' => '',
            'value' => 'Kansai University' // Set your default value here
        ]) ?>

    </div>
</div>


<div class="row">
    <div class="col-md">
        <?= $form->field($model, 'col_name')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => 'Dr. Prof. Keiko IKEDA' ]) ?>
        <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true, 'placeholder' => '', 'value'=> '81663681174 
']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'col_address')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => 'Center for International Education, Division of International Affairs']) ?>
        <?= $form->field($model, 'col_email')->textInput([
            'type' => 'email', // Correct syntax for setting the input type
            'maxlength' => true,
            'placeholder' => 'mi-room@ml.kandai.jp',
            'value' => 'mi-room@ml.kandai.jp' // Set your default email value here
        ]) ?>

    </div>
</div>
<?= $form->field($model, 'col_collaborators_name')->textarea(['maxlength' => true, 'placeholder' => '', 'rows' => 6, 'value' => 'names......']) ?>


<div class="row">
    <div class="col-md-8">
        <?= $form->field($model, 'col_wire_up')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => 'wire up .....']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'country')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => 'Japan']) ?>
    </div>
</div>
<h4>Person In Charge Details</h4>

<div class="row">
    <div class="col-md poc">
        <?= $form->field($model, 'needMe')->hiddenInput(['value' => '1'])->label(false) ?>
        <?= $form->field($model, 'pi_name')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false) ?>
        <?= $form->field($model, 'pi_kulliyyah')->hiddenInput(['value' => Yii::$app->user->identity->type])->label(false) ?>
        <?= $form->field($model, 'pi_email')->hiddenInput(['value' => Yii::$app->user->identity->email])->label(false) ?>
        <?= $form->field($model, 'pi_phone_number')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => '0193079894']) ?>
    </div>
</div>

<div id="extra-pi-fields-container"></div>
<button class="btn btn-lg btn-dark text-capitalize mb-3" onclick="handleAdd()" data-clicks="0">Add person in charge
</button>
<?= $form->field($model, 'project_title')->textarea(['rows' => 6, 'value' => 'Project Title Title Project']) ?>
<div class="row">
    <div class="col-md">
        <?= $form->field($model, 'grant_fund')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => '1000']) ?>
    </div>

    <div class="col-md">
        <?= $form->field($model, 'member')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => '10']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'transfer_to')->dropDownList(
            ['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'],
            [
                'prompt' => 'select OSC',
                'options' => [
                    'IO' => ['selected' => true] // Set 'IO' as default
                ]
            ]
        ) ?>

    </div>
</div>


<?php //= $form->field($model, 'sign_date')->textInput() ?>
<!---->
<?php //= $form->field($model, 'end_date')->textInput() ?>



<?= $form->field($model, 'proposal')->textarea(['rows' => 6, 'maxlength' => true, 'value' => 'proposal.....................']) ?>



<?= $form->field($model, 'fileUpload', ['template' => $templateFileInput])->fileInput()->label('Document') ?>

<div class="modal-footer p-0">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'checked', 'value' => 10]) ?>
</div>
<?php ActiveForm::end(); ?>
