<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_create_pending_users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'            => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'username'      => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'email'         => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'password'      => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'password_bnet' => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'expansion'     => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'joindate'      => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('pending_users');
    }

    public function down()
    {
        $this->dbforge->drop_table('pending_users');
    }
}
