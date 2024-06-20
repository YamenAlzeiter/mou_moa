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
/** @var yii\data\ActiveDataProvider $agreTypeDataProvider */


?>
<div class="d-flex justify-content-between align-items-center" >
    <h1 class="mb-0 fs-7 "><i class = "ti ti-align-right "></i> Agreement Type</h1>
    <?= Html::button('<i class="ti fs-7 ti-plus"></i>',
        [
            'value' => Url::to(['create-type']),
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
        'dataProvider' => $agreTypeDataProvider,
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'columns' => [
            'type',

            [
                'class' => ActionColumn::className(),
                'template' => '{update}{delete}',
                'contentOptions' => ['class' => 'text-end'],
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->buttonWithoutStatus($model, 'type-update', 'Update');
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="ti fs-7 ti-trash"></i>',
                            ['delete-type', 'id' => $model->id], // Controller action and ID
                            [
                                'class' => 'btn-action text-danger',
                                'data-confirm' => 'Are you sure you want to delete this reminder?', // Add confirmation
                                'data-method' => 'post',
                                'data' => ['action' => Url::to(['delete-type', 'id' => $model->id]),],
                            ]
                        );
                    }
                ],
            ],
        ],
    ]); ?>


    <?php Pjax::end(); ?>

</div>
