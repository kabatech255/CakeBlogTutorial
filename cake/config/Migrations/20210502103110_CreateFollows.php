<?php

use Migrations\AbstractMigration;

class CreateFollows extends AbstractMigration
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
    $table = $this->table('follows');
    $table->addColumn('follow_id', 'integer', [
      'default' => null,
      'limit' => 11,
      'null' => false,
    ]);
    $table->addColumn('follower_id', 'integer', [
      'default' => null,
      'limit' => 11,
      'null' => false,
    ]);
    $table->addColumn('created', 'datetime', [
      'default' => null,
      'null' => false,
    ]);
    $table->addColumn('modified', 'datetime', [
      'default' => null,
      'null' => false,
    ]);
    $table->create();
  }
}
