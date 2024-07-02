<?php

use common\helpers\builders;
use common\helpers\viewRenderer;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/** @var common\models\AgreementPoc $modelsPoc */
/** @var common\models\Activities $haveActivity */


$build = new builders();
$view = new viewRenderer();
YiiAsset::register($this);
?>

<?php modal::begin(['title' => '', 'id' => 'modal-activity', 'size' => 'modal-lg', 'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'], 'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'], 'centerVertical' => true, 'scrollable' => true,]);

echo "<div id='modalContent'></div>";

modal::end();
?>

    <div class="d-flex justify-content-between">
        <div class="d-flex gap-3">
            <?= $build->pillBuilder($model->status, 'mb-3') ?>

            <?php if ($model->primaryAgreementPoc): ?>
                <p class="badge  fw-bolder mw-pill rounded-2 bg-indigo-subtle text-indigo fs-5">
                    <span class='text-gray-dark fw-bolder fs-5'><?=  $model->primaryAgreementPoc->pi_kcdio  ?></span>
                </p>
            <?php endif; ?>

            <p class="badge  fw-bolder mw-pill rounded-2 bg-danger-subtle text-danger fs-5">
                <span class='text-danger fw-bolder fs-5'><?= $model->transfer_to ?></span>
            </p>

            <?php if (!Yii::$app->user->isGuest && $model->status == 21 || $model->status == 121): ?>
                <p class="badge  fw-bolder mw-pill rounded-2 bg-dark-subtle text-light fs-5">MCOM:
                    <span><?= Yii::$app->formatter->asDate($model->mcom_date, 'dd-MM-yyyy') ?></span></p>
            <?php endif; ?>

            <span class='text-gray-dark fw-bolder fs-5'><?= $model->agreement_type ?></span>

        </div>
        <?php $view->renderActionButton("Activities: $model->id", 'View Activities', Url::to(['view-activities', 'id' => $model->id]), $haveActivity); ?>
    </div>

    <!--section collaborator details-->
    <h4>Collaborator Details</h4>

    <div class="row">
        <div class="col-12">
            <?= $view->renderer($model->col_organization, 'Organization') ?>
        </div>
        <div class="col-6">
            <?= $view->renderer($model->col_name, 'Name') ?>
            <?= $view->renderer($model->col_phone_number, 'Phone Number') ?>
            <?= $view->renderer($model->col_email, 'Email Address', true) ?>
            <?= $view->renderer($model->col_address, 'Address') ?>
        </div>
        <div class="col-6">
            <?= $view->renderer($model->col_collaborators_name, 'Collaborators Name') ?>
            <?= $view->renderer($model->col_wire_up, 'Wire Up') ?>
        </div>
    </div>


    <!--section person in charge details-->
<?php foreach ($modelsPoc as $index => $modelPoc): ?>
    <h4>Person In Charge Details <?= $modelPoc->pi_is_primary ? '(primary)':'' ?></h4>
    <div class="row">
        <div class="col-md-6">
            <?= $view->renderer($modelPoc->pi_name, 'Name') ?>
            <?= $view->renderer($modelPoc->pi_kcdio, 'Kulliyyah') ?>
            <?= $view->renderer($modelPoc->pi_address, 'Address') ?>
        </div>
        <div class="col-md-6">
            <?= $view->renderer($modelPoc->pi_phone, 'Phone Number') ?>
            <?= $view->renderer($modelPoc->pi_email, 'Email Address', true) ?>
            <?= $view->renderer($modelPoc->pi_role, 'Role') ?>
        </div>
    </div>
<?php endforeach; ?>

    <!--section additional details-->
    <h4> Details</h4>
<?= $view->renderer($model->project_title, $model->transfer_to == "OIL" ? 'Research Title' : 'Project Title') ?>
<?= $view->renderer($model->grant_fund, 'Fund') ?>
<?= $view->renderer($model->member, 'No. of Project Members') ?>
<?= $view->renderer($model->proposal, 'Proposal') ?>

<?= $view->renderer($model->ssm, 'SSM') ?>
<?= $view->renderer($model->company_profile, 'Company Profile') ?>
    <h4>Dates</h4>
    <div class = "row">
        <div class = "col-6">
            <?= $view->renderer($model->execution_date, 'Execution Date') ?>
            <?= $view->renderer($model->sign_date, 'Sign Date') ?>
            <?= $view->renderer($model->end_date, 'End Date') ?>
        </div>
        <div class = "col-6">
            <?php if ($model->transfer_to == 'RMC'):?>
                <?= $view->renderer($model->project_start_date, 'Start Date') ?>
                <?= $view->renderer($model->project_end_date, 'End Date') ?>
            <?php endif;?>
            <?= $view->renderer($model->mcom_date, 'MCOM Date') ?>
        </div>
    </div>



    <!--section files-->
   <?php if(!Yii::$app->user->isGuest):?>
    <h4>Files</h4>
    <div class="row">
    <div class="col-md-6">
    <?php
$folder = $model->applicant_doc;
$files = [];
$totalSize = 0;

if (is_dir($folder)) {
    $files = array_diff(scandir($folder), ['.', '..']);
    foreach ($files as $file) {
        $filePath = $folder . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath)) {
                $totalSize += filesize($filePath);
            }
    }
}

if (!empty($files)) {
    echo GridView::widget(
            ['dataProvider' => new ArrayDataProvider(['allModels' => array_values($files), 'pagination' => false,]), 'summary' => 'Total folder size: ' . Yii::$app->formatter->asShortSize($totalSize), 'columns' => [['attribute' => 'file', 'label' => 'File Name', 'value' => function ($file) use ($model) {
        $build = new builders();
        return $build->downloadLinkBuilder($model->applicant_doc . $file, $file);
    }, 'format' => 'raw',],
                [
                        'label' => 'File Size', 'value' => function ($file) use ($model) {
        $filePath = $model->applicant_doc . DIRECTORY_SEPARATOR . $file;
        if (is_file($filePath)) {
            return Yii::$app->formatter->asShortSize(filesize($filePath));
        }
        return 'N/A';
    },], ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}', 'buttons' => ['delete' => function ($url, $fileModel, $key) use ($model) {
        return Html::a('<span class="ti ti-trash fs-7 text-danger"></span>', ['delete-file', 'id' => $model->id, 'filename' => $fileModel], ['class' => 'btn-action', 'id' => 'modelButton', 'data-confirm' => 'Are you sure you want to delete this file?', 'data-method' => 'post',]);
    },],],],]);
} else {
    echo 'No files found.';
}
?>
    </div>
    <div class="col-md-6">
        <?php
        $folder = $model->dp_doc;
        $files = [];
        $totalSize = 0;
        if (is_dir($folder)) {
            $files = array_diff(scandir($folder), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $folder . DIRECTORY_SEPARATOR . $file;
                if (is_file($filePath)) {
                    $totalSize += filesize($filePath);
                }
            }
        }

        if (!empty($files)) {
            echo GridView::widget(['dataProvider' => new ArrayDataProvider(['allModels' => array_values($files), 'pagination' => false, // Disable pagination
            ]), 'summary' => 'Total folder size: ' . Yii::$app->formatter->asShortSize($totalSize), 'columns' => [['attribute' => 'file', 'label' => 'File Name', 'value' => function ($file) use ($model) {
                $build = new builders();
                return $build->downloadLinkBuilder($model->applicant_doc . $file, $file);
            }, 'format' => 'raw',],],]);
        }
        ?>
    </div>
    </div>
    <?php endif;?>


<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', ['depends' => [JqueryAsset::class]]);
$this->registerJs(<<<JS
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
JS
);
?>