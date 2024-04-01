<?php


use Itstructure\CKEditor\CKEditor;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\bootstrap5\ActiveForm $form */
$templateFileInput = '<div class="col align-items-center"><div class="col-md-2 col-form-label">{label}</div><div class="col-md">{input}</div>{error}</div>';
$approveMap = [
    10 => 1 , // init -> accept OSC
    1  => 11, // OSC -> OLA approve OLA
    21 => 31, // OLA -> / approve OLA
    31 => 41, // OLA -> / approve OLA
    61 => 81,
];
$notCompleteMap = [
    10 => 2 , // OSC -> Applicant
    1  => 12, // OLA -> Applicant
    21 => 33, // OLA -> Applicant
    31 => 43, // OLA -> Applicant
    61 => 51,
];
$rejectMap = [
    21 => 32, // OLA -> Applicant
    31 => 42, // OLA -> Applicant
];

if($model->status != 41 && $model->status !=51){

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

<?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

<?php if($model->status == 41): ?>
    <?= $form->field($model, 'status')->hiddenInput(['value' => 51])->label(false)?>
    <?= $form->field($model, 'olaDraft', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
<?php elseif($model->status == 51) :?>
    <?= $form->field($model, 'status')->hiddenInput(['value' => 61])->label(false)?>
    <?= $form->field($model, 'oscDraft', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
<?php else: ?>
    <div class="mb-2">
        <?= $form->field($model, 'status')->radioList(
            $options,
            [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $class = 'border-dark p-4 border rounded-4';
                    return '<label class="border-dark-light px-4 py-5 w-25 border rounded-4  fs-4">' . Html::radio($name, $checked, ['id' => "is" . $value, 'value' => $value, 'class' => 'mx-2']) . $label . '</label>';
                }
            ]
        )->label(false); ?>
    </div>
    <?php if ($model->status == 61): ?>
        <div class="doc-approved mb-4 d-none">
            <?= $form->field($model, 'finalDraft', ['template' => $templateFileInput])->fileInput()->label('Document') ?>
        </div>
    <?php endif;?>
    <div class="not-complete mb-4 d-none">
        <?= $form->field($model, 'reason')->widget(CKEditor::className(),  [
            'preset' => 'basic',
            'options' => ['value' => ''],
        ])->label(false);?>
    </div>
<?php endif; ?>
    <div class="d-flex flex-row gap-2 mb-2 justify-content-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>



<?php ActiveForm::end(); ?>




    <script>
        $("#is2, #is12, #is32, #is42, #is33, #is43, #is51").on("change", function () {
            console.log('here is eme1')
            if (this.checked) {
                $(".not-complete").removeClass('d-none');
                $(".doc-approved").addClass('d-none');
            }
        });
        $("#is1, #is11, #is31, #is41, #is81").on("change", function () {
            console.log('here is eme')
            if (this.checked) {
                $(".not-complete").addClass('d-none');
                $(".doc-approved").removeClass('d-none');
            }
        });
    </script>

<?php $script = <<<JS
    $('form#{$model->formName()}').on('beforeSubmit', function (e){
        var \$form = $(this);
        $.post(
            \$form.attr("action"),
            \$form.serialize()
        )
        .done(function(result){
            if(result.message === 'Success'){
                $(document).find('#secondmodal').modal('hide');
                $.pjax.reload({container : '#grid-view'});
            }else{
                $(\$form).trigger('reset');
                $("#message").html(result.message);
            }
        }).fail(function (){
            console.log('server error');
        });
        return false
    });
 JS;
$this->registerJs($script); ?>