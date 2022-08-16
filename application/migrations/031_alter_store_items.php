<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_alter_store_items extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('store_items', array(
            'description' => array(
                'type'  => 'TEXT',
                'null'  => true,
                'after' => 'name'
            ),
            'price_type'  => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0',
                'after'      => 'type'
            ),
        ));

        $this->dbforge->modify_column('store_items', array(
            'price_dp' => array(
                'name'       => 'dp',
                'type'       => 'INT',
                'constraint' => '10',
                'default'    => '0',
                'unsigned'   => true
            ),
            'price_vp' => array(
                'name'       => 'vp',
                'type'       => 'INT',
                'constraint' => '10',
                'default'    => '0',
                'unsigned'   => true
            ),
            'qquery'   => array(
                'name' => 'command',
                'type' => 'TEXT',
                'null' => true
            ),
        ));

        $this->dbforge->drop_column('store_items', 'image');
        $this->dbforge->drop_column('store_items', 'itemid');
    }

    public function down()
    {
        $this->dbforge->drop_column('store_items', 'description');
        $this->dbforge->drop_column('store_items', 'price_type');

        $this->dbforge->modify_column('store_items', array(
            'dp'      => array(
                'name'       => 'price_dp',
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'vp'      => array(
                'name'       => 'price_vp',
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'command' => array(
                'name' => 'qquery',
                'type' => 'TEXT',
                'null' => true
            ),
        ));

        $this->dbforge->add_column('store_items', array(
            'itemid' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'after'      => 'price_vp'
            ),
            'image'  => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'item1.jpg',
                'after'      => 'icon'
            ),
        ));
    }
}
