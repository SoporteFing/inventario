<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ResiduesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ResiduesTable Test Case
 */
class ResiduesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ResiduesTable
     */
    public $Residues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.residues'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Residues') ? [] : ['className' => ResiduesTable::class];
        $this->Residues = TableRegistry::get('Residues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Residues);

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
