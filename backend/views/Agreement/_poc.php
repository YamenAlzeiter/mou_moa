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

$model->temp_attribute_poc = $model->pi_kulliyyah;
$model->temp_attribute = $model->pi_kulliyyah;

?>

<div class="row">

    <div class="col-12 col-md-6">
        <?= $form->field($model, 'temp_attribute_poc')->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag', 'kcdio'), ['prompt' => 'Select KCDIO', 'onchange' => '$.get("' . Yii::$app->urlManager->createUrl('agreement/get-kcdio-poc') . '", { id: $(this).val() }, 
                    function (data){
                    $("select#agreement-temp_attribute").html(data);
                    $("select#agreement-temp_attribute").trigger("change");
                })']) ?>
    </div>
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'temp_attribute')->dropDownList([],['prompt' => 'Select POC', 'onchange' => '
        $.get("' . Yii::$app->urlManager->createUrl('agreement/get-poc-info') . '", { id: $(this).val() })
            .done(function(data) {
                $("#' . Html::getInputId($model, 'pi_name') . '").val(data.name);
                $("#' . Html::getInputId($model, 'pi_kulliyyah') . '").val(data.kulliyyah);
                $("#' . Html::getInputId($model, 'pi_email') . '").val(data.email);
                $("#' . Html::getInputId($model, 'pi_phone_number') . '").val(data.phone_number);
            })
            .fail(function() {
                // If the request fails, clear all the fields
                $("#' . Html::getInputId($model, 'pi_name') . '").val("");
                $("#' . Html::getInputId($model, 'pi_kulliyyah') . '").val("");
                $("#' . Html::getInputId($model, 'pi_email') . '").val("");
                $("#' . Html::getInputId($model, 'pi_phone_number') . '").val("");
            });
    ']) ?>
    </div>

    <div class="col-12 col-md-6"><?= $form->field($model, 'pi_email')->textInput(['maxlength' => true, 'readonly' => true])->label('Email') ?></div>
    <div class="col-12 col-md-6"><?= $form->field($model, 'pi_phone_number')->textInput(['maxlength' => true, 'readonly' => true])->label('Phone Number') ?></div>
    <?= $form->field($model, 'pi_kulliyyah', ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0'],])->hiddenInput(['maxlength' => true, 'class' => ''])->label(false) ?>
    <?= $form->field($model, 'pi_name', ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0'],])->hiddenInput(['maxlength' => true, 'class' => ''])->label(false) ?>

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
