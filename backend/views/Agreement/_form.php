<?php

use Itstructure\CKEditor\CKEditor;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\bootstrap5\ActiveForm $form */
$templateFileInput = '<div class="col align-items-center"><div class="col-md-2 col-form-label">{label}</div><div class="col-md">{input}</div>{error}</div>';
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
         72 => 61

];
$notCompleteMap = [
         10 => 2,  // OSC -> Applicant
          1 => 12, // OLA -> Applicant
         15 => 2,  // OSC -> OLA approve OLA
         21 => 33, // OLA -> Applicant
         31 => 43, // OLA -> Applicant
         61 => 72, // OLA -> OSC

         46 => 47,
        121 => 33,
];
$rejectMap = [
         21 => 32, // OLA -> Applicant
         31 => 42, // OLA -> Applicant

        121 => 32,
];
$conditionalMap = [
         21 => 34
];

$form = ActiveForm::begin(['id' => 'actiontaken', 'validateOnBlur' => false, 'validateOnChange' => false, 'options' => ['enctype' => 'multipart/form-data'],]);

if (!in_array($model->status, [41, 51, 72, 81])) {

    $tag = (in_array($model->status, [21, 31])) ? 'KIV' : 'Not Complete';

    $options = [$approveMap[$model->status] => 'Approve', $notCompleteMap[$model->status] => $tag,];

    if (in_array($model->status, [21, 31, 121])) {
        if ($model->status == 21)
        {
            $options += [$conditionalMap[$model->status] => 'Conditional Recommend'];
        }
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

    <?php if (in_array($model->status, [41, 72])): ?>
        <?= $form->field($model, 'status')->hiddenInput(['value' => $approveMap[$model->status]])->label(false) ?>
        <?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
        <?php echo $model->status?>
    <?php endif;?>

    <?php if(in_array($model->status, [46, 61])):?>
        <div class="doc-approved mb-4 d-none">
            <?= $form->field($model, 'files_applicant', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
        </div>
    <?php endif;?>

        <div class="not-complete mb-4 d-none">
            <?= $form->field($model, 'reason')->widget(CKEditor::className(), [
                'preset' => 'basic',])
            ?>
        </div>


    <div class="d-flex flex-row gap-2 mb-2 justify-content-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>


    <script>
        $("#is2, #is12, #is42, #is43, #is31, #is32, #is34, #is33, #is41, #is47, #is72").on("change", function () {

            if (this.checked) {
                $(".not-complete").removeClass('d-none');
            }
        });
        $("#is1, #is11, #is51, #is81").on("change", function () {

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
        $(" #is47").on("change", function () {

            if (this.checked) {
                $(".doc-approved").addClass('d-none');
            }
        });
    </script>
