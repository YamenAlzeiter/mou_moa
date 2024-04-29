<?php

use common\helpers\builders;
use common\models\Kcdio;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'K/C/D/I/O';

?>
<div class="kcdio-index">




    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'columns' => [
            [
                    'attribute' => 'kcdio',
                    'label' => 'KCDIO'
            ],
            'tag',
            [
                'class' => ActionColumn::className(),
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $build = new builders();

                        return $build->buttonWithoutStatus($model, 'update', 'Update');

                    },
                ],
            ],
        ],
    ]); ?>


    <?php Pjax::end(); ?>

</div>

<?= Html::button('<i class="ti ti-plus fs-7" data-toggle="tooltip" title="view"></i>', [
    'value' => Url::to(['create']),
    'class' => 'add-btn btn-create rounded-circle',
    'id' => 'modalButton',
    'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value')); $('#modal').find('.modal-title').html('<h1>Create</h1>');"
]); ?>