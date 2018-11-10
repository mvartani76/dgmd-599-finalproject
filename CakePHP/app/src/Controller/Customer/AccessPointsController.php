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

        $params = [
            'TableName' => $tableName,
            'ProjectionExpression' => 'ap_mac_addr, payload.mac_addr, payload.vendor, payload.rssi, log_time',
            'KeyConditionExpression' => 'ap_mac_addr = :mmaacc',
            'limit' => 15,
            'ExpressionAttributeValues' => $eav
        ];

        $scanResults = [];

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
        $this->set(compact('scanResults'));
        $this->set(compact('ic', 'notes', 'it', 'dc', 'periodStartRaw', 'periodEndRaw'));
        $this->set('accessPoint', $accessPoint);
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
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $accessPoint = $this->AccessPoints->patchEntity($accessPoint, $this->request->getData());
            if ($this->AccessPoints->save($accessPoint)) {
                $this->Flash->success(__('The access point has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The access point could not be saved. Please, try again.'));
        }
        $customers = $this->AccessPoints->Customers->find('list', ['limit' => 200]);
        $this->set(compact('accessPoint', 'customers'));
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
