<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_changelogs extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'          => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'title'       => array(
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ),
            'description' => array(
                'type' => 'TEXT'
            ),
            'date'        => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            )
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('changelogs');

        $data = array(
            'title'       => 'Battle Chicken Does Not Despawn',
            'description' => '<p>This CMS is tested with vMaNGOS Latest Stable Build - 19bc922ec4db0b2ee2a864bd3eae1d14991ed135</p>',
            'date'        => '1659139200',
        );
        $this->db->insert('changelogs', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('changelogs');
    }
}
