<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Agreements';

$type = Yii::$app->user->identity->type;


echo '<div class="table-responsive">';
Pjax::begin();
echo '<div class="container-md my-3 p-3 rounded-3 bg-light shadow">
        <div class="row align-items-end">
            <div class="col-md-9 col-sm-12">
                '.$this->render('_search', ['model' => $searchModel]).'
            </div>';

if ($type == 'IO') {
    echo '<div class="col-md-3 col-sm-12 mt-3 mt-md-0">
                        '.Html::button(
            '<i class="ti fs-5 ti-table" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="Import Records"></i> Import Records',
            [
                'value' => Url::to(['import']),
                'class' => 'btn btn-lg btn-success w-100',
                'id' => 'modelButton',
                'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value'), function() {
                                                // Set the modal title
                                                $('#modal').find('.modal-title').html('<h1 class=\"mb-0\">Bulk Import</h1>'); 
                                            });"
            ]
        ).'
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
Pjax::end();
echo '</div>';
?>
