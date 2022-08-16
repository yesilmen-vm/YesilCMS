<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_bugtracker_priority extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'    => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'title' => array(
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('bugtracker_priority');
        $data = array(
            array('title' => 'High'),
            array('title' => 'Medium'),
            array('title' => 'Low'),
        );
        $this->db->insert_batch('bugtracker_priority', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('bugtracker_priority');
    }
}
