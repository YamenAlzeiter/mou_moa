<?php

use common\helpers\builders;
use common\models\Status;
use kartik\datetime\DateTimePicker;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Model $model */

?>
<div class="status-form">

    <?php $form = ActiveForm::begin([]); ?>

    <?= $form->field($model, 'date_from')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Enter event start time ...', 'id' => 'date_from'],
        'layout' => '{picker}{input}',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true,
        ],
        'bsVersion' => '5'
    ]); ?>
    <?= $form->field($model, 'date_until')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Enter event end time ...', 'id' => 'date_until'],
        'layout' => '{picker}{input}',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'hh:ii', // Only allow time input
            'todayHighlight' => true,
            'startView' => 1, // Show the time view only
            'maxView' => 1, // Show the time view only
        ],
        'bsVersion' => '5'
    ]); ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
    // When the date_from field changes
    $('#date_from').on('change', function() {
        var dateFrom = $(this).val();
        var datePart = dateFrom.split(' ')[0]; // Get the date part only
        var timeUntil = $('#date_until').val();
        if (timeUntil !== '') {
            $('#date_until').val(datePart + ' ' + timeUntil);
        } else {
            $('#date_until').val(datePart + ' 00:00'); // Default time if no time selected
        }
    });

    // When the date_until time changes, maintain the date part
    $('#date_until').on('change', function() {
        var timeUntil = $(this).val();
        var dateFrom = $('#date_from').val();
        var datePart = dateFrom.split(' ')[0]; // Get the date part only
        $('#date_until').val(datePart + ' ' + timeUntil);
    });
JS;
$this->registerJs($js);
?>
