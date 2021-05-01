<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArticleCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArticleCategoriesTable Test Case
 */
class ArticleCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ArticleCategoriesTable
     */
    public $ArticleCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ArticleCategories',
        'app.Articles',
        'app.Categories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ArticleCategories') ? [] : ['className' => ArticleCategoriesTable::class];
        $this->ArticleCategories = TableRegistry::getTableLocator()->get('ArticleCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ArticleCategories);

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
