<?php

use common\models\Kcdio;
use common\models\McomDate;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = 'Update Agreement: ' . $model->id;
$templateFileInput = '<div class="col align-items-center"><div class="col-md-2 col-form-label">{label}</div><div class="col-md">{input}</div>{error}</div>';

$status = [
    2  => 10,
    12 => 1 ,
    11 => 21,
    33 => 10,
    43 => 10,
];

$currentDate = date('Y-m-d'); // Get the current date in the format 'YYYY-MM-DD'


?>
<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],
    ],
]); ?>


<?php if($model->status == 2 || $model->status == 12 || $model->status == 33 || $model->status == 43):?>
    <div class="row">
        <div class="col-4">
            <?= $form->field($model, 'agreement_type')->dropDownList(['MOU (Academic)' => 'MOU (Academic)', 'MOU (Non-Academic)' => 'MOU (Non-Academic)', 'MOA (Academic)' => 'MOA (Academic)', 'MOA (Non-Academic)' => 'MOA (Non-Academic)'], ['prompt' => 'Select Type']) ?>
        </div>
        <div class="col-8">
            <?= $form->field($model, 'col_organization')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        </div>
    </div>
    <div class = "row">
        <div class = "col">
            <?= $form->field($model, 'col_name')->textInput(['placeholder' => true, 'placeholder => ']) ?>
            <?= $form->field($model, 'col_phone_number')->textInput(['placeholder' => true, 'placeholder => ']) ?>
        </div>
        <div class = "col">
            <?= $form->field($model, 'col_address')->textInput(['placeholder' => true, 'placeholder => ']) ?>
            <?= $form->field($model, 'col_email')->textInput(['placeholder' => true, 'placeholder => ']) ?>
        </div>
    </div>
    <?= $form->field($model, 'col_collaborators_name')->textarea(['placeholder' => true, 'placeholder => ', 'rows' => 6]) ?>

    <?= $form->field($model, 'col_wire_up')->textInput(['placeholder' => true, 'placeholder => ']) ?>

    <h4>Person In Charge Details</h4>

    <div class = "row">
        <div class = "col">
            <?= $form->field($model, 'pi_name')->textInput(['placeholder' => true, 'placeholder => ']) ?>

            <?= $form->field($model, 'pi_phone_number')->textInput(['placeholder' => true, 'placeholder => ']) ?>
        </div>
        <div class = "col">
            <?= $form->field($model, 'pi_kulliyyah')->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag','kcdio'), ['prompt' => 'Select Nationality']) ?>

            <?= $form->field($model, 'pi_email')->textInput(['placeholder' => true, 'placeholder => ']) ?>
        </div>
    </div>
    <?= $form->field($model, 'project_title')->textarea(['rows' => 6]) ?>
    <div class = "row">
        <div class = "col">
            <?= $form->field($model, 'grant_fund')->textInput(['placeholder' => true, 'placeholder => ']) ?>
        </div>
        <div class = "col">
            <?= $form->field($model, 'member')->textInput(['placeholder' => true, 'placeholder => ']) ?>
        </div>
        <div class = "col">
            <?= $form->field($model, 'transfer_to')->dropDownList(['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'], ['prompt' => 'select one']) ?>
        </div>
    </div>


    <?php //= $form->field($model, 'sign_date')->textInput() ?>
    <!---->
    <?php //= $form->field($model, 'end_date')->textInput() ?>



    <?= $form->field($model, 'proposal')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ssm')->textInput(['placeholder' => true, 'placeholder => ']) ?>

    <?= $form->field($model, 'company_profile')->textInput(['placeholder' => true, 'placeholder => ']) ?>

    <?= $form->field($model, 'fileUpload', ['template' => $templateFileInput])->fileInput()->label('Document') ?>


<?php elseif ($model->status == 11):?>

    <?= $form->field($model, 'mcom_date')->dropDownList(ArrayHelper::map((array)  McomDate::find()
        ->where(['<', 'counter', 20])
        ->andWhere(['>', 'date', $currentDate])
        ->all(), 'date', 'date'),['prompt' => 'Select a Date']) ?>

<?php endif;?>

<div class="modal-footer p-0">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name'=>'checked', 'value'=> $status[$model->status]]) ?>
    <?php ActiveForm::end(); ?>
</div>