<?php

use common\helpers\builders;
use yii\bootstrap5\Modal;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Agreements';

?>


<?php modal::begin([
    'title' => '',
    'id' => 'modal',
    'size' => 'modal-xl',
    'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'],
    'centerVertical' => true,
    'scrollable' => true,
    'footer' => '&nbsp;',
]);

echo "<div id='modalContent'></div>";

modal::end();
?>

<?php Pjax::begin(); ?>

<div class = "container-md my-3 p-3 rounded-3 bg-light shadow">
    <div class = "row align-items-end">
        <div class = "col-md-9 col-sm-12"> <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
        <div
            class = "col-md-3 col-sm-12 mt-3 mt-md-0"> <?= Html::button('<i class="ti ti-plus fs-7" ></i> Add New Record',
                [
                    'value' => Url::to(['create']),
                    'class' => 'btn btn-lg btn-success w-100', // Adjust width as needed
                    'id' => 'modalButton',
                    'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value')); $('#modal').find('.modal-title').html('<h1>Create</h1>');",
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-placement' => 'top',
                    'title' => 'Add New Record'
                ]); ?>
        </div>
    </div>
</div>
<div class="container-md my-3 p-4 rounded-3 bg-white shadow">
<div class = "table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider, //        'filterModel' => $searchModel,
//        'dataColumnClass' => 'common\helpers\customColumClass',
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap rounded-3 overflow-hidden'],
        'summary' => '',
        'rowOptions' => function ($model) {
            $build = new builders();
            return $build->tableProbChanger($model->status, 'Applicant')
            || $build->tableProbChanger($model->status, 'ApplicantMCOM')
                ? ['class' => 'need-action fw-bold'] : [];
        },
        'columns' => [
            'id',
            [
                'attribute' => 'col_organization',
                'contentOptions' => ['class' => 'truncate'],
            ],
            'champion',
            [
                'attribute' => 'created_at',
                'label' => 'Date',
                'format' => ['date', 'php:d/m/y'],
                'enableSorting' => false,
            ], 'country',
            'agreement_type', [
                'label' => 'Status',
                'attribute' => 'Status',
                'format' => 'raw',
                'value' => function ($model) {
                    $statusHelper = new builders();
                    return $statusHelper->pillBuilder($model->status);
                },
            ], [
                'class' => ActionColumn::className(),
                'template' => '{view}{addActivity}{MCOMDate}{update}{log}',
                'contentOptions' => ['class' => 'text-end'],
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->tableProbChanger($model->status, 'Applicant') ? $build->actionBuilder($model,
                            'update') : null;
                    }, 'MCOMDate' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->tableProbChanger($model->status, 'ApplicantMCOM') ? $build->actionBuilder($model,
                            'MCOM Date') : null;
                    }, 'addActivity' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->tableProbChanger($model->status,
                            'ApplicantActivity') ? $build->actionBuilder($model, 'Add Activity',) : null;
                    }, 'view' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->actionBuilder($model, 'view');
                    }, 'log' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->actionBuilder($model, 'log');
                    },
                ],
            ],
        ], 'pager' => [
            'class' => yii\bootstrap5\LinkPager::className(),
            'listOptions' => ['class' => 'pagination justify-content-center gap-2 borderless'],
            'activePageCssClass' => ['class' => 'link-white active'],// additional pager options if needed
        ], 'layout' => "{items}\n{pager}",
    ]); ?>
</div>
</div>

<?php Pjax::end(); ?>


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
