<?php
namespace App\Model\Table;

use App\Model\Entity\Location;
use App\ORM\D2goTable as Table;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Locations Model
 *
 * @property \Cake\ORM\Association\HasMany $LocationsNotes
 * @property \Cake\ORM\Association\BelongsTo $Retailers
 * @property \Cake\ORM\Association\HasMany $Zones
 */
class LocationsTable extends Table
{

    /* From https://www.usps.com/send/official-abbreviations.htm */

    public static $states = array(
        'AL'=>'ALABAMA',
        'AK'=>'ALASKA',
        'AS'=>'AMERICAN SAMOA',
        'AZ'=>'ARIZONA',
        'AR'=>'ARKANSAS',
        'CA'=>'CALIFORNIA',
        'CO'=>'COLORADO',
        'CT'=>'CONNECTICUT',
        'DE'=>'DELAWARE',
        'DC'=>'DISTRICT OF COLUMBIA',
        'FM'=>'FEDERATED STATES OF MICRONESIA',
        'FL'=>'FLORIDA',
        'GA'=>'GEORGIA',
        'GU'=>'GUAM GU',
        'HI'=>'HAWAII',
        'ID'=>'IDAHO',
        'IL'=>'ILLINOIS',
        'IN'=>'INDIANA',
        'IA'=>'IOWA',
        'KS'=>'KANSAS',
        'KY'=>'KENTUCKY',
        'LA'=>'LOUISIANA',
        'ME'=>'MAINE',
        'MH'=>'MARSHALL ISLANDS',
        'MD'=>'MARYLAND',
        'MA'=>'MASSACHUSETTS',
        'MI'=>'MICHIGAN',
        'MN'=>'MINNESOTA',
        'MS'=>'MISSISSIPPI',
        'MO'=>'MISSOURI',
        'MT'=>'MONTANA',
        'NE'=>'NEBRASKA',
        'NV'=>'NEVADA',
        'NH'=>'NEW HAMPSHIRE',
        'NJ'=>'NEW JERSEY',
        'NM'=>'NEW MEXICO',
        'NY'=>'NEW YORK',
        'NC'=>'NORTH CAROLINA',
        'ND'=>'NORTH DAKOTA',
        'MP'=>'NORTHERN MARIANA ISLANDS',
        'OH'=>'OHIO',
        'OK'=>'OKLAHOMA',
        'OR'=>'OREGON',
        'PW'=>'PALAU',
        'PA'=>'PENNSYLVANIA',
        'PR'=>'PUERTO RICO',
        'RI'=>'RHODE ISLAND',
        'SC'=>'SOUTH CAROLINA',
        'SD'=>'SOUTH DAKOTA',
        'TN'=>'TENNESSEE',
        'TX'=>'TEXAS',
        'UT'=>'UTAH',
        'VT'=>'VERMONT',
        'VI'=>'VIRGIN ISLANDS',
        'VA'=>'VIRGINIA',
        'WA'=>'WASHINGTON',
        'WV'=>'WEST VIRGINIA',
        'WI'=>'WISCONSIN',
        'WY'=>'WYOMING',
        'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
        'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
        'AP'=>'ARMED FORCES PACIFIC'
    );

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('locations');
        $this->displayField('location');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('CounterCache', [
            'Retailers' => ['locations_count']
        ]);

        $this->hasMany('LocationsContacts', [
            'foreignKey' => 'location_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
            'saveStrategy' => 'replace'
        ]);

        $this->hasMany('LocationsNearby', [
            'foreignKey' => 'location_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
            'saveStrategy' => 'replace'
        ]);

        $this->belongsTo('Countries', [
            'foreignKey' => 'country_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('Regions', [
            'foreignKey' => 'region_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('Retailers', [
            'foreignKey' => 'retailer_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('Zones', [
            'foreignKey' => 'location_id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('Apzones', [
            'foreignKey' => 'location_id',
            'joinType' => 'LEFT'
        ]);

        $this->hasMany('Geofences', [
            'foreignKey' => 'location_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasMany('LocationsNotes', [
            'foreignKey' => 'location_id',
            'order' => ['LocationsNotes.created' => 'ASC']
        ]);
        $this->hasMany('LocationsContacts', [
            'foreignKey' => 'location_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
            'saveStrategy' => 'replace'
        ]);

        $this->hasOne('Placetypes',[
            'foreignKey' => 'placetype_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('location', 'create')
            ->notEmpty('location');

        $validator
            ->add('store_no', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('store_no');

        $validator
            ->allowEmpty('fixture_no');

        $validator
            ->add('lat', 'valid', ['rule' => 'numeric'])
            ->notEmpty('lat', 'Latitude is required');

        $validator
            ->add('lng', 'valid', ['rule' => 'numeric'])
            ->notEmpty('lng', 'Longitude is required');

        $validator
            ->allowEmpty('address1')
            ->notEmpty('address1', 'Address is required');

        $validator
            ->allowEmpty('address2');

        $validator
            ->allowEmpty('address3');

        $validator
            ->notEmpty('city');

        $validator
            ->notEmpty('postal_code');

        $validator->add('country_id', 'custom', [
            'rule' => function ($value, $context) {
                return $value > 0;
            },
            'message' => 'Please select a Country'
        ])->notEmpty('country_id');

        $validator
            ->allowEmpty('region_id');

        $validator
            ->add('zip', 'valid', ['rule' => 'numeric'])
            ->notEmpty('zip');

        return $validator;
    }

    public function find($type = 'all', $options = array()) {

        $options['contain'][] = 'Regions';
        $options['contain'][] = 'Countries';

        $options['select'][] = 'Regions.*';
        $options['select'][] = 'Countries.*';

        return parent::find($type, $options);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        return $rules;
    }

    /**
     * Use google api to convert geopoints to address
     * @param $lat
     * @param $lng
     * @return string
     */
    public static function geo2Address($lat,$lng){

        $maps = new \GoogleMapsGeocoder();
        $maps->setLatitude($lat);
        $maps->setLongitude($lng);

        $geoLocation = $maps->geocode();

        return json_encode($geoLocation['results']['0']);
    }

    /**
     * Use google api to convert address to geoposition
     * @param $address
     * @return string
     */
    public static function address2Geo($address) {

        $maps = new \GoogleMapsGeocoder();
        $maps->setAddress($address);
        $geoLocation = $maps->geocode();

        return json_encode($geoLocation['results']['0']['geometry']);
    }

    /**
     * Returns a list of locations for a customer id (or IDs).
     *
     * @param int|array $customer_id
     * @return Cake\ORM\Query
     */
    public function getCustomerLocationsList(array $customer_id)
    {
        $query = $this->find('list', [
            'fields' => [
                'Locations.id',
                'Locations.location',
            ]
        ])->where([
            'Locations.customer_id IN' => $customer_id,
            'Locations.flag_duplicate' => false,
        ]);

        return $query;
    }

    /**
     * Flags a location as a duplicate.
     *
     * @param \App\Model\Entity\Location $location
     * @return \Cake\Datasource\EntityInterface
     */
    public function flagDuplicate(Location $location)
    {
        $location->set('flag_duplicate', true);
        return $this->save($location);
    }

    /**
     * Checks by Lat / Long decimals if location exists.
     *
     * `LIKE` can't be used in this query because the query builder casts the
     * lat field conditions to decimal values, so no 34.456%.
     *
     * @param decimal $lat
     * @param decimal $lng
     * @param array $customer_id
     * @return Cake\ORM\Query
     */
    public function getNearbyLocations(float $lat, float $lng, array $customer_id)
    {
        // Creaate new lat/lngs to use for the between comparison.
        $latA = $lat > 0 ? $lat - 0.004 : $lat + 0.004;
        $latB = $lat > 0 ? $lat + 0.004 : $lat - 0.004;
        $lngA = $lng > 0 ? $lng + 0.004 : $lng - 0.004;
        $lngB = $lng > 0 ? $lng - 0.004 : $lng + 0.004;

        $query = $this->find()
            ->where([
                'Locations.lat >=' => $latA > $latB ? $latB : $latA,
                'Locations.lat <=' => $latA > $latB ? $latA : $latB,
                'Locations.lng >=' => $lngA > $lngB ? $lngB : $lngA,
                'Locations.lng <=' => $lngA > $lngB ? $lngA : $lngB,
                'Locations.customer_id IN' => $customer_id,
            ])
            ->limit(25);

        return $query;
    }
}
