<?php

use Carbon\Carbon;
use common\helpers\agreementPocMaker;
use common\helpers\pocFieldMaker;
use common\models\AgreementType;
use common\models\Kcdio;
use common\models\McomDate;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = 'Update Agreement: ' . $model->id;
$templateFileInput = '<div class="col-md align-items-center"><div class="col-md-md-2 col-md-form-label">{label}</div>
                        <div class="col-md-md">{input}{error}</div></div>';

$status = [2 => 15, 12 => 15, 11 => 21, 33 => 21, 34 => 31, 47 => 46, 43 => 21, 51 => 61, 72 => 61,];

$additionalPoc = new pocFieldMaker();

$currentDate = Carbon::now();
$nextTwoWeeks = $currentDate->copy()->addWeeks(2);
$nextTwoMonth = $currentDate->copy()->addMonths(2);

$model->mcom_date = '';
$additionalPoc = new agreementPocMaker();
?>
<?php $form = ActiveForm::begin(['id' => 'update-form', 'fieldConfig' => ['template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],],]); ?>


<?php if (in_array($model->status, [2, 12, 33, 34, 43, 47])): ?>
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
            <?= $form->field($model, 'col_name')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
            <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        </div>
        <div class="col-md">
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
    <?php foreach ($modelsPoc as $index => $modelPoc):
        $additionalPoc->renderUpdatedPocFields($form, $modelPoc, $index);
        //id needed but it's not included in get methode ..........sadly
        echo $form->field($modelPoc, "[$index]id", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['value' => $modelPoc->id, 'maxlength' => true, 'readonly' => true])->label(false);
    endforeach; ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'project_title')->textarea(['rows' => 6, 'value' => 'Project Title Title Project']) ?>
        </div>
<!--        <div class="col-md-3">-->
<!--            <div class="col-md">--><?php //= $form->field($model, 'champion')->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag', 'kcdio'), ['prompt' => 'select champion']) ?><!--</div>-->
<!--        </div>-->
        <?= $form->field($model, 'champion')->hiddenInput(['value'=> Yii::$app->user->identity->type])->label(false) ?>
    </div>
    <div class="row">
        <div class="col-md">
            <?= $form->field($model, 'grant_fund')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        </div>

        <div class="col-md">
            <?= $form->field($model, 'member')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'transfer_to')->dropDownList(['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'], ['prompt' => 'select OSC']) ?>
        </div>
    </div>

    <?= $form->field($model, 'proposal')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'files_applicant[]', ['template' => $templateFileInput])->fileInput(['multiple' => true])->label('Document') ?>

<?php endif; ?>
<?php
if (in_array($model->status, [11, 33, 43])): ?>

    <?= $form->field($model, 'mcom_date')->dropDownList(ArrayHelper::map(McomDate::find()->where(['<', 'counter', 10])->andWhere(['>', 'date', $nextTwoWeeks->toDateString()])->andWhere(['<', 'date', $nextTwoMonth->toDateString()])->limit(3) // Limit the number of results to three
    ->all(), 'date', function ($model) {
        return 'Date: ' . ' ' . $model->date . ', available: ' . ' ' . (10 - $model->counter);
    }), ['prompt' => 'Select a Date', 'required' => true] // Adding 'required' => true here
    ) ?>


<?php
elseif (in_array($model->status, [51, 72])): ?>
    <!--    --><?php //= $form->field($model, 'status')->hiddenInput(['value' => $status[$model->status]])->label(false) ?>
    <?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput(['required' => true])->label('Document') ?>

<?php
elseif ($model->status == 110): ?>
    <h4>Do You want to Extend the Agreement?</h4>
    <div class="mb-2">
        <?= $form->field($model, 'status')->radioList(['91' => 'Yes', '92' => 'No'], ['class' => 'gap-2 row', // Use flexbox
            'item' => function ($index, $label, $name, $checked, $value) {
                return '<label class=" col-md  border-dark-light px-4 py-5 border rounded-4 text-nowrap fs-4">' . Html::radio($name, $checked, ['id' => "is" . $value, 'value' => $value, 'class' => 'mx-2']) . $label . '</label>';
            }])->label(false); ?>
        <div class="end_date d-none">
            <div class="col-md"><?= $form->field($model, 'end_date')->textInput(['type' => 'date']) ?></div>
        </div>
    </div>
<?php
endif; ?>

<?php if ($model->status == 110): ?>
    <div class="modal-footer p-0">
        <?= Html::submitButton('Submit', ['id' => 'form-update-submit', 'class' => 'btn btn-success', 'name' => 'checked']) ?>
        <?php ActiveForm::end(); ?>
    </div>
<?php else: ?>
    <div class="modal-footer p-0">
        <?= Html::submitButton('Submit', ['id' => 'form-update-submit', 'class' => 'btn btn-success', 'name' => 'checked', 'value' => $status[$model->status]]) ?>
        <?php ActiveForm::end(); ?>
    </div>
<?php endif; ?>
<?php
$existingFilesSize = 0;
$baseUploadPath = Yii::getAlias('@common/uploads') . '/' . $model->id . '/applicant/';
$storedFiles = array_diff(scandir($baseUploadPath), ['.', '..']);

foreach ($storedFiles as $file) {
    $filePath = $baseUploadPath . DIRECTORY_SEPARATOR . $file;
    if (is_file($filePath)) {
        $existingFilesSize += filesize($filePath);
    }
}
?>

<script>
    $(document).ready(function () {

        const existingFilesSize = <?= $existingFilesSize ?>;
        const submitButton = $('#form-update-submit');
        console.log('files size: ' + existingFilesSize);
        const sizeLimit = 10 * 1024 * 1024; // 1 MB in bytes

        $('input[type="file"][name="Agreement[files_applicant][]"]').on('change', function () {
            let uploadedSize = 0;

            const files = $(this)[0].files;
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    uploadedSize += files[i].size;
                }
            }

            const totalSize = existingFilesSize + uploadedSize;
            console.log('Total size: ' + totalSize);

            if (totalSize > sizeLimit) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Size Limit Exceeded',
                    text: 'The total size of uploaded files exceeds the limit of 1 MB.',
                }).then(() => {
                    // Clear the file input if the limit is exceeded
                    $(this).val('');
                    submitButton.prop('disabled', true);
                    submitButton.addClass('btn-danger');
                    submitButton.removeClass('btn-sucess')
                });

            } else {
                // Enable the submit button if the limit is not exceeded
                submitButton.prop('disabled', false);
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
