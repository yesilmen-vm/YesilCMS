<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_slides extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'          => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'title'       => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => false
            ),
            'type'        => array(
                'type'       => 'INT',
                'constraint' => '1',
                'unsigned'   => true
            ),
            'route'       => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('slides');

        $data = array(
            array(
                'title'       => 'YesilCMS - Welcome to your website.',
                'description' => 'YesilCMS is fully compatible with vMaNGOS and has many new features such as Armory, Account Activation, Password Recovery, API infrastructure and more.',
                'type'        => '1',
                'route'       => 's-1.jpg'
            ),
            array('title' => 'YesilCMS - Slide Page 2', 'description' => 'Showcase for multi-page slideshow.', 'type' => '1', 'route' => 's-2.jpg'),
        );
        $this->db->insert_batch('slides', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('slides');
    }
}
