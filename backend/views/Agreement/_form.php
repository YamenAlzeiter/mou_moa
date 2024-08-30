<?php

use common\helpers\Variables;
use Itstructure\CKEditor\CKEditor;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\bootstrap5\ActiveForm $form */
$templateFileInput = '<div class="col-md align-items-center"><div class="col-md-md-2 col-md-form-label">{label}</div>
                        <div class="col-md-md">{input}{error}</div></div>';
$approveMap = [
        Variables::agreement_init => Variables::agreement_approved_osc,
        Variables::agreement_approved_osc  => Variables::agreement_approved_ola,
        Variables::agreement_resubmitted => Variables::agreement_approved_osc,
        Variables::agreement_MCOM_date_set => Variables::agreement_MCOM_approved,
        Variables::agreement_MCOM_approved => Variables::agreement_UMC_approve,
        Variables::agreement_conditional_upload => Variables::agreement_draft_uploaded_ola,
        Variables::agreement_MCOM_date_changed => Variables::agreement_MCOM_approved,
        Variables::agreement_draft_uploaded_ola => Variables::agreement_draft_upload_applicant,
        Variables::agreement_draft_upload_applicant => Variables::agreement_draft_approve_final_draft,
        Variables::agreement_UMC_approve => Variables::agreement_draft_uploaded_ola,
        Variables::agreement_draft_rejected_ola => Variables::agreement_draft_upload_applicant,
        Variables::agreement_draft_approve_final_draft => Variables::agreement_executed,
        Variables::agreement_approved_circulation => Variables::agreement_MCOM_approved,
        Variables::agreement_approved_via_power => Variables::agreement_draft_uploaded_ola,
    ];
$notCompleteMap = [
        Variables::agreement_init => Variables::agreement_not_complete_osc,
        Variables::agreement_resubmitted => Variables::agreement_not_complete_osc,
        Variables::agreement_approved_osc => Variables::agreement_not_complete_ola,
        Variables::agreement_MCOM_date_set => Variables::agreement_MCOM_KIV,
        Variables::agreement_MCOM_approved => Variables::agreement_UMC_KIV,
        Variables::agreement_draft_upload_applicant => Variables::agreement_draft_rejected_ola,
        Variables::agreement_conditional_upload => Variables::agreement_conditional_upload_not_complete,
        Variables::agreement_MCOM_date_changed => Variables::agreement_MCOM_KIV,
        Variables::agreement_approved_circulation => Variables::agreement_MCOM_KIV,
    86 => 87,

];
$rejectMap = [
        Variables::agreement_MCOM_date_set => Variables::agreement_MCOM_reject,
        Variables::agreement_MCOM_approved => Variables::agreement_UMC_reject,
        Variables::agreement_MCOM_date_changed => Variables::agreement_MCOM_reject,
        Variables::agreement_approved_circulation => Variables::agreement_MCOM_reject,
];
$extraApprove = [
    Variables::agreement_approved_osc => Variables::agreement_approved_circulation,
    Variables::agreement_MCOM_date_set => Variables::agreement_approved_via_power,
    Variables::agreement_MCOM_approved => Variables::agreement_approved_via_power,
    Variables::agreement_approved_circulation => Variables::agreement_approved_via_power,
];


$form = ActiveForm::begin(['id' => 'actiontaken', 'validateOnBlur' => false, 'validateOnChange' => false, 'options' => ['enctype' => 'multipart/form-data'],]);

if (!in_array($model->status,
    [
        Variables::agreement_draft_uploaded_ola,
        Variables::agreement_draft_rejected_ola,
        Variables::agreement_draft_approve_final_draft,
        Variables::agreement_UMC_approve,
        Variables::agreement_approved_via_power
    ])) {

    $notCompleteTag = (in_array($model->status, [Variables::agreement_MCOM_date_set, Variables::agreement_MCOM_approved, Variables::agreement_MCOM_date_changed])) ? 'KIV' : 'Not Complete';
    $recommendedTag = !($model->status == Variables::agreement_MCOM_approved) ? 'Recommended' : 'Approve';

    $options = [$approveMap[$model->status] => $recommendedTag, $notCompleteMap[$model->status] => $notCompleteTag,];

    if (in_array($model->status, [Variables::agreement_MCOM_date_set, Variables::agreement_MCOM_approved, Variables::agreement_MCOM_date_changed])) {
        $options += [$rejectMap[$model->status] => 'Reject'];
    }

    if ($model->status == Variables::agreement_approved_osc) {
        $options += [$extraApprove[$model->status] => 'Paper by circulation'];
    }

    if (in_array($model->status, [Variables::agreement_approved_circulation, Variables::agreement_MCOM_approved, Variables::agreement_MCOM_date_changed])) {
        $options += [$extraApprove[$model->status] => 'Approved By OLA via power delegated by UMC'];
    }


    echo $form->field($model, 'status')->radioList(
        $options,
        [
            'item' => function($index, $label, $name, $checked, $value) {
                return '
            <label class="plan ' . strtolower($value) . '-plan" for="is' . $value . '">
            
                <input type="radio" id="is' . $value . '" name="' . $name . '" value="' . $value . '" ' . ($checked ? 'checked' : '') . ' />
                <div class="plan-content">
                    <div class="plan-details">
                        <span>' . $label . '</span>
                    </div>
                </div>
                <p class="invalid-feedback mb-0"></p>
            </label>
            ';
            },
            'class' => 'plans',
            'errorOptions' => ['class' => 'invalid-feedback'],
        ]
    )->label(false);
}

$currentDate = date('Y-m-d'); // Get the current date in the format 'YYYY-MM-DD'

$model->reason = null; // init reason to null for ckeditor value

?>

<div class="agreement-form">
    <?php if (in_array($model->status, [Variables::agreement_draft_rejected_ola, Variables::agreement_UMC_approve, Variables::agreement_approved_via_power])): ?>
        <?= $form->field($model, 'status')->hiddenInput(['value' => $approveMap[$model->status]])->label(false) ?>
        <?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
    <?php endif;?>

    <?php if(in_array($model->status, [Variables::agreement_conditional_upload, Variables::agreement_draft_upload_applicant])):?>
        <div class="doc-approved mb-4 d-none">
            <?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
        </div>
    <?php endif;?>
    <?php if ($model->status == Variables::agreement_draft_approve_final_draft): ?>
    <h4>Commencement Date</h4>
        <div class="row">
            <div class="col-md"><?= $form->field($model, 'agreement_sign_date')->textInput(['type' => 'date']) ?></div>
            <div class="col-md"><?= $form->field($model, 'agreement_expiration_date')->textInput(['type' => 'date']) ?></div>
        </div>
    <h4>Execution Date</h4>
        <div class="row">
            <div class="col-md"><?= $form->field($model, 'execution_date')->textInput(['type' => 'date']) ?></div>
        </div>
        <?= $form->field($model, 'status')->hiddenInput(['value' =>$approveMap[$model->status]])->label(false)?>

        <?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
    <?php endif; ?>

    <div class="row">
        <div class="col umc_date d-none">
            <?= $form->field($model, 'umc_date')->textInput(['type' => 'date', 'id' => 'umc_date'])->label('UMC Date') ?>
        </div>
        <div class="col umc_ser d-none">
            <?= $form->field($model, 'umc_series')->textInput(['type' => 'date', 'id' => 'umc_series'])->label('UMC Series') ?>
        </div>
        <div class="col principle d-none">
            <?= $form->field($model, 'principle')->dropDownList(['in principle' => 'in principle'], ['prompt' => 'Select One', 'id' => 'principle'])->label('Principle') ?>
        </div>
        <div class="col-12 advice d-none">
            <?= $form->field($model, 'advice')->widget(CKEditor::className(), ['preset' => 'basic',]) ?>
        </div>
    </div>

    <div class="not-complete mb-4 d-none">
        <?= $form->field($model, 'reason')->widget(CKEditor::className(), ['preset' => 'basic',]) ?>
    </div>
    <div class="circulation d-none">
        <?= $form->field($model, 'circulation')->textInput(['id' => 'circulation'])->label('circulation NO.') ?>
    </div>




    <div class="d-flex flex-row gap-2 mb-2 justify-content-end">
        <?= Html::submitButton('Submit', ['class' => 'btn-submit mb-4']) ?>
    </div>


    <?php ActiveForm::end(); ?>


    <script>

        $(document).ready(function() {
            // Call updateVisibility once on page load to ensure correct initial state
            updateVisibility();
            updateNotCompleteVisibility();
            updateDocApprovedVisibility();
            updateCKEditorVisibility();

            // Bind the change event to all radio buttons within the form container
            $('input[type="radio"]').on('change', function() {
                updateVisibility();
                updateNotCompleteVisibility();
                updateDocApprovedVisibility();
            });

            // Bind the change event to the principle dropdown
            $('#principle').on('change', function() {
                updateCKEditorVisibility();
            });

            function updateVisibility() {
                $('.umc_date, .advice, .principle, .circulation').addClass('d-none');

                if ($('#is31').is(':checked') || $('#is43').is(':checked') || $('#is42').is(':checked') || $('#is41').is(':checked'))  {
                    $('.umc_date').removeClass('d-none');
                }

                if ($('#is33').is(':checked') || $('#is43').is(':checked')) {
                    $('.advice').removeClass('d-none');
                }

                if ($('#is41').is(':checked') || $('#is31').is(':checked')) {
                    $('.principle').removeClass('d-none');
                }
                if($('#is13').is(':checked')){
                    $('.circulation').removeClass('d-none');
                }
            }

            function updateNotCompleteVisibility() {
                const notCompleteSelectors = "#is2, #is12, #is42, #is43, #is31, #is32, #is34, #is33, #is47, #is72, #is87, #is52";
                const completeSelectors = "#is1, #is11, #is13, #is51, #is81, #is91";

                if ($(notCompleteSelectors).is(':checked')) {
                    $(".not-complete").removeClass('d-none');
                } else if ($(completeSelectors).is(':checked')) {
                    $(".not-complete").addClass('d-none');
                }
            }

            function updateDocApprovedVisibility() {
                if ($('#is72').is(':checked') || $('#is47').is(':checked') || $('#is52').is(':checked')) {
                    $(".doc-approved").addClass('d-none');
                } else if ($('#is81').is(':checked') || $('#is51').is(':checked')) {
                    $(".doc-approved").removeClass('d-none');
                }
            }

            function updateCKEditorVisibility() {
                if ($('#principle').val() === 'in principle') {
                    $(".not-complete").removeClass('d-none');
                } else {
                    $(".not-complete").addClass('d-none');
                }
            }
        });


    </script>


    <?php
    $existingFilesSize = 0;
    $baseUploadPath = Yii::getAlias('@common/uploads') . '/' . $model->id . '/applicant/';

    if (is_dir($baseUploadPath)) {
        $storedFiles = array_diff(scandir($baseUploadPath), ['.', '..']);

        foreach ($storedFiles as $file) {
            $filePath = $baseUploadPath . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath)) {
                $existingFilesSize += filesize($filePath);
            }
        }
    } else {
        Yii::error("Directory does not exist: $baseUploadPath", __METHOD__);
    }
    ?>


    <script>
        $(document).ready(function() {

            const existingFilesSize = <?= $existingFilesSize ?>;
            const submitButton = $('#form-update-submit');
            console.log('files size: ' + existingFilesSize);
            const sizeLimit = 10 * 1024 * 1024; // 1 MB in bytes

            $('input[type="file"][name="Agreement[files_applicant][]"]').on('change', function() {
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