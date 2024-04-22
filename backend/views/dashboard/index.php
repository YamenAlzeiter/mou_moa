<?php

use common\helpers\builders;
use common\models\Agreement;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
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
</div>

<?php Pjax::begin(); ?>
<div class="table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'columns' => [
            'id',

            [
                'attribute' => 'col_organization',
                'contentOptions' => ['class' => 'truncate'],
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
<?php Pjax::end(); ?>

<?php modal::begin([
    'title' => '',
    'id' => 'modal',
    'size' => 'modal-xl',
    'bodyOptions' => ['class' =>'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'],
    'centerVertical' => true,
    'scrollable' => true,
    'footer' =>  '&nbsp;',
]);

echo "<div id='modalContent'></div>";

modal::end();
?>
