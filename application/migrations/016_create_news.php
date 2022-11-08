<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
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
            array(
                'title'       => 'YesilCMS is now much more powerful.',
                'description' => '<p>Many improvements and features have been added with the latest version.</p><p>Some of them can be listed as;</p><ul dir="auto"><li><strong>Brand new&nbsp;built-in database viewer.</strong><ul dir="auto"><li>Progressive database search (1.2 to 1.12)</li><li>Item search with all related data.</li><li>Spell search with all related data and dev-required data.</li><li>Object, Creature and Quest page is in WIP state.</li></ul></li><li><strong>Brand new&nbsp;customizable armory.</strong><ul dir="auto"><li>Base character info</li><li>3D Model Viewer (Fast: Uses plain&nbsp;displayID, Detailed: Converts old&nbsp;displayID&nbsp;to Classic&nbsp;displayID&nbsp;using Classic\'s DBC. You can also create a separate table instead of remote call.)</li><li>Dynamic Base Stats</li><li>Progressive Armory (1.2 to 1.12 can be selected by user as well)</li><li>Primary &amp; Secondary Professions</li><li>PvP Stats</li><li>Ability to show enchants on items (by using WoWHead\'s tooltip instead of ClassicDB)</li><li>Ability to show all character stats instead of just base-ones</li></ul></li><li><strong>Brand new&nbsp;PvP Page</strong><ul dir="auto"><li>All pvp data that player may want to see.</li><li>Wide filtering option.<ul dir="auto"><li>Ability to filter by All Time and Last Week</li><li>Ability to filter by Faction</li><li>Ability to filter by specific name</li></ul></li></ul></li><li><strong>Unique&nbsp;Timeline Module&nbsp;with responsive design and full flexibility.</strong><ul dir="auto"><li>Ability to add any patch on choice (including custom ones)</li><li>Ability to order automatically or custom regardless of date</li><li>Separated Description, General, PvE and PvP sections better for maintainability.</li><li>Ability to add unique image for each patch.</li></ul></li></ul><p>The project is at the very beginning of the road and there are unlimited options that can be implemented. You can also contribute <a title="YesilCMS Github Page" href="https://github.com/yesilmen-vm/YesilCMS/" target="_blank" rel="noopener">here</a>!</p>',
                'image'       => 'news_default_2.jpg',
                'date'        => '1667908800'
            )
        );
        $this->db->insert_batch('news', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('news');
    }
}
