<?php

use common\helpers\builders;
use common\helpers\statusLable;
use common\models\Agreement;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\helpers\customColumClass;
/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Agreements';

?>


<?php modal::begin([
    'title' => '',
    'id' => 'modal',
    'size' => 'modal-xl',
    'bodyOptions' => ['class' =>'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding'],
    'centerVertical' => true,
    'scrollable' => true,
    ]);
echo "<div id='modalContent'></div>";
modal::end();
?>


<!--    <h1>--><?php //= Html::encode($this->title) ?><!--</h1>-->

    <?php Pjax::begin(); ?>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

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
                'template' => '{view}{MCOMDate}{update}{delete}{log}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $build = new builders();
                        if($model->status == null  || $model->status == 2 || $model->status == 12 || $model->status == 33 || $model->status == 43 ){
                            return $build->actionBuilder($model, 'update');
                        }
                        else return null;
                    },'MCOMDate' => function ($url, $model, $key) {
                        $build = new builders();
                        if($model->status == 11){
                            return $build->actionBuilder($model, 'MCOM Date');
                        }
                        else return null;
                    },
                    'view' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->actionBuilder($model, 'view');
                    },
                    'log' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->actionBuilder($model, 'log');
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="ti ti-trash text-danger fs-7"></i>', Url::to(['delete', 'id' => $model->id]), ['data-bs-toggle' => "tooltip",
                            'data-bs-placement' => "bottom", 'data-bs-html' => "true",
                            'title' => 'Delete', 'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>


<?= Html::button('<i class="ti ti-plus fs-7" data-toggle="tooltip" title="view"></i>', [
    'value' => Url::to(['create']),
    'class' => 'btn-danger rounded-circle btn-create',
    'id' => 'modalButton',
    'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value')); $('#modal').find('.modal-title').html('<h1>Create</h1>');"
]);
?>


<!-- Enables automatic updates of the GridView every 5 seconds -->
<!--<script>-->
<!--    $(document).ready(function() {-->
<!--        setInterval(function() {-->
<!--            $.ajax({-->
<!--                url: '/agreement/check-for-updates',-->
<!--                success: function(data) {-->
<!--                    if (data.hasUpdates) {-->
<!--                        $.pjax.reload({ container: '#p0' });-->
<!--                    }-->
<!--                }-->
<!--            });-->
<!--        }, 5000);-->
<!--    });-->
<!--</script>-->
