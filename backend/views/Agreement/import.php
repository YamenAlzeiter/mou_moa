<?php


use Itstructure\CKEditor\CKEditor;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Import $model */
/** @var yii\bootstrap5\ActiveForm $form */
$templateFileInput = '<div class="col align-items-center">
                            <div class="col-md-2 col-form-label">
                                {label}
                            </div>
                                <div class="col-md ">
                                    {input}
                                </div>
                            {error}
                        </div>';
?>

<?php $form = ActiveForm::begin([
    'id' => 'actiontaken', 'validateOnBlur' => false, 'validateOnChange' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>
<h3>INSTRUCTION</h3>
<ul>
    <li>Choose between Activity or Agreement</li>
    <li>if you're importing activities, you should follow this <a href="">Template</a></li>
    <li>if you're importing Agreement, you should follow this <a href="">Template</a></li>
    <li>Use consistent date formatting (DD/MM/YYYY) throughout your document. Dates that renew after a period or are open-ended may cause errors.</li>
</ul>
<div class="row">
    <div class="col-md">
        <?= $form->field($model, 'type')->dropDownList(['Activity' => 'Activity', 'Agreement' => 'Agreement'], ['prompt' => 'Select an Option'])?>

        </div>
    <div class="col-md">
        <?= $form->field($model, 'import_from')->hiddenInput(['value' => Yii::$app->user->identity->type])->label(false)?>
<!--        --><?php //= $form->field($model,'import_from')->dropDownList(['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'], ['prompt' => 'Select an Option'])?>
    </div>
    </div>
<?= $form->field($model, 'importedFile', ['template' => $templateFileInput])->fileInput()->label('Document')->label(false) ?>

<div class = "d-flex flex-row gap-2 mb-2 justify-content-end">
    <?= Html::submitButton('Import', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
