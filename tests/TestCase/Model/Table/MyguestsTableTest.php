<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MyguestsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MyguestsTable Test Case
 */
class MyguestsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MyguestsTable
     */
    public $Myguests;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.myguests'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Myguests') ? [] : ['className' => MyguestsTable::class];
        $this->Myguests = TableRegistry::get('Myguests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Myguests);

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
