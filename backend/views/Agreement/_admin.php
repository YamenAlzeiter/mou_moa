<?php

use common\helpers\builders;
use yii\grid\ActionColumn;
use yii\grid\GridView;


/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
echo '<div class="container-md my-3 p-4 rounded-3 bg-white shadow"> <div class="table-responsive">';
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap rounded-3 overflow-hidden'],
    'columns' => [
        'id',
        [
            'attribute' => 'col_organization',
            'contentOptions' => ['class' => 'truncate'],
        ],
        [
            'attribute' => 'created_at', 'label' => 'Date', 'format' => ['date', 'php:d/m/y'],
            'enableSorting' => false,
        ],
        'country',
        'pi_kulliyyah',
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
            'template' => '{view}{log}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->actionBuilder($model, 'view');
                },
                'log' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->actionBuilder($model, 'log');
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
    'layout' => "{items}\n{summary}\n{pager}",
]);
echo '</div></div>'
?>
