<?php

/** @var yii\web\View $this */
/** @var $events */

use yii\bootstrap5\ButtonDropdown;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Offcanvas;
use yii\helpers\Url;

$this->title = 'Calendar';
?>
<div class="container-md my-3 p-4 rounded-3 bg-white shadow ">

    <?= \yii2fullcalendar\yii2fullcalendar::widget([
        'clientOptions' => [
            'events' => $events,
            'editable' => true,
            'eventClick' => new \yii\web\JsExpression("
            function(event, jsEvent, view) {
                var parts = event.id.split('_');
                var action = parts[0];
                var id = parts[1];
                var url = '" . Url::to(['agreement/']) . "' + '/' + action + '?id=' + id;
              
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('#updateModal .modal-body').html(data);
                        $('#updateModal').modal('show');
                    }
                });
            }
        "),],
    ]) ?>
    </div>


<?php
Modal::begin([
    'title' => '',
    'id' => 'updateModal',
    'size' => 'modal-xl',
    'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'],
    'centerVertical' => true,
    'scrollable' => true,
    'footer' => '&nbsp;',
]);

Modal::end();
?>
