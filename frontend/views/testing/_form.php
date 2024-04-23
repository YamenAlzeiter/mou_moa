<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Testing $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="testing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <div id="email-fields-container">
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>

    <button type="button" id="add-email">Add Email</button>
    <button type="button" id="remove-email">Remove Email</button>

    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
</div>
<script>
    $('#contact-form').yiiActiveForm('add', {
        id: 'address',
        name: 'address',
        container: '.field-address',
        input: '#address',
        error: '.help-block',
        validate:  function (attribute, value, messages, deferred, $form) {
            yii.validation.required(value, messages, {message: "Validation Message Here"});
        }
    });

</script>