<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge $dbforge
 */
class Migration_alter_store_top extends CI_Migration
{
    public function up()
    {
        $this->dbforge->modify_column('store_top', array(
            'id_store' => array(
                'name'       => 'store_item',
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'unique'     => true
            ),
        ));
    }

    public function down()
    {
        $this->dbforge->modify_column('store_top', array(
            'store_item' => array(
                'name'       => 'id_store',
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'unique'     => false
            ),
        ));
    }
}
