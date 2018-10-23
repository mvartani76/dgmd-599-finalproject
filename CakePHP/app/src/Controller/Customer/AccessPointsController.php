<?php
namespace App\Controller\Customer;

use App\Controller\AppController;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Network\Exception\ForbiddenException;
use Cake\Datasource\ConnectionManager;
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
            'contain' => ['Customers']
        ];
        $accessPoints = $this->paginate($this->AccessPoints);

        $this->set(compact('accessPoints'));
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
                'The Access Point cannot be found or You do not have access to this beacon', [
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

            $periodStart = date('Y-m-d 00:00:00', strtotime($startDate));
            $periodEnd   = date('Y-m-d 23:59:59', strtotime($endDate));

        } else {

            $periodStart  = date('Y-m-d 00:00:00', strtotime('-7 days'));
            $periodEnd    = date('Y-m-d 23:59:59', time());
        }
        $pageNumber = [
            'scan_results' => 1,
            'AccessPointsNotes' => 1
        ];

        if (!empty($this->request->query)) {
            if (!empty($this->request->query['ajax']) && $this->request->query['ajax']) {

                $page = (!empty($this->request->query['page'])) ? $this->request->query['page'] : 1;
                $pageNumber[$this->request->query['mdl']] = $page;

            }
        }
        if (!empty($this->request->query['mdl'])) {
            if ($this->request->query['mdl'] === 'AccessPointsNotes') {
                $accessPointsNotesPage = true;
                $scanResultsPage = false;
            } elseif ($this->request->query['mdl'] === 'scan_results') {
                $scanResultsPage = true;
                $accessPointsNotesPage = false;
            }
        } else {
            $scanResultsPage = true;
            $accessPointsNotesPage = true;
        }
        $periodStartRaw = date('Y-m-d h:i:s', strtotime($periodStart));
        $periodEndRaw   = date('Y-m-d h:i:s', strtotime($periodEnd));


        if ($scanResultsPage) {
            $scanResults  = $this->Paginator->paginate(
                $this->AccessPoints->scan_results
                    ->find()
                    ->where(
                        [
                            'scan_results.accesspoint_id' => $id
                        ]
                    )
                    ->contain(
                        [
                            'access_points'
                        ]
                    )
                    ->order(
                        [
                            'scan_timestamp' => 'DESC'
                        ]
                    ),
                [
                    'limit' => 10,
                    'page' => $pageNumber['scan_results'],
                    'sortWhitelist' => [
                        'id',
                        'location',
                        'impressions_count',
                        'regional_name'
                    ]
                ]

            );
        } else {
            $scanResults = [];
        }



        $scanResults    = TableRegistry::get('scan_results')->find();

        $this->set('scanResults', $scanResults);
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
            $this->request->data['access_points']['customer_id'] = $this->AuthUser->user('customer_id');

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
