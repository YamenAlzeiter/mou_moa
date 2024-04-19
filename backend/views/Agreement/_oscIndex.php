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



<div class="d-flex align-items-end justify-content-between">
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    <?= Html::button(
        '<i class="ti fs-5 ti-table" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="Import records"></i> Import Records',
        [
            'value' => Url::to(['import']), // Replace with your actual route
            'class' => 'btn btn-success fs-5',
            'id' => 'modelButton',
            'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value'), function() {
            // Append the HTML snippet to the modal content
            $('#modalContent').append(''); 
            
            // Set the modal title
            $('#modal').find('.modal-title').html('<h1 class=\"mb-0\">Bulk Import</h1>'); 
        });"
        ]
    ); ?>

</div>


<div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //        'filterModel' => $searchModel,
        //        'dataColumnClass' => 'common\helpers\customColumClass',
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'rowOptions' => function($model){
            $build = new builders();
            return $build->tableProbChanger($model->status, 'OSC') ? ['class' => 'need-action fw-bold'] : [];
        },
        'columns' => [
            'id',
            'col_organization',
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
                        return $build->tableProbChanger($model->status, 'OSC') ?  $build->actionBuilder($model, 'update',): null;
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



