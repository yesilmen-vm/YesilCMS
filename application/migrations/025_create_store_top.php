<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_create_store_top extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'       => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'id_store' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('store_top');
    }

    public function down()
    {
        $this->dbforge->drop_table('store_top');
    }
}
