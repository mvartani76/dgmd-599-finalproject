<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\ORM\D2goTable as Table;
use Cake\Validation\Validator;

/**
 * Heatmaps Model
 *
 * @property \App\Model\Table\AccessPointsTable|\Cake\ORM\Association\BelongsTo $AccessPoints
 * @property \App\Model\Table\FloorplansLibraryTable|\Cake\ORM\Association\BelongsTo $FloorplansLibrary
 *
 * @method \App\Model\Entity\Heatmap get($primaryKey, $options = [])
 * @method \App\Model\Entity\Heatmap newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Heatmap[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Heatmap|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Heatmap patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Heatmap[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Heatmap findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HeatmapsTable extends Table
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

        $this->setTable('heatmaps');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AccessPoints', [
            'foreignKey' => 'accesspoint_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('FloorplansLibrary', [
            'foreignKey' => 'floorplan_id',
            'joinType' => 'INNER'
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
            ->requirePresence('x', 'create')
            ->notEmpty('x');

        $validator
            ->requirePresence('y', 'create')
            ->notEmpty('y');

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
        $rules->add($rules->existsIn(['accesspoint_id'], 'AccessPoints'));
        $rules->add($rules->existsIn(['floorplan_id'], 'FloorplansLibrary'));

        return $rules;
    }
}
