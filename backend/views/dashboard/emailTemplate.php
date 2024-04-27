<?php

use common\helpers\builders;
use yii\bootstrap5\Modal;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $emailDataProvider */

?>
<div class="overflow-auto rounded-2 " style="max-height: 300px;">


    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $emailDataProvider,
        'tableOptions' => ['class' => 'table table-light border-black table-header-flex text-nowrap rounder-2 overflow-scroll'], 'summary' => '',
        'headerRowOptions' => ['class' => 'sticky-top shadow-sm'],
        'columns' => [
            'subject',
            [
                'class' => ActionColumn::className(),
                'template' => '{update}{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->buttonWithoutStatus($model, 'view-email-template',$model->subject);
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

