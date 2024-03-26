<?php

use common\helpers\builders;
use common\models\Agreement;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;


/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>

<?php Pjax::begin(); ?>
<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'dataColumnClass' => 'common\helpers\customColumClass',
    'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex'],
    'summary' => '',
    'columns' => [
        'id',
        'col_organization',
        'col_name',
        'col_address',
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
            'template' => '{update}{view}{log}',
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
                     if($model->status == 10) {
                         return $build->actionBuilder($model, 'update',);
                     } else return null;
                },
            ],
        ],
    ],
]); ?>

<?php Pjax::end(); ?>


