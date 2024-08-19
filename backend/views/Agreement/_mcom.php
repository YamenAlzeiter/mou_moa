<?php

use Carbon\Carbon;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\bootstrap5\ActiveForm $form */

$currentDate = Carbon::now();
$nextTwoWeeks = $currentDate->copy()->addWeeks(2);
$nextTwoMonth = $currentDate->copy()->addMonths(2);
?>

<?php $form = ActiveForm::begin([
    'id' => 'actiontaken',
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>

<?php if ($model->status == 21): ?>


    <?= $form->field($model, 'mcom_date')->dropDownList(
        ArrayHelper::map($mcomDates, 'date_from', function ($model) {
            $dateFrom = new DateTime($model->date_from);
            $dateUntil = new DateTime($model->date_until);
            return 'Date: ' . ' ' . $dateFrom->format('Y/M/d H:i') .", Time: " . $dateFrom->format('H:i') . " - " . $dateUntil->format('H:i') .  ', available: ' . ' ' . (10 - $model->counter);
        }),
        ['prompt' => 'Select a Date', 'required' => true]
    ) ?>


<?php endif; ?>

<div class = "d-flex flex-row gap-2 mb-2 justify-content-end">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>

