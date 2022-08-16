<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_alter_users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('users', array(
            'lastvisit' => array(
                'type'       => 'INT',
                'constraint' => '10',
                'default'    => '0',
                'after'      => 'joindate'
            ),
        ));
    }

    public function down()
    {
        $this->dbforge->drop_column('users', 'lastvisit');
    }
}
