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
                ArrayHelper::merge(ArrayHelper::map(AgreementType::find()->all(), 'type', 'type'), ['other' => 'Other']),
                [
                    'prompt' => 'Select Type',
                    'id' => 'agreement-type-dropdown'
                ]
            ) ?>
        </div>
        <div id="other-agreement-type" class="col-md-4">
            <?= $form->field($model, 'agreement_type_other')->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'transfer_to')->dropDownList(
                ['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'],
                [
                    'prompt' => 'Select OSC',
                    'id' => 'transfer-to-dropdown'
                ]
            ) ?>
        </div>
    </div>
    <!-- Collaborator details start -->
    <div class="row">
        <h4>Collaborator Details</h4>
        <div class="col-md-12">
            <?= $form->field($model, 'col_organization')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'col_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'col_address')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'col_email')->textInput(['type' => 'email']) ?>
        </div>
    </div>
    <?= $form->field($model, 'col_collaborators_name')->textarea(['maxlength' => true, 'rows' => 6]) ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'col_wire_up')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <!-- Collaborator details end -->



    <?php foreach ($modelsPoc as $index => $modelPoc):
        $additionalPoc->renderUpdatedPocFields($form, $modelPoc, $index);
        echo $form->field($modelPoc, "[$index]id", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['value' => $modelPoc->id, 'maxlength' => true, 'readonly' => true])->label(false);
    endforeach; ?>

    <!-- Project information start -->
    <h4>Project Information</h4>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'project_title')->textarea(['rows' => 6, 'value' => 'Project Title Title Project']) ?>
        </div>
    </div>
    <div id="rmc-additional-info" class="row d-none">
        <div class="col-md">
            <?= $form->field($model, 'project_start_date')->textInput(['type' => 'date', 'id' => 'project-start-date']) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'project_end_date')->textInput(['type' => 'date', 'id' => 'project-end-date']) ?>
        </div>
        <div class="col-md">
            <p id="duration" class="mb-0 fw-bold"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md">
            <?= $form->field($model, 'grant_fund')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'member')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div id="oil-additional-info" class="row d-none">
        <div class="col-md"><?= $form->field($model, 'ssm')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md"><?= $form->field($model, 'company_profile')->textInput(['maxlength' => true]) ?></div>
    </div>
    <?= $form->field($model, 'proposal')->textarea(['rows' => 6, 'maxlength' => true]) ?>
    <?= $form->field($model, 'files_applicant[]', ['template' => $templateFileInput])->fileInput(['multiple' => true])->label('Document') ?>

<?php endif; ?>
<?php
if (in_array($model->status, [11, 33, 43])): ?>

    <?= $form->field($model, 'mcom_date')->dropDownList(
        ArrayHelper::map($mcomDates, 'date_from', function ($model) {
            $dateFrom = new DateTime($model->date_from);
            $dateUntil = new DateTime($model->date_until);
            return 'Date: ' . ' ' . $dateFrom->format('Y/M/d H:i') .", Time: " . $dateFrom->format('H:i') . " - " . $dateUntil->format('H:i') .  ', available: ' . ' ' . (10 - $model->counter);
        }),
        ['prompt' => 'Select a Date', 'required' => true]
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

    $(document).ready(function() {
        function calculateDuration() {
            var startDate = new Date($('#project-start-date').val());
            var endDate = new Date($('#project-end-date').val());

            if (startDate && endDate && startDate <= endDate) {
                var duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                $('#duration').text('Duration: ' + duration + ' days');
            } else {
                $('#duration').text('Please select valid start and end dates.');
            }
        }

        $('#project-start-date, #project-end-date').on('change', calculateDuration);
        calculateDuration();
    });

    $(document).ready(function() {
        function populateRoleDropdowns() {
            var selectedValue = $('#transfer-to-dropdown').val();
            var $roleDropdowns = $('.role-dropdown');

            $roleDropdowns.each(function() {
                var $this = $(this);
                var currentValue = $this.val();
                $this.empty();
                $this.append($('<option>', { value: '', text: 'Select Role' }));

                var options = [];
                if (selectedValue === 'IO' || selectedValue === 'OIL') {
                    options = [
                        { value: 'Project Leader', text: 'Project Leader' },
                        { value: 'Member', text: 'Member' }
                    ];
                } else if (selectedValue === 'RMC') {
                    options = [
                        { value: 'Principal Researcher', text: 'Principal Researcher' },
                        { value: 'Co Researcher', text: 'Co Researcher' }
                    ];
                }

                $.each(options, function(index, option) {
                    $this.append($('<option>', { value: option.value, text: option.text }));
                });

                $this.val(currentValue);
            });
        }

        $('#transfer-to-dropdown').on('change', populateRoleDropdowns);
        $('#transfer-to-dropdown').trigger('change');

        $('#add-poc-button').on('click', function() {
            var pocIndex = $('#poc-container .poc-row').length;
            if (pocIndex < 5) {
                var newRow = `<?php $additionalPoc->renderExtraPocFields($form, $modelPoc);?>`;
                newRow = newRow.replace(/\[pocIndex\]/g, pocIndex);
                newRow = newRow.replace(/AgreementPoc\d*\[pi_/g, 'AgreementPoc[' + pocIndex + '][pi_');
                newRow = newRow.replace(/id="agreementpoc-pocindex/g, 'id="agreementpoc-' + pocIndex);
                $('#poc-container').append(newRow);
                pocIndex++;

                populateRoleDropdowns();
            } else {
                Swal.fire({
                    title: "Oops...!",
                    text: "You Can't Add More than 5 Person in Charge.",
                    icon: "error",
                });
            }
        });

        $(document).on('click', '.remove-poc-button', function() {
            var index = $(this).data('index');
            $('#poc-row-' + index).remove();
        });
    });
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
$('#agreement-type-dropdown').change(function() {
    if ($(this).val() === 'other') {
        $('#other-agreement-type input').prop('disabled', false);
    } else {
        $('#other-agreement-type input').prop('disabled', true);
    }
}).change();

$('#transfer-to-dropdown').change(function() {
    if ($(this).val() === 'OIL') {
        $('#oil-additional-info').removeClass('d-none');
        $('#rmc-additional-info').addClass('d-none');
    }else if  ($(this).val() === 'RMC'){
        $('#rmc-additional-info').removeClass('d-none');
        $('#oil-additional-info').addClass('d-none');
    } else {
        $('#oil-additional-info').addClass('d-none');
        $('#rmc-additional-info').addClass('d-none');
    }

    var form = $('#{$form->id}');
    form.yiiActiveForm('validateAttribute', 'model-member');
    form.yiiActiveForm('validateAttribute', 'model-grant_fund');
    form.yiiActiveForm('validateAttribute', 'model-project_title');
}).change();

$('#{$form->id}').on('beforeSubmit', function() {
    if ($('#agreement-type-dropdown').val() === 'other') {
        var otherValue = $('#{$model->formName()}-agreement_type_other').val();
        $('#{$model->formName()}-agreement_type').val(otherValue);
    }
});

 $('input[name="Agreement[status]"]').on('change', function() {
        if ($('#is91').is(':checked')) {
            $('.end_date').removeClass('d-none');
        } else {
            $('.end_date').addClass('d-none');
        }
    });

    

JS;
$this->registerJs($script);
?>
