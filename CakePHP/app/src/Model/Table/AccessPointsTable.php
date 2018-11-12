<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\ORM\D2goTable as Table;
use Cake\Validation\Validator;

/**
 * AccessPoints Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 *
 * @method \App\Model\Entity\AccessPoint get($primaryKey, $options = [])
 * @method \App\Model\Entity\AccessPoint newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AccessPoint[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AccessPoint|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccessPoint patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AccessPoint[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AccessPoint findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccessPointsTable extends Table
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

        $this->setTable('access_points');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('scan_results', [
            'foreignKey' => 'accesspoint_id'
        ]);
        
        $this->hasOne('Apzones', [
            'foreignKey' => 'accesspoint_id'
        ]);

        $this->belongsTo('Customers', [
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
            ->requirePresence('mac_addr', 'create')
            ->notEmpty('mac_addr')
            ->add('mac_addr', 'validLength', [
                    'rule' => [$this, 'isValidMacAddrLength'],
                    'message' => 'Make sure MAC Address is 12 characters'])
            ->add('mac_addr', 'validChars', [
                    'rule' => [$this, 'isValidMacAddrChars'],
                    'message' => 'Make sure MAC Address contains only characters 0-9 and a-f']);
            
        $validator
            ->allowEmpty('total_devices_count');

        $validator
            ->allowEmpty('total_unique_devices_count');

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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));

        return $rules;
    }

    /**
     * Function to use in validator that checks to see if the 
     * entered MAC Address input is 12 characters.
     */
    public function isValidMacAddrLength($value, $context)
    {
        if (strlen($value) != 12) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * Function to use in validator that checks to make sure that
     * only hexadecimal characters are entered. Hex characters are
     * abcdef and 0123456789.
     */
    public function isValidMacAddrChars($value, $context)
    {
        if (!preg_match("/[^abcdef\d]/",$value)) {
            return true;
        }
        else {
            return false;
        }
    }
}
