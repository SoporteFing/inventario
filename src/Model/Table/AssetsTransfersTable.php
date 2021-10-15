<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AssetsTransfers Model
 *
 * @property \App\Model\Table\TransfersTable|\Cake\ORM\Association\BelongsTo $Transfers
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 *
 * @method \App\Model\Entity\AssetsTransfer get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssetsTransfer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssetsTransfer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssetsTransfer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssetsTransfer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssetsTransfer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssetsTransfer findOrCreate($search, callable $callback = null, $options = [])
 */
class AssetsTransfersTable extends Table
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

        $this->setTable('assets_transfers');
        $this->setDisplayField('transfers_id');
        $this->setPrimaryKey(['transfer_id', 'assets_id']);

        $this->belongsTo('Transfers', [
            'foreignKey' => 'transfer_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Assets', [
            'foreignKey' => 'assets_id',
            'joinType' => 'INNER'
        ]);
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
        $rules->add($rules->existsIn(['transfer_id'], 'Transfers'));
        $rules->add($rules->existsIn(['assets_id'], 'Assets'));

        return $rules;
    }
}
