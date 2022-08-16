<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_users extends CI_Migration
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
            'username' => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ),
            'email'    => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ),
            'joindate' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'profile'  => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '1'
            ),
            'dp'       => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
            'vp'       => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('users');

        // Add Index
        $this->db->query('CREATE INDEX username_idx_ysl ON users (username);');
    }

    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}
