<?php

use Carbon\Carbon;
use common\helpers\agreementPocMaker;
use common\helpers\pocFieldMaker;
use common\models\Agreement;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var common\models\Collaboration $colModel */

$this->title = 'Update Agreement: ' . $model->id;
$templateFileInput = '<div class="col-md align-items-center"><div class="col-md-md-2 col-md-form-label">{label}</div>
                        <div class="col-md-md">{input}{error}</div></div>';

$status = [2 => 15, 12 => 15, 11 => 21, 33 => 21, 34 => 31, 47 => 46, 43 => 21, 51 => 61, 72 => 61,];

$additionalPoc = new pocFieldMaker();

$currentDate = Carbon::now();
$nextTwoWeeks = $currentDate->copy()->addWeeks(2);
$nextTwoMonth = $currentDate->copy()->addMonths(2);
$roleData = [];


$model->mcom_date = '';
$additionalPoc = new agreementPocMaker();
foreach ($modelsPoc as $index => $modelPoc) {
    $roleData[$index] = $modelPoc->pi_role;
}

$existingTypes = (array) $model->agreement_type;

// Predefined types
$predefinedTypes = [
    'MOU (Academic)',
    'MOU (Non-Academic)',
    'MOA (Academic)',
    'MOA (Non-Academic)',
    'RCA',
    'other'
];
$filteredExistingTypes = array_filter($existingTypes, function($type) use ($predefinedTypes) {
    return !in_array($type, $predefinedTypes);
});

$options = ArrayHelper::merge(
    array_combine($filteredExistingTypes, $filteredExistingTypes),
    array_combine($predefinedTypes, $predefinedTypes)
);
?>
<?php $form = ActiveForm::begin(['id' => 'update-form', 'fieldConfig' => ['template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],],]); ?>


<?php if (in_array($model->status, [2, 12, 33, 34, 43, 47])): ?>
    <div class="row">
        <div class="col-md-4">
            <?=$form->field($model, 'agreement_type')->dropDownList(
                $options,
                [
                    'prompt' => 'Select Type',
                    'id' => 'agreement-type-dropdown'
                ]
            );
            ?>
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
            <?= $form->field($colModel, 'col_organization')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($colModel, 'col_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($colModel, 'col_phone_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md">
            <?= $form->field($colModel, 'col_address')->textInput(['maxlength' => true]) ?>
            <?= $form->field($colModel, 'col_email')->textInput(['type' => 'email']) ?>
        </div>
    </div>
    <?= $form->field($colModel, 'col_collaborators_name')->textarea(['maxlength' => true, 'rows' => 6]) ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($colModel, 'col_wire_up')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($colModel, 'country')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <!-- Collaborator details end -->

<div id="poc-container">

    <?php foreach ($modelsPoc as $index => $modelPoc):
        $additionalPoc->renderUpdatedPocFields($form, $modelPoc, $index);
        echo $form->field($modelPoc, "[$index]id", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['value' => $modelPoc->id, 'maxlength' => true, 'readonly' => true])->label(false);
    endforeach; ?>
</div>
    <div class="d-grid mb-3">
        <?= Html::button('Add person in charge', ['class' => 'btn btn-dark btn-block btn-lg', 'id' => 'add-poc-button']) ?>
    </div>

    <!-- Project information start -->
    <h4>Project Information</h4>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'project_title')->textarea(['rows' => 6, 'value' => 'Project Title Title Project']) ?>
        </div>
    </div>
    <div id="rmc-additional-info" class="row d-none">
        <div class="col-md">
            <?= $form->field($model, 'rmc_start_date')->textInput(['type' => 'date', 'id' => 'project-start-date']) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'rmc_end_date')->textInput(['type' => 'date', 'id' => 'project-end-date']) ?>
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
    <?= $form->field($model,'pi_delete_ids')->hiddenInput()->label(false)?>
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
        <?= $form->field($model, 'status')->radioList(['111' => 'Yes', '92' => 'No'], ['class' => 'gap-2 row', // Use flexbox
            'item' => function ($index, $label, $name, $checked, $value) {
                return '<label class=" col-md  border-dark-light px-4 py-5 border rounded-4 text-nowrap fs-4">' . Html::radio($name, $checked, ['id' => "is" . $value, 'value' => $value, 'class' => 'mx-2']) . $label . '</label>';
            }])->label(false); ?>
        <div class="end_date d-none">
            <div class="col-md"><?= $form->field($model, 'agreement_expiration_date')->textInput(['type' => 'date']) ?></div>
        </div>
    </div>
<?php
endif; ?>

<?php if ($model->status == 110): ?>
    <div class="modal-footer p-0">
        <?= Html::submitButton('Submit', ['id' => 'form-update-submit', 'class' => 'btn-submit', 'name' => 'checked']) ?>
        <?php ActiveForm::end(); ?>
    </div>
<?php else: ?>
    <div class="modal-footer p-0">
        <?= Html::submitButton('Submit', ['id' => 'form-update-submit', 'class' => 'btn-submit', 'name' => 'checked', 'value' => $status[$model->status]]) ?>
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
        var roleData = <?= json_encode($roleData); ?>;
            function populateRoleDropdowns() {
                var selectedValue = $('#transfer-to-dropdown').val();
                var $roleDropdowns = $('.role-dropdown');

                $roleDropdowns.each(function(index) {
                    var $this = $(this);
                    var currentValue = roleData[index];
                    $this.empty();
                    $this.append($('<option>', { value: '', text: 'Select Role' }));

                    var options = [];
                    if (selectedValue === 'IO' || selectedValue === 'OIL') {
                        options = [
                            { value: 'Project Leader', text: 'Project Leader'},
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

// Call the function when the page loads to initialize the dropdowns
            $(document).ready(function() {
                populateRoleDropdowns();

                // Attach the function to the change event of the transfer-to dropdown
                $('#transfer-to-dropdown').change(function() {
                    populateRoleDropdowns();
                });
            });

        $('#transfer-to-dropdown').on('change', populateRoleDropdowns);
        $('#transfer-to-dropdown').trigger('change');

        $('#add-poc-button').on('click', function() {

            var pocIndex = $('#poc-container .poc-row').length;
            console.log(pocIndex)
            if (pocIndex < 5) {
                var newRow = `<?php $additionalPoc->renderExtraPocFields($form, new \common\models\AgreementPoc());?>`;
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
        var deletedPocIds = [];

        $(document).on('click', '.remove-poc-button', function() {
            var index = $(this).data('index');
            var pocId = $('input[name="AgreementPoc[' + index + '][id]"]').val();
            if (pocId) {
                deletedPocIds.push(pocId);
            }

            $('#poc-row-' + index).remove();
            $('#agreement-pi_delete_ids').val(deletedPocIds.join(','));

        });
    });
    $(document).ready(function () {

        const existingFilesSize = <?= $existingFilesSize ?>;
        const submitButton = $('#form-update-submit');
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
        if ($('#is111').is(':checked')) {
            $('.end_date').removeClass('d-none');
        } else {
            $('.end_date').addClass('d-none');
        }
    });

    

JS;
$this->registerJs($script);
?>
