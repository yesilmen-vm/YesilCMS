<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_create_store_categories extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'   => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'name' => array(
                'type'       => 'VARCHAR',
                'constraint' => '150'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('store_categories');
    }

    public function down()
    {
        $this->dbforge->drop_table('store_categories');
    }
}
