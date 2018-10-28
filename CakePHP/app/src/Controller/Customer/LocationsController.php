<?php
namespace App\Controller\Customer;

use App\Controller\AppController;
use App\Model\Entity\Location;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Solarium\Client;

/**
 * Locations Controller
 *
 * @property \App\Model\Table\LocationsTable $Locations
 */
class LocationsController extends AppController
{
    public $components = [
        'Location'
    ];

    private $exceptions = [
        'geolocation'
    ];

    /**
     * Search method
     *
     * @return void
     */
    public function search()
    {
        $this->Flash->calloutFlash(
            'Coming soon', [
            'key' => 'authError',
            'clear' => true,
            'params' => [
                'heading' => 'Not implemented yet',
                'class' => 'callout-danger',
                'fa' => 'excl'
            ]
        ]);
        return $this->redirect(['action' => 'index']);
    }

    public function beforeFilter(Event $e) {
        $this->Security->config('unlockedActions', ['addBeacon', 'impressions', 'uploadLocations', 'personas', 'noteAuthor', 'add', 'edit', 'view']);
        $this->Security->validatePost = false;

        if(in_array($this->request->action, $this->exceptions)){
            $this->Security->config('validatePost',false);
            $this->Security->config('csrfCheck',false);
        }

        parent::beforeFilter($e);
    }

    public function noteAuthor($id) {

        if (!$this->request->is('ajax')) {
            $this->redirect(['action' => 'index']);
        }

        $this->viewBuilder()->layout('json_payload');

        $location      = $this->Locations->get($id);
        $locationsNote = $this->Locations->LocationsNotes->newEntity();

        if ($this->request->is(['put', 'post', 'patch'])) {

            $this->request->data['LocationsNotes']['location_id'] = $id;
            $this->request->data['LocationsNotes']['author_id'] = $this->Auth->user('id');

            $locationsNote = $this->Locations->LocationsNotes->patchEntity($locationsNote, $this->request->data);


            if ($this->Locations->LocationsNotes->save($locationsNote)) {

                $locationsNote = $this->Locations->LocationsNotes->find()->where(['LocationsNotes.id' => $locationsNote->id])
                    ->contain(
                        [
                            'Authors' => [
                                'Profiles'
                            ]
                        ]
                    )->first();

                $extras = [
                    'response_code' => 200,
                    'message' => 'Note successfully added',
                    'content' => $locationsNote,
                    'post' => [
                        'day' => date('j', strtotime($locationsNote->created)),
                        'month' => date('M', strtotime($locationsNote->created)),
                        'author' => $locationsNote->author->profile->first_name . ' ' . $locationsNote->author->profile->last_name,
                        'time' => $locationsNote->created,
                        'subject' => $locationsNote->subject,
                        'entry' => $locationsNote->entry
                    ]
                ];

                $this->set(compact('extras', 'location'));

                return $this->render('note_added');

            } else {

                $extras = [
                    'response_code' => 120,
                    'message' => 'Error adding note'
                ];
                $this->set(compact('extras', 'locationsNote', 'location'));

                return $this->render('note_author');

            }
        }

        $this->set(compact('locationsNote', 'location'));
        $this->set('_serialize', ['locations']);


    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => [
                'Retailers',
                'Regions',
                'Countries',
                'Zones.Beacons',
                'Zones.FlaggedBeacons',
            ],
            'conditions' => [
                'Locations.customer_id IN' => $this->request->session()->read('multiple_customers'),
                'Locations.flag_duplicate' => false,
            ],
        ];

        $this->set('locations', $this->paginate($this->Locations));
        $this->set('_serialize', ['locations']);
    }

    /**
     * Fetches impressions via AJAX
     * @param $location_id
     */
    public function ajaxImpressions($location_id, $type = 'beacon') {
        if ($this->request->is('ajax')) {
            if ($type == "beacon") {
                $this->loadModel('Impressions');

                $this->paginate = [
                    'sortWhitelist' => [
                        'Impressions.timestamp'
                    ],
                    'limit' => 10,
                    'straight' => false,
                    'conditions' => [
                        //'Beacons.customer_id IN ('. implode(',', $this->request->session()->read('multiple_customers')) .")"
                    ],
                    'join' => [
                        [
                            'table' => 'zones',
                            'alias' => 'Zones2',
                            'type' => 'inner',
                            'conditions' => [
                                'Zones2.id = Impressions.zone_id',
                                'Zones2.location_id' => $location_id
                            ]
                        ]
                    ],
                    'order' => ['Impressions.timestamp' => 'desc'],
                    'contain' => [
                        'Zones' => ['Locations'],
                        'Devices',
                        'Beacons'
                    ]
                ];

                $impressions = $this->paginate($this->Impressions);
                $this->set(compact('impressions'));
                $this->render('/Element/CustomerSection/Locations/View/Tabs/Impressions');
            } else {
                $this->loadModel('GeofenceImpressions');
                $this->paginate = [
                    'sortWhitelist' => [
                        'GeofenceImpressions.id',
                        'GeofenceImpressions.timestamp',
                        'GeofenceImpressions.impression_id',
                        'GeofenceImpressions.name',
                        'GeofenceImpressions.personas_count',
                        'GeofenceImpressions.geofence_id',
                        'Devices.os',
                        'Personas.persona'
                    ],
                    'contain' => [
                        'Geofences.Locations.Retailers',
                        'Applications',
                        'Devices'
                    ],
                    'conditions' => [
                        'Geofences.location_id' => $location_id,
                        'Geofences.customer_id IN ('. implode(',', $this->request->session()->read('multiple_customers')) .")"
                    ],
                    'order' => ['GeofenceImpressions.timestamp' => 'desc'],
                    'limit' => Configure::read('Settings.number_records_per_page')
                ];
                $impressions = $this->paginate($this->GeofenceImpressions);

                $this->set(compact('impressions'));
                $this->set('_serialize', ['impressions']);
                $this->render('/Element/CustomerSection/Impressions/View/Tabs/GeofenceImpressions');
            }
        }
    }

    /**
     * View method
     *
     * @param string|null $id Location id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {


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


        if ($id === null) {
            if (!empty($this->request->data['locationId'])) {
                $id = $this->request->data['locationId'];
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
            'Impressions' => 1,
            'Zones' => 1,
            'LocationsNotes' => 1
        ];

        if (!empty($this->request->query)) {
            if (!empty($this->request->query['ajax']) && $this->request->query['ajax']) {

                $page = (!empty($this->request->query['page'])) ? $this->request->query['page'] : 1;
                $pageNumber[$this->request->query['mdl']] = $page;

            }
        }
        if (!empty($this->request->query['mdl'])) {
            if ($this->request->query['mdl'] === 'LocationsNotes') {
                $locationsNotesPage = true;
                $impressionsPage    = false;
                $zonesPage          = false;
            } elseif ($this->request->query['mdl'] === 'Impressions') {
                $impressionsPage    = true;
                $locationsNotesPage = false;
                $zonesPage          = false;
            } elseif ($this->request->query['mdl'] === 'Zones') {
                $impressionsPage    = false;
                $locationsNotesPage = false;
                $zonesPage          = true;
            }
        } else {
            $impressionsPage    = true;
            $locationsNotesPage = true;
            $zonesPage = true;
        }

        $periodStartRaw = date('Y-m-d h:i:s', strtotime($periodStart));
        $periodEndRaw   = date('Y-m-d h:i:s', strtotime($periodEnd));

        $this->loadModel('LocationsNotes');
        $this->loadModel('Locations');

        $location = $this->Locations->find()
            ->contain(['Retailers', 'LocationsContacts' => ['LocationsContactsPhones'], 'Regions', 'Countries', 'Zones' => ['Beacons']])
            ->where(['Locations.id '=> $id, 'Locations.customer_id IN ('. implode(',', $this->request->session()->read('multiple_customers')) .")"])
            ->first();
        if (empty($location)) {
            $this->Flash->calloutFlash(
                'You do not have access to view those location details, locations are assigned to a customer account, if you think this is in error please contact support', [
                'key' => 'authError',
                'clear' => true,
                'params' => [
                    'heading' => 'No access',
                    'class' => 'callout-danger',
                    'fa' => 'excl'
                ]
            ]);
            return $this->redirect(['action' => 'index']);
        }

        $Impressions = TableRegistry::get('Impressions');
        $Beacons     = TableRegistry::get('Beacons');
        if ($locationsNotesPage) {
            $this->paginate = [
                'sortWhitelist' => [
                    'impressions_count'
                ],
                'limit' => 3,
                'page' => $pageNumber['LocationsNotes'],
                'conditions' => [
                    'LocationsNotes.location_id' => $id
                ],
                'order' => ['LocationsNotes.created' => 'desc'],
                'contain' => [
                    'Authors' => [
                        'Profiles'
                    ]
                ]
            ];

            $notes      = $this->paginate($this->LocationsNotes);
        } else {
            $notes = [];
        }
        $this->loadModel('Impressions');
        $this->loadModel('Zones');
        if ($zonesPage) {
            $zones  = $this->Paginator->paginate(
                $this->Zones
                    ->find()
                    ->where(
                        [
                            'Zones.location_id' => $id
                        ]
                    )
                    ->contain(
                        [
                            'FlaggedBeacons',
                            'Beacons',
                            'Locations' => [
                                'Retailers'
                            ]
                        ]
                    )
                    ->order(
                        [

                        ]
                    ),
                [
                    'limit' => 10,
                    'page' => $pageNumber['Zones'],
                    'sortWhitelist' => [
                        'impressions_count'
                    ]
                ]

            );
        } else {
            $zones = [];
        }
        $dc = $Impressions
            ->find()
            ->select(
                [
                    'Impressions.id'
                ]
            )
            ->group(
                [
                    'Impressions.device_id'
                ]
            )
            ->join(
                [
                    [
                        'table' => 'zones',
                        'alias' => 'Zones',
                        'type'  => 'inner',
                        'conditions' => [
                            'Zones.id = Impressions.zone_id',
                            'Zones.location_id' => $id
                        ]
                    ]
                ]
            )
            ->where([
                'Impressions.timestamp >=' => $periodStart,
                'Impressions.timestamp <=' => $periodEnd
            ])->count();
        $bc = $Beacons
            ->find('all', [
            ])
            ->select(
                [
                    'total_beacons' => 'COUNT(Beacons.id)'
                ]
            )
            ->join(
                [
                    [
                        'table' => 'zones',
                        'alias' => 'Zones',
                        'type'  => 'inner',
                        'conditions' => [
                            'Zones.beacon_id = Beacons.id',
                            'Zones.location_id' => $id
                        ]
                    ]
                ]
            )
            ->first();
        $qt = $Impressions
            ->find()
            ->select(
                [
                    'total_impressions' => 'COUNT(Impressions.id)'
                ]
            )
            ->join(
                [
                    [
                        'table' => 'zones',
                        'alias' => 'Zones',
                        'type'  => 'inner',
                        'conditions' => [
                            'Zones.id = Impressions.zone_id',
                            'Zones.location_id' => $id
                        ]
                    ]
                ]
            )
            ->where([
                'Impressions.timestamp >=' => ($periodStart),
                'Impressions.timestamp <=' => ($periodEnd)
            ])
            ->first();
        $WeeksImpressionCount   = (int)($qt->total_impressions);
        $LocationDevicesCount   = (int)($dc);
        $LocationZonesCount 	= (int)($location->zones_count);
        $LocationAPZonesCount   = (int)($location->apzones_count);

        $beacon = $this->Locations->Zones->Beacons->newEntity();
        $beacon->major = $this->AuthUser->user('customer.beacon_major');

        $this->set(compact('beacon','notes', 'zones', 'LocationZonesCount', 'periodStartRaw', 'periodEndRaw', 'LocationDevicesCount', 'WeeksImpressionCount'));

        $this->set('location', $location);
        $this->set('_serialize', ['LocationZonesCount', 'LocationDevicesCount', 'WeeksImpressionCount']);
    }


    public function impressions() {

        if (!$this->request->is('ajax')) {
            return $this->redirect(['action' => 'index']);
        }

        $lId = $this->request->data['location_id'];
        $st  = (!empty($this->request->data['start']) ? $this->request->data['start'] : date('m/d/Y', strtotime('-8 days')));
        $end = (!empty($this->request->data['end']) ? $this->request->data['end'] : date('m/d/Y', time()));
        $stf    = strtotime($st);
        $ndf    = strtotime($end);


        $Impressions = TableRegistry::get('Impressions');

        $impressions = $Impressions->find()
            ->select(
                [
                    'date'  => 'DATE(Impressions.timestamp)',
                    'count' => 'COUNT(DATE(Impressions.timestamp))'
                ]
            )
            ->where(
                [
                    'Impressions.timestamp >=' => $stf,
                    'Impressions.timestamp <=' => $ndf
                ]
            )
            ->group(
                'DATE(Impressions.timestamp)'
            )
            ->join(
                [
                    [
                        'table' => 'zones',
                        'alias' => 'Zones',
                        'type'  => 'inner',
                        'conditions' => [
                            'Zones.id= Impressions.zone_id',
                            'Zones.location_id' => $lId
                        ]
                    ]
                ]
            )
            ->order(
                [
                    'DATE(Impressions.timestamp)' => 'ASC'
                ]
            )
            ->contain(
                [

                ]
            )->toArray();

        $dates = Hash::extract($impressions, '{n}.date');
        $values = Hash::extract($impressions, '{n}.count');

        $json = [
            'dates' => $dates,
            'values' => array_map(function($x) { return number_format($x); }, $values),
        ];

        $this->set(compact('json'));
        $this->set('_serialize', ['json']);

    }



    /**
     * List method
     *
     * @param string|null $id Location id.
     * @return void
     */
    public function listing($retailer_id = null)
    {
        $retailer = $this->Locations->Retailers->get($retailer_id, [

        ]);


        $this->paginate = [
            'sortWhitelist' => [
                'Locations.id',
                'Locations.beacon_id',
                'Locations.location',
                'Retailers.name',
                'Locations.impressions_count',
                'Locations.fixture_no',
                'Locations.location',
                'Locations.regional_name'
            ],
            'limit' => Configure::read('Settings.number_records_per_page'),
            'contain' => [
                'Zones' => [ 'Beacons', 'FlaggedBeacons' ],
                'Retailers'
            ],
            'conditions' => [
                'Locations.retailer_id' => $retailer_id
            ],
            'order' => ['Retailers.name' => 'asc']
        ];

        $this->set('retailer', $retailer);
        $this->set('locations', $this->paginate($this->Locations));
        $this->set('_serialize', ['locations']);
    }





    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $countries      = TableRegistry::get('Countries')->getSortedList();

        $this->loadModel('Placetypes');
        $placetypes = $this->Placetypes->find('list');
        $location = $this->Locations->newEntity();

        $regions = [];
        $dRegions = [];


        if ($this->request->is('post')) {

            $this->request->data['Locations']['customer_id'] =  $this->AuthUser->user('customer_id');

            $location = $this->Locations->newEntity($this->request->data, [
                'associated' => [
                    'LocationsContacts',
                    'LocationsContacts.LocationsContactsPhones',
                ]
            ]);


            if ($location->country) {
                $dRegions = $this->Location->getRegions($location->billing_country_id);
            }

            if (!empty($location->locations_contacts)) {
                foreach ($location->locations_contacts as $k => $contact) {
                    $regions[$contact->country_id] = $this->Location->getRegions($contact->country_id);
                }
            }



            if ($this->Locations->save($location, [
                'associated' => [
                    'LocationsContacts',
                    'LocationsContacts.LocationsContactsPhones',
                ]
            ])) {

                $this->Flash->calloutFlash(
                    'Location and associated data added successfully', [
                    'key' => 'authError',
                    'clear' => true,
                    'params' => [
                        'heading' => 'Success',
                        'class' => 'callout-success',
                        'fa' => 'check'
                    ]
                ]);

                if ($this->request->data('getGeofence') == true) {
                    return $this->redirect('customer/geofences/add?location_id='.$location->id);
                }

                return $this->redirect(['action' => 'index']);

            } else {
                if ($location->errors()) {
                    $location->invalid(["country_id" => ["_required" => "Please re-choose your country"]]);
                }
                $this->Flash->calloutFlash(
                    'Error adding location', [
                    'key' => 'authError',
                    'clear' => true,
                    'params' => [
                        'heading' => 'Error',
                        'class' => 'callout-danger',
                        'fa' => 'excl'
                    ]
                ]);
            }
        }

        $retailers = $this->Locations->Retailers->find('list', ['order' => ['Retailers.name' => 'ASC'], 'limit' => 200]);

        $this->set(compact('location', 'dRegions', 'countries', 'regions', 'countries', 'retailers','placetypes'));
        $this->set('_serialize', ['location']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Location id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $countries      = TableRegistry::get('Countries')->getSortedList();
        $dRegions = [];
        $location = $this->Locations->find(
                    )->where(
                        [
                            'Locations.id' => $id
                        ]
                    )->contain(
                        [
                            'Zones' => [
                                'Beacons'
                            ],
                            'LocationsContacts' => [
                                'LocationsContactsPhones'
                            ]
                        ]
                    )->first();

        if ($location->country) {
            $dRegions = $this->Location->getRegions($location->country_id);
        }

        foreach($location->locations_contacts as $k => $contact) {
            $regions[$contact->country_id] = $this->Location->getRegions($contact->country_id);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {

            $location = $this->Locations->patchEntity($location, $this->request->data, [
                'associated' => [
                    'LocationsContacts',
                    'LocationsContacts.LocationsContactsPhones',
                ]
            ]);

            if ($this->Locations->save($location, [
                'associated' => [
                    'LocationsContacts',
                    'LocationsContacts.LocationsContactsPhones',
                ]
            ])) {

                $this->Flash->calloutFlash(
                    'Location and associated data saved successfully', [
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
                    'Error saving location', [
                    'key' => 'authError',
                    'clear' => true,
                    'params' => [
                        'heading' => 'Error',
                        'class' => 'callout-danger',
                        'fa' => 'excl'
                    ]
                ]);
            }

        }


        $retailers = $this->Locations->Retailers->find('list', ['limit' => 200]);

        $this->set(compact('location', 'retailers', 'dRegions', 'countries', 'regions'));

        $this->set('_serialize', ['location']);
    }

    public function update() {

        $this->loadModel('FlaggedBeaconsDevicesNearby');

        $devices = $this->FlaggedBeaconsDevicesNearby->find()->where(['FlaggedBeaconsDevicesNearby.region_id IS NULL'])->all();

        $Regions   = TableRegistry::get('Regions');
        $country = 240;

        $succ = 0;
        $total = count($devices);
        $failed = 0;

        $fails = [];

        foreach($devices as $device) {

            $rg = $Regions->find()->where(
                [
                    'country_id' => $country,
                    'subdiv'     => 'US-' . $device->state
                ]
            );

            if (!$rg->isEmpty()) {

                $reg = $rg->first();

                $d = [
                    'country_id' => $country,
                    'region_id' => $reg->id
                ];

                $device = $this->FlaggedBeaconsDevicesNearby->patchEntity($device, $d);
                if ($this->FlaggedBeaconsDevicesNearby->save($device)) {
                    $succ++;
                }

            } else {
                $fails[] = $device;
                $failed++;
            }




        }

        echo "Complete. Processed {$succ} items out of a total {$total}.<br/>";
        echo "There were {$failed} failures.";
        exit();
    }


    /**
     * Adds a beacon to a location
     */
    public function addBeacon() {
        $this->autoRender = false;
        if ($this->request->is('post')) {

            if ($this->request->data['reassign_beacon'] == 1) {
                $reassign = true;
            }


            $this->loadModel('Beacons');

            if (empty($this->request->data['Beacons']['major'])) {
                echo json_encode(['success' => 0, 'message' => 'Error: Major cannot be zero, customer has no Beacon Major set.']);
                die();
            }
            $this->request->data['Beacons']['minor_hex'] = strtoupper(sprintf("%04x", $this->request->data['Beacons']['minor_dec']));
            $this->request->data['Beacons']['deployment_status'] = "NEW_UNASSIGNED";
            $this->request->data['Beacons']['customer_id'] = $this->AuthUser->user('customer_id');
            $this->request->data['Beacons']['uuid'] = "8D847D20-0116-435F-9A21-2FA79A706D9E";

            $exists = $this->Beacons->find()
                ->where(
                    [
                        'major' => $this->request->data['Beacons']['major'],
                        'minor_dec' => $this->request->data['Beacons']['minor_dec'],

                    ])
                ->contain(['Zones.Locations'])
                ->first();

            if (empty($reassign) && !empty($exists)) {
                if (!empty($exists->zone->location)) {
                    $this->response->body(json_encode(['success' => 0, 'message' => 'Beacon already exists at the following locations', 'reassign' => 'location', 'location' => $exists->zone->location->location . ' / ' . $exists->zone->placement ?? 'No Zone']));
                } else {
                    $this->response->body(json_encode(['success' => 0, 'reassign' => 'new']));
                }
                return $this->response;
            }

            if (empty($reassign) && empty($exists)) {
                $beacon = $this->Beacons->newEntity($this->request->data);

                if ($nb = $this->Beacons->save($beacon)) { //save beacon
                    $zone = $this->Beacons->Zones->newEntity([
                        'fixture_no' => "N/A",
                        'placement' => $this->request->data['Zones']['placement'] ?? "Unknown placement - this zone was automatically inserted when inserting an auto detected beacon and zone.",
                        'location_id' => $this->request->data['location_id'],
                        'beacon_id' => $nb->id
                    ]);


                    if ($this->Beacons->Zones->save($zone)) {
                        $this->response->body(json_encode(['success' => 1, 'message' => "Beacon {$nb->id} was added successfully to this Location."]));

                    } else {
                        $this->response->body(json_encode(['success' => 0, 'message' => 'Error: Failed to add zone']));
                    }
                } else {
                    $this->response->body(json_encode(['success' => 0, 'message' => 'Error: Failed to add Beacon']));
                }
                return $this->response;

            } else {
                //reassign the beacon here
                //find current zone if it exists ($exists)
                if (!empty($exists->zone)) {

                    $exists->zone->location_id = $this->request->data['location_id'];
                    $exists->zone->placement = $this->request->data['Zones']['placement'];

                    if ($this->Beacons->Zones->save($exists->zone)) {
                        $this->response->body(json_encode(['success' => 1, 'message' => "Beacon {$exists->zone->beacon_id} was added successfully to this Location."]));
                    } else {
                        $this->response->body(json_encode(['success' => 0, 'message' => 'Error: Failed to edit zone']));
                    }

                    return $this->response;

                } else {
                    //otherwise just create a new zone
                    $zone = $this->Beacons->Zones->newEntity(
                        [
                            'location_id' => $this->request->data['location_id'],
                            'beacon_id' => $exists->id

                        ]
                    );

                    if ($this->Beacons->Zones->save($zone)) {
                        $this->response->body(json_encode(['success' => 1, 'message' => 'Zone added Successfully']));
                        return $this->response;
                    }
                }
            }
        } else {
            $this->response->body(json_encode(['success' => 0, 'message' => 'Error: Wrong Method']));
            return $this->response;
        }
    }

    /**
     * Ajax Request to Reverse address to Points
     * @return \Cake\Http\Response
     */
    public function geolocation() {

        if(isset($this->request->data['address'])){

            $address = $this->request->data['address'];

            $this->response->body(Location::address2Geo($address));
        }

        if(isset($this->request->data['lat']) && isset($this->request->data['lng'])){

            $lat = $this->request->data['lat'];
            $lng = $this->request->data['lng'];

            $this->response->body(Location::geo2Address($lat,$lng));
        }

        return $this->response;
    }
}
