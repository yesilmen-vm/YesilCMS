<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_forum extends CI_Migration
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
            'name'        => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'category'    => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'null'       => false
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => false
            ),
            'icon'        => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false
            ),
            'type'        => array(
                'type'       => 'INT',
                'constraint' => '1',
                'unsigned'   => true,
                'comment'    => '1 = everyone | 2 = staff | 3 = staff post + everyone see',
                'null'       => false,
                'default'    => '1'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('forum');

        $data = array(
            array('name' => 'Announcements', 'category' => '1', 'description' => 'Anything related about the server', 'icon' => 'forums/forum_005.png', 'type' => '3'),
            array('name' => 'Help & Support', 'category' => '1', 'description' => 'You can get all the support related to your account here.', 'icon' => 'forums/forum_016.png', 'type' => '1'),
            array('name' => 'General', 'category' => '2', 'description' => 'General section about anything related with game', 'icon' => 'forums/forum_010.png', 'type' => '3')
        );
        $this->db->insert_batch('forum', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('forum');
    }
}
