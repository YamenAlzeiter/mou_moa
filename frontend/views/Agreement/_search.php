<?php

use common\models\McomDate;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <?php $form = ActiveForm::begin([
                'action' => [''],
                'method' => 'get',
                'options' => ['class' => 'row gap-2']
            ]); ?>

            <div class="col-md-3 p-0">
                <?= $form->field($model, 'full_info', ['options' => ['mb-0']])->textInput([
                    'class' => 'form-control', // Add class for styling
                    'placeholder' => 'Search', // Placeholder text
                    'onchange' => '$(this).closest("form").submit();', // Submit form on change
                ])->label(false) ?>
            </div>
            <div class="col-md-2 p-0">
                <?= $form->field($model, 'agreement_type', ['options' => ['mb-0']])->dropDownList([
                    'MOU' => 'MOU',
                    'MOUA' => 'MOUA',
                    'MOA' => 'MOA',
                    'MOAA' => 'MOAA'
                ],    [
                    'class' => 'form-select',
                    'prompt' => 'Pick Country', // Placeholder text
                    'onchange' => '$(this).closest("form").submit();', // Submit form on change
                ])->label(false) ?>
            </div>
            <div class="col-md-2 p-0">
                <?= $form->field($model, 'mcom_date')->dropDownList(ArrayHelper::map((array)  McomDate::find()
                    ->all(), 'date', 'date'),['prompt' => 'Select a Date',  'class' => 'form-select', 'onchange' => '$(this).closest("form").submit();',])->label(false) ?>

            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-success">Export</button>
        </div>
    </div>
</div>

