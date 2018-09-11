<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Myguests Model
 *
 * @method \App\Model\Entity\Myguest get($primaryKey, $options = [])
 * @method \App\Model\Entity\Myguest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Myguest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Myguest|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Myguest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Myguest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Myguest findOrCreate($search, callable $callback = null, $options = [])
 */
class MyguestsTable extends Table
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

        $this->setTable('myguests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 30)
            ->requirePresence('firstname', 'create')
            ->notEmpty('firstname');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 30)
            ->requirePresence('lastname', 'create')
            ->notEmpty('lastname');

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->dateTime('reg_date')
            ->requirePresence('reg_date', 'create')
            ->notEmpty('reg_date');

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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
