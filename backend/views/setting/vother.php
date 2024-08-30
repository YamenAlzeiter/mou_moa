<?php

use common\helpers\builders;
use yii\bootstrap5\Html;
use yii\bootstrap5\Offcanvas;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
$this->title = 'Other';
?>
<div class = "container-md my-3 p-4">
    <div class = "row">
        <div class = "col">
            <div class = "container-md my-3 p-4 rounded-3 bg-white shadow">
                <div class = "table-responsive">
                    <div class = "d-flex justify-content-between align-items-center">
                        <h1 class = "mb-0 fs-7"><i class = "ti ti-clock "></i> Reminders</h1>
                        <?= Html::button('<i class="ti fs-7 ti-plus"></i>',
                            [
                                'value' => Url::to(['create-reminder']),
                                'class' => 'btn btn-outline-dark text-start d-flex align-items-center gap-2 text-capitalize',
                                'id' => 'modelButton',
                                'data-bs-toggle' => "offcanvas", 'data-bs-target' => "#myOffcanvas",
                                'onclick' => 'loadOffcanvasContent(this)',
                            ]
                        );
                        ?>
                    </div>
                    <hr class = "border border-black ">


                    <?php


                    $models = $reminderDataProvider->getModels();
                    foreach ($models as $model) {
                        echo '<div class="d-flex  align-items-center gap-4 mb-2">';
                        echo Html::button('<i class="ti fs-7 ti-clock"></i> Remind '.$model->reminder_before.'  '.$model->type.' before ',
                            [
                                'value' => Url::to(['update-reminder', 'id' => $model->id]),
                                'class' => 'btn btn-outline-dark text-start d-flex flex-fill align-items-center gap-2 text-capitalize',
                                'id' => 'modelButton',
                                'data-bs-toggle' => "offcanvas",
                                'data-bs-target' => "#myOffcanvas",
                                'onclick' => 'loadOffcanvasContent(this)',
                            ]
                        );

                        // Delete Button
                        echo Html::a('<i class="ti fs-6 ti-trash"></i>',
                            ['delete-reminder', 'id' => $model->id], // Controller action and ID
                            [
                                'class' => 'btn btn-outline-danger',
                                'data-confirm' => 'Are you sure you want to delete this reminder?', // Add confirmation
                                'data-method' => 'post',
                                'data' => ['action' => Url::to(['delete-reminder', 'id' => $model->id]),],
                            ]
                        );
                        echo '</div>';
                    }

                    ?>
                </div>
            </div>

        </div>

        <div class = "col">
            <div class = "container-md my-3 p-4 rounded-3 bg-white shadow">
                <div class = "table-responsive">
                    <div class = "d-flex justify-content-between align-items-center">
                        <h1 class = "mb-0 fs-7 "><i class = "ti ti-align-right "></i>MCOM Date</h1>
                        <?= Html::button('<i class="ti fs-7 ti-plus"></i>',
                            [
                                'value' => Url::to(['create-mcom']),
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
                        'dataProvider' => $mcomDataProvider,
                        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap'],
                        'summary' => '',
                        'columns' => [
                            [
                                'attribute' => 'date_from', 'label' => 'Date', 'format' => ['date', 'php:d/M/y'],
                                'enableSorting' => false,
                            ],
                            [
                                'attribute' => 'date_from', 'label' => 'From', 'format' => ['date', 'php:h:m'],
                                'enableSorting' => false,
                            ],
                            [
                                'attribute' => 'date_until', 'label' => 'Until', 'format' => ['date', 'php:h:m'],
                                'enableSorting' => false,
                            ],
                            [
                                'class' => ActionColumn::className(),
                                'template' => '{update}{delete}',
                                'contentOptions' => ['class' => 'text-end'],
                                'buttons' => [
                                    'update' => function ($url, $model, $key) {
                                        return Html::button('<i class="ti fs-7 ti-pencil"></i>',
                                            [
                                                'value' => Url::to(['mcom-update', 'id' => $model->id]), // Use the update action and pass the model ID
                                                'class' => 'btn-action',
                                                'id' => 'modelButton-' . $model->id, // Ensure unique ID for each button
                                                'data-bs-toggle' => "offcanvas",
                                                'data-bs-target' => "#myOffcanvas",
                                                'onclick' => 'loadOffcanvasContent(this)',
                                            ]
                                        );
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<i class="ti fs-7 ti-trash"></i>',
                                            ['delete-mcom', 'id' => $model->id], // Controller action and ID
                                            [
                                                'class' => 'btn-action text-danger',
                                                'data-confirm' => 'Are you sure you want to delete this reminder?',
                                                // Add confirmation
                                                'data-method' => 'post',
                                                'data' => ['action' => Url::to(['delete-mcom', 'id' => $model->id])],
                                            ]
                                        );
                                    }
                                ],
                            ],
                        ],
                    ]); ?>



                    <?php Pjax::end(); ?>


                </div>
            </div>
        </div>
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
