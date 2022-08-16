<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_alter_store_categories extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('store_categories', array(
            'route'   => array(
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
                'unique'     => true,
                'after'      => 'name'
            ),
            'realmid' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0',
                'after'      => 'route'
            ),
        ));
    }

    public function down()
    {
        $this->dbforge->drop_column('store_categories', 'route');
        $this->dbforge->drop_column('store_categories', 'realmid');
    }
}
