<?php



use onmotion\apexcharts\ApexchartsWidget;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AgreementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $countryChartData array */
/* @var $pieChartData array */

?>

<div class="agreement-index">
    <!-- Existing grid and other content -->

    <h2>Agreements by Country</h2>
    <?= ApexchartsWidget::widget([
        'type' => 'bar',
        'series' => [
            [
                'name' => 'Count',
                'data' => $countryChartData['series'],
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
                'categories' => $countryChartData['categories'],
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Count',
                ],
            ],
            'title' => [
                'text' => 'Agreements by Country',
                'align' => 'left',
            ],
        ],
    ]); ?>

    <h2>Executed vs Expired Agreements</h2>
    <?= ApexchartsWidget::widget([
        'type' => 'pie',
        'series' => $pieChartData['series'],
        'chartOptions' => [
            'labels' => $pieChartData['categories'],
            'title' => [
                'text' => 'Executed vs Expired Agreements',
                'align' => 'left',
            ],
        ],
    ]); ?>
</div>
