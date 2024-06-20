<?php

use common\models\Kcdio;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;


/** @var common\models\Poc $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<?php
$form = ActiveForm::begin([
            'fieldConfig' => [
                    'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>",
                    'labelOptions' => ['class' => ''],], 'id' =>'add_poc']);
?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => ''])?>
    <?= $form->field($model, 'kcdio')->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag','kcdio'),['prompt' => 'Select One'])?>
    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true, 'placeholder' => ''])?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => ''])?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => ''])?>


    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit']) ?>
    </div>

<?php ActiveForm::end(); ?>
