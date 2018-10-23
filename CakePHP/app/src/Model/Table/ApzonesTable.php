<?php
namespace App\Model\Table;

use App\Model\Entity\Apzone;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\ORM\D2goTable as Table;
use Cake\Validation\Validator;

/**
 * Apzones Model
 *
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 * @property \App\Model\Table\AccessPointsTable|\Cake\ORM\Association\BelongsTo $AccessPoints
 *
 * @method \App\Model\Entity\Apzone get($primaryKey, $options = [])
 * @method \App\Model\Entity\Apzone newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Apzone[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Apzone|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Apzone patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Apzone[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Apzone findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ApzonesTable extends Table
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

        $this->setTable('apzones');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('CounterCache', [
            'Locations' => ['apzones_count']
        ]);

        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('access_points', [
            'foreignKey' => 'accesspoint_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('scan_results', [
            'className' => 'ScanResults',
            'foreignKey' => 'apzone_id'
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
            ->requirePresence('accesspoint_id')
            ->notEmpty('accesspoint_id')
            ->numeric('accesspoint_id');

        $validator
            ->requirePresence('location_id')
            ->notEmpty('location_id')
            ->numeric('location_id');

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
        $rules->add($rules->existsIn(['location_id'], 'Locations'));
        $rules->add($rules->existsIn(['accesspoint_id'], 'access_points'));

        return $rules;
    }
}
