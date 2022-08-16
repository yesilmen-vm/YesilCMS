<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_create_forum_replies extends CI_Migration
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
            'topic'      => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'author'     => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'commentary' => array(
                'type' => 'TEXT'
            ),
            'date'       => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('forum_replies');
    }

    public function down()
    {
        $this->dbforge->drop_table('forum_replies');
    }
}
