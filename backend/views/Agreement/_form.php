<?php


use Itstructure\CKEditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\widgets\ActiveForm $form */

$approveMap = [
    10 => 1 , // init -> accept OSC
    1  => 11, // OSC -> OLA approve OLA
    21 => 31, // OLA -> / approve OLA
    31 => 41, // OLA -> / approve OLA
];
$notCompleteMap = [
    10 => 2 , // OSC -> Applicant
    1  => 12, // OLA -> Applicant
    21 => 33, // OLA -> Applicant
    31 => 43, // OLA -> Applicant
];
$rejectMap = [
    21 => 32, // OLA -> Applicant
    31 => 42, // OLA -> Applicant
];

if($model->status != 41 || $model->status != 51){
    $options = [
        $approveMap[$model->status] => 'Approved',
        $notCompleteMap[$model->status] => 'Not Complete',
    ];

    if ($model->status == 21 || $model->status == 31){
        // Add Rejected option directly to the array
        $options[$rejectMap[$model->status]] = 'Rejected';
    }
}

?>


<div class="agreement-form">
<?php $form = ActiveForm::begin(); ?>
    <div class="mb-2">
        <?= $form->field($model, 'status')->radioList(
            [
                $approveMap[$model->status] => 'Approved',
                $notCompleteMap [$model->status] => 'Not Complete',
            ], [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $class = 'border-dark p-4 border rounded-4';
                    return '<label class="border-dark-light px-4 py-5 w-25 border rounded-4 fs-4">' . Html::radio($name, $checked, ['id' =>"is".$value, 'value' => $value, 'class' => 'mx-2']) . $label . '</label>';
                }
            ]
        )->label(false) ?>
    </div>

    <div class="not-complete mb-4 d-none">
        <?= $form->field($model, 'reason')->widget(CKEditor::className(),  [
            'preset' => 'basic',
            'options' => ['value' => ''],
        ])->label(false);?>
    </div>

    <div class="d-flex flex-row gap-2 mb-2 justify-content-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

    <script>
        $("#is2, #is12, #is32, #is42, #is33, #is43").on("change", function () {
            console.log('here is eme')
            if (this.checked) {
                $(".not-complete").removeClass('d-none');
            }
        });
        $("#is1, #is11, #is31, #is41").on("change", function () {
            console.log('here is eme')
            if (this.checked) {
                $(".not-complete").addClass('d-none');
            }
        });
    </script>
