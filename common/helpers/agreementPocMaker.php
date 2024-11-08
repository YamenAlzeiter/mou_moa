<?php

namespace common\helpers;

use common\models\Kcdio;
use common\models\LookupCdKcdiom;
use common\models\Poc;
use Yii;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

class agreementPocMaker
{
        function renderExtraPocFields($form, $model)
        { ?>
            <div id="poc-row-${pocIndex}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="mb-0">Additional Person in Charge Details IIUM</h4>
                    <button type="button" class="btn btn-danger remove-poc-button" data-index="${pocIndex}"><i class="ti ti-x fs-6"></i></button>
                </div>
                <div class="row poc-row">

                    <div class="col-12 col-md-6">
                        <?= $form->field($model, "[pocIndex]pi_kcdio")
                            ->dropDownList(
                                ArrayHelper::map(
                                    LookupCdKcdiom::find()->where(['not', ['abb_code' => null]])->all(),
                                    'abb_code',
                                    'kcdiom_desc'
                                ),
                                ['prompt' => 'Select KCDIO']
                            )
                        ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, "[pocIndex]pi_name")->textInput(['maxlength' => true, 'placeholder' => '']) ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, "[pocIndex]pi_email")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                    </div>
                    <div class="col-12 col-md-6">
                        <?= $form->field($model, "[pocIndex]pi_phone")->textInput(['maxlength' => true, 'placeholder' => '']) ?>
                    </div>
                    <div class="col-12 col-md-9">
                        <?= $form->field($model, "[pocIndex]pi_address")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                    </div>
                    <div class="col-12 col-md-3">
                        <?= $form->field($model, "[pocIndex]pi_role")->dropDownList([], ['class' => 'role-dropdown', 'prompt' => 'Select Role']) ?>
                    </div>
                    <?= $form->field($model, "[pocIndex]pi_is_primary", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['value' => false])->label(false) ?>
                </div>
            </div>
        <?php }

        function renderInitPocFields($form, $model, $index)
        {
            ?>
            <h4>Details of Person in Charge IIUM </h4>
            <div class="row poc-row">
                <div class="col-12 col-md-6">
                    <?= $form->field($model, "[$index]pi_kcdio")
                        ->dropDownList(
                            ArrayHelper::map(
                                LookupCdKcdiom::find()->where(['not', ['abb_code' => null]])->all(),
                                'abb_code',
                                'kcdiom_desc'
                            ),
                            ['prompt' => 'Select KCDIO']
                        )
                    ?>
                </div>
                <div class="col-12 col-md-6">
                    <?= $form->field($model, "[$index]pi_name")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                </div>
                <div class="col-12 col-md-6">
                    <?= $form->field($model, "[$index]pi_email")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                </div>
                <div class="col-12 col-md-6">
                    <?= $form->field($model, "[$index]pi_phone")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                </div>
                <div class="col-12 col-md-9">
                    <?= $form->field($model, "[$index]pi_address")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                </div>
                <div class="col-12 col-md-3">
                    <?= $form->field($model, "[$index]pi_role")->dropDownList([], ['class' => 'role-dropdown', 'prompt' => 'Select Role']) ?>
                </div>

                    <?= $form->field($model, "[$index]pi_is_primary", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['value' => true])->label(false) ?>

            </div>

        <?php
        }

    function renderUpdatedPocFields($form, $model, $index) {?>
        <div id="poc-row-<?= $index?>">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4><?php echo $model->pi_is_primary ? 'Details of Person in Charge IIUM Primary' : 'Details of Person in Charge IIUM'; ?></h4>
                <?= !$model->pi_is_primary ? '<button type="button" class="btn btn-danger remove-poc-button" data-index="' . $index . '"><i class="ti ti-x fs-6"></i></button>' : '' ?>

            </div>
            <div class="row poc-row">
                <div class="col-12 col-md-6">
                    <?= $form->field($model, "[$index]pi_kcdio")
                        ->dropDownList(
                            ArrayHelper::map(
                                LookupCdKcdiom::find()->where(['not', ['abb_code' => null]])->all(),
                                'abb_code',
                                'kcdiom_desc'
                            ),
                            ['prompt' => 'Select KCDIO']
                        )
                    ?>

                </div>
                <div class="col-12 col-md-6">
                    <?= $form->field($model, "[$index]pi_name")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                </div>

                <div class="col-12 col-md-6">
                    <?= $form->field($model, "[$index]pi_email")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                </div>
                <div class="col-12 col-md-6">
                    <?= $form->field($model, "[$index]pi_phone")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                </div>
                <div class="col-12 col-md-9">
                    <?= $form->field($model, "[$index]pi_address")->textInput(['maxlength' => true, 'placeholder' => ''])?>
                </div>
                <div class="col-12 col-md-3">
                    <?= $form->field($model, "[$index]pi_role")->dropDownList([], ['class' => 'role-dropdown', 'prompt' => 'Select Role']) ?>
                </div>
            </div>
        </div>
    <?php }

}

?>



