<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_alter_pending_users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('pending_users', array(
            'key' => array(
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
                'after'      => 'joindate'
            ),
        ));
    }

    public function down()
    {
        $this->dbforge->drop_column('pending_users', 'key');
    }
}
