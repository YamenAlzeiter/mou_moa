<?php

use common\helpers\builders;
use yii\grid\ActionColumn;
use yii\grid\GridView;


/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
echo '<div class="container-md my-3 p-4 rounded-3 bg-white shadow">';
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap rounded-3 overflow-hidden'],
    'rowOptions' => function ($model) {
        $build = new builders();
        return $build->tableProbChanger($model->status, 'OLA') ? ['class' => 'need-action fw-bolder'] : [];
    },
    'columns' => [
        'id',
        [
            'attribute' => 'collaboration.col_organization',
            'contentOptions' => ['class' => 'truncate'],
        ],
        [
            'attribute' => 'created_at', 'label' => 'Date', 'format' => ['date', 'php:d/m/y'],
            'enableSorting' => false,
        ],
        'collaboration.country',
        [
            'label' => 'Champion',
            'value' => function ($model) {
                return $model->primaryAgreementPoc ? $model->primaryAgreementPoc->pi_kcdio : null;
            },
        ],
        'agreement_type',
        [
            'label' => 'Status',
            'attribute' => 'Status',
            'format' => 'raw',
            'value' => function ($model) {
                $statusHelper = new builders();
                return $statusHelper->pillBuilder($model->status);
            },
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{update}{view}{MCOMDate}{log}',
            'contentOptions' => ['class' => 'text-end'],
            'buttons' => [
                'MCOMDate' => function ($url, $model, $key) {
                    $build = new builders();
                    return $model->status == 21 ? $build->actionBuilder($model, 'mcom') : null;
                }, 'view' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->actionBuilder($model, 'view');
                },
                'log' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->actionBuilder($model, 'log');
                },
                'update' => function ($url, $model, $key) {
                    $build = new builders();

                    return $build->tableProbChanger($model->status, 'OLA') ? $build->actionBuilder($model,
                        'update') : null;

                },
            ],
        ],
    ],
    'pager' => [
        'class' => yii\bootstrap5\LinkPager::className(),
        'listOptions' => ['class' => 'pagination justify-content-center gap-2 borderless'],
        'activePageCssClass' => ['class' => 'link-white active'],
        // additional pager options if needed
    ],
    'layout' => "{items}\n{summary}\n{pager}\n",
]);
echo '</div>';
?>


