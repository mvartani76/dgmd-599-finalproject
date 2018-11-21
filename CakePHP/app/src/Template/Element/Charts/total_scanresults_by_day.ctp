<?php

$chart = new \Ghunti\HighchartsPHP\Highchart();
$chart->includeExtraScripts(array('highcharts-more'));
$chart->chart->renderTo = 'highcharts-container-2';

$chart->chart->type = 'column';

$chart->title->text = 'Total Scan Results by Day';

$chart->yAxis->min = 0;
$chart->yAxis->title->text = 'Total Scan Results';
$chart->xAxis->categories = $days;
$chart->xAxis->labels     = [
    'rotation' => -45,
    'align' => 'right',
    'style' => [
        'font' => 'normal 11px Arial, sans-serif'
    ]
];

$chart->series[] = array('shadow' => 1, 'name' => 'Total Scan Results', 'data' => $totalScanCountByDay);

?>

<?php echo $chart->render('chart', null, true); ?>