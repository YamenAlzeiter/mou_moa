<?php

use common\helpers\builders;
use common\models\Kcdio;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $pocDataProvider */
/** @var common\models\search\PocSearch $pocSearchModel */

?>

<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0 fs-7"><i class="ti ti-clock"></i> Person In Charge</h1>

    <?= Html::button('<i class="ti fs-7 ti-plus"></i>',
        [
            'value' => Url::to(['create-poc']),
            'class' => 'btn btn-outline-dark text-start d-flex align-items-center gap-2 text-capitalize',
            'id' => 'modelButton',
            'data-bs-toggle' => "offcanvas", 'data-bs-target' => "#myOffcanvas", 'onclick' => 'loadOffcanvasContent(this)',
        ]
    );
    ?>
</div>
<hr class="border border-black">
<div class="poc-search">

    <?php Pjax::begin(['id' => 'poc-grid-pjax']); ?>

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['data-pjax' => true],
        'fieldConfig' => [
            'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>",
            'labelOptions' => ['class' => ''],
        ],
    ]); ?>

    <?= $form->field($pocSearchModel, 'name')->textInput(['placeholder' => '']) ?>

    <?php ActiveForm::end(); ?>

    <hr>
    <div class="flex-column d-flex gap-2 overflow-auto" style="max-height: 500px">

        <?= GridView::widget([
            'dataProvider' => $pocDataProvider,
            'tableOptions' => ['class' => 'table table-borderless table-striped table-header-flex text-nowrap'],
            'summary' => '',
            'columns' => [
                'name',
                'kcdio',
                [
                    'class' => ActionColumn::className(),
                    'template' => '{update}{delete}',
                    'contentOptions' => ['class' => 'text-end'],
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            $build = new builders();
                            return $build->buttonWithoutStatus($model, 'poc-update', 'Update');
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<i class="ti fs-7 ti-trash"></i>',
                                ['delete-poc', 'id' => $model->id],
                                [
                                    'class' => 'btn-action text-danger',
                                    'data-confirm' => 'Are you sure you want to delete this reminder?',
                                    'data-method' => 'post',
                                    'data' => ['action' => Url::to(['delete-poc', 'id' => $model->id])],
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

