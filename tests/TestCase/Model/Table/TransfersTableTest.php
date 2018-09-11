<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransfersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransfersTable Test Case
 */
class TransfersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TransfersTable
     */
    public $Transfers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.transfers',
        'app.assets',
        'app.types',
        'app.users',
        'app.locations',
        'app.loans',
        'app.assets_transfers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Transfers') ? [] : ['className' => TransfersTable::class];
        $this->Transfers = TableRegistry::get('Transfers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Transfers);

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
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
