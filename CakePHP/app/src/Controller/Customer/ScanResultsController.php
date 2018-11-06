<?php
namespace App\Controller\Customer;

use App\Controller\AppController;
use Aws\Sdk as AwsSdk;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Cake\I18n\FrozenTime;

/**
 * ScanResults Controller
 *
 * @property \App\Model\Table\ScanResultsTable $ScanResults
 *
 * @method \App\Model\Entity\ScanResult[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ScanResultsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
       /* $this->paginate = [
            'contain' => ['access_points']
        ];
        $scanResults = $this->paginate($this->ScanResults);

        $this->set(compact('scanResults'));
*/

        $sdk = new AwsSdk([
            'region'   => env('AWS_DYNAMODB_REGION', 'us-west-2'),
            'version'  => env('AWS_DYNAMODB_VERSION', 'latest'),
            'credentials' => [
                'key' => env('AWS_DYNAMODB_CREDENTIALS_KEY', null),
                'secret'  => env('AWS_DYNAMODB_CREDENTIALS_SECRET', null),
            ],
        ]);

        $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'wdds_testdata';

        $eav = $marshaler->marshalJson('
            {
                ":yyyy": 1985
            }
        ');

        $params = [
            'TableName' => $tableName,
            'Limit' => 15
        ];

        $scanResults = [];

        try {
            $result = $dynamodb->scan($params);
            foreach ($result['Items'] as $mac_addr) {
                $scanResults[] = $marshaler->unmarshalItem($mac_addr);
            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
            die();
        }

        $this->set(compact('scanResults'));
    }

    /**
     * View method
     *
     * @param string|null $id Scan Result id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $scanResult = $this->ScanResults->get($id, [
            'contain' => ['access_points']
        ]);

        $this->set('scanResult', $scanResult);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $scanResult = $this->ScanResults->newEntity();
        if ($this->request->is('post')) {
            $scanResult = $this->ScanResults->patchEntity($scanResult, $this->request->getData());
            if ($this->ScanResults->save($scanResult)) {
                $this->Flash->success(__('The scan result has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scan result could not be saved. Please, try again.'));
        }
        $accessPoints = $this->ScanResults->access_points->find('list', ['limit' => 200]);
        $this->set(compact('scanResult', 'accessPoints'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Scan Result id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $scanResult = $this->ScanResults->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $scanResult = $this->ScanResults->patchEntity($scanResult, $this->request->getData());
            if ($this->ScanResults->save($scanResult)) {
                $this->Flash->success(__('The scan result has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The scan result could not be saved. Please, try again.'));
        }
        $accessPoints = $this->ScanResults->access_points->find('list', ['limit' => 200]);
        $this->set(compact('scanResult', 'accessPoints'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Scan Result id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $scanResult = $this->ScanResults->get($id);
        if ($this->ScanResults->delete($scanResult)) {
            $this->Flash->success(__('The scan result has been deleted.'));
        } else {
            $this->Flash->error(__('The scan result could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
