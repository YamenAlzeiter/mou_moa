<?php

use common\helpers\builders;
use common\models\Status;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $statusDataProvider */

?>
<div class="overflow-auto rounded-2" style="max-height: 300px;">

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $statusDataProvider,
        'tableOptions' => ['class' => 'table table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'headerRowOptions' => ['class' => 'sticky-top shadow-sm'],
        'columns' => [
            'tag',
            'description',
            [
                'class' => ActionColumn::className(),
                'template' => '{update}',
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