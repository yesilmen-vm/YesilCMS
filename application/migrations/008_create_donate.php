<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_donate extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'     => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'name'   => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'price'  => array(
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => false
            ),
            'tax'    => array(
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'default'    => '0.00',
                'null'       => false
            ),
            'points' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'null'       => false
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('donate');
        $data = array(
            array('name' => 'Simple', 'price' => '10.00', 'tax' => '0.00', 'points' => '20'),
            array('name' => 'Normal', 'price' => '20.00', 'tax' => '2.00', 'points' => '22'),
            array('name' => 'Professional', 'price' => '30.00', 'tax' => '0.00', 'points' => '40'),
        );
        $this->db->insert_batch('donate', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('donate');
    }
}
