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

        $accessPoints = TableRegistry::get('AccessPoints');
        
        $accessPointsCount = $accessPoints
            ->find('all', [

            ])
            ->where([         ])
            ->count();

        $timeNow     = date('H:i:s', strtotime('now'));

        $todayStart  = date('Y-m-d 00:00:00', strtotime('today'));
        $todayEnd    = date('Y-m-d 23:59:59', strtotime('today'));
        $yesterdayStart  = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $yesterdayEnd    = date('Y-m-d ' . $timeNow, strtotime('-1 day'));


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
                }

            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
            die();
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

        // Count the total amount of scans for each day of the week
        $tmp = array_count_values(array_column(array_column($scanResults,'payload'),'day'));

        // Populate structured array with scan counts by day
        foreach ($days as $day) {
            if (array_key_exists($day, $tmp)) {
                $totalScanCountByDay[$day] = $tmp[$day];
            }
        }

        // Count the total amount of scans for each day of the week (only last week)
        $tmp = array_count_values(array_column(array_column($lastWeekScanResults,'payload'),'day'));

        // Populate structured array with scan counts by day
        foreach ($daysLastWeek as $day) {
            if (array_key_exists($day, $tmp)) {
                $totalScanCountByDayLastWeek[$day] = $tmp[$day];
            }
        }

        // Remove the key values from the array to be compatible with HighCharts
        $totalScanCountByDay = array_values($totalScanCountByDay);

        // Remove the key values from the array to be compatible with HighCharts
        $totalScanCountByDayLastWeek = array_values($totalScanCountByDayLastWeek);

        $totalScanCountLastWeek = count($lastWeekScanResults);
        $totalScanCountLastTwoWeeks = count($lastTwoWeekScanResults);
        
        // The total count for the week after last is equal to the total
        // for two weeks minus the last week
        $totalScanCount2ndWeek = $totalScanCountLastTwoWeeks - $totalScanCountLastWeek;

        // Calculate the % change from this last week to the previous
        if ($totalScanCount2ndWeek > 0)
        {
             $chg = abs((($totalScanCountLastWeek - $totalScanCount2ndWeek)/$totalScanCount2ndWeek) * 100);
        } else {
            $chg = 0;
        }

        if ($totalScanCountLastWeek < $totalScanCount2ndWeek) {
            $dw['dir'] = 'red';
            $dw['arr'] = 'desc';
        } else {
            $dw['dir'] = 'green';
            $dw['arr'] = 'asc';
        }

        $dw['chg'] = $chg;

        // Count the total amount of scans for each day of the week
        $totalScanCountByVendor = array_count_values(array_column(array_column($scanResults,'payload'),'vendor'));

        // Count the total unique device mac addresses
        // This assumes that mac addr is a column of the payload array and payload is a column of the scanResults array
        $totalUniqueDevices = count(array_unique(array_column(array_column($scanResults,'payload'),'mac_addr')));

        // Get the Unique Vendors array --> Needed for plotting scans by Vendor
        $uniqueVendors = array_unique(array_column(array_column($scanResults,'payload'),'vendor'));
        
        // Count the total unique device vendors
        // This assumes that vendor is a column of the payload array and payload is a column of the scanResults array
        $totalUniqueVendors = count($uniqueVendors);
        $totalScanCount = count($scanResults);
        $this->set(compact('accessPointsCount'));
        $this->set(compact('totalUniqueVendors', 'totalUniqueDevices', 'totalScanCount', 'accessPointsCount', 'totalScanCountByDay', 'totalScanCountByDayLastWeek', 'days', 'daysLastWeek', 'totalScanCountByVendor'));
	
        $this->set(compact('totalUniqueVendors', 'totalUniqueDevices', 'totalScanCount', 'totalScanCountLastWeek', 'accessPointsCount', 'totalScanCountByDay', 'totalScanCountByDayLastWeek', 'days', 'daysLastWeek', 'totalScanCountByVendor', 'dw'));
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