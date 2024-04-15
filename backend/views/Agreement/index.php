<?php

use common\models\Agreement;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Agreements';

$type = Yii::$app->user->identity->type;


if(!Yii::$app->user->isGuest){
    if($type == "IO" || $type == "RMC" || $type == "OIL"){
        echo $this->render('_oscIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
    elseif ($type == "OLA"){
        echo $this->render('_olaIndex', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
}
?>


    <!--modal view IO/RMC/OIL-->
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