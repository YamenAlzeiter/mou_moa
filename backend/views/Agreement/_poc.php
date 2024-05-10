<?php

use common\models\Kcdio;
use common\models\Poc;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\bootstrap5\ActiveForm $form */

$form = ActiveForm::begin(['id' => 'actiontaken', 'validateOnBlur' => false, 'validateOnChange' => false, 'options' => ['enctype' => 'multipart/form-data'],]);

$model->poc_kcdio_getter = $model->pi_kulliyyah;
$model->poc_kcdio_getter_x = $model->pi_kulliyyah_x;
$model->poc_kcdio_getter_xx = $model->pi_kulliyyah_xx;

$additionalPoc = new \common\helpers\pocFieldMaker()

?>

<div class="row">

<!--    <div class="col-12 col-md-6">-->
<!--        --><?php //= $form->field($model, 'temp_attribute_poc')->dropDownList(
//                ArrayHelper::map(Kcdio::find()->all(), 'tag', 'kcdio'),
//                ['prompt' => 'Select KCDIO',
//                    'onchange' => '$.get("' . Yii::$app->urlManager->createUrl('agreement/get-kcdio-poc') . '", { id: $(this).val() },
//                    function (data){
//                    $("select#agreement-temp_attribute").html(data);
//                    $("select#agreement-temp_attribute").trigger("change");
//                })']) ?>
<!--    </div>-->
<!--    <div class="col-12 col-md-6">-->
<!--        --><?php //= $form->field($model, 'temp_attribute')->dropDownList(ArrayHelper::map(Poc::find()->where(['kcdio' => $model->pi_kulliyyah])->all(),'name','name'),['prompt' => 'Select POC', 'options' =>  [$model->pi_name => ['selected' => true]], 'onchange' => '
//        $.get("' . Yii::$app->urlManager->createUrl('agreement/get-poc-info') . '", { id: $(this).val() })
//            .done(function(data) {
//                $("#' . Html::getInputId($model, 'pi_name') . '").val(data.name);
//                $("#' . Html::getInputId($model, 'pi_kulliyyah') . '").val(data.kulliyyah);
//                $("#' . Html::getInputId($model, 'pi_email') . '").val(data.email);
//                $("#' . Html::getInputId($model, 'pi_phone_number') . '").val(data.phone_number);
//            })
//            .fail(function() {
//                // If the request fails, clear all the fields
//                $("#' . Html::getInputId($model, 'pi_name') . '").val("");
//                $("#' . Html::getInputId($model, 'pi_kulliyyah') . '").val("");
//                $("#' . Html::getInputId($model, 'pi_email') . '").val("");
//                $("#' . Html::getInputId($model, 'pi_phone_number') . '").val("");
//            });
//    ']) ?>
<!--    </div>-->
<!---->
<!--    <div class="col-12 col-md-6">--><?php //= $form->field($model, 'pi_email')->textInput(['maxlength' => true, 'readonly' => true])->label('Email') ?><!--</div>-->
<!--    <div class="col-12 col-md-6">--><?php //= $form->field($model, 'pi_phone_number')->textInput(['maxlength' => true, 'readonly' => true])->label('Phone Number') ?><!--</div>-->
<!--    --><?php //= $form->field($model, 'pi_kulliyyah', ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0'],])->hiddenInput(['maxlength' => true, 'class' => ''])->label(false) ?>
<!--    --><?php //= $form->field($model, 'pi_name', ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0'],])->hiddenInput(['maxlength' => true, 'class' => ''])->label(false) ?>
<!---->
<!--</div>-->


<?php
    $additionalPoc->renderExtraFields($form, $model, '');

if ($model->pi_name_x) {
    $additionalPoc->renderExtraFields($form, $model, '_x');
}

if ($model->pi_name_xx) {
    $additionalPoc->renderExtraFields($form, $model, '_xx');
}
?>

<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
