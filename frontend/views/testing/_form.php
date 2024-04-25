<?php

use dominus77\sweetalert2\Alert;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Testing $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="testing-form">

    <?php $form = ActiveForm::begin(['id' => 'test-form',  'options' => [
        'data-pjax' => 1
    ],]); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email1')->textInput(['maxlength' => true]) ?>


    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
</div>


<script>
    $('#test-form').on('beforeSubmit', function () {
        var $yiiform = $(this);
        $.ajax({
            type: $yiiform.attr('method'),
            url: $yiiform.attr('action'),
            data: $yiiform.serializeArray(),
        })
            .done(function(data) {
                if (data.success) {
                    $.pjax.reload({container: '#p0'});
                    $('#modal').modal('hide'); // Hide modal
                    Swal.fire({
                        title: "Success!",
                        text: "New Record Added.",
                        icon: "success",
                    });
                }
            })
            .fail(function() {
                Swal.fire({
                    title: "Oops..!",
                    text: "Something Went Wrong.",
                    icon: "warning",
                });
            });
        return false;
    });
</script>