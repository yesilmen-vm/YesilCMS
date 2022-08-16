<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_create_realms extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'               => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'hostname'         => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => '127.0.0.1'
            ),
            'username'         => array(
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false
            ),
            'password'         => array(
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true
            ),
            'char_database'    => array(
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false
            ),
            'realmID'          => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'console_hostname' => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => '127.0.0.1'
            ),
            'console_username' => array(
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false
            ),
            'console_password' => array(
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false
            ),
            'console_port'     => array(
                'type'       => 'INT',
                'constraint' => '6',
                'unsigned'   => true,
                'default'    => '7878'
            ),
            'emulator'         => array(
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'TC'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('realms');
    }

    public function down()
    {
        $this->dbforge->drop_table('realms');
    }
}
