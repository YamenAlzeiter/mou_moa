<?php

use Carbon\Carbon;
use common\models\McomDate;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var yii\bootstrap5\ActiveForm $form */

$currentDate = Carbon::now();
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
        ArrayHelper::map(
            McomDate::find()
                ->where(['<', 'counter', 10])
                ->andWhere(['>', 'date', $currentDate->toDateString()])
                ->andWhere(['<', 'date', $nextTwoMonth->toDateString()])
                ->limit(3) // Limit the number of results to three
                ->all(),
            'date',
            function ($model) {
                return 'Date: ' . ' ' . $model->date . ', available: ' . ' ' . (10 - $model->counter);
            }
        ),
        ['prompt' => 'Select a Date']
    ) ?>

<?php endif; ?>

<div class = "d-flex flex-row gap-2 mb-2 justify-content-end">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>

