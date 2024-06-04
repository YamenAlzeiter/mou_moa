<?php

/** @var common\models\Agreement $model */

use common\helpers\builders;
use yii\bootstrap5\Html;
use yii\grid\GridView;

?>


<h1 class="fs-7">
    <?= $model->id . ": " . $model->col_organization ?>
</h1>
<?= GridView::widget([
    'id' => 'custom-gridview-id',
    'options' => ['id' => 'log'],
    'dataProvider' => $logsDataProvider,
    'tableOptions' => ['class' => 'table text-nowrap mb-0 '],
    'summary' => '',
    'columns' => [
        [
            'attribute' => 'created_at',
            'label' => 'Date',
            'format' => ['date', 'php:d/M/y H:i'],
            'enableSorting' => false,
            'headerOptions' => ['class' => 'col-3 '],

        ],
        [
            'label' => 'Current Status',
            'format' => 'raw',
            'value' => function ($model) {
                $statusHelper = new builders();
                return $statusHelper->pillBuilder($model->new_status);
            },
            'contentOptions' => ['class' => 'col-2'],

        ],
        'created_by' => [
            'attribute' => 'created_by',
            'headerOptions' => ['class' => 'col-5'],
            'enableSorting' => false,
        ],
        [
            'attribute' => 'message',
            'format' => 'raw',
            'enableSorting' => false,
            'value' => function ($model) {
                if ($model->message == "") {
                    return '/';
                } else {
                    return $model->message;
                }
            },
            'headerOptions' => ['class' => 'col-4'],
        ],
    ],
]); ?>
