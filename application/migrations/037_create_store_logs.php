<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_create_store_logs extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'         => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'accountid'  => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'charid'     => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'item_name'  => array(
                'type'       => 'VARCHAR',
                'constraint' => '150'
            ),
            'type'       => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
            'price_type' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
            'dp'         => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
            'vp'         => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
            'date'       => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('store_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('store_logs');
    }
}
