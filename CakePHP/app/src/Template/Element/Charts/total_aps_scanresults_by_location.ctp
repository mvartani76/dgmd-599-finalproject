<?php
use Cake\Utility\Hash;

$chart = new \Ghunti\HighchartsPHP\Highchart();
$chart->includeExtraScripts(array('highcharts-more'));
$chart->chart->renderTo = 'aplocations';

$chart->chart->type = 'column';
$chart->chart->zoomType = "xy";
$chart->title->text = 'Access Points and Scan Results by Location';

$data = array();
$cats = array();

$chart->tooltip->formatter = new \Ghunti\HighchartsPHP\HighchartJsExpr("
    function() {
        var mv = this.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, \",\");
        return this.key.name + '\'s ' + this.series.name + ' (' + mv + ')';
    }
");

$role = 'customer';


$chart->yAxis->min = 0;
$chart->yAxis->title->text = 'Total Scan Results';
$chart->xAxis = [
    [
        'categories' => $locationData['name'] ?? 'N/A',
        'labels' => [
            'rotation' => -45,
            'formatter' => new \Ghunti\HighchartsPHP\HighchartJsExpr("function() {
                return '<a href=\"/" . $role . "/locations/view/' + this.value.id + '\">' + this.value.name + '</a>';            
            }"),
            'useHTML' => true,
            'align' => 'right',
            'style' => [
                'font' => 'normal 11px Arial, sans-serif'
                ]
            ]
        ]
    ];



$leftYaxis = new \Ghunti\HighchartsPHP\HighchartOption();
$leftYaxis->labels->style->color = "#89A54E";
$leftYaxis->title->useHTML = true;
$leftYaxis->title->text = "<a href=\"/customer/access_points\">Access Points</a>";
$leftYaxis->title->style->color = "#89A54E";


$rightYaxis = new \Ghunti\HighchartsPHP\HighchartOption();
$rightYaxis->title->useHTML = true;
$rightYaxis->title->text = "<a href=\"/customer/ScanResults\">Scan Results</a>";
$rightYaxis->title->style->color = "#4572A7";
$rightYaxis->labels->style->color = "#4572A7";
$rightYaxis->opposite = 1;


$chart->yAxis = array(
    $leftYaxis,
    $rightYaxis
);


$chart->series[] = array('shadow' => 1, 'name' => 'Access Points', 'type' => 'column', 'data' => $locationData['access_points'] ?? 'N/A');
$chart->series[] = array('shadow' => 1, 'name' => 'Scan Results', 'yAxis' => 1, 'type' => 'column', 'data' => $locationData['scan_results'] ?? 'N/A');

$chart->legend->layout = "vertical";
$chart->legend->align = "left";
$chart->legend->x = 50;
$chart->legend->verticalAlign = "top";
$chart->legend->y = 25;
$chart->legend->floating = 1;
$chart->legend->backgroundColor = "#FFFFFF";

?>

<?php echo $chart->render('chart', null, true); ?>