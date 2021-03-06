<?php
namespace App\Controller\Customer;

use App\Controller\AppController;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;
use Cake\Datasource\ConnectionManager;
use Aws\Sdk as AwsSdk;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
/**
 * AccessPoints Controller
 *
 * @property \App\Model\Table\AccessPointsTable $AccessPoints
 *
 * @method \App\Model\Entity\AccessPoint[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AccessPointsController extends AppController
{


    public function beforeFilter(Event $event) {
        $this->Security->config('unlockedActions', ['view']);
        parent::beforeFilter($event);
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Customers', 'Apzones.Locations']
        ];
        $accessPoints = $this->paginate($this->AccessPoints);

        $this->set(compact('accessPoints', 'Apzones.Locations'));

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

        $totalDevices = array();

        // Compute the total number of scanned devices for each accessPoint and store into
        // total_devices_count --> only using this for index display as the value could be stale
        foreach ($accessPoints as $accessPoint):
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

            // Set the params for the dB query to show total scanresults
            // Used for displaying unique devices over a given time
            $unique_params = [
                'TableName' => $tableName,
                'KeyConditionExpression' => 'ap_mac_addr = :mmaacc',
                'ExpressionAttributeValues' => $eav,
                'Select' => "ALL_ATTRIBUTES"
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

            // Reset the $totalScanResults array
            $totalScanResults = array();
            // Query the total amount of scan results available for the given access point
            try {
                $result = $dynamodb->query($unique_params);
                foreach ($result['Items'] as $mac_addr) {
                    $totalScanResults[] = $marshaler->unmarshalItem($mac_addr);
                }
            } catch (DynamoDbException $e) {
                echo "Unable to query:\n";
                echo $e->getMessage() . "\n";
                die();
            }

            // Count the total unique device mac addresses
            // This assumes that mac addr is a column of the payload array and payload is a column of the totalScanResults array
            $totalUniqueDevices = count(array_unique(array_column(array_column($totalScanResults,'payload'),'mac_addr')));

            // Set the values in the accessPoint object so to not have to update the index.ctp file
            $accessPoint->total_devices_count = $countresult['Count'];
            $accessPoint->total_unique_devices_count = $totalUniqueDevices;

        endforeach;
    }

    /**
     * View method
     *
     * @param string|null $id Access Point id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $accessPoint = $this->AccessPoints->get($id, [
            'contain' => ['Customers']
        ]);


        if (!$accessPoint) {
            $this->Flash->calloutFlash(
                'The Access Point cannot be found or You do not have access to this Access Point', [
                'key' => 'authError',
                'clear' => true,
                'params' => [
                    'heading' => 'You cannot view this Access Point',
                    'class' => 'callout-danger',
                    'fa' => 'excl'
                ]
            ]);
            return $this->redirect(['action' => 'index']);
        }

        // Pass in the AWS credentials from the .env file
        $sdk = new AwsSdk([
            'region'   => env('AWS_DYNAMODB_REGION', 'us-west-2'),
            'version'  => env('AWS_DYNAMODB_VERSION', 'latest'),
            'credentials' => [
                'key' => env('AWS_DYNAMODB_CREDENTIALS_KEY', null),
                'secret'  => env('AWS_DYNAMODB_CREDENTIALS_SECRET', null),
            ],
        ]);

        if (isset($this->request->query['ajax']) && $this->request->query['ajax'] == true) {
            if ($this->request->is('ajax')) {
                $this->viewBuilder()->layout('ajax_paging');
            }
        }
        if (isset($this->request->query['mdl'])) {
            if ($this->request->is('ajax')) {
                $this->viewBuilder()->layout('ajax_paging');
                $this->set('contentKey', $this->request->query['mdl']);
            }
        }

        if (!empty($this->request->data['action']) && $this->request->data['action'] == 'updateStats') {

            $startDate  = urldecode($this->request->data['starttime']);
            $endDate    = urldecode($this->request->data['endtime']);

            // Keeping in unix time (in msec) as that is what is stored in dynamoDB
            $periodStart = strtotime($startDate)*1000;
            $periodEnd   = strtotime($endDate)*1000;

        } else {

            $periodStart  = strtotime('-7 days')*1000;
            $periodEnd    = time()*1000;
        }

        $periodStartRaw = date('Y-m-d h:i:s', $periodStart/1000);
        $periodEndRaw   = date('Y-m-d h:i:s', $periodEnd/1000);


        // Create a DynamoDb instance
        $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'wdds_testdata';

        // Need to format the access point mac address with colons as this is
        // how it is stored in dynamo dB
        $apstr = join(':', str_split($accessPoint->mac_addr,2));

        // Set the filter expression for mac address to be the selected access point mac address
        // Creating the JSON string that marshjson would have done
        $eav = array(":mmaacc"=>array("S"=>$apstr));

        // Set the ExpressionAttributeValues for the time based query.
        // Note that the numbers for starttime/endtime need to be surrounded by double quotation marks.
        $eav_time = array(":mmaacc"=>array("S"=>$apstr),":starttime"=>array("N"=>"$periodStart"),":endtime"=>array("N"=>"$periodEnd"));

        $page = $this->request->getQuery('page', 0);
        $key = $this->request->getQuery('key');
        $key = $key ? unserialize($key) : $key;


        // Set the params for the dB query to count all scanresults
        $count_params = [
            'TableName' => $tableName,
            'KeyConditionExpression' => 'ap_mac_addr = :mmaacc',
            'ExpressionAttributeValues' => $eav,
            'Select' => "COUNT"
            ];

        // Set the params for the dB query to count scanresults over the selected time period
        $count_params_time = [
            'TableName' => $tableName,
            'KeyConditionExpression' => 'ap_mac_addr = :mmaacc AND log_time BETWEEN :starttime AND :endtime',
            'ExpressionAttributeValues' => $eav_time,
            'Select' => "COUNT"
            ];

        // Set the params for the dB query to show total scanresults
        // Used for displaying unique devices over a given time
        $unique_params = [
            'TableName' => $tableName,
            'KeyConditionExpression' => 'ap_mac_addr = :mmaacc',
            'ExpressionAttributeValues' => $eav,
            'Select' => "ALL_ATTRIBUTES"
            ];

        // Set the params for the dB query to show scanresults over the selected time period
        // Used for displaying unique devices over a given time
        $unique_params_time = [
            'TableName' => $tableName,
            'KeyConditionExpression' => 'ap_mac_addr = :mmaacc AND log_time BETWEEN :starttime AND :endtime',
            'ExpressionAttributeValues' => $eav_time,
            'Select' => "ALL_ATTRIBUTES"
            ];

        // Set the params for the dB scan to show results for access point
        // Conditionally set the params using ExclusiveStartKey if available
        // Limit the results to 15
        if (isset($key)) {
            $params = [
                'TableName' => $tableName,
                'ProjectionExpression' => 'ap_mac_addr, payload.mac_addr, payload.vendor, payload.rssi, log_time',
                'KeyConditionExpression' => 'ap_mac_addr = :mmaacc',
                'ExpressionAttributeValues' => $eav,
                'Limit' => 15,
                'ExclusiveStartKey' => $key
            ];
        } else {
            $params = [
                'TableName' => $tableName,
                'ProjectionExpression' => 'ap_mac_addr, payload.mac_addr, payload.vendor, payload.rssi, log_time',
                'KeyConditionExpression' => 'ap_mac_addr = :mmaacc',
                'ExpressionAttributeValues' => $eav,
                'Limit' => 15
            ];
        }

        $scanResults = [];

        // Count the number items matching the filter conditions but not limiting to 15
        // Not sure which is the more efficient method?
        try {
            $countresult = $dynamodb->query($count_params);
        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
            die();
        }

        $totalScanCount = $countresult['Count'];

        // Count the number items matching the filter conditions over the time period
        // but not limiting to 15. Not sure which is the more efficient method?
        try {
            $countresult_time = $dynamodb->query($count_params_time);
        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
            die();
        }

        $totalScanCount_time = $countresult_time['Count'];

        // Query the total amount of scan results available for the given access point
        try {
            $result = $dynamodb->query($unique_params);
            foreach ($result['Items'] as $mac_addr) {
                $totalScanResults[] = $marshaler->unmarshalItem($mac_addr);
            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
            die();
        }

        // Count the total unique device mac addresses
        // This assumes that mac addr is a column of the payload array and payload is a column of the totalScanResults array
        $totalUniqueDevices = count(array_unique(array_column(array_column($totalScanResults,'payload'),'mac_addr')));

        // Query the total amount of scan results available for the given access point over a specified time
        try {
            $result = $dynamodb->query($unique_params_time);
            foreach ($result['Items'] as $mac_addr) {
                $totalScanResults_time[] = $marshaler->unmarshalItem($mac_addr);
            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
            die();
        }

        // Count the total unique device mac addresses over time
        // This assumes that mac addr is a column of the payload array and payload is a column of the totalScanResults array
        if ($result['Count'] > 0) {
            $totalUniqueDevices_time = count(array_unique(array_column(array_column($totalScanResults_time,'payload'),'mac_addr')));
        } else {
            $totalUniqueDevices_time = 0;
        }

        // Query the scan results for the given access point but limit to 15 items
        try {
            $result = $dynamodb->query($params);
            foreach ($result['Items'] as $mac_addr) {
                $scanResults[] = $marshaler->unmarshalItem($mac_addr);
            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
            die();
        }

        $scanResults = (object) $scanResults;

        // Set the previous last evaluated key to be able to navigate back to the previous page
        if (isset($lastevalkey)) {
            $prevlastvalkey = $lastevalkey;
        } else {
            $prevlastvalkey = $result['LastEvaluatedKey'];
        }
        $lastevalkey = $result['LastEvaluatedKey'];

        $this->set('accessPoint', $accessPoint);

        $this->set(compact('scanResults','lastevalkey', 'prevlastvalkey', 'page'));
        $this->set(compact('totalScanCount', 'totalScanCount_time', 'totalUniqueDevices', 'totalUniqueDevices_time'));
        $this->set(compact('periodStartRaw', 'periodEndRaw'));

        // Need to serialize the variables to have them visible for the JSON response
        $this->set('_serialize', ['totalScanCount', 'totalScanCount_time', 'totalUniqueDevices', 'totalUniqueDevices_time']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $accessPoint = $this->AccessPoints->newEntity();

        if ($this->request->is('post')) {
            $this->request->data['customer_id'] = $this->AuthUser->user('customer_id');

            $this->request->data['Locations']['retailer_id'] = 20;
            $this->request->data['Locations']['customer_id'] = $this->AuthUser->user('customer_id');

            $accessPoint = $this->AccessPoints->patchEntity($accessPoint, $this->request->data);
            $location = $this->AccessPoints->Apzones->Locations->newEntity($this->request->data);

            if ($nap = $this->AccessPoints->save($accessPoint)) { //save access point
                if ($nl = $this->AccessPoints->Apzones->Locations->save($location)) { //save location
                    $apzone = $this->AccessPoints->Apzones->newEntity(
                        [
                            'fixture_no' => "N/A",
                            'placement' => $this->request->data['Apzones']['placement'] ?? "Unknown placement - this apzone was automatically inserted when inserting an auto detected accessPoint and apzone.",
                            'location_id' => $nl->id,
                            'accesspoint_id' => $nap->id
                        ]);

                    if ($this->AccessPoints->Apzones->save($apzone)) {
                        $this->Flash->calloutFlash(
                            'Access Point created successfully.', [
                            'key' => 'authError',
                            'clear' => true,
                            'params' => [
                                'heading' => 'Success',
                                'class' => 'callout-success',
                                'fa' => 'check'
                            ]
                        ]);
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->calloutFlash(
                            'Apzone could not be saved', [
                            'key' => 'authError',
                            'clear' => true,
                            'params' => [
                                'heading' => 'Error',
                                'class' => 'callout-danger',
                                'fa' => 'excl'
                            ]
                        ]);
                        return $this->redirect(['action' => 'add']);
                    }
                } else {
                    $this->Flash->calloutFlash(
                        'Location could not be saved', [
                        'key' => 'authError',
                        'clear' => true,
                        'params' => [
                            'heading' => 'Error',
                            'class' => 'callout-danger',
                            'fa' => 'excl'
                        ]
                    ]);
                    return $this->redirect(['action' => 'add']);
                }
            } else {
                $this->Flash->calloutFlash(
                    'AccessPoint could not be saved', [
                    'key' => 'authError',
                    'clear' => true,
                    'params' => [
                        'heading' => 'Error',
                        'class' => 'callout-danger',
                        'fa' => 'excl'
                    ]
                ]);


                return $this->redirect(['action' => 'add']);
            }
        }
        $customers = $this->AccessPoints->Customers->find('list', ['limit' => 200]);
        

        $countries      = TableRegistry::get('Countries')->getSortedList();
        $this->loadModel('Placetypes');
        $placetypes = $this->Placetypes->find('list');

        $this->set('location', $this->AccessPoints->Apzones->Locations->newEntity());
        $this->set('countries', $countries);
        $this->set(compact('accessPoint','placetypes'));
        $this->set('_serialize', ['accessPoint']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Access Point id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $accessPoint = $this->AccessPoints->get($id, [
            'contain' => ['Heatmaps']
        ]);

        // Handle incoming submit message from form
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->request->data['customer_id'] = $this->AuthUser->user('customer_id');
            $this->request->data['access_points']['customer_id'] = $this->AuthUser->user('customer_id');


            $accessPoint = $this->AccessPoints->patchEntity($accessPoint, $this->request->getData());
            if ($this->AccessPoints->save($accessPoint)) {
                
                // If heatamp already exists for this access point, we can patch, otherwise
                // we will need to create a new entity --> if the user says to attach floorplan
                if (isset($accessPoint->heatmap)) {
                    debug($this->request['data']);
                }
                else {
                    // Only save the updated data if the user wants to attach floorplan
                    if ($this->request['data']['attachFloorplan'] == 'Yes') {
                        
                        $heatmap = $this->AccessPoints->Heatmaps->newEntity([
                            'accesspoint_id' => $accessPoint->id,
                            'floorplan_id' => $this->request->data['heatmap']['floorplan_id'],
                            'x' => $this->request->data['heatmap']['x'],
                            'y' => $this->request->data['heatmap']['y']
                        ]);
                        // Try to save the heatmap associated with the access point
                        if ($this->AccessPoints->Heatmaps->save($heatmap)) {
                            $this->Flash->success(__('The access point has been saved.'));
                        } else {
                            $this->Flash->error(__('The heatmap could not be saved. Please, try again.'));
                        }
                    }
                }

                $this->Flash->success(__('The access point has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The access point could not be saved. Please, try again.'));
        }

        $heatmaps = $this->loadModel('Heatmaps')->find();

        // Listing with key/value fields to be able to see the floorplan title as a text string
        // but pass the id # to save
        $floorplans_select = $this->loadModel('floorplans_library')->find('list', ['keyField' => 'id',
        'valueField' => 'title']);

        // Only search for a specific floorplan id if the heatmap exists linking the two
        if (isset($accessPoint->heatmap)) {
            $floorplans = $this->loadModel('floorplans_library')->find()->where(
                            [
                                'floorplans_library.id' => $accessPoint->heatmap->floorplan_id
                            ])->first();
        }
        else {
            $floorplans = $this->loadModel('floorplans_library')->find('all');
        }

        $customers = $this->AccessPoints->Customers->find('list', ['limit' => 200]);
        $this->set(compact('accessPoint', 'customers', 'heatmaps', 'floorplans', 'floorplans_select'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Access Point id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $accessPoint = $this->AccessPoints->get($id);
        if ($this->AccessPoints->delete($accessPoint)) {
            $this->Flash->success(__('The access point has been deleted.'));
        } else {
            $this->Flash->error(__('The access point could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
