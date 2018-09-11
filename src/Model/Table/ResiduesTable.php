<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


/**
 * Residues Model
 *
 * @method \App\Model\Entity\Residue get($primaryKey, $options = [])
 * @method \App\Model\Entity\Residue newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Residue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Residue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Residue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Residue[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Residue findOrCreate($search, callable $callback = null, $options = [])
 */
class ResiduesTable extends Table
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

        $this->setTable('residues');
        $this->setDisplayField('residues_id');
        $this->setPrimaryKey('residues_id');
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'file' => [
                'fields' => [
                    'dir' => 'file_dir',
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
            ->scalar('residues_id')
            ->maxLength('residues_id', 200)
            ->alphaNumeric('residues_id', 'El número de autorización debe contener solo caracteres alfanuméricos.')
            ->notEmpty('residues_id', 'El número de autorización es requerido.');

        $validator
            ->scalar('name1')
            ->maxLength('name1', 50)
            ->requirePresence('name1', 'create')
            ->add('name1',[ 
                [
                'rule'=>['custom', ' /^[a-zA-ZÀ-ÖØ-öø-ÿ ]+$/ '],
                'message'=>'Debe contener sólo caracteres del alfabeto.'
                ]
            ])
            ->notEmpty('name1','Este campo es requerido.');

        $validator
            ->scalar('identification1')
            ->maxLength('identification1', 9,'La cédula debe contener 9 dígitos' )
            ->minLength('identification1', 9,'La cédula debe contener 9 dígitos' )
            ->numeric('identification1','La cédula debe contener sólo digitos')
            ->requirePresence('identification1', 'create')
            ->notEmpty('identification1','Este campo es requerido');

        $validator
            ->scalar('name2')
            ->maxLength('name2', 50)
            ->requirePresence('name2', 'create')            
            ->add('name2',[ 
                [
                'rule'=>['custom', ' /^[a-zA-ZÀ-ÖØ-öø-ÿ ]+$/ '],
                'message'=>'Debe contener sólo caracteres del alfabeto.'
                ]
            ])
            ->notEmpty('name2','Este campo es requerido');

        $validator
            ->scalar('identification2')
            ->requirePresence('identification2', 'create')
            ->numeric('identification2','La cédula debe contener sólo digitos')
            ->maxLength('identification2', 9,'La cédula debe contener 9 dígitos' )
            ->minLength('identification2', 9,'La cédula debe contener 9 dígitos' )
            ->notEmpty('identification2','Este campo es requerido');

        $validator
            ->date('date','dmy', 'Formato de fecha no válido.')
            ->notEmpty('date','Este campo es requerido');

        $validator
            ->boolean('descargado')
            ->allowEmpty('descargado');

        $validator
            ->maxLength('file', 255)
            ->allowEmpty('file');

        $validator
            ->scalar('file_dir')
            ->maxLength('file_dir', 255)
            ->allowEmpty('file_dir');

        return $validator;
    }


}
