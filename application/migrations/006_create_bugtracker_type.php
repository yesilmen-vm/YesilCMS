<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_bugtracker_type extends CI_Migration
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
        $this->dbforge->create_table('bugtracker_type');
        $data = array(
            array('title' => 'Achievements'),
            array('title' => 'Battle Pets'),
            array('title' => 'Battlegrounds - Arena'),
            array('title' => 'Classes'),
            array('title' => 'Creatures'),
            array('title' => 'Exploits/Usebugs'),
            array('title' => 'Garrison'),
            array('title' => 'Guilds'),
            array('title' => 'Instances'),
            array('title' => 'Items'),
            array('title' => 'Other'),
            array('title' => 'Professions'),
            array('title' => 'Quests'),
            array('title' => 'Website'),
        );
        $this->db->insert_batch('bugtracker_type', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('bugtracker_type');
    }
}
