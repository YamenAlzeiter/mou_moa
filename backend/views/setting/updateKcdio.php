<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Kcdio $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="kcdio-form">


    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],
        ],
    ]); ?>
    <?= $form->field($model, 'tag')->textInput(['maxlength' => true, 'placeholder'=> '']) ?>
    <?= $form->field($model, 'kcdio')->textInput(['maxlength' => true, 'placeholder'=> '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
