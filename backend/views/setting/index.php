<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Offcanvas;
use yii\helpers\Url;

    $this->title = 'Dashboard';

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $statusDataProvider */
/** @var yii\data\ActiveDataProvider $emailDataProvider */
/** @var yii\data\ActiveDataProvider $reminderDataProvider */
/** @var yii\data\ActiveDataProvider $kcdioDataProvider */

/** @var yii\data\ActiveDataProvider $agreTypeDataProvider */

?>



<div class="container">
    <div class="row d-flex flex-wrap">
        <!-- First row: Left column and stacked right column -->
        <div class="col-12 col-lg-7 col-md-12 my-2 d-flex">
            <div class="card  border rounded-5 flex-grow-1 ">
                <div class="card-body">
                    <h1 class="fs-7"><i class="ti ti-status-change"></i> Status</h1>
                    <hr class="border border-black">
                    <div class="overflow-auto">
                        <?= $this->render('status', ['statusDataProvider' => $statusDataProvider]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5 col-md-12">
            <div class="row mb-2">
                <div class="col-12 my-2 d-flex">
                    <div class="card bg-light-gray  border rounded-5 flex-grow-1 ">
                        <div class="card-body">
                            <h1 class="fs-7"><i class="ti ti-mailbox"></i> Email Template</h1>
                            <hr class="border border-black">
                            <div class="overflow-auto">
                                <?= $this->render('emailTemplate', ['emailDataProvider' => $emailDataProvider]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 my-2 d-flex">
                    <div class="card bg-light-gray  rounded-5 flex-grow-1 ">
                        <div class="card-body">
                            <?= $this->render('reminder', ['reminderDataProvider' => $reminderDataProvider]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7 col-md-12 my-2 d-flex">
            <div class="card  border rounded-5 flex-grow-1 ">
                <div class="card-body">
                    <div class="overflow-auto">
                        <?= $this->render('kcdio', ['kcdioDataProvider' => $kcdioDataProvider]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 col-md-12 my-2 d-flex">
        <div class="card  border rounded-5 flex-grow-1 ">
            <div class="card-body">
                <div class="overflow-auto">
                    <?= $this->render('type', ['agreTypeDataProvider' => $agreTypeDataProvider]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<?php
Offcanvas::begin([
    'title' => '', 'placement' => 'end', 'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between flex-row-reverse'], 'options' => [
        'id' => 'myOffcanvas',
    ], 'backdrop' => true
]);

echo "<div id='offcanvas-body'></div>";

Offcanvas::end(); ?>


<?php modal::begin([
    'title' => '', 'id' => 'modal', 'size' => 'modal-xl', 'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'], 'centerVertical' => true,
    'scrollable' => true, 'footer' => '&nbsp;',
]);

echo "<div id='modalContent'></div>";

modal::end();
?>
<script>
    function loadOffcanvasContent(buttonElement) {
        const url = $(buttonElement).attr('value');
        $('#myOffcanvas').find('#offcanvas-body').load(url, function() {
            $('#offcanvas-body').append(''); // Add your loading indicator logic if needed
        });
    }
</script>