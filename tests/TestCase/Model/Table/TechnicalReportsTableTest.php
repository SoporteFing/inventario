<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TechnicalReportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TechnicalReportsTable Test Case
 */
class TechnicalReportsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TechnicalReportsTable
     */
    public $TechnicalReports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.technical_reports',
        'app.assets',
        'app.types',
        'app.users',
        'app.locations',
        'app.loans',
        'app.residues',
        'app.internals'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TechnicalReports') ? [] : ['className' => TechnicalReportsTable::class];
        $this->TechnicalReports = TableRegistry::get('TechnicalReports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TechnicalReports);

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
