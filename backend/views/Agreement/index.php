<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Agreements';

$type = Yii::$app->user->identity->type;
$build = new \common\helpers\builders();

echo '<div class="table-responsive">';
echo '<div class="container-md my-3 p-3 rounded-3 bg-light shadow">
        <div class="row align-items-end">
            <div class="col-md-9 col-sm-12">
                '.$this->render('_search', ['model' => $searchModel]).'
            </div>';

if ($type == "IO" || $type == "RMC" || $type == "OIL") {
    echo '<div class="col-md-3 col-sm-12 ">
            <div class="d-flex flex-column align-items-center justify-content-center gap-2">'
                    . $build->createButton(['import'], 'ti-table', 'Import Records', 'Bulk Import', ['class' => 'btn btn-lg btn-primary w-100']) .
                    $build->createButton(['create-poc'], 'ti-plus', 'Add Person In Charge', 'Create Person in Charge', ['class' => 'btn btn-lg btn-outline-primary w-100']) .
            '</div>
           </div>';
}
if ($type == "OLA") {
    echo '<div class="col-md-3 col-sm-12 ">
            <div class="d-flex flex-column align-items-center justify-content-center gap-2">'
        . $build->createButton(['create'], 'ti-plus', 'Create Special Record', 'Create Special Record', ['class' => 'btn btn-lg btn-primary w-100']) .
        '</div>
           </div>';
}
echo '</div>
    </div>';


if (!Yii::$app->user->isGuest) {
    if ($type == "IO" || $type == "RMC" || $type == "OIL") {
        echo $this->render('_oscIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    } elseif ($type == "OLA") {
        echo $this->render('_olaIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    } elseif ($type == "admin") {
        echo $this->render('_admin', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
}
echo '</div>';
?>
