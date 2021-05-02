<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FollowsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FollowsTable Test Case
 */
class FollowsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FollowsTable
     */
    public $Follows;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Follows',
        'app.Follows',
        'app.Followers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Follows') ? [] : ['className' => FollowsTable::class];
        $this->Follows = TableRegistry::getTableLocator()->get('Follows', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Follows);

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
