<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_ranks_default extends CI_Migration
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
            'comment' => array(
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('ranks_default');
        $data = array(
            array('comment' => 'Rank Admin'),
            array('comment' => 'Rank Visitor'),
            array('comment' => 'Rank User'),
        );
        $this->db->insert_batch('ranks_default', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('ranks_default');
    }
}
