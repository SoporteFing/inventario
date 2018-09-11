<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssetsTransfersFixture
 *
 */
class AssetsTransfersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'transfer_id' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'assets_id' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'FK_AssetsId' => ['type' => 'index', 'columns' => ['assets_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['transfer_id', 'assets_id'], 'length' => []],
            'FK_AssetsId' => ['type' => 'foreign', 'columns' => ['assets_id'], 'references' => ['assets', 'plaque'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'FK_TransferId' => ['type' => 'foreign', 'columns' => ['transfer_id'], 'references' => ['transfers', 'transfers_id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'transfer_id' => 'e6aaa779-db6a-4549-8183-3e1d2fdd9e35',
            'assets_id' => '0e4a807c-0c49-4f64-860d-fd9b9aa867bc'
        ],
    ];
}
