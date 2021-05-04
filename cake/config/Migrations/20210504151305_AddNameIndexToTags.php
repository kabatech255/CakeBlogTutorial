<?php
use Migrations\AbstractMigration;

class AddNameIndexToTags extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('tags');
        $table->addIndex([
            'name',
        ], [
            'name' => 'BY_NAME',
            'unique' => true,
        ]);
        $table->update();
    }
}
