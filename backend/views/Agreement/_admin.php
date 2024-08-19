<?php

use common\helpers\builders;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$url = Url::to(['bulk-delete']);

$js = <<<JS
$(document).ready(function() {
    function toggleBulkDeleteButton() {
        var keys = $('#agreement-grid').yiiGridView('getSelectedRows');
        if (keys.length > 0) {
            $('.bulk-delete-container').removeClass('d-none');
        } else {
            $('.bulk-delete-container').addClass('d-none');
        }
    }

    // Initial check to hide/show button on page load
    toggleBulkDeleteButton();

    // Trigger the toggle function on selection change
    $('#agreement-grid').on('change', 'input[name="selection[]"]', function() {
        toggleBulkDeleteButton();
    });

    //sweet alert ...
    $('#bulk-delete').on('click', function(e) {
        e.preventDefault();
        var keys = $('#agreement-grid').yiiGridView('getSelectedRows');
        if (keys.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Agreemtns selected',
                text: 'Please select at least one Agreemtn to delete.'
            });
            return;
        }
        Swal.fire({
            title: 'Are you sure?',
              text: "All data and associated files will be permanently deleted from the server. This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '{$url}',
                    data: {ids: keys},
                    success: function(data) {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                'Your selected Agreements have been deleted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            );
                        }
                    }
                });
            }
        });
    });
});
JS;

$this->registerJs(new JsExpression($js));

echo '<div class="container-md my-3 p-4 rounded-3 bg-white shadow"> <div class="table-responsive">';
echo GridView::widget([
    'id' => 'agreement-grid',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap rounded-3 overflow-hidden'],
    'columns' => [
        ['class' => CheckboxColumn::className()],
        'id',
        [
            'attribute' => 'collaboration.col_organization',
            'contentOptions' => ['class' => 'truncate'],
        ],
        [
            'attribute' => 'created_at', 'label' => 'Date', 'format' => ['date', 'php:d/m/y'],
            'enableSorting' => false,
        ],
        'collaboration.country',
        [
            'label' => 'Champion',
            'value' => function ($model) {
                return $model->primaryAgreementPoc ? $model->primaryAgreementPoc->pi_kcdio : null;
            },
        ],
        'agreement_type',
        [
            'label' => 'Status',
            'attribute' => 'Status',
            'format' => 'raw',
            'value' => function ($model) {
                $statusHelper = new builders();
                return $statusHelper->pillBuilder($model->status);
            },
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{view}{log}{updatePoc}',
            'contentOptions' => ['class' => 'text-end'],
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->actionBuilder($model, 'view');
                },
                'log' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->actionBuilder($model, 'log');
                },
                'updatePoc' => function ($url, $model, $key) {
                    $build = new builders();
                    return $build->tableProbChanger($model->status, 'ApplicantActivity') ? $build->actionBuilder($model, 'update-poc') : null;
                }
            ],
        ],
    ],
    'pager' => [
        'class' => yii\bootstrap5\LinkPager::className(),
        'listOptions' => ['class' => 'pagination justify-content-center gap-2 borderless'],
        'activePageCssClass' => ['class' => 'link-white active'],
    ],
    'layout' => "{items}\n{summary}\n{pager}",
]);
echo '</div></div>';
?>
