<?php

use common\models\LookupCdKcdiom;
use onmotion\apexcharts\ApexchartsWidget;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
$this->title = 'Dashboard';
?>


<?php Pjax::begin(['id' => 'chart-pjax-container']); ?>
<div class="table-responsive">
    <div class="container-md my-3 p-3 rounded-3 bg-white shadow">
        <div class="row ">


            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'options' => ['data-pjax' => true], // Enable PJAX for the form
            ]); ?>

            <div class="row align-items-end">
                <div class="col-auto">
                    <?= $form->field($searchModel, 'full_info', ['options' => ['class' => '']])->textInput(['placeholder' => 'Search', 'onchange' => '$(this).closest("form").submit();']) ?>

                </div>
                <div class="col-auto">
                    <?= $form->field($searchModel, 'status', ['options' => ['class' => '']])->dropDownList(['' => 'All', 'Active' => 'Active', 'Expired' => 'Expired'], ['onchange' => '$(this).closest("form").submit();',]) ?>
                </div>
                <div class="col-auto">
                    <?= $form->field($searchModel, 'transfer_to', ['options' => ['class' => '']])->dropDownList(['' => 'All', 'IO' => 'IO', 'OIL' => 'OIL', 'RMC' => 'RMC'], ['onchange' => '$(this).closest("form").submit();',])->label('OSC') ?>
                </div>
                <div class="col-auto">
                    <?= $form->field($searchModel, "kcdio", ['options' => ['class' => '']])
                        ->dropDownList(
                            ArrayHelper::map(
                                LookupCdKcdiom::find()->where(['not', ['abb_code' => null]])->all(),
                                'abb_code',
                                'kcdiom_desc'
                            ),
                            ['prompt' => 'Select KCDIO', 'onchange' => '$(this).closest("form").submit();']
                        )->label('KCDIOM')
                    ?>
                </div>
                <div class="col-auto">
                    <?= $form->field($searchModel, 'chartType', ['options' => ['class' => '']])->dropDownList([
                        'KCDIO' => 'KCDIO',
                        'agreement_type' => 'Agreement Type',
                        'country' => 'Country',
                        'transfer_to' => 'Transfer To',
                    ], ['prompt' => 'Select Chart Type', 'onchange' => '$(this).closest("form").submit();',]) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="table-responsive">
    <div class="container-md my-3 p-3 rounded-3 bg-white shadow">
        <?= ApexchartsWidget::widget([
            'type' => 'bar',
            'series' => [
                [
                    'name' => 'Count',
                    'data' => $chartData['series'],
                ],
            ],
            'chartOptions' => [
                'chart' => [
                    'type' => 'bar',
                    'height' => 350,
                ],
                'plotOptions' => [
                    'bar' => [
                        'horizontal' => false,
                        'columnWidth' => '55%',
                        'endingShape' => 'rounded',
                    ],
                ],
                'dataLabels' => [
                    'enabled' => false,
                ],
                'xaxis' => [
                    'categories' => $chartData['categories'],
                ],
                'yaxis' => [
                    'title' => [
                        'text' => 'Count',
                    ],
                ],
                'title' => [
                    'text' => 'Agreements by ' . ucfirst($searchModel->chartType),
                    'align' => 'left',
                ],
            ],
        ]); ?>
    </div>
</div>
    <?php Pjax::end(); ?>

