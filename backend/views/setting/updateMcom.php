<?php

use common\helpers\builders;
use common\models\Status;
use kartik\datetime\DateTimePicker;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\McomDate $model */

?>
<div class="status-form">

    <?php $form = ActiveForm::begin([]); ?>

    <?= $form->field($model, 'date_from')->widget(DateTimePicker::classname(), [
        'options' => [
            'placeholder' => 'Enter event start time ...',
            'id' => 'date_from',
            'value' => $model->date_from ? date('Y-m-d H:i', strtotime($model->date_from)) : null, // Initialize with existing value if it exists
        ],
        'layout' => '{picker}{input}',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii', // Ensure the format is correct
            'todayHighlight' => true,
        ],
        'bsVersion' => '5'
    ]); ?>



    <?= $form->field($model, 'date_until')->widget(DateTimePicker::classname(), [
        'options' => [
            'placeholder' => 'Enter event end time ...',
            'id' => 'date_until',
            'value' => $model->date_until ? date('Y-m-d H:i', strtotime($model->date_until)) : null, // Initialize with existing value if it exists
        ],
        'layout' => '{picker}{input}',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii', // Ensure the format is correct
            'todayHighlight' => true,
            'startView' => 2,
            'minView' => 0,
            'maxView' => 1,
        ],
        'bsVersion' => '5'
    ]); ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

