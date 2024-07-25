<?php

use onmotion\apexcharts\ApexchartsWidget;

echo ApexchartsWidget::widget([
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
            'text' => 'Agreement Type Counts',
            'align' => 'left',
        ],
    ],
]);
