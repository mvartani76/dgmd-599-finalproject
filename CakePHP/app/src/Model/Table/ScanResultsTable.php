<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\ORM\D2goTable as Table;
use Cake\Validation\Validator;

/**
 * ScanResults Model
 *
 * @property \App\Model\Table\AccessPointsTable|\Cake\ORM\Association\BelongsTo $Accesspoints
 *
 * @method \App\Model\Entity\ScanResult get($primaryKey, $options = [])
 * @method \App\Model\Entity\ScanResult newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ScanResult[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ScanResult|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ScanResult patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ScanResult[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ScanResult findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ScanResultsTable extends Table
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

        $this->setTable('scan_results');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('access_points', [
            'foreignKey' => 'accesspoint_id'
            'joinType' => 'LEFT',
        ]);
        $this->belongsTo('Apzones', [
            'foreignKey' => 'apzone_id',
            'joinType' => 'LEFT',
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
            ->dateTime('scan_timestamp')
            ->allowEmpty('scan_timestamp');

        $validator
            ->scalar('mac_addr')
            ->maxLength('mac_addr', 12)
            ->allowEmpty('mac_addr');

        $validator
            ->allowEmpty('rssi');

        $validator
            ->scalar('vendor')
            ->maxLength('vendor', 128)
            ->allowEmpty('vendor');

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
        $rules->add($rules->existsIn(['accesspoint_id'], 'access_points'));

        return $rules;
    }
}
