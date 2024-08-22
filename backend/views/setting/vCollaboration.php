<?php use common\helpers\builders;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title = 'Organizations';
Pjax::begin(); ?>
<div class="container-md my-3 p-3 rounded-3 bg-white shadow">
<?= GridView::widget([
    'dataProvider' => $collaborationDataProvider,
    'tableOptions' => ['class' => 'table table-light border-black table-header-flex text-nowrap rounder-2 overflow-scroll'], 'summary' => '',
    'headerRowOptions' => ['class' => 'sticky-top shadow-sm'],
    'columns' => [
        'col_organization',
        'country',
        [
            'class' => ActionColumn::className(),
            'template' => '{update}',
            'contentOptions' => ['class' => 'text-end'],
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    $build = new builders();

                    return $build->buttonWithoutStatus($model, 'update-collaboration', 'Update');

                },
            ],
        ],
    ],
]); ?>

<?php Pjax::end(); ?>

</div>

