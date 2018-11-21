<script type="text/javascript" src="//code.highcharts.com/highcharts.js"></script>
<?php

$chart = new \Ghunti\HighchartsPHP\Highchart();

$chart->chart->renderTo = "totalscanresultsbyvendor";
$chart->chart->plotBackgroundColor = null;
$chart->chart->plotBorderWidth = null;
$chart->chart->plotShadow = false;
$chart->title->text = "Total Scans by Vendor";

echo $this->element('Charts/config');

$chart->tooltip->formatter = new \Ghunti\HighchartsPHP\HighchartJsExpr(
    "function() {
                                return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
                            }"
);

$chart->plotOptions->pie->allowPointSelect = 1;
$chart->plotOptions->pie->cursor = "pointer";
$chart->plotOptions->pie->dataLabels->enabled = false;
$chart->plotOptions->pie->showInLegend = 1;

$n = 0;
foreach ($totalScanCountByVendor as $key => $val) {

    $tscvfinal[$n][0] = $key;
    $tscvfinal[$n][1] = $val;
    $n++;
}

$chart->series[] = array(
    'type' => "pie",
    'shadow' => 1,
    'name' => "Vendors",
    'data' => $tscvfinal
);

?>

<?php echo $chart->render('chart', null, true); ?>
