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
/** @var common\models\Activities $haveActivity */

$this->title = $model->id;

$build = new builders();
$view = new viewRenderer();
YiiAsset::register($this);
?>

<?php modal::begin([
    'title' => '', 'id' => 'modal-activity', 'size' => 'modal-lg',
    'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'], 'centerVertical' => true,
    'scrollable' => true,
]);

echo "<div id='modalContent'></div>";

modal::end();
?>

    <div class="d-flex justify-content-between">
        <div class="d-flex gap-3">
            <?= $build->pillBuilder($model->status, 'mb-3') ?>
            <?php if (!Yii::$app->user->isGuest && $model->status == 21): ?>
                <p class="badge  fw-bolder mw-pill rounded-2 bg-dark-subtle text-light fs-5">MCOM:
                    <span><?= Yii::$app->formatter->asDate($model->mcom_date, 'dd-MM-yyyy') ?>
</span></p>
            <?php endif; ?>
            <span class='text-gray-dark fw-bolder fs-5'><?= $model->agreement_type ?></span>

        </div>
        <?php $view->renderActionButton("Activities: $model->id", 'View Activities',
            Url::to(['view-activities', 'id' => $model->id]), $haveActivity); ?>
    </div>

    <!--section collaborator details-->
    <h4>Collaborator Details</h4>
<?php if ($model->col_name == "" || $model->col_name == null): ?>
    <?= $view->renderer($model->col_details, 'Details') ?>
<?php else : ?>
    <div class="row">
        <div class="col-6">
            <?= $view->renderer($model->col_name, 'Name') ?>
            <?= $view->renderer($model->col_phone_number, 'Phone Number') ?>
            <?= $view->renderer($model->col_collaborators_name, 'Collaborators Name') ?>
            <?= $view->renderer($model->col_address, 'Address') ?>

        </div>
        <div class="col-6">
            <?= $view->renderer($model->col_organization, 'Organization') ?>
            <?= $view->renderer($model->col_email, 'Email Address', true) ?>
            <?= $view->renderer($model->col_wire_up, 'Wire Up') ?>
        </div>
    </div>
<?php endif; ?>

    <!--section person in charge details-->
    <h4>Person In Charge Details</h4>
<?php if ($model->pi_name == "" || $model->pi_name == null): ?>
    <?= $view->renderer($model->pi_details, 'Details') ?>
<?php else : ?>
    <div class="row">
        <div class="col-md-6">
            <?= $view->renderer($model->pi_name, 'Name') ?>
            <?= $view->renderer($model->pi_kulliyyah, 'Kulliyyah') ?>
        </div>
        <div class="col-md-6">
            <?= $view->renderer($model->pi_phone_number, 'Phone Number') ?>
            <?= $view->renderer($model->pi_email, 'Email Address', true) ?>
        </div>
    </div>
    <?php if ($model->pi_name_x != null): ?>
        <h4>1. Person In Charge Details</h4>
        <div class="row">
            <div class="col-md-6">
                <?= $view->renderer($model->pi_name_x, 'Name') ?>
                <?= $view->renderer($model->pi_kulliyyah_x, 'Kulliyyah') ?>
            </div>
            <div class="col-md-6">
                <?= $view->renderer($model->pi_phone_number_x, 'Phone Number') ?>
                <?= $view->renderer($model->pi_email_x, 'Email Address', true) ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($model->pi_name_xx != null): ?>
        <h4>2. Person In Charge Details</h4>
        <div class="row">
            <div class="col-md-6">
                <?= $view->renderer($model->pi_name_xx, 'Name') ?>
                <?= $view->renderer($model->pi_kulliyyah_xx, 'Kulliyyah') ?>
            </div>
            <div class="col-md-6">
                <?= $view->renderer($model->pi_phone_number_xx, 'Phone Number') ?>
                <?= $view->renderer($model->pi_email_xx, 'Email Address', true) ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

    <!--section additional details-->
    <h4> Details</h4>
<?= $view->renderer($model->grant_fund, 'Fund') ?>
<?= $view->renderer($model->member, 'Number of Members') ?>
<?= $view->renderer($model->proposal, 'Proposal') ?>
<?= $view->renderer($model->project_title, 'Project Title') ?>
<?= $view->renderer($model->ssm, 'SSM') ?>
<?= $view->renderer($model->company_profile, 'Company Profile') ?>
<?= $view->renderer($model->mcom_date, 'MCOM Date') ?>
<?= $view->renderer($model->sign_date, 'Sign Date') ?>
<?= $view->renderer($model->end_date, 'Expiry Date') ?>


    <!--section files-->
    <h4>Files</h4>
    <div class="row">
        <div class="col-md-6">
            <?php
            $folder = $model->applicant_doc;
            $files = [];
            if (is_dir($folder)) {
                $files = array_diff(scandir($folder), ['.', '..']);
            }

            if (!empty($files)) {
                echo GridView::widget([
                    'dataProvider' => new ArrayDataProvider([
                        'allModels' => array_values($files),
                        'pagination' => false, // Disable pagination
                    ]),
                    'summary' => false,
                    'columns' => [
                        [
                            'attribute' => 'file',
                            'label' => 'File Name',
                            'value' => function ($file) use ($model) {
                                $build = new builders();
                                return $build->downloadLinkBuilder($model->applicant_doc . $file, $file);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{delete}',
                            'buttons' => [
                                'delete' => function ($url, $fileModel, $key) use ($model) {
                                    return Html::a(
                                        '<span class="ti ti-trash fs-7 text-danger"></span>',
                                        ['delete-file', 'id' => $model->id, 'filename' => $fileModel],
                                        [
                                            'class' => 'btn-action',
                                            'id' => 'modelButton',
                                            'data-confirm' => 'Are you sure you want to delete this file?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                },
                            ],
                        ],
                    ],
                ]);
            } else {
                echo 'No files found.';
            }
            ?>
        </div>
        <div class="col-md-6">
            <?php
            $folder = $model->dp_doc;
            $files = [];
            if (is_dir($folder)) {
                $files = array_diff(scandir($folder), ['.', '..']);
            }

            if (!empty($files)) {
                echo GridView::widget([
                    'dataProvider' => new ArrayDataProvider([
                        'allModels' => array_values($files),
                        'pagination' => false, // Disable pagination
                    ]),
                    'summary' => false,
                    'columns' => [
                        [
                            'attribute' => 'file',
                            'label' => 'File Name',
                            'value' => function ($file) use ($model) {
                                $build = new builders();
                                return $build->downloadLinkBuilder($model->applicant_doc . $file, $file);
                            },
                            'format' => 'raw',
                        ],
                    ],
                ]);
            }
            ?>
        </div>
    </div>



<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
    ['depends' => [JqueryAsset::class]]);
$this->registerJs(<<<JS
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
JS
);
?>