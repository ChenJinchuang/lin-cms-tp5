<?php

use think\migration\Migrator;

class Book extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('book', array('engine' => 'InnoDB'));
        $table->addColumn('title', 'string', array('limit' => 50))
            ->addColumn('author', 'string', array('limit' => 30))
            ->addColumn('summary', 'string', array('limit' => 1000))
            ->addColumn('image', 'string', array('limit' => 50))
            ->addTimestamps('create_time', 'update_time')
            ->addSoftDelete()
            ->create();
    }
}
