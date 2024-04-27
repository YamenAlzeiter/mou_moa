<?php

use yii\bootstrap5\Modal;


/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow border rounded-5">
            <div class="card-body">
                <h1><i class="ti ti-status-change"></i> Status</h1>
                <hr class="border  border-black">
                <div class="overflow-auto">
                    <?= $this->render('status', ['statusDataProvider' => $statusDataProvider]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card bg-light-gray shadow border rounded-5">
            <div class="card-body">
                <h1><i class="ti ti-mailbox"></i> Email Template</h1>
                <hr class="border  border-black">
                <div class="overflow-auto">
                    <?= $this->render('emailTemplate', ['emailDataProvider' => $emailDataProvider]) ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php modal::begin(['title' => '', 'id' => 'modal', 'size' => 'modal-xl', 'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'], 'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'], 'centerVertical' => true, 'scrollable' => true, 'footer' => '&nbsp;',]);

echo "<div id='modalContent'></div>";

modal::end();
?>
