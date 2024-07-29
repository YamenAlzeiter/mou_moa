<?php

use common\helpers\builders;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $kcdioDataProvider */


?>
<div class = "container-md my-3 p-4 rounded-3 bg-white shadow">
    <div class = "table-responsive">
        <div class = "d-flex justify-content-between align-items-center">
            <h1 class = "mb-0 fs-7 "><i class = "ti ti-clock "></i> K/C/D/I/O</h1>
            <?= Html::button('<i class="ti fs-7 ti-plus"></i>',

                       [
                        'value' => Url::to(['create-kcdio']),
                        'class' => 'btn btn-outline-dark text-start d-flex align-items-center gap-2 text-capitalize',
                        'id' => 'modelButton',
                        'data-bs-toggle' => "offcanvas", 'data-bs-target' => "#myOffcanvas",
                        'onclick' => 'loadOffcanvasContent(this)',
                       ]
            );
            ?>
        </div>
        <hr class = "border border-black">

        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $kcdioDataProvider,
            'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '],
            'summary' => '',
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
                            return Html::button('<i class="ti fs-7 ti-pencil"></i>',
                                [
                                    'value' => Url::to(['update-kcdio', 'id' => $model->id]), // Use the update action and pass the model ID
                                    'class' => 'btn-action',
                                    'id' => 'modelButton-' . $model->id, // Ensure unique ID for each button
                                    'data-bs-toggle' => "offcanvas",
                                    'data-bs-target' => "#myOffcanvas",
                                    'onclick' => 'loadOffcanvasContent(this)',
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>


<script>
    function loadOffcanvasContent(buttonElement) {
        const url = $(buttonElement).attr('value');
        $('#myOffcanvas').find('#offcanvas-body').load(url, function () {
            $('#offcanvas-body').append(''); // Add your loading indicator logic if needed
        });
    }
</script>