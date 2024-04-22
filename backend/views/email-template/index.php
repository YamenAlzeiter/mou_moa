<?php

use common\helpers\builders;
use common\models\EmailTemplate;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Email Templates';

?>
<div class="email-template-index">


    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap  '], 'summary' => '',
        'columns' => [
            'id',
            'subject',
            [
                'class' => ActionColumn::className(),
                'template' => '{update}{view}{log}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $build = new builders();
                        return $build->buttonWithoutStatus($model, 'view');
                    },

                    'update' => function ($url, $model, $key) {
                        $build = new builders();

                        return $build->buttonWithoutStatus($model, 'update');

                    },
                ],
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
    'footer' =>  '&nbsp;',
]);

echo "<div id='modalContent'></div>";

modal::end();
?>