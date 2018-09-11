<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transfers Model
 *
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsToMany $Assets
 *
 * @method \App\Model\Entity\Transfer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Transfer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Transfer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Transfer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transfer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Transfer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Transfer findOrCreate($search, callable $callback = null, $options = [])
 */
class TransfersTable extends Table
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

        $this->setTable('transfers');
        $this->setDisplayField('transfers_id');
        $this->setPrimaryKey('transfers_id');
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'file_name' => [
                'fields' => [
                    'dir' => 'path',
                    'size' => 'file_size',
                    'type' => 'file_type',
                ],
                'path' => 'webroot{DS}files{DS}{model}{DS}{field}{DS}}{primaryKey}{DS}',
                'nameCallback' => function ($table, $entity, $data, $field, $settings) {
                    return strtolower($data['name']);
                },

                'keepFilesOnDelete' => false
            ]
        ]);

        $this->belongsToMany('Assets', [
            'foreignKey' => 'transfer_id',
            'targetForeignKey' => 'asset_id',
            'joinTable' => 'assets_transfers'
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
            ->scalar('transfers_id')
            ->maxLength('transfers_id', 100)
            ->alphaNumeric('transfers_id', 'El número de autorización debe contener solo caracteres alfanuméricos.')
            ->notEmpty('transfers_id', 'El número de autorización es requerido.');


        $validator
            ->date('date','ymd', 'Formato de fecha no válido.')
            ->notEmpty('date','Este campo es requerido');

        $validator
            ->scalar('functionary')
            ->maxLength('functionary', 100)
            ->requirePresence('functionary', 'create')
            ->notEmpty('functionary');

        $validator
            ->scalar('identification')
            ->maxLength('identification', 9,'La cédula debe contener 9 dígitos' )
            ->minLength('identification', 9,'La cédula debe contener 9 dígitos' )
            ->numeric('identification','La cédula debe contener sólo digitos')
            ->requirePresence('identification', 'create')
            ->notEmpty('identification','Este campo es requerido');

        $validator
            ->scalar('functionary_recib')
            ->maxLength('functionary_recib', 100)
            ->requirePresence('functionary_recib', 'create')
            ->add('functionary_recib',[ 
                [
                'rule'=>['custom', ' /^[a-zA-ZÀ-ÖØ-öø-ÿ ]+$/ '],
                'message'=>'Debe contener sólo caracteres del alfabeto.'
                ]
            ])
            ->notEmpty('functionary_recib','Este campo es requerido');

            $validator
            ->scalar('identification_recib')
            ->maxLength('identification_recib', 9,'La cédula debe contener 9 dígitos' )
            ->minLength('identification_recib', 9,'La cédula debe contener 9 dígitos' )
            ->numeric('identification_recib','La cédula debe contener sólo digitos')
            ->notEmpty('identification_recib','Este campo es requerido');
            

        $validator
            ->scalar('Acade_Unit_recib')
            ->maxLength('Acade_Unit_recib', 30)
            ->notEmpty('Acade_Unit_recib','Este campo es requerido');

        $validator
            ->scalar('path')
            ->maxLength('path', 100)
            ->allowEmpty('path');

        $validator
            ->maxLength('file_name', 100)
            ->allowEmpty('file_name');

        $validator
            ->boolean('descargado')
            ->allowEmpty('descargado');

        return $validator;
    }
}
