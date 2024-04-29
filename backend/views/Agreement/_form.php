<?php

use Itstructure\CKEditor\CKEditor;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\bootstrap5\ActiveForm $form */
$templateFileInput = '<div class="col align-items-center"><div class="col-md-2 col-form-label">{label}</div><div class="col-md">{input}</div>{error}</div>';
$approveMap = [10 => 1,  // init -> accept OSC
    1 => 11,  // OSC -> OLA approve OLA
    15 => 1,  // OSC -> OLA approve OLA
    21 => 31, // OLA -> / approve OLA
    121 => 31, 31 => 41, // OLA -> / approve OLA
    61 => 81,

];
$notCompleteMap = [10 => 2,  // OSC -> Applicant
    1 => 12,  // OLA -> Applicant
    15 => 2,  // OSC -> OLA approve OLA
    21 => 33, // OLA -> Applicant
    121 => 33, 31 => 43, // OLA -> Applicant
    61 => 72, // OLA -> OSC
];
$rejectMap = [21 => 32, // OLA -> Applicant
    121 => 32, 31 => 42, // OLA -> Applicant
];

if ($model->status != 41 && $model->status != 51 && $model->status != 72 && $model->status != 81) {
    $tag = ($model->status == 21 || $model->status == 31) ? 'KIV' : 'Not Complete';
    $options = [$approveMap[$model->status] => 'Recommended', $notCompleteMap[$model->status] => $tag,];
    if ($model->status == 21 || $model->status == 31 || $model->status == 121) {
        $options += [$rejectMap[$model->status] => ' Not Recommended'];

    }
}
$currentDate = date('Y-m-d'); // Get the current date in the format 'YYYY-MM-DD'
?>

<div class="agreement-form">

    <?php $form = ActiveForm::begin(['id' => 'actiontaken', 'validateOnBlur' => false, 'validateOnChange' => false, 'options' => ['enctype' => 'multipart/form-data'],]); ?>

    <?php if ($model->status == 41): ?>
        <?= $form->field($model, 'status')->hiddenInput(['value' => 51])->label(false) ?>
        <?= $form->field($model, 'olaDraft', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
    <?php elseif ($model->status == 51) : ?>
        <?= $form->field($model, 'status')->hiddenInput(['value' => 61])->label(false) ?>
        <?= $form->field($model, 'oscDraft', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
    <?php elseif ($model->status == 72) : ?>
        <?= $form->field($model, 'status')->hiddenInput(['value' => 61])->label(false) ?>
        <?= $form->field($model, 'oscDraft', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
    <?php else: ?>
        <div class="mb-2">
            <?= $form->field($model, 'status')->radioList($options, ['class' => 'gap-2 row', // Use flexbox
                'item' => function ($index, $label, $name, $checked, $value) {
                    return '<label class=" col-md  border-dark-light px-4 py-5 border rounded-4 text-nowrap fs-4">' . Html::radio($name, $checked, ['id' => "is" . $value, 'value' => $value, 'class' => 'mx-2']) . $label . '</label>';
                }])->label(false); ?>
        </div>
        <?php if ($model->status == 61): ?>
            <div class="doc-approved mb-4 d-none">
                <?= $form->field($model, 'finalDraft', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
            </div>
        <?php endif; ?>
        <div class="not-complete mb-4 d-none">

            <?= $form->field($model, 'reason')->widget(CKEditor::className(), ['preset' => 'basic',]) ?>
        </div>
    <?php endif; ?>


    <div class="d-flex flex-row gap-2 mb-2 justify-content-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>


    <script>
        $("#is2, #is12, #is42, #is43, #is51").on("change", function () {

            if (this.checked) {
                $(".not-complete").removeClass('d-none');
            }
        });
        $("#is1, #is11, #is31, #is41").on("change", function () {

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

            if (this.checked) {
                $(".doc-approved").removeClass('d-none');
            }
        });
    </script>
