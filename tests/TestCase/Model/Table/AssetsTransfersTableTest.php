<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AssetsTransfersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AssetsTransfersTable Test Case
 */
class AssetsTransfersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AssetsTransfersTable
     */
    public $AssetsTransfers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.assets_transfers',
        'app.transfers',
        'app.assets',
        'app.types',
        'app.users',
        'app.locations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AssetsTransfers') ? [] : ['className' => AssetsTransfersTable::class];
        $this->AssetsTransfers = TableRegistry::get('AssetsTransfers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AssetsTransfers);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
