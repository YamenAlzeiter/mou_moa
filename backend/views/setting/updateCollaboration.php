<?php


use Itstructure\CKEditor\CKEditor;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Collaboration $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="email-template-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],
        ],
    ]); ?>

    <!-- Collaborator details start -->
    <div class="row">
        <h4>Collaborator Details</h4>
        <div class="col-md-12">
            <?= $form->field($model, 'col_organization')->textInput(['maxlength' => true, 'id' => 'col_organization', 'placeholder' => '']) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'col_name')->textInput(['maxlength' => true, 'id' => 'col_name', 'placeholder' => '']) ?>
            <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true, 'id' => 'col_phone_number', 'placeholder' => '']) ?>
        </div>
        <div class="col-md">
            <?= $form->field($model, 'col_address')->textInput(['maxlength' => true, 'id' => 'col_address', 'placeholder' => '']) ?>
            <?= $form->field($model, 'col_email')->textInput(['type' => 'email', 'maxlength' => true, 'id' => 'col_email', 'placeholder' => '']) ?>
        </div>
    </div>
    <?= $form->field($model, 'col_collaborators_name')->textarea(['maxlength' => true, 'rows' => 6, 'id' => 'col_collaborators_name', 'placeholder' => '']) ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'col_wire_up')->textInput(['maxlength' => true, 'id' => 'col_wire_up', 'placeholder' => '']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'country')->textInput(['maxlength' => true, 'id' => 'country', 'placeholder' => '']) ?>
        </div>
    </div>
    <!-- Collaborator details end -->


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
