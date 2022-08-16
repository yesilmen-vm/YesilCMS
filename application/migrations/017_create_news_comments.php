<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_create_news_comments extends CI_Migration
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
            'id_new'     => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
            'commentary' => array(
                'type' => 'TEXT',
                'null' => false
            ),
            'date'       => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
            'author'     => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('news_comments');
    }

    public function down()
    {
        $this->dbforge->drop_table('news_comments');
    }
}
