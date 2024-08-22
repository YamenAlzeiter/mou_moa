<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Agreements';
$build = new \common\helpers\builders();
Pjax::begin();
echo '<div class="table-responsive">';
echo '<div class="container-md my-3 p-3 rounded-3 bg-white shadow">
        <div class="row align-items-end">
            <div class="col-md-9 col-sm-12">
                '.$this->render('_search', ['model' => $searchModel]). '
            </div>';

if (Yii::$app->user->can('IO') || Yii::$app->user->can('RMC') || Yii::$app->user->can('OIL')) {
    echo '<div class="col-md-3 col-sm-12 ">
            <div class="d-flex flex-column align-items-center justify-content-center gap-2">'
                    . $build->createButton(['import'], 'ti-table', 'Import Records', 'Bulk Import', ['class' => 'btn btn-lg btn-primary w-100']) .

            '</div>
           </div>';
}
if (Yii::$app->user->can('OLA')) {
    echo '<div class="col-md-3 col-sm-12 ">
            <div class="d-flex flex-column align-items-center justify-content-center gap-2">'
        . $build->createButton(['create'], 'ti-plus', 'Create Special Record', 'Create Special Record', ['class' => 'btn btn-lg btn-primary w-100']) .
        '</div>
           </div>';
}
if(Yii::$app->user->can('OSIC')){
    echo '<div class="bulk-delete-container col-md-3 col-sm-12 d-none">
            <div class="d-flex flex-column align-items-end justify-content-end gap-2">'
        . Html::button('<i class="ti ti-trash fs-7"></i>', ['id' => 'bulk-delete', 'class' => 'btn btn-danger']) .
        '</div>
           </div>';
}
echo '</div>
    </div>';
?>

    <?php
    if (!Yii::$app->user->isGuest) {
        if (Yii::$app->user->can('IO') || Yii::$app->user->can('RMC') || Yii::$app->user->can('OIL')) {
            echo $this->render('_oscIndex', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel
            ]);
        } elseif (Yii::$app->user->can('OLA')) {
            echo $this->render('_olaIndex', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel
            ]);
        } elseif (Yii::$app->user->can('OSIC')) {
            echo $this->render('_admin', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel
            ]);
        }
    }
    Pjax::end();
    ?>


