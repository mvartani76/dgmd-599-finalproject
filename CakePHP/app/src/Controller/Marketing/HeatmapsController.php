<?php
namespace App\Controller\Marketing;

use App\Controller\AppController;
use Aws\Sdk as AwsSdk;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

/**
 * Heatmaps Controller
 *
 * @property \App\Model\Table\HeatmapsTable $Heatmaps
 *
 * @method \App\Model\Entity\Heatmap[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HeatmapsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['access_points', 'floorplans_library']
        ];
        $heatmaps = $this->paginate($this->Heatmaps);

        $this->set(compact('heatmaps'));
    }

    /**
     * View method
     *
     * @param string|null $id Heatmap id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $heatmap = $this->Heatmaps->get($id, [
            'contain' => ['access_points', 'floorplans_library']
        ]);

        $this->set('heatmap', $heatmap);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $heatmap = $this->Heatmaps->newEntity();
        if ($this->request->is('post')) {
            $heatmap = $this->Heatmaps->patchEntity($heatmap, $this->request->getData());
            if ($this->Heatmaps->save($heatmap)) {
                $this->Flash->success(__('The heatmap has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The heatmap could not be saved. Please, try again.'));
        }
        $accessPoints = $this->Heatmaps->AccessPoints->find('list', ['limit' => 200]);
        $floorplansLibrary = $this->Heatmaps->FloorplansLibrary->find('list', ['limit' => 200]);
        $this->set(compact('heatmap', 'accessPoints', 'floorplansLibrary'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Heatmap id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $heatmap = $this->Heatmaps->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $heatmap = $this->Heatmaps->patchEntity($heatmap, $this->request->getData());
            if ($this->Heatmaps->save($heatmap)) {
                $this->Flash->success(__('The heatmap has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The heatmap could not be saved. Please, try again.'));
        }
        $accessPoints = $this->Heatmaps->access_points->find('list', ['limit' => 200]);
        $floorplans = $this->Heatmaps->floorplans_library->find('list', ['limit' => 200]);
        $this->set(compact('heatmap', 'accessPoints', 'floorplans'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Heatmap id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $heatmap = $this->Heatmaps->get($id);
        if ($this->Heatmaps->delete($heatmap)) {
            $this->Flash->success(__('The heatmap has been deleted.'));
        } else {
            $this->Flash->error(__('The heatmap could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function preview($id = null)
    {
        $heatmap = $this->Heatmaps->get($id, [
            'contain' => ['access_points', 'floorplans_library']
        ]);

        $this->set('heatmap', $heatmap);
    }

    /**
     * Show Activity method
     *
     * @return \Cake\Http\Response|void
     */
    public function showactivity()
    {
        $this->paginate = [
            'contain' => ['access_points', 'floorplans_library']
        ];
        $heatmaps = $this->paginate($this->Heatmaps);

        $this->set(compact('heatmaps'));
    }

    public function show($id = null)
    {
        $heatmap = $this->Heatmaps->get($id, [
            'contain' => ['access_points', 'floorplans_library']
        ]);

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
        $apstr = join(':', str_split($heatmap->access_point->mac_addr,2));

        // Set the filter expression for mac address to be the selected access point mac address
        // Creating the JSON string that marshjson would have done
        $eav = array(":mmaacc"=>array("S"=>$apstr));

        // Set the ExpressionAttributeValues for the time based query.
        // Note that the numbers for starttime/endtime need to be surrounded by double quotation marks.
        $eav_time = array(":mmaacc"=>array("S"=>$apstr),":starttime"=>array("N"=>"$periodStart"),":endtime"=>array("N"=>"$periodEnd"));


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
                'ProjectionExpression' => 'payload.rssi',
                'KeyConditionExpression' => 'ap_mac_addr = :mmaacc',
                'ExpressionAttributeValues' => $eav,
                'Limit' => 15
            ];
        }

        $scanResults = [];


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

        //$scanResults = (object) $scanResults;

        //pr($scanResults);

        // Set the previous last evaluated key to be able to navigate back to the previous page
        /*if (isset($lastevalkey)) {
            $prevlastvalkey = $lastevalkey;
        } else {
            $prevlastvalkey = $result['LastEvaluatedKey'];
        }
        $lastevalkey = $result['LastEvaluatedKey'];

        $this->set('accessPoint', $accessPoint);

        $this->set(compact('scanResults','lastevalkey', 'prevlastvalkey', 'page'));
        $this->set(compact('totalScanCount', 'totalScanCount_time', 'totalUniqueDevices', 'totalUniqueDevices_time'));
        $this->set(compact('notes', 'periodStartRaw', 'periodEndRaw'));

        // Need to serialize the variables to have them visible for the JSON response
        $this->set('_serialize', ['totalScanCount', 'totalScanCount_time', 'totalUniqueDevices', 'totalUniqueDevices_time']);
*/
        $points = [];
        $maxval = 0;
        for ($i=0; $i<200; $i++){
            $val = floor(mt_rand(0,100));
            $maxval = max($maxval, $val);
          //$point = {
          //  x: Math.floor(Math.random()*width),
          //  y: Math.floor(Math.random()*height),
          //  value: val
          //};
            //pr($val);
            $point['x'] = floor(mt_rand(0,800));
            $point['y'] = floor(mt_rand(0,400));
            $point['value'] = $val;
            $mypoints['data'][] = $point;
        }
        $mypoints['max'] = $maxval;
//pr($points);

        $this->set('heatmap', $heatmap);
        $this->set('mypoints', json_encode($mypoints));

    }
}
