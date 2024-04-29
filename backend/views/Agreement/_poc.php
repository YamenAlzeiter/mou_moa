<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\bootstrap5\ActiveForm $form */

$form = ActiveForm::begin(['id' => 'actiontaken', 'validateOnBlur' => false, 'validateOnChange' => false, 'options' => ['enctype' => 'multipart/form-data'],]);


?>
<div class="row">
    <div class="col-6 col-md-6">
        <?php
        echo $form->field($model, 'pi_name')->textInput(['maxlength' => true, 'placeholder' => '']);
        echo $form->field($model, 'pi_email')->textInput(['maxlength' => true, 'placeholder' => '']);
        ?>
    </div>
    <div class="col-6 col-md-6">
        <?php
        echo $form->field($model, 'pi_phone_number')->textInput(['maxlength' => true, 'placeholder' => '']);
        echo $form->field($model, 'pi_kulliyyah')->textInput(['maxlength' => true, 'placeholder' => '']);
        ?>
    </div>
</div>
<?php if($model->pi_name_extra): ?>
<hr>
<h4>Extra Person in Charge</h4>
<div class="row">
    <div class="col-6 col-md-6">
        <?php
        echo $form->field($model, 'pi_name_extra')->textInput(['maxlength' => true, 'placeholder' => '']);
        echo $form->field($model, 'pi_email_extra')->textInput(['maxlength' => true, 'placeholder' => '']);
        ?>
    </div>
    <div class="col-6 col-md-6">
        <?php
        echo $form->field($model, 'pi_phone_number_extra')->textInput(['maxlength' => true, 'placeholder' => '']);
        echo $form->field($model, 'pi_kulliyyah_extra')->textInput(['maxlength' => true, 'placeholder' => '']);
        ?>
    </div>
</div>
<?php endif;?>
<?php if($model->pi_name_extra2): ?>
<hr>
    <h4>Extra Person in Charge2</h4>
<div class="row">
    <div class="col-6 col-md-6">
        <?php
        echo $form->field($model, 'pi_name_extra2')->textInput(['maxlength' => true, 'placeholder' => '']);
        echo $form->field($model, 'pi_email_extra2')->textInput(['maxlength' => true, 'placeholder' => '']);
        ?>
    </div>
    <div class="col-6 col-md-6">
        <?php
        echo $form->field($model, 'pi_phone_number_extra2')->textInput(['maxlength' => true, 'placeholder' => '']);
        echo $form->field($model, 'pi_kulliyyah_extra2')->textInput(['maxlength' => true, 'placeholder' => '']);
        ?>
    </div>
</div>
<?php endif;?>
<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
