<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_create_store_items extends CI_Migration
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
            'name'     => array(
                'type'       => 'VARCHAR',
                'constraint' => '150'
            ),
            'category' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'null'       => false
            ),
            'type'     => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'null'       => false
            ),
            'price_dp' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'price_vp' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'itemid'   => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'icon'     => array(
                'type'       => 'VARCHAR',
                'constraint' => '255'
            ),
            'image'    => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'item1.jpg'
            ),
            'qquery'   => array(
                'type' => 'TEXT',
                'null' => true
            )

        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('store_items');
    }

    public function down()
    {
        $this->dbforge->drop_table('store_items');
    }
}
