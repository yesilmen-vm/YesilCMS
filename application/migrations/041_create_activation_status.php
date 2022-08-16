<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_activation_status extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'           => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'null'           => false,
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'email'        => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ),
            'hash'         => array(
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'       => false
            ),
            'ip'           => array(
                'type'       => 'VARBINARY',
                'constraint' => '16',
                'default'    => null,
                'comment'    => 'Machine Readable v4/v6, requires > MySQL v5.6'
            ),
            'status'       => array(
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => null,
                'comment'    => '-1 = Expired, 0 = Not Used/Activated, 1 = Used/Activated'
            ),
            'requested_at' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'default'    => null,
                'unsigned'   => true,
            ),
            'activated_at' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'default'    => null,
                'unsigned'   => true,
            )
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('users_activation_status');

        // Add Index
        $this->db->query('CREATE INDEX idx_hash ON users_activation_status (hash);');
    }

    public function down()
    {
        $this->dbforge->drop_table('users_activation_status');
    }
}
