<?php

use common\helpers\builders;
use yii\bootstrap5\Modal;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Email Templates';

?>
<div class="email-template-index">


    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'columns' => [
            'subject',
            [
                'class' => ActionColumn::className(),
                'template' => '{update}{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->buttonWithoutStatus($model, 'view',$model->subject);
                    },
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

