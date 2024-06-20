<?php

use common\helpers\builders;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $emailDataProvider */
$this->title = 'Email Template';

?>
<div class = "container-md my-3 p-4 rounded-3 bg-white shadow">
    <div class = "table-responsive">


        <?php Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $emailDataProvider,
            'tableOptions' => ['class' => 'table table-light border-black table-header-flex text-nowrap rounder-2 overflow-scroll'],
            'summary' => '',
            'columns' => [
                'subject',
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update}{view}',
                    'contentOptions' => ['class' => 'text-end'],
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $build = new builders();
                            return $build->buttonWithoutStatus($model, 'view-email-template', $model->subject);
                        },
                        'update' => function ($url, $model, $key) {
                            $build = new builders();

                            return $build->buttonWithoutStatus($model, 'update-email-template', 'Update');

                        },
                    ],
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>

    </div>
</div>
