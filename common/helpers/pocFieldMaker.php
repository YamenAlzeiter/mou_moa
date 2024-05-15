<?php
namespace common\helpers;

use common\models\Kcdio;
use common\models\Poc;
use Yii;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

class pocFieldMaker
{
    function renderExtraFields($form, $model, $attributePrefix) {
        ?>
        <div class="row">
            <div class="col-12 col-md-6">
                <?= $form->field($model, "poc_kcdio_getter{$attributePrefix}")->dropDownList(
                    ArrayHelper::map(Kcdio::find()->all(), 'tag', 'kcdio'),
                    ['prompt' => 'Select KCDIO',
                        'onchange' => '$.get("' . Yii::$app->urlManager->createUrl('agreement/get-kcdio-poc') . '", { id: $(this).val() }, 
                function (data){
                $("select#agreement-poc_name_getter' . $attributePrefix . '").html(data);
                $("select#agreement-poc_name_getter ' . $attributePrefix . '").trigger("change");
            })']) ?>
            </div>
            <div class="col-12 col-md-6">
                <?= $form->field($model, "poc_name_getter{$attributePrefix}")->dropDownList(
                    ArrayHelper::map(Poc::find()->where(['kcdio' => $model->{"pi_kulliyyah$attributePrefix"}])->all(), 'name', 'name'),
                    ['prompt' => 'Select POC', 'options' => [$model->{"pi_name$attributePrefix"} => ['selected' => true]], 'onchange' => '
        $.get("' . Yii::$app->urlManager->createUrl('agreement/get-poc-info') . '", { id: $(this).val() })
            .done(function(data) {
                $("#' . Html::getInputId($model, "pi_name$attributePrefix") . '").val(data.name);
                $("#' . Html::getInputId($model, "pi_kulliyyah$attributePrefix") . '").val(data.kulliyyah);
                $("#' . Html::getInputId($model, "pi_email$attributePrefix") . '").val(data.email);
                $("#' . Html::getInputId($model, "pi_phone_number$attributePrefix") . '").val(data.phone_number);
            })
            .fail(function() {
                // If the request fails, clear all the fields
                $("#' . Html::getInputId($model, "pi_name$attributePrefix") . '").val("");
                $("#' . Html::getInputId($model, "pi_kulliyyah$attributePrefix") . '").val("");
                $("#' . Html::getInputId($model, "pi_email$attributePrefix") . '").val("");
                $("#' . Html::getInputId($model, "pi_phone_number$attributePrefix") . '").val("");
            });
    ']) ?>
            </div>

            <div class="col-12 col-md-6"><?= $form->field($model, "pi_email$attributePrefix")->textInput(['maxlength' => true, 'readonly' => true])->label('Email') ?></div>
            <div class="col-12 col-md-6"><?= $form->field($model, "pi_phone_number$attributePrefix")->textInput(['maxlength' => true, 'readonly' => true])->label('Phone Number') ?></div>
            <?= $form->field($model, "pi_kulliyyah$attributePrefix", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['maxlength' => true, 'class' => ''])->label(false) ?>
            <?= $form->field($model, "pi_name$attributePrefix", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])
                ->hiddenInput(['maxlength' => true, 'class' => ''])
                ->label(false) ?>
        </div>
        <?php
    }
}
?>
