<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_forum_topics extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'      => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'forums'  => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'title'   => array(
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ),
            'author'  => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'date'    => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'content' => array(
                'type' => 'TEXT',
                'null' => false
            ),
            'locked'  => array(
                'type'       => 'INT',
                'constraint' => '1',
                'unsigned'   => true,
                'default'    => '0'
            ),
            'pinned'  => array(
                'type'       => 'INT',
                'constraint' => '1',
                'unsigned'   => true,
                'default'    => '0'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('forum_topics');

        $data = array(
            'forums'  => '1',
            'title'   => 'Hello World!',
            'author'  => '1',
            'date'    => 1659139200,
            'content' => '<p>Welcome.</p>',
            'locked'  => '0',
            'pinned'  => '0',

        );
        $this->db->insert('forum_topics', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('forum_topics');
    }
}
