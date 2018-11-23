<?php

$chart = new \Ghunti\HighchartsPHP\Highchart();
$chart->includeExtraScripts(array('highcharts-more'));
$chart->chart->renderTo = 'uniquedevicesbyday';

$chart->chart->type = 'column';

$chart->title->text = 'Unique Devices by Day';

$chart->yAxis->min = 0;
$chart->yAxis->title->text = 'Unique Devices';
$chart->xAxis->categories = $days;
$chart->xAxis->labels     = [
    'rotation' => -45,
    'align' => 'right',
    'style' => [
        'font' => 'normal 11px Arial, sans-serif'
    ]
];

$chart->series[] = array('shadow' => 1, 'name' => 'Unique Devices', 'data' => $totalUniqueDevicesCountByDay);

?>

<?php echo $chart->render('chart', null, true); ?>