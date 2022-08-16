<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_forum_category extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'   => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'name' => array(
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('forum_category');

        $data = array(
            array('name' => 'Official Information'),
            array('name' => 'Community')
        );
        $this->db->insert_batch('forum_category', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('forum_category');
    }
}
