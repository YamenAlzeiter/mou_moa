<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;


/** @var common\models\Poc $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<?php
$form = ActiveForm::begin([
            'fieldConfig' => [
                    'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>",
                    'labelOptions' => ['class' => ''],], 'id' =>'add_poc']);
?>

<div class="row">
    <div class="col-12 col-md-6"><?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => ''])?></div>
    <div class="col-12 col-md-6"><?= $form->field($model, 'phone_number')->textInput(['maxlength' => true, 'placeholder' => ''])?></div>
    <div class="col-12 col-md-6"><?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => ''])?></div>
    <div class="col-12 col-md-6"><?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => ''])?></div>
</div>

    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-10']) ?>
    </div>

<?php ActiveForm::end(); ?>
