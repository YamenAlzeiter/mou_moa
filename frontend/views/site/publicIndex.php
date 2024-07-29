<?php

use common\helpers\builders;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>


<?php Pjax::begin(); ?>

    <div class="d-flex flex-column justify-content-center align-items-center vh-100">
<!--        <div class="my-3 p-3 border-2 rounded-3 bg-light-gray shadow">-->
<!--            --><?php //= $this->render('/agreement/_search', ['model' => $searchModel]); ?>
<!--        </div>-->
        <div class="rounded-3 bg-white shadow">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => [
                        'class' => 'table table-borderless table-striped table-header-flex text-nowrap rounded-3 overflow-hidden w-100',
                        'style' => 'min-width: 1200px;', // optional: set a minimum width
                    ],
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
                        'sign_date',
                        'end_date',
                        'agreement_type',
                    ],
                ]); ?>
            </div>
        </div>
    </div>


<?php Pjax::end(); ?>