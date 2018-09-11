<?php
namespace App\Model\Table;

//use Cake\ORM\Query;
//use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Types Model
 *
 */
class TypesTable extends Table
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

        //$this->setTable('types');
        $this->setDisplayField('name');
       // $this->setPrimaryKey('type_id');
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
            ->scalar('type_id')
            ->maxLength('type_id', 255)
            ->notEmpty('type_id');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->notEmpty('name','Debe ingresar un nombre');

        $validator
            ->scalar('description')
            ->maxLength('description', 4294967295)
            ->allowEmpty('description');

        return $validator;
    }
}
