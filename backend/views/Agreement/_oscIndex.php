<?php

use common\helpers\builders;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;


/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
echo '<div class="container-md my-3 p-4 rounded-3 bg-white shadow"> <div class="table-responsive">';
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap rounded-3 overflow-hidden'],

    'rowOptions' => function ($model) {
        $build = new builders();
        return $build->tableProbChanger($model->status, 'OSC') ? ['class' => 'need-action fw-bold'] : [];
    },
    'columns' => [
        'id',

        [
            'attribute' => 'col_organization',
            'contentOptions' => ['class' => 'truncate'],
        ],
        'country',
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
            'template' => '{update}{addActivity}{view}{log}{updatePoc}',
            'contentOptions' => ['class' => 'text-end'],
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->actionBuilder($model, 'view');
                },
                'log' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->actionBuilder($model, 'log');
                },
                'update' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->tableProbChanger($model->status, 'OSC') ? $build->actionBuilder($model,
                        'update',) : null;
                },
                'updatePoc' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->tableProbChanger($model->status, 'ApplicantActivity') ? $build->actionBuilder($model, 'update-poc') : null;
                }
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
echo '</div></div>';
?>




