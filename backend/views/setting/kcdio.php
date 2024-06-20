<?php

use common\helpers\builders;
use common\models\Kcdio;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $kcdioDataProvider */


?>
    <div class="d-flex justify-content-between align-items-center" >
        <h1 class="mb-0 fs-7 "><i class = "ti ti-clock "></i> K/C/D/I/O</h1>
        <?= Html::button('<i class="ti fs-7 ti-plus"></i>',
            [
                'value' => Url::to(['create-kcdio']),
                'class' => 'btn btn-outline-dark text-start d-flex align-items-center gap-2 text-capitalize', 'id' => 'modelButton',
                'data-bs-toggle' => "offcanvas", 'data-bs-target' => "#myOffcanvas",'onclick' => 'loadOffcanvasContent(this)',
            ]
        );
        ?>
    </div>
    <hr class = "border border-black">
<div class = "flex-column d-flex gap-2 overflow-auto" style="max-height: 500px">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $kcdioDataProvider,
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'columns' => [
            [
                    'attribute' => 'kcdio',
                    'label' => 'KCDIO'
            ],
            'tag',
            [
                'class' => ActionColumn::className(),
                'template' => '{update}',
                'contentOptions' => ['class' => 'text-end'],
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $build = new builders();

                        return $build->buttonWithoutStatus($model, 'update-kcdio', 'Update');

                    },
                ],
            ],
        ],
    ]); ?>


    <?php Pjax::end(); ?>

</div>
