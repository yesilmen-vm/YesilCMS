<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_create_donate_logs extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'          => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true,
                'null'           => false
            ),
            'user_id'     => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'null'       => false
            ),
            'payment_id'  => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'hash'        => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'total'       => array(
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => false
            ),
            'points'      => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'null'       => false,
                'default'    => '0'
            ),
            'create_time' => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'status'      => array(
                'type'       => 'INT',
                'constraint' => '1',
                'unsigned'   => true,
                'null'       => false,
                'default'    => '0'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('donate_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('donate_logs');
    }
}
