<?php

use coderius\pell\Pell;
use Itstructure\CKEditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\EmailTemplate $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="email-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>


<?=      Pell::widget([
    'model' => $model,
    'attribute' => 'body',
]);?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
