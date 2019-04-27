<?php

use think\migration\Migrator;
use think\migration\db\Column;

class LinLog extends Migrator
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
        $table = $this->table('lin_log', array('engine' => 'InnoDB'));
        $table->addColumn('message', 'string', array('limit' => 450))
            ->addTimestamps('time')
            ->addColumn('user_id', 'integer', array('limit' => 11))
            ->addColumn('user_name', 'string', array('limit' => 20))
            ->addColumn('status_code', 'integer', array('limit' => 11))
            ->addColumn('method', 'string', array('limit' => 20))
            ->addColumn('path', 'string', array('limit' => 50))
            ->addColumn('authority', 'string', array('limit' => 100, 'null' => 'null'))
            ->create();
    }
}
