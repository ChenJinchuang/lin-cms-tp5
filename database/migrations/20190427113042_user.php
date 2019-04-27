<?php

use think\migration\Migrator;

class User extends Migrator
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
        $table = $this->table('lin_user', array('engine' => 'InnoDB'));
        $table->addColumn('nickname', 'string', array('limit' => 24))
            ->addColumn('password', 'string', array('limit' => 100))
            ->addColumn('email', 'string', array('limit' => 100))
            ->addColumn('admin', 'integer', array('limit' => 6, 'default' => 1))
            ->addColumn('active', 'integer', array('limit' => 6,'default' => 1))
            ->addColumn('group_id', 'integer', array('limit' => 11, 'null' => 'null'))
            ->addTimestamps('create_time', 'update_time')
            ->addSoftDelete()
            ->addIndex(array('nickname', 'email'))
            ->create();
    }
}
