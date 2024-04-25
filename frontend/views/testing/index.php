<?php

use common\models\Testing;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\TestingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Testings';

?>

<div class="testing-index">

        <?= Html::button('<i class="ti ti-plus fs-7" data-toggle="tooltip" title="view"></i> Add New Record', [
            'value' => Url::to(['create']),
            'class' => 'btn btn-lg btn-success shadow',
            'id' => 'modalButton',
            'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value')); $('#modal').find('.modal-title').html('<h1>Create</h1>');"
        ]); ?>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'first_name',
            'last_name',
            'email:email',
            'email1:email',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Testing $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>



<?php modal::begin([
    'title' => '',
    'id' => 'modal',
    'size' => 'modal-xl',
    'bodyOptions' => ['class' =>'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'],
    'centerVertical' => true,
    'scrollable' => true,
    'footer' => '',
]);

echo "<div id='modalContent'></div>";

modal::end();
?>