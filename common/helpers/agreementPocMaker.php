<?php

namespace common\helpers;

use common\models\Kcdio;
use common\models\Poc;
use Yii;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

class agreementPocMaker
{
    function renderExtraPocFields($form, $model)
    { ?>
        <div class="row poc-row">
            <div class="col-12 col-md-6">
                <?= $form->field($model, "[pocIndex]pi_kcdio")->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag', 'kcdio'), ['prompt' => 'Select KCDIO', 'id' => 'agreement-kcdio_name_getter-[pocIndex]', 'onchange' => '$.get("/agreement/get-kcdio-poc", { id: $(this).val() }, function (data) {
                                $("select#agreement-poc_name_getter-[pocIndex]").html(data);
                                $("select#agreement-poc_name_getter-[pocIndex]").trigger("change");
                            })']) ?>
            </div>
            <div class="col-12 col-md-6">
                <?= $form->field($model, "[pocIndex]pi_name")->dropDownList(ArrayHelper::map(Poc::find()->where(['kcdio' => $model->{"pi_kcdio"}])->all(), 'id', 'name'), ['prompt' => 'Select POC', 'id' => 'agreement-poc_name_getter-[pocIndex]', 'onchange' => '$.get("/agreement/get-poc-info", { id: $(this).val() })
                                .done(function(data) {
                                    $("#agreementpoc-[pocIndex]-pi_name").val(data.name);
                                    $("#agreementpoc-[pocIndex]-pi_address").val(data.address);
                                    $("#agreementpoc-[pocIndex]-pi_email").val(data.email);
                                    $("#agreementpoc-[pocIndex]-pi_phone").val(data.phone_number);
                                })
                                .fail(function() {
                                    $("#agreementpoc-[pocIndex]-pi_name").val(data.name);
                                    $("#agreementpoc-[pocIndex]-pi_address").val("");
                                    $("#agreementpoc-[pocIndex]-pi_email").val("");
                                    $("#agreementpoc-[pocIndex]-pi_phone").val("");
                                });']) ?>
            </div>
            <?= $form->field($model, "[pocIndex]pi_name", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
            <div class="col-12 col-md-6">
                <?= $form->field($model, "[pocIndex]pi_email")->textInput(['maxlength' => true, 'readonly' => true])->label('Email') ?>
            </div>
            <div class="col-12 col-md-6">
                <?= $form->field($model, "[pocIndex]pi_phone")->textInput(['maxlength' => true, 'readonly' => true])->label('Phone Number') ?>
            </div>
            <div class="col-12 col-md-12">
                <?= $form->field($model, "[pocIndex]pi_address")->textInput(['maxlength' => true, 'readonly' => true])->label('Address') ?>
            </div>
        </div>
    <?php }

    function renderInitPocFields($form, $model, $index, $defaultPoc)
    { ?>
        <div class="row poc-row">

            <div class="col-12 col-md-6">
                <?= $form->field($model, "[$index]pi_kcdio")->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag', 'kcdio'), ['prompt' => 'Select KCDIO', 'options' => [$defaultPoc->kcdio => ['selected' => true]], 'onchange' => '$.get("' . Yii::$app->urlManager->createUrl('agreement/get-kcdio-poc') . '", { id: $(this).val() }, function (data) {
            $("select#agreement-poc_name_getter-' . $index . '").html(data);
            $("select#agreement-poc_name_getter-' . $index . '").trigger("change");
        })']) ?>
            </div>
            <div class="col-12 col-md-6">


                <?= $form->field($model, "[$index]pi_name")->dropDownList(ArrayHelper::map(Poc::find()->where(['kcdio' => $defaultPoc->kcdio])->all(), 'id', 'name'), ['prompt' => 'asdf POC', 'id' => 'agreement-poc_name_getter-' . $index, 'options' => [$defaultPoc->id => ['Selected' => true]], 'onchange' => '$.get("/agreement/get-poc-info", { id: $(this).val() })
                        .done(function(data) {
                    $("#' . Html::getInputId($model, "[$index]pi_name") . '").val(data.name);
                    $("#' . Html::getInputId($model, "[$index]pi_address") . '").val(data.address);
                    $("#' . Html::getInputId($model, "[$index]pi_email") . '").val(data.email);
                    $("#' . Html::getInputId($model, "[$index]pi_phone") . '").val(data.phone_number);
                })
                              .fail(function() {
                    $("#' . Html::getInputId($model, "[$index]pi_name") . '").val("");
                    $("#' . Html::getInputId($model, "[$index]pi_address") . '").val("");
                    $("#' . Html::getInputId($model, "[$index]pi_email") . '").val("");
                    $("#' . Html::getInputId($model, "[$index]pi_phone") . '").val("");
                });']) ?>

            </div>

            <?= $form->field($model, "[$index]pi_name", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
            <div class="col-12 col-md-6">
                <?= $form->field($model, "[$index]pi_email")->textInput(['maxlength' => true, 'readonly' => true])->label('Email') ?>
            </div>
            <div class="col-12 col-md-6">
                <?= $form->field($model, "[$index]pi_phone")->textInput(['maxlength' => true, 'readonly' => true])->label('Phone Number') ?>
            </div>
            <div class="col-12 col-md-12">
            </div>
            <?= $form->field($model, "[$index]pi_address")->textInput(['maxlength' => true, 'readonly' => true])->label('Address') ?>
        </div>
    <?php }

    function renderUpdatedPocFields($form, $model, $index)
    {?>
        <div class="row poc-row">

            <div class="col-12 col-md-6">
                <?= $form->field($model, "[$index]pi_kcdio")
                    ->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag', 'kcdio'),
                        ['prompt' => 'Select KCDIO', 'options' => [$model->pi_kcdio => ['selected' => true]], 'onchange' => '$.get("' . Yii::$app->urlManager->createUrl('agreement/get-kcdio-poc') . '", { id: $(this).val() }, function (data) {
            $("select#agreement-poc_name_getter-' . $index . '").html(data);
            $("select#agreement-poc_name_getter-' . $index . '").trigger("change");
        })']) ?>
            </div>
            <div class="col-12 col-md-6">


                <?= $form->field($model, "[$index]pi_name")
                    ->dropDownList(ArrayHelper::map(Poc::find()->where(['kcdio' => $model->pi_kcdio])->all(), 'name', 'name'),
                        ['prompt' => 'asdf POC', 'id' => 'agreement-poc_name_getter-' . $index, 'options' => [$model->pi_name => ['Selected' => true]], 'onchange' => '$.get("/agreement/get-poc-info", { id: $(this).val() })
                        .done(function(data) {
                    $("#' . Html::getInputId($model, "[$index]pi_name") . '").val(data.name);
                    $("#' . Html::getInputId($model, "[$index]pi_address") . '").val(data.address);
                    $("#' . Html::getInputId($model, "[$index]pi_email") . '").val(data.email);
                    $("#' . Html::getInputId($model, "[$index]pi_phone") . '").val(data.phone_number);
                })
                              .fail(function() {
                    $("#' . Html::getInputId($model, "[$index]pi_name") . '").val("");
                    $("#' . Html::getInputId($model, "[$index]pi_address") . '").val("");
                    $("#' . Html::getInputId($model, "[$index]pi_email") . '").val("");
                    $("#' . Html::getInputId($model, "[$index]pi_phone") . '").val("");
                });']) ?>

            </div>

            <?= $form->field($model, "[$index]pi_name", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
            <div class="col-12 col-md-6">
                <?= $form->field($model, "[$index]pi_email")->textInput(['maxlength' => true])->label('Email') ?>
            </div>
            <div class="col-12 col-md-6">
                <?= $form->field($model, "[$index]pi_phone")->textInput(['maxlength' => true])->label('Phone Number') ?>
            </div>
            <div class="col-12 col-md-12">
            </div>
            <?= $form->field($model, "[$index]pi_address")->textInput(['maxlength' => true])->label('Address') ?>
        </div>
    <?php }

}

?>



