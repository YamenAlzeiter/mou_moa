<?php


use Itstructure\CKEditor\CKEditor;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Reminder $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="email-template-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],
        ],
    ]); ?>

    <p>Remind Before </p>
    <?= $form->field($model, 'reminder_before')->dropDownList(
        ArrayHelper::map(range(1, 12), function($value) {
            return $value;
        }, function($value) {
            return $value;
        })
    ) ?>
    <?= $form->field($model, 'type')->dropDownList(['DAY' => 'Days', 'MONTH' => 'Months']) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
