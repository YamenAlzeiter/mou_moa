<?php

use common\helpers\builders;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>

<?php modal::begin(['title' => '', 'id' => 'modal', 'size' => 'modal-xl', 'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'], 'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'], 'centerVertical' => true, 'scrollable' => true, 'footer' => '&nbsp;',]);

echo "<div id='modalContent'></div>";

modal::end();
?>
<?php Pjax::begin(); ?>
    <div class="my-3 p-3 border-2 rounded-3 bg-light-gray shadow">
        <?= $this->render('/agreement/_search', ['model' => $searchModel]); ?>
    </div>
    <div class="container-md my-3 p-4 rounded-3 bg-white shadow">
        <div class="table-responsive">

            <?= GridView::widget(['dataProvider' => $dataProvider, //    'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap rounded-3 overflow-hidden'],
                'summary' => '',
                'rowOptions' => function ($model) {
                    $build = new builders();
                    return $build->tableProbChanger($model->status, 'OLA') ? ['class' => 'need-action fw-bolder'] : [];
                },
                'columns' => [
                    'id',
                    [
                        'attribute' => 'col_organization',
                        'contentOptions' => ['class' => 'truncate'],
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Date',
                        'format' => ['date', 'php:d/m/y'],
                        'enableSorting' => false,
                    ],
                    'country',
                    [
                        'label' => 'Champion',
                        'value' => function ($model) {
                            return $model->primaryAgreementPoc ? $model->primaryAgreementPoc->pi_kcdio : null;
                        },
                    ],
                    [
                            'label' => 'PIC',
                        'value' => function ($model) {
                            return $model->primaryAgreementPoc ? $model->primaryAgreementPoc->pi_name : null;
                        },
                    ],
                    'sign_date',
                    'end_date',
                    'agreement_type',
                ],
                'pager' => ['class' => yii\bootstrap5\LinkPager::className(),
                    'listOptions' => ['class' => 'pagination justify-content-center gap-2 borderless'],
                    'activePageCssClass' => ['class' => 'link-white active'],
                ], 'layout' => "{items}\n{pager}",]); ?>

        </div>
    </div>
<?php Pjax::end(); ?>