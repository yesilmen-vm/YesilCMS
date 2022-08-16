<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_bugtracker extends CI_Migration
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
            'url'         => array(
                'type' => 'TEXT'
            ),
            'status'      => array(
                'type'       => 'INT',
                'constraint' => '1',
                'unsigned'   => true,
                'default'    => '1'
            ),
            'type'        => array(
                'type'       => 'INT',
                'constraint' => '1',
                'default'    => '1'
            ),
            'priority'    => array(
                'type'       => 'INT',
                'constraint' => '1',
                'unsigned'   => true,
                'default'    => '1'
            ),
            'date'        => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'author'      => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true
            ),
            'close'       => array(
                'type'       => 'INT',
                'constraint' => '1',
                'unsigned'   => true,
                'default'    => '0'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('bugtracker');

        $data = array(
            'title'       => 'Battle Chicken Does Not Despawn',
            'description' => '<p>Gnomish Battle Chicken does not depsawn after 1.50 min and stays up until its dead.</p><p>&nbsp;</p><p><strong><span style="font-size: 14pt;">Expected behavior</span></strong></p><ul><li>It should be up for 1.50 min or until its destroyed.</li></ul><p><strong><span style="font-size: 14pt;">Steps to reproduce</span></strong></p><ul><li>Learn Engineering, adjust skill &gt;= 230</li><li>Add Gnomish Battle Chicken into inventory (10725) and equip</li><li>Use it and wait for 1.50 min</li></ul>',
            'status'      => '8',
            'type'        => '2',
            'priority'    => '1',
            'date'        => '1659139200',
            'author'      => '1'
        );
        $this->db->insert('bugtracker', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('bugtracker');
    }
}
