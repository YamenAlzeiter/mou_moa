<?php

use Itstructure\CKEditor\CKEditor;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\bootstrap5\ActiveForm $form */
$templateFileInput = '<div class="col-md align-items-center"><div class="col-md-md-2 col-md-form-label">{label}</div>
                        <div class="col-md-md">{input}{error}</div></div>';
$approveMap = [
         10 => 1,  // init -> accept OSC
         1  => 11,  // OSC -> OLA approve OLA
         15 => 1,  // OSC -> OLA approve OLA
         21 => 31, // OLA -> / approve OLA
         31 => 41, // OLA -> / approve OLA

         46 => 51,
        121 => 31,
         61 => 81,

         41 => 51,
         51 => 61,
         72 => 61,
         81 => 91,

];
$notCompleteMap = [
         10 => 2,  // OSC -> Applicant
          1 => 12, // OLA -> Applicant
         15 => 2,  // OSC -> OLA approve OLA
         21 => 33, // OLA -> Applicant
         31 => 43, // OLA -> Applicant
         61 => 72, // OLA -> OSC

         46 => 47,
         86 => 87,
        121 => 33,
];
$rejectMap = [
         21 => 32, // OLA -> Applicant
         31 => 42, // OLA -> Applicant
        1 => 2,
        121 => 32,
];


$form = ActiveForm::begin(['id' => 'actiontaken', 'validateOnBlur' => false, 'validateOnChange' => false, 'options' => ['enctype' => 'multipart/form-data'],]);

if (!in_array($model->status, [51, 72, 81, 41])) {

    $rejectTag = (in_array($model->status, [21, 31])) ? 'KIV' : 'Not Complete';
    $recommendedTag = !(in_array($model->status, [31])) ? 'Recommended' : 'Approve';

    $options = [$approveMap[$model->status] => $recommendedTag, $notCompleteMap[$model->status] => $rejectTag,];

    if (in_array($model->status, [21, 31, 121])) {
        $options += [$rejectMap[$model->status] => ' Reject'];
    }
    echo $form->field($model, 'status')->radioList($options, [
            'class' => 'gap-2 row',
            'item' => function ($index, $label, $name, $checked, $value) {
                        return '<label class=" col-md  border-dark-light px-4 py-5 border rounded-4 text-nowrap fs-4">'
                            . Html::radio($name, $checked, ['id' => "is" . $value,
                                                            'value' => $value,
                                                            'class' => 'mx-2']) . $label .
                                '</label>';
            }])->label(false);
}

$currentDate = date('Y-m-d'); // Get the current date in the format 'YYYY-MM-DD'

$model->reason = null; // init reason to null for ckeditor value

?>

<div class="agreement-form">

    <?php if (in_array($model->status, [72, 41])): ?>
        <?= $form->field($model, 'status')->hiddenInput(['value' => $approveMap[$model->status]])->label(false) ?>
        <?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
    <?php endif;?>

    <?php if(in_array($model->status, [46, 61])):?>
        <div class="doc-approved mb-4 d-none">
            <?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
        </div>
    <?php endif;?>
    <?php if (in_array($model->status, [81])): ?>
    <h4>Commencement Date</h4>
        <div class="row">
            <div class="col-md"><?= $form->field($model, 'project_start_date')->textInput(['type' => 'date']) ?></div>
            <div class="col-md"><?= $form->field($model, 'project_end_date')->textInput(['type' => 'date']) ?></div>
        </div>
    <h4>Execution Date</h4>
        <div class="row">
            <div class="col-md"><?= $form->field($model, 'execution_date')->textInput(['type' => 'date']) ?></div>
        </div>
        <?= $form->field($model, 'status')->hiddenInput(['value' =>$approveMap[$model->status]])->label(false)?>

        <?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
    <?php endif; ?>

        <div class="not-complete mb-4 d-none">
            <?= $form->field($model, 'reason')->widget(CKEditor::className(), ['preset' => 'basic',]) ?>
        </div>


    <div class="d-flex flex-row gap-2 mb-2 justify-content-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>


    <script>
        $("#is2, #is12, #is42, #is43, #is31, #is32, #is34, #is33, #is41, #is47, #is72, #is87, #is52").on("change", function () {

            if (this.checked) {
                $(".not-complete").removeClass('d-none');
            }
        });
        $("#is1, #is11, #is51, #is81, #is91").on("change", function () {

            if (this.checked) {
                $(".not-complete").addClass('d-none');
            }
        });
        $(" #is72").on("change", function () {

            if (this.checked) {
                $(".doc-approved").addClass('d-none');
            }
        });
        $(" #is81").on("change", function () {
                console.log('quit ')
            if (this.checked) {
                $(".doc-approved").removeClass('d-none');
            }
        });
        $(" #is51").on("change", function () {

            if (this.checked) {
                $(".doc-approved").removeClass('d-none');
            }
        });
        $(" #is47, #is52").on("change", function () {

            if (this.checked) {
                $(".doc-approved").addClass('d-none');
            }
        });
    </script>


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