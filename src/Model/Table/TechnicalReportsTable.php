<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TechnicalReports Model
 *
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 * @property \App\Model\Table\ResiduesTable|\Cake\ORM\Association\BelongsTo $Residues
 * @property \App\Model\Table\InternalsTable|\Cake\ORM\Association\BelongsTo $Internals
 *
 * @method \App\Model\Entity\TechnicalReport get($primaryKey, $options = [])
 * @method \App\Model\Entity\TechnicalReport newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TechnicalReport[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TechnicalReport|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TechnicalReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TechnicalReport[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TechnicalReport findOrCreate($search, callable $callback = null, $options = [])
 */
class TechnicalReportsTable extends Table
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

        $this->setTable('technical_reports');
        $this->setDisplayField('technical_report_id');
        $this->setPrimaryKey('technical_report_id');

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

        $this->belongsTo('Assets', [
            'foreignKey' => 'assets_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Residues', [
            'foreignKey' => 'residues_id'
        ]);
        $this->belongsTo('Internals', [
            'foreignKey' => 'internal_id'
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
            ->integer('technical_report_id')
            ->allowEmpty('technical_report_id', 'create');

        $validator
            ->scalar('assets_id')
            ->maxLength('assets_id', 255)
            ->notEmpty('assets_id', 'create');

        $validator
            ->scalar('evaluation')
            ->maxLength('evaluation', 500)
            ->requirePresence('evaluation', 'create')
            ->notEmpty('evaluation');

        $validator
            ->scalar('recommendation')
            ->maxLength('recommendation', 1)
            ->requirePresence('recommendation', 'create')
            ->notEmpty('recommendation');

        $validator
            ->date('date')
            ->notEmpty('date');

        $validator
            ->maxLength('file_name', 200)
            ->allowEmpty('file_name');

        $validator
            ->scalar('path')
            ->maxLength('path', 200)
            ->allowEmpty('path');

        $validator
            ->scalar('evaluator_name')
            ->maxLength('evaluator_name', 100)
            ->notEmpty('evaluator_name');

        $validator
            ->scalar('year')
            ->maxLength('year', 4)
            ->allowEmpty('year');

        $validator
            ->scalar('facultyInitials')
            ->maxLength('facultyInitials', 20)
            ->allowEmpty('facultyInitials');

        $validator
            ->boolean('descargado')
            ->allowEmpty('descargado');

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
        $rules->add($rules->existsIn(['assets_id'], 'Assets'));
        $rules->add($rules->existsIn(['residues_id'], 'Residues'));
        //$rules->add($rules->existsIn(['internal_id'], 'Internals'));

        return $rules;
    }
}
