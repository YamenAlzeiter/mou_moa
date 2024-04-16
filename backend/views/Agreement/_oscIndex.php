<?php

use common\helpers\builders;
use common\models\Agreement;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;


/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>



<?= $this->render('_search', ['model' => $searchModel]); ?>


<div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'dataColumnClass' => 'common\helpers\customColumClass',
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'rowOptions' => function($model){
            if($model->status == 10 || $model->status == 51 || $model->status == 15 || $model->status == 72 || $model->status == 81){
                return ['class' => 'need-action fw-bolder'];
            }
        },
        'columns' => [
            'id',
            'col_organization',
            'country',
            'sign_date',
            'end_date',
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
                'template' => '{update}{addActivity}{view}{log}',
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
                        if($model->status == 10 || $model->status == 51 || $model->status == 15 || $model->status == 72 || $model->status == 81) {
                            return $build->actionBuilder($model, 'update',);
                        } else return null;
                    }
                ],
            ],
        ],
        'pager' => [
            'class' => yii\bootstrap5\LinkPager::className(),
            'listOptions' => ['class' => 'pagination justify-content-center gap-2 borderless'],
            'activePageCssClass' => ['class' => 'link-white active'],


            // additional pager options if needed
        ],'layout' => "{items}\n{pager}",
    ]); ?>

</div>



