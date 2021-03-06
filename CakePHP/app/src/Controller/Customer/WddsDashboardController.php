<?php


namespace App\Controller\Customer;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\View\Exception\MissingTemplateException;
use App\Controller\DashboardController as NonCustomerDashboardController;
use Tools\Model\Table\Table;
use Aws\Sdk as AwsSdk;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;


/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class WddsDashboardController extends NonCustomerDashboardController
{

    private function calculate_change(&$output, $new, $old) {
        // Calculate the % change from this last week to the previous
        if ($old > 0)
        {
             $chg = abs((($new - $old)/$old) * 100);
        } else {
            $chg = 0;
        }

        if ($new < $old) {
            $output['dir'] = 'red';
            $output['arr'] = 'desc';
        } else {
            $output['dir'] = 'green';
            $output['arr'] = 'asc';
        }

        $output['chg'] = $chg;
    }


    public function index() {
        $this->loadModel('Dashboards');

        $dashboard = $this->Dashboards->findOrCreate([
            'user_id' => $this->Auth->user('id'),
            'customer_id'=> $this->Auth->user('customer_id')
        ], [$this->Dashboards, 'createDefaultDashboard']);

        if (empty($dashboard->dashboard_widgets)) {
            $dashboard->dashboard_widgets = $this->Dashboards->DashboardWidgets->findByDashboardId($dashboard->id)
                ->order('DashboardWidgets.sort')->toArray();
        }
        $dashboardSettings = Configure::read('Dashboard');

        $this->set('dashboardSettings', $dashboardSettings);
        $this->set('dashboard', $dashboard);
        $this->viewBuilder()->layout('default');

        $days = [
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday',
            5 => 'Saturday',
            6 => 'Sunday'
        ];

        $daysLastWeek = [
            0 => date('l',strtotime('-6 days')),
            1 => date('l',strtotime('-5 days')),
            2 => date('l',strtotime('-4 days')),
            3 => date('l',strtotime('-3 days')),
            4 => date('l',strtotime('-2 days')),
            5 => date('l',strtotime('-1 days')),
            6 => date('l',time()),
        ];

        // Load in AWS Dynamo DB data
        // Pass in the AWS credentials from the .env file
        $sdk = new AwsSdk([
            'region'   => env('AWS_DYNAMODB_REGION', 'us-west-2'),
            'version'  => env('AWS_DYNAMODB_VERSION', 'latest'),
            'credentials' => [
                'key' => env('AWS_DYNAMODB_CREDENTIALS_KEY', null),
                'secret'  => env('AWS_DYNAMODB_CREDENTIALS_SECRET', null),
            ],
        ]);

        // Create a DynamoDb instance
        $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'wdds_testdata';

        /*
         * Daily Scan Results
         */

        $params = [
                'TableName' => $tableName
            ];

        $scanResults = [];
        $lastWeekScanResults = [];
        $lastTwoWeekScanResults = [];
        $week2ScanResults = [];

        $lastWeekTime = strtotime('-6 days');
        $lastTwoWeeks = strtotime('-13 days');

        try {
            $result = $dynamodb->scan($params);
            foreach ($result['Items'] as $mac_addr) {
                $scanResult = $marshaler->unmarshalItem($mac_addr);
                $scanResult['payload']['day'] = date('l',$scanResult['payload']['log_time']);
                $scanResults[] = $scanResult;

                // separate results from last two weeks
                if ($scanResult['payload']['log_time'] > $lastTwoWeeks)
                {
                    $lastTwoWeekScanResults[] = $scanResult;

                    // Separate results from last week
                    if ($scanResult['payload']['log_time'] > $lastWeekTime)
                    {                  
                        $lastWeekScanResults[] = $scanResult;
                    }
                    else {
                        $week2ScanResults[] = $scanResult;
                    }
                }
            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
            die();
        }
        
        $accessPoints = TableRegistry::get('access_points');
        $locations = TableRegistry::get('Locations');

        $accessPointsCount = $accessPoints
            ->find('all', [])
            ->where([])
            ->count();

        $locationsPerf = $locations->find()->distinct('Locations.id')->matching('Apzones')->all();

        // Organize the access point and locations data for chart
        foreach($locationsPerf as $location) {
            
            // Find all the access points associated with a location
            $accessPoints = $accessPoints->find('all',[])
                ->select(['id', 'mac_addr'])
                ->where(['access_points.id = ' => $location->_matchingData['Apzones']->accesspoint_id]);
            

            // Zero out total scan results for each location
            $loc_scanresult_count = 0;

            foreach($accessPoints as $accessPoint) {


                // Need to format the access point mac address with colons as this is
                // how it is stored in dynamo dB
                $apstr = join(':', str_split($accessPoint->mac_addr,2));

                // Set the filter expression for mac address to be the selected access point mac address
                // Creating the JSON string that marshjson would have done
                $eav = array(":mmaacc"=>array("S"=>$apstr));

                // Set the params for the dB query to count all scanresults
                $count_params = [
                    'TableName' => $tableName,
                    'KeyConditionExpression' => 'ap_mac_addr = :mmaacc',
                    'ExpressionAttributeValues' => $eav,
                    'Select' => "COUNT"
                    ];

                // Count the number items matching the filter conditions but not limiting to 15
                // Not sure which is the more efficient method?
                try {
                    $countresult = $dynamodb->query($count_params);
                } catch (DynamoDbException $e) {
                    echo "Unable to query:\n";
                    echo $e->getMessage() . "\n";
                    die();
                }

                // Sum the total scan result count over all the access points for a given location
                $loc_scanresult_count = $loc_scanresult_count + $countresult['Count'];
            }
            $locationData['name'][] = [ 'id' => $location->id, 'name' => $location->location];
            $locationData['access_points'][] = (int)$location->apzones_count;
            $locationData['scan_results'][] = $loc_scanresult_count;
        }




        // Create a base array that has zero counts for each day
        $totalScanCountByDay = [
            'Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0
        ];

        $totalUniqueDevicesCountByDay = [
            'Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday' => 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0
        ];

        // Create a base array that has zero counts for each day
        // This one is for the last 7 days though
        $totalScanCountByDayLastWeek = [
            date('l',strtotime('-6 days')) => 0,
            date('l',strtotime('-5 days')) => 0,
            date('l',strtotime('-4 days')) => 0,
            date('l',strtotime('-3 days')) => 0,
            date('l',strtotime('-2 days')) => 0,
            date('l',strtotime('-1 days')) => 0,
            date('l',time()) => 0,
        ];

        $totalUniqueDevicesCountByDayLastWeek = [
            date('l',strtotime('-6 days')) => 0,
            date('l',strtotime('-5 days')) => 0,
            date('l',strtotime('-4 days')) => 0,
            date('l',strtotime('-3 days')) => 0,
            date('l',strtotime('-2 days')) => 0,
            date('l',strtotime('-1 days')) => 0,
            date('l',time()) => 0,
        ];

        // Count the total amount of scans for each day of the week
        $tmp = array_count_values(array_column(array_column($scanResults,'payload'),'day'));

        // Count the total amount of unique devices for each day of the week
        $uniqueDevices = array_unique(array_column(array_column($scanResults,'payload'),'mac_addr'));
        $tmp_ak = array_keys($uniqueDevices);
        
        $unique_payload = array_intersect_key(array_column($scanResults,'payload'), array_flip($tmp_ak));
        $tmp_ud = array_count_values(array_column($unique_payload,'day'));

        // Populate structured array with scan counts by day
        foreach ($days as $day) {
            if (array_key_exists($day, $tmp)) {
                $totalScanCountByDay[$day] = $tmp[$day];
            }
            if (array_key_exists($day, $tmp_ud)) {
                $totalUniqueDevicesCountByDay[$day] = $tmp_ud[$day];
            }
        }

        // Count the total amount of scans for each day of the week (only last week)
        $tmp_lwsr = array_count_values(array_column(array_column($lastWeekScanResults,'payload'),'day'));
        
        $lastWeekUniqueDevices = array_unique(array_column(array_column($lastWeekScanResults,'payload'),'mac_addr'));
        $tmp_ak = array_keys($lastWeekUniqueDevices);
        
        $unique_payload = array_intersect_key(array_column($lastWeekScanResults,'payload'), array_flip($tmp_ak));
        $tmp_ulwsr = array_count_values(array_column($unique_payload,'day'));

        // Populate structured array with scan counts by day
        foreach ($daysLastWeek as $day) {
            if (array_key_exists($day, $tmp_lwsr)) {
                $totalScanCountByDayLastWeek[$day] = $tmp_lwsr[$day];
            }
            if (array_key_exists($day, $tmp_ulwsr)) {
                $totalUniqueDevicesCountByDayLastWeek[$day] = $tmp_ulwsr[$day];
            }
        }

        $ytsc = $totalScanCountByDayLastWeek[date('l',strtotime('-1 days'))];
        $tsc = $totalScanCountByDayLastWeek[date('l',time())];

        $ytudc = $totalUniqueDevicesCountByDayLastWeek[date('l',strtotime('-1 days'))];
        $tudc = $totalUniqueDevicesCountByDayLastWeek[date('l',time())];

        // Calculate daily change values scan results
        $this->calculate_change($d, $tsc, $ytsc);

        // Calculate daily change values for unique devices
        $this->calculate_change($du, $tudc, $ytudc);

        // Remove the key values from the array to be compatible with HighCharts
        $totalScanCountByDay = array_values($totalScanCountByDay);

        $totalUniqueDevicesCountByDay = array_values($totalUniqueDevicesCountByDay);

        // Remove the key values from the array to be compatible with HighCharts
        $totalScanCountByDayLastWeek = array_values($totalScanCountByDayLastWeek);
        $totalUniqueDevicesCountByDayLastWeek = array_values($totalUniqueDevicesCountByDayLastWeek);
        
        $totalScanCountLastWeek = count($lastWeekScanResults);
        $totalScanCountLastTwoWeeks = count($lastTwoWeekScanResults);
        
        // The total count for the week after last is equal to the total
        // for two weeks minus the last week
        $totalScanCount2ndWeek = $totalScanCountLastTwoWeeks - $totalScanCountLastWeek;

        // Calculate weekly change values for scan results
        $this->calculate_change($dw, $totalScanCountLastWeek, $totalScanCount2ndWeek);

        $totalUniqueDevicesCountLastWeek = count($lastWeekUniqueDevices);
        
        $totalUniqueDevicesCount2ndWeek = count(array_unique(array_column(array_column($week2ScanResults,'payload'),'mac_addr')));;

        // Calculate weekly change values for unique devices
        $this->calculate_change($dwu, $totalUniqueDevicesCountLastWeek, $totalUniqueDevicesCount2ndWeek);

        // Count the total amount of scans for each day of the week
        $totalScanCountByVendor = array_count_values(array_column(array_column($scanResults,'payload'),'vendor'));

        // Count the total unique device mac addresses
        // This assumes that mac addr is a column of the payload array and payload is a column of the scanResults array
        $totalUniqueDevicesCount = count(array_unique(array_column(array_column($scanResults,'payload'),'mac_addr')));

        // Get the Unique Vendors array --> Needed for plotting scans by Vendor
        $uniqueVendors = array_unique(array_column(array_column($scanResults,'payload'),'vendor'));
        
        // Count the total unique device vendors
        // This assumes that vendor is a column of the payload array and payload is a column of the scanResults array
        $totalUniqueVendors = count($uniqueVendors);
        $totalScanCount = count($scanResults);

        $this->set(compact('totalUniqueVendors', 'totalUniqueDevicesCount', 'totalScanCount', 'totalScanCountLastWeek', 'accessPointsCount', 'totalScanCountByDay', 'totalUniqueDevicesCountByDay', 'totalScanCountByDayLastWeek', 'totalUniqueDevicesCountByDayLastWeek', 'days', 'daysLastWeek', 'totalScanCountByVendor', 'totalUniqueDevicesCountLastWeek', 'tsc', 'tudc', 'd', 'dw','du','dwu', 'locationData'));

    }

    public function deleteDashboard($user_id,$customer_id) {
        $this->loadModel('Dashboards');
        $this->loadModel('DashboardWidgets');
        $conn = ConnectionManager::get('default');
        $conn->begin();
        if(!$this->DashboardWidgets->deleteAll(['user_id'=> $user_id, 'customer_id'=>$customer_id])) {
            $conn->rollback();
            return false;
        }
        if(!$this->Dashboards->deleteAll(['user_id'=> $user_id, 'customer_id'=>$customer_id])) {
            $conn->rollback();
            return false;
        }
        $conn->commit();
        return true;
    }

    public function resetDashboard() {
        $user_id = $this->Auth->user('id');
        $customer_id = $this->Auth->user('customer_id');
        if($this->deleteDashboard($user_id,$customer_id)) {
            $this->Flash->calloutFlash(
                'The dashboard has been reseted.', [
                'key' => 'authError',
                'clear' => true,
                'params' => [
                    'heading' => 'Success',
                    'class' => 'callout-success',
                    'fa' => 'check'
                ]
            ]);
        } else {
            $this->Flash->calloutFlash(
                'The dashboard could not be reseted. Please, try again.', [
                'key' => 'authError',
                'clear' => true,
                'params' => [
                    'heading' => 'Error',
                    'class' => 'callout-danger',
                    'fa' => 'excl'
                ]
            ]);
        }
        return $this->redirect(['action' => 'index']);
    }
}