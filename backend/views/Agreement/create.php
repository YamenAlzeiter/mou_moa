<?php

use Carbon\Carbon;
use common\helpers\agreementPocMaker;
use common\models\AgreementType;
use common\models\Kcdio;
use common\models\McomDate;
use common\models\Poc;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$additionalPoc = new agreementPocMaker();

$currentDate = Carbon::now();
$nextTwoWeeks = $currentDate->copy()->addWeeks(2);
$nextTwoMonth = $currentDate->copy()->addMonths(2);

$this->title = 'Create';
$templateFileInput = '<div class="col-md align-items-center"><div class="col-md-md-2 col-md-form-label">{label}</div>
                        <div class="col-md-md">{input}{error}</div></div>';
?>

<?php $form = ActiveForm::begin(['id' => 'create-form', 'fieldConfig' => ['template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],],]); ?>

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
    <?php foreach ($modelsPoc as $index => $modelPoc):?>
        <div class="row poc-row">

            <div class="col-12 col-md-6">
                <?= $form->field($modelPoc, "[$index]pi_kcdio")
                    ->dropDownList(
                        ArrayHelper::map(Kcdio::find()->all(), 'tag', 'kcdio'), ['prompt' => 'Select KCDIO', 'onchange' => '$.get("' . Yii::$app->urlManager->createUrl('agreement/get-kcdio-poc') . '", { id: $(this).val() }, function (data) {
            $("select#agreement-poc_name_getter-' . $index . '").html(data);
            $("select#agreement-poc_name_getter-' . $index . '").trigger("change");
        })']) ?>
            </div>
            <div class="col-12 col-md-6">
                <?= $form->field($modelPoc, "[$index]pi_name")->dropDownList(ArrayHelper::map(Poc::find()->where(['kcdio' => $modelPoc->pi_kcdio])->all(), 'id', 'name'), ['prompt' => 'Select POC', 'id' => 'agreement-poc_name_getter-'.$index, 'onchange' => '$.get("/agreement/get-poc-info", { id: $(this).val() })
                        .done(function(data) {
                    $("#' . Html::getInputId($modelPoc, "[$index]pi_name") . '").val(data.name);
                    $("#' . Html::getInputId($modelPoc, "[$index]pi_address") . '").val(data.address);
                    $("#' . Html::getInputId($modelPoc, "[$index]pi_email") . '").val(data.email);
                    $("#' . Html::getInputId($modelPoc, "[$index]pi_phone") . '").val(data.phone_number);
                })
                              .fail(function() {
                    $("#' . Html::getInputId($modelPoc, "[$index]pi_name") . '").val("");
                    $("#' . Html::getInputId($modelPoc, "[$index]pi_address") . '").val("");
                    $("#' . Html::getInputId($modelPoc, "[$index]pi_email") . '").val("");
                    $("#' . Html::getInputId($modelPoc, "[$index]pi_phone") . '").val("");
                });']) ?>

            </div>

            <?= $form->field($modelPoc, "[$index]pi_name", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput()->label(false) ?>
            <div class="col-12 col-md-6">
                <?= $form->field($modelPoc, "[$index]pi_email")->textInput(['maxlength' => true, 'readonly' => true])->label('Email') ?>
            </div>
            <div class="col-12 col-md-6">
                <?= $form->field($modelPoc, "[$index]pi_phone")->textInput(['maxlength' => true, 'readonly' => true])->label('Phone Number') ?>
            </div>
            <div class="col-12 col-md-12">
            </div>
            <?= $form->field($modelPoc, "[$index]pi_address")->textInput(['maxlength' => true, 'readonly' => true])->label('Address') ?>
        </div>
    <?php endforeach; ?>
</div>
<div class="row mb-3">
    <div class="col"><?= Html::button('Add another POC', ['class' => 'btn btn-outline-dark btn-block btn-lg', 'id' => 'add-poc-button']) ?></div>
</div>

<?= $form->field($model, 'project_title')->textarea(['rows' => 6, 'value' => 'Project Title Title Project']) ?>
<div class="row">
    <div class="col-md">
        <?= $form->field($model, 'grant_fund')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => '1000']) ?>
    </div>

    <div class="col-md">
        <?= $form->field($model, 'member')->textInput(['maxlength' => true, 'placeholder' => '', 'value' => '10']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'transfer_to')->dropDownList(['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'], ['prompt' => 'select OSC', 'options' => ['IO' => ['selected' => true] // Set 'IO' as default
        ]]) ?>

    </div>
</div>


<?php //= $form->field($model, 'sign_date')->textInput() ?>
<!---->
<?php //= $form->field($model, 'end_date')->textInput() ?>



<?= $form->field($model, 'proposal')->textarea(['rows' => 6, 'maxlength' => true, 'value' => 'proposal.....................']) ?>

<?= $form->field($model, 'mcom_date')->dropDownList(
    ArrayHelper::map(
        McomDate::find()
            ->where(['<', 'counter', 10])
            ->andWhere(['>', 'date', $nextTwoWeeks->toDateString()])
            ->andWhere(['<', 'date', $nextTwoMonth->toDateString()])
            ->limit(3) // Limit the number of results to three
            ->all(),
        'date',
        function ($model) {
            return 'Date: ' . ' ' . $model->date . ', available: ' . ' ' . (10 - $model->counter);
        }
    ),
    ['prompt' => 'Select a Date']
) ?>

<div class="row">
    <div class="col-md"><?= $form->field($model, 'sign_date')->textInput(['type' => 'date']) ?></div>
    <div class="col-md"><?= $form->field($model, 'end_date')->textInput(['type' => 'date']) ?></div>
</div>


<?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput()->label('Document') ?>

<div class="modal-footer p-0">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'checked', 'value' => 91]) ?>
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
}).change(); // Trigger change event initially to set initial state

$('#{$form->id}').on('beforeSubmit', function(){
    if ($('#agreement-type-dropdown').val() === 'other') {
        var otherValue = $('#{$model->formName()}-agreement_type_other').val();
        $('#{$model->formName()}-agreement_type').val(otherValue);
    }
    return true;
});
JS;
$this->registerJs($script);
?>
