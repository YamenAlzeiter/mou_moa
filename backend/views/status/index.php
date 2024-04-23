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
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Status';
?>
<div class="status-index">

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'columns' => [
            'tag',
            'description',
            [
                'class' => ActionColumn::className(),
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $build = new builders();

                        return $build->buttonWithoutStatus($model, 'update', 'Update');

                    },
                ],
            ],
        ],
    ]); ?>


    <?php Pjax::end(); ?>

</div>

