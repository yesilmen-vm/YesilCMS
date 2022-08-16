<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 */
class Migration_create_votes_logs extends CI_Migration
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
            'idaccount'  => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'idvote'     => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'points'     => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'lasttime'   => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'expired_at' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('votes_logs');
    }

    public function down()
    {
        $this->dbforge->drop_table('votes_logs');
    }
}
