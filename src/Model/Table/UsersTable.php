<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');

        $this->hasMany('ActivityLogs', [
            'foreignKey' => 'idUser',
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
            ->scalar('correo')
            ->maxLength('correo', 100)
            ->requirePresence('correo', 'create')
            ->requirePresence('correo', 'update')
            ->add('correo',[ 
                [
                'rule'=>['custom', ' /^.+@.+\.com$/ '],
                'message'=>'Debe seguir el formato: correo@ejemplo.com'
                ]
            ])
            ->notEmpty('correo','Este campo es requerido.');


        $validator
            ->scalar('nombre')
            ->maxLength('nombre', 50)
            ->requirePresence('nombre', 'create')
            ->requirePresence('nombre', 'update')
            ->add('nombre',[ 
                [
                'rule'=>['custom', ' /^[a-zA-ZÀ-ÖØ-öø-ÿ ]+$/ '],
                'message'=>'Debe contener sólo caracteres del alfabeto.'
                ]
            ])
            ->notEmpty('nombre','Este campo es requerido.');

        $validator
            ->scalar('apellido1')
            ->maxLength('apellido1', 50)
            ->requirePresence('apellido1', 'create')
            ->requirePresence('apellido1', 'update')
            ->add('apellido1',[ 
                [
                'rule'=>['custom', ' /^[a-zA-ZÀ-ÖØ-öø-ÿ ]+$/ '],
                'message'=>'Debe contener sólo caracteres del alfabeto.'
                ]
            ])
            ->notEmpty('apellido1','Este campo es requerido.');

        $validator
            ->scalar('apellido2')
            ->maxLength('apellido2', 50)
            ->requirePresence('apellido2', 'create')
            ->requirePresence('apellido2', 'update')
            ->add('apellido2',[ 
                [
                'rule'=>['custom', ' /^[a-zA-ZÀ-ÖØ-öø-ÿ ]+$/ '],
                'message'=>'Debe contener sólo caracteres del alfabeto.'
                ]
            ])
            ->notEmpty('apellido2','Este campo es requerido.');

        $validator
            ->scalar('id')
            ->maxLength('id', 15,'La identificación debe contener 15 dígitos máximo' )
            ->minLength('id', 9,'La identificación debe contener 9 dígitos mínimo. Asegúrese de incluir los 0 en su identificación.' )
            ->requirePresence('id', 'create')
            ->requirePresence('id', 'update')
            ->add('id',[ 
                [
                'rule'=>['custom', ' /^[a-zA-Z0-9]+$/ '],
                'message'=>'Debe contener sólo números o letras'
                ]
            ])
            ->notEmpty('id','Este campo es requerido');



        $validator
            ->scalar('username')
            ->maxLength('username', 100)
            ->requirePresence('username', 'create')
            ->requirePresence('username', 'update')
            ->notEmpty('username');

        $validator
            ->scalar('password')
            ->maxLength('password', 60)
            ->minLength('password', 8, 'La constraseña debe tener mínimo 8 caracteres')
            ->requirePresence('password', 'create')
            ->requirePresence('password', 'update')
            ->notEmpty('residues_id', 'La constraseña es requerida.');

        $validator
            ->scalar('password2')
            ->maxLength('password2', 60)
            ->requirePresence('password', 'create')
            ->requirePresence('password', 'update')
            ->notEmpty('residues_id', 'La confirmación de constraseña es requerida.');

        $validator
            ->integer('id_rol')
            ->requirePresence('id_rol', 'create')
            ->requirePresence('id_rol', 'update')
            ->notEmpty('id_rol');

        $validator
            ->boolean('account_status')
            ->requirePresence('account_status', 'create')
            ->requirePresence('account_status', 'update')
            ->notEmpty('account_status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /**public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));


        return $rules;
    }**/



    public function buildRules(RulesChecker $rules)
    {



        
    

        return $rules;
    }
    
}
