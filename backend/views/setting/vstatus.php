<?php

use common\helpers\builders;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $statusDataProvider */
$this->title = 'Status';

?>
<div class = "container-md my-3 p-4 rounded-3 bg-white shadow">
    <div class = "table-responsive">

        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $statusDataProvider,
            'tableOptions' => ['class' => 'table table-borderless table-striped table-header-flex text-nowrap  '],
            'summary' => '',
            'columns' => [
                'tag',
                'description',
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update}',
                    'contentOptions' => ['class' => 'text-end'],
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            $build = new builders();
                            return $build->buttonWithoutStatus($model, 'status-update', 'Update');

                        },
                    ],
                ],
            ],
        ]); ?>


        <?php Pjax::end(); ?>

    </div>
</div>