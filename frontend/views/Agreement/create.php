<?php

use common\models\Kcdio;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = 'Create';
$templateFileInput = '<div class="col align-items-center"><div class="col-md-2 col-form-label">{label}</div><div class="col-md">{input}</div>{error}</div>';
?>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],
    ],
]); ?>
<div class="row">
    <div class="col-4">
        <?= $form->field($model, 'agreement_type')->dropDownList(['moua' => 'MOU (Academic)', 'mou' => 'MOU (Non-Academic)', 'moaa' => 'MOA (Academic)', 'MOA' => 'MOA (Non-Academic)'], ['prompt' => 'Select Type']) ?>
    </div>
    <div class="col-8">
        <?= $form->field($model, 'col_organization')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>


<div class = "row">
    <div class = "col">
        <?= $form->field($model, 'col_name')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true, 'placeholder'=>'']) ?>
    </div>
    <div class = "col">
        <?= $form->field($model, 'col_address')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        <?= $form->field($model, 'col_email')->textInput(['type => email', 'maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>
<?= $form->field($model, 'col_collaborators_name')->textarea(['maxlength' => true, 'placeholder' => '', 'rows' => 6]) ?>

<?= $form->field($model, 'col_wire_up')->textInput(['maxlength' => true, 'placeholder' => '']) ?>

<h4>Person In Charge Details</h4>

<div class = "row">
    <div class = "col">
        <?= $form->field($model, 'pi_name')->textInput(['maxlength' => true, 'placeholder' => '']) ?>

        <?= $form->field($model, 'pi_phone_number')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
    <div class = "col">
        <?= $form->field($model, 'pi_kulliyyah')->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag','kcdio'), ['prompt' => 'Select KCDIO']) ?>

        <?= $form->field($model, 'pi_email')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>
<?= $form->field($model, 'project_title')->textarea(['rows' => 6]) ?>
<div class = "row">
    <div class = "col">
        <?= $form->field($model, 'grant_fund')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>

    <div class = "col">
        <?= $form->field($model, 'member')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
    <div class = "col">
            <?= $form->field($model, 'transfer_to')->dropDownList(['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'], ['prompt' => 'select one']) ?>
    </div>
</div>


<?php //= $form->field($model, 'sign_date')->textInput() ?>
<!---->
<?php //= $form->field($model, 'end_date')->textInput() ?>



<?= $form->field($model, 'proposal')->textarea(['rows' => 6]) ?>



<?= $form->field($model, 'fileUpload', ['template' => $templateFileInput])->fileInput()->label('Document') ?>

<div class="modal-footer p-0">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name'=>'checked', 'value'=> 10]) ?>
    <?php ActiveForm::end(); ?>
</div>

