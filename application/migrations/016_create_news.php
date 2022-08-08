<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property mixed $dbforge
 * @property mixed $db
 */
class Migration_create_news extends CI_Migration
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
                'type' => 'TEXT',
                'null' => false
            ),
            'image'       => array(
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ),
            'date'        => array(
                'type'       => 'INT',
                'constraint' => '10',
                'unsigned'   => true,
                'default'    => '0'
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('news');
        $data = array(
            array(
                'title'       => 'Welcome to your new playground.',
                'description' => '<p>This version has been developed by <a href="https://discord.com/users/377721579615813632">Yesilmen</a> over the latest version of BlizzCMS and includes <strong>vMANGoS compability</strong> and <strong>new features.</strong></p><p>Some of the new features and improvements can be seen below;</p><ul><li>Complete VMaNGOS compability.</li><li>New installation script that directs the user based on OS/Environment.</li><li>Tweaks to work on multiple Web Servers including Apache/Nginx/IIS.</li><li>Redis caching for *nix operating systems.</li><li>Functioning&nbsp;<a href="https://www.google.com/recaptcha/admin/create">reCAPTCHA</a>.</li><li>New lightweight dark theme.</li><li>Brand new customizable armory.<ul><li>Base character info,</li><li>3D Model Viewer (Fast: Uses plain displayID, Detailed: Converts old displayID to Classic displayID using Classic\'s DBC. You can also create a separate table instead of remote call.),</li><li>Dynamic Base Stats,</li><li>Primary &amp; Secondary Professions,</li><li>PvP Stats,</li><li>Ability to show enchants on items (by using WoWHead\'s tooltip instead of ClassicDB),</li><li>Ability to show all character stats instead of just base-ones.</li></ul></li><li>Rest API implementation for future developments.</li><li>Built-in account activation.</li><li>Built-in account recovery.</li><li>Built-in dynamic CSRF protection on each page.</li><li>Tweaked Admin Panel. (SMTP tester, handlers and logs etc.)</li><li>On-the-fly downloadable Realmlist.</li><li>Bug fixes and improvements.</li><li>and more...</li></ul><p>It still requires a lot of changes and effort, this is the part where you step in! :-)</p>',
                'image'       => 'news_default.jpg',
                'date'        => '1659139200'
            ),
        );
        $this->db->insert_batch('news', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('news');
    }
}
