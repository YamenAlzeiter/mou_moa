<?php

use common\helpers\agreementPocMaker;
use common\models\AgreementType;
use common\models\Kcdio;
use common\models\Poc;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/* @var $modelsPoc common\models\AgreementPoc[] */

$this->title = 'Create';
$templateFileInput = '<div class="col-md align-items-center"><div class="col-md-md-2 col-md-form-label">{label}</div>
                        <div class="col-md-md">{input}{error}</div></div>';

// get the default user for person in charge
$defaultPoc = Poc::findOne(['staff_id' => Yii::$app->user->identity->staff_id]);
$additionalPoc = new agreementPocMaker()
?>

<?php $form = ActiveForm::begin(['id' => 'create-form', 'fieldConfig' => ['template' => "<div class='form-floating'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],],]); ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'agreement_type')->dropDownList(
            ArrayHelper::merge(ArrayHelper::map(AgreementType::find()->all(), 'type', 'type'),['other' => 'Other']),
            [
                'prompt' => 'Select Type',
                'id' => 'agreement-type-dropdown'
            ]
        ) ?>
    </div>
    <div id="other-agreement-type" class="col-md-8">
        <?= $form->field($model, 'agreement_type_other')->textInput(['maxlength' => true, 'disabled' => true]) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'col_organization')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => 'Kansai University' // Set your default value here
        ]) ?>
    </div>
</div>


<div class="row">
    <div class="col-md">
        <?= $form->field($model, 'col_name')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => 'Dr. Prof. Keiko IKEDA']) ?>
        <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => '81663681174 
']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'col_address')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => 'Center for International Education, Division of International Affairs']) ?>
        <?= $form->field($model, 'col_email')->textInput(['type' => 'email', // Correct syntax for setting the input type
            'maxlength' => true, 'placeholder' => 'mi-room@ml.kandai.jp', 'value' => 'mi-room@ml.kandai.jp' // Set your default email value here
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
<div id="poc-container">
    <?php foreach ($modelsPoc as $index => $modelPoc):
        $additionalPoc->renderInitPocFields($form, $modelPoc, $index, $defaultPoc);
    endforeach; ?>
</div>

<div class="row mb-3">
    <div class="col"><?= Html::button('Add person in charge', ['class' => 'btn btn-outline-dark btn-block btn-lg', 'id' => 'add-poc-button']) ?></div>
</div>


<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'project_title')->textarea(['rows' => 6, 'value' => 'Project Title Title Project']) ?>
    </div>
    <!--    <div class="col-md-3">-->
    <!--        <div class="col-md">--><?php //= $form->field($model, 'champion')->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag', 'kcdio'), ['prompt' => 'select champion']) ?><!--</div>-->
    <!--    </div>-->
    <?= $form->field($model, 'champion')->hiddenInput(['value'=> Yii::$app->user->identity->type])->label(false) ?>
</div>
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
            ['prompt' => 'Select OSC', 'options' => ['IO' => ['selected' => true]], 'id' => 'transfer-to-dropdown']
        ) ?>
    </div>
</div>

<div id="oil-additional-info" class="row d-none">
    <div class="col-md"><?= $form->field($model, 'ssm')->textInput(['maxlength' => true, 'placeholder' => '']) ?></div>
    <div class="col-md"><?= $form->field($model, 'company_profile')->textInput(['maxlength' => true, 'placeholder' => '']) ?></div>
</div>

<?= $form->field($model, 'proposal')->textarea(['rows' => 6, 'maxlength' => true, 'value' => 'proposal.....................']) ?>
<?= $form->field($model, 'files_applicant[]', ['template' => $templateFileInput])->fileInput(['multiple' => true])->label('Document') ?>

<div class="modal-footer p-0">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'checked', 'value' => 10]) ?>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function () {
        var pocIndex = $('#poc-container .poc-row').length;
        $("#agreement-poc_name_getter-0").trigger("change");

        $('#add-poc-button').on('click', function () {

            if (pocIndex < 5) {
                var newRow = `<?php $additionalPoc->renderExtraPocFields($form, $modelPoc);?>`;
                newRow = newRow.replace(/\[pocIndex\]/g, pocIndex);
                newRow = newRow.replace(/AgreementPoc\d*\[pi_/g, 'AgreementPoc[' + pocIndex + '][pi_');
                newRow = newRow.replace(/id="agreementpoc-pocindex/g, 'id="agreementpoc-' + pocIndex);
                $('#poc-container').append(newRow);
                pocIndex++;

            } else {
                Swal.fire({
                    title: "Oops...!",
                    text: "You Can't Add More than 5 Person in Charge.",
                    icon: "error",
                });

            }
        });
    });
</script>
<?php
$script = <<< JS
$('#agreement-type-dropdown').change(function(){
     if ($(this).val() === 'other') {
        $('#other-agreement-type input').prop('disabled', false);
    } else {
        $('#other-agreement-type input').prop('disabled', true);
    }
}).change();

$('#transfer-to-dropdown').change(function(){
    if ($(this).val() === 'OIL') {
        $('#oil-additional-info').removeClass('d-none');
    } else {
        $('#oil-additional-info').addClass('d-none');
    }
}).change(); 

$('#{$form->id}').on('beforeSubmit', function(){
    if ($('#agreement-type-dropdown').val() === 'other') {
        var otherValue = $('#{$model->formName()}-agreement_type_other').val();
        $('#{$model->formName()}-agreement_type').val(otherValue);
    }
});

JS;
$this->registerJs($script);
?>
