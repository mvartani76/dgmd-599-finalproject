<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Customers Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $ParentCustomers
 * @property |\Cake\ORM\Association\BelongsTo $Retailers
 * @property \App\Model\Table\CustomerTypesTable|\Cake\ORM\Association\BelongsTo $CustomerTypes
 * @property |\Cake\ORM\Association\BelongsTo $BillingRegions
 * @property |\Cake\ORM\Association\BelongsTo $BillingCountries
 * @property |\Cake\ORM\Association\HasMany $AccessPoints
 * @property |\Cake\ORM\Association\HasMany $Analytics
 * @property |\Cake\ORM\Association\HasMany $Apps
 * @property |\Cake\ORM\Association\HasMany $Beacons
 * @property |\Cake\ORM\Association\HasMany $CampaignContent
 * @property \App\Model\Table\CampaignsTable|\Cake\ORM\Association\HasMany $Campaigns
 * @property |\Cake\ORM\Association\HasMany $ChildCustomers
 * @property \App\Model\Table\CustomersContactsTable|\Cake\ORM\Association\HasMany $CustomersContacts
 * @property \App\Model\Table\CustomersNotesTable|\Cake\ORM\Association\HasMany $CustomersNotes
 * @property |\Cake\ORM\Association\HasMany $DashboardWidgets
 * @property |\Cake\ORM\Association\HasMany $Dashboards
 * @property \App\Model\Table\FloorplansLibraryTable|\Cake\ORM\Association\HasMany $FloorplansLibrary
 * @property |\Cake\ORM\Association\HasMany $Geofences
 * @property |\Cake\ORM\Association\HasMany $Groups
 * @property |\Cake\ORM\Association\HasMany $Locations
 * @property \App\Model\Table\MediaLibraryTable|\Cake\ORM\Association\HasMany $MediaLibrary
 * @property |\Cake\ORM\Association\HasMany $Redirects
 * @property |\Cake\ORM\Association\HasMany $Surveys
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Customer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Customer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Customer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Customer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Customer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Customer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Customer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\CounterCacheBehavior
 */
class CustomersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('customers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('CounterCache', [
            'CustomerTypes' => ['customer_count']
        ]);

        $this->belongsTo('ParentCustomers', [
            'className' => 'Customers',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Retailers', [
            'foreignKey' => 'retailer_id'
        ]);
        $this->belongsTo('CustomerTypes', [
            'foreignKey' => 'customer_types_id',
            'joinType' => 'INNER'
        ]);
//        $this->belongsTo('BillingRegions', [
//            'foreignKey' => 'billing_region_id',
//            'joinType' => 'INNER'
//        ]);
//        $this->belongsTo('BillingCountries', [
//            'foreignKey' => 'billing_country_id',
//            'joinType' => 'INNER'
//        ]);
        $this->hasMany('AccessPoints', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Analytics', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Apps', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Beacons', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CampaignContent', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Campaigns', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('ChildCustomers', [
            'className' => 'Customers',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('CustomersContacts', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('CustomersNotes', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('DashboardWidgets', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Dashboards', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('FloorplansLibrary', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Geofences', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Groups', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Locations', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('MediaLibrary', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('FloorplansLibrary', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Redirects', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Surveys', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'customer_id'
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
            ->scalar('additional')
            ->maxLength('additional', 255)
            ->allowEmpty('additional');

        $validator
            ->integer('beacon_major')
            ->allowEmpty('beacon_major');

        $validator
            ->boolean('is_participating_retailer')
            ->requirePresence('is_participating_retailer', 'create')
            ->notEmpty('is_participating_retailer');

        $validator
            ->scalar('name')
            ->maxLength('name', 200)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('billing_street1')
            ->maxLength('billing_street1', 250)
            ->requirePresence('billing_street1', 'create')
            ->notEmpty('billing_street1');

        $validator
            ->scalar('billing_street2')
            ->maxLength('billing_street2', 150)
            ->allowEmpty('billing_street2');

        $validator
            ->scalar('billing_city')
            ->maxLength('billing_city', 60)
            ->requirePresence('billing_city', 'create')
            ->notEmpty('billing_city');

        $validator
            ->scalar('billing_postal_code')
            ->maxLength('billing_postal_code', 20)
            ->requirePresence('billing_postal_code', 'create')
            ->notEmpty('billing_postal_code');

        $validator
            ->scalar('main_phone')
            ->maxLength('main_phone', 50)
            ->requirePresence('main_phone', 'create')
            ->notEmpty('main_phone');

        return $validator;
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentCustomers'));
        $rules->add($rules->existsIn(['retailer_id'], 'Retailers'));
        $rules->add($rules->existsIn(['customer_types_id'], 'CustomerTypes'));
        //$rules->add($rules->existsIn(['billing_region_id'], 'BillingRegions'));
        //$rules->add($rules->existsIn(['billing_country_id'], 'BillingCountries'));

        return $rules;
    }
}
