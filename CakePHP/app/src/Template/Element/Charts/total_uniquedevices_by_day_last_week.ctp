<?php

$chart = new \Ghunti\HighchartsPHP\Highchart();
$chart->includeExtraScripts(array('highcharts-more'));
$chart->chart->renderTo = 'uniquedevicesbydaylastweek';

$chart->chart->type = 'column';

$chart->title->text = 'Unique Devices by Day (Last 7 Days)';

$chart->yAxis->min = 0;
$chart->yAxis->title->text = 'Unique Devices';
$chart->xAxis->categories = $daysLastWeek;
$chart->xAxis->labels     = [
    'rotation' => -45,
    'align' => 'right',
    'style' => [
        'font' => 'normal 11px Arial, sans-serif'
    ]
];

$chart->series[] = array('shadow' => 1, 'name' => 'Unique Devices', 'data' => $totalUniqueDevicesCountByDayLastWeek);

?>

<?php echo $chart->render('chart', null, true); ?>