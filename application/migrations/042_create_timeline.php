<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_timeline extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id'          => array(
                'type'           => 'INT',
                'constraint'     => '10',
                'null'           => false,
                'unsigned'       => true,
                'auto_increment' => true
            ),
            'description' => array(
                'type'    => 'JSON',
                'null'    => false,
                'comment' => 'JSON for MySQL, LONGTEXT for MariaDB'
            ),
            'patch'       => array(
                'type'       => 'VARCHAR',
                'constraint' => '16',
                'null'       => false
            ),
            'date'        => array(
                'type'    => 'DATE',
                'default' => false,
                'comment' => 'YYYY-MM-DD'
            ),
            'image'       => array(
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false
            ),
            'order'       => array(
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 1,
                'null'       => false,
                'unsigned'   => true,
            )
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('timeline');

        $data = array(
            array(
                'description' => '{"description":"<p><strong>Patch 1.1.0 was the first version<\/strong> of the game during the North America release of World of Warcraft.<\/p>\r\n<p>Racial traits are now available for all races. Each race receives at least 4 traits (several passive and at least one active trait per race). Undead racial traits have changed to be more consistent with the new traits and Undead players are now considered Humanoid targets rather than Undead targets.<\/p>\r\n<p><strong>New Raid Encounters<\/strong><\/p>\r\n<p>Rumors of Onyxia, an enormous black dragon, have been heard through out Azeroth. Be sure to bring many brave warriors for she won\'t take kindly to intruders in her lair. Both factions will need to complete unique, and challenging quests to gain access to the Onyxia encounter. **During the beta testing there will be a temporary placeholder vendor who sells keys to Onyxia\'s lair for testing purposes.<\/p>\r\n<p>Discovered in the heart of Blackrock Mountain beyond the Depths, lies the Molten Core. Within the Molten Core lives a multitude of ancient and powerful evil. Adventurers be ware, for the dangers found within the Molten Core are many and takes many forms.<\/p>\r\n<p>**Raid loot is being worked on and temporary loot has been placed in raid encounters<\/p>","general":[""],"pve":["Scholomance, a level 57-60 instanced dungeon in Western Plaguelands is now open.","Ragefire Chasm, a low level instance dungeon in Orgrimmar, is now available."],"pvp":["Mind Control and Hunter\'s Mark flag you for PvP when used on enemy players.","Healing and buffing NPCs will not flag you for PvP unless those NPCs are in combat.","NPCs no longer tap creatures they are fighting."]}',
                'patch'       => '1.1.0',
                'date'        => '2004-11-07',
                'image'       => 'cc5c52b0d41717547bf1d9b9ccb74135.jpg',
                'order'       => 1
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.2.0: Mysteries of Maraudon<\/strong> was released on December 18, 2004.<\/p>\r\n<p><strong>Happy Holidays!<\/strong><\/p>\r\n<p>The holidays are here and the citizens of Azeroth are celebrating the occasion with festive decorations and winter time activities. Keep an eye out for some fun and exciting surprises throughout the world!<\/p>","general":["Players will now receive credit for killing a monster even if they die during battle.","Players will now be able to purchase and train mounts from other races in their faction. However, you will first need to obtain \"Exalted\" reputation status with that race in order to do so. Mounts of the opposing faction are not available for purchase.","A \"Looking for Group\" channel has been added. Additional improvements in looking for group functionality will be added in future patches."],"pve":["New Dungeon: Maraudon","If you die in Molten Core, you will now be able to retrieve your corpse at the Blackrock Depths instance line.","Several instance bosses and sub-bosses have had their levels slightly lowered."],"pvp":["Gurubashi Arena - The arena in Stranglethorn Vale has been changed so that free-for-all PvP will only take place on the floor of the arena, and no longer in the stands or on the entrance ramp. Please keep in mind that on PvP realms, members of the opposite faction can still attack you anywhere in the arena because Stranglethorn Vale is a contested area. There is now a short countdown before a duel starts."]}',
                'patch'       => '1.2.0',
                'date'        => '2004-12-18',
                'image'       => '0eaa74871efd8005cd12c8a3e0a17348.jpg',
                'order'       => 2
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.3.0: Ruins of the Dire Maul<\/strong> was released on March 7, 2005.<\/p>\r\n<p><strong>Diremaul<\/strong><\/p>\r\n<p>Diremaul, a new dungeon for players level 56-60, is now open and ready for business. Diremaul is populated by a fierce tribe of ogres and is located in the western region of Feralas. Diremaul will be limited groups of no more than five players each.<\/p>","general":["Bind on Pickup items that are of a quality below the default group loot threshold (white\/gray) will no longer require confirmation before looting.","Quests that are not completable while in a raid group are no longer allowed if the instance is owned by a raid group, regardless of whether you are currently in that raid group or not."],"pve":["New Dungeon: Dire Maul","Players who have completed the Molten Core discovery quest can now port directly to the zone, bypassing Blackrock Depths.","Several of the dungeon and raid armor sets have been updated with their non-placeholder textures. These sets include the Devout, Prophecy, Elements, Arcanist\'s and Wildheart sets. Future patches will update more dungeon and raid sets."],"pvp":["Numbers and punctuation will not be passed through chat communication to members of the opposing faction.","Spells in PvP now have a slight increase in range and area of effect when targets are moving. This should improve the overall usability of spells and ranged attacks."]}',
                'patch'       => '1.3.0',
                'date'        => '2005-03-07',
                'image'       => '2b12c9c00fcf52f931b63eeadc557415.jpg',
                'order'       => 3
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.4.0: The Call to War<\/strong>\u00a0was released on April 19, 2005.<\/p>\r\n<p><strong>PvP Honor System<\/strong><\/p>\r\n<p>The Player versus Player Honor System is now active. Players will be able to gain rankings based on their PvP performance, with lucrative rewards for those who distinguish themselves on the field of battle! Read more about the PvP Honor system.<\/p>\r\n<p><strong>Children\'s Week<\/strong><\/p>\r\n<p>Children\'s Week is celebrated in Orgrimmar and Stormwind City at the start of May and lasts for a week. It is a time to give back to the innocents of war: the orphans!<\/p>","general":["Level 60 mounts purchased at vendors now have a whole new look. Faster undead mounts already have a different look and so have not changed. The new mounts can be seen standing near the mount vendors. If you have one of the old fast mounts, you can exchange it for one with a new look at the mount vendor.","Healing-over-time spells should now be improved by "],"pve":["Invasions by elementals at different locations on Kalimdor have reportedly been occurring sporadically every few days. Concerned adventurers should investigate Silithus, Un\'Goro Crater, Azshara, and Winterspring to counter these incursions."],"pvp":["Player vs Player Honor System"]}',
                'patch'       => '1.4.0',
                'date'        => '2005-04-19',
                'image'       => '121b0f29a5053e16487d222cae35d5cb.jpg',
                'order'       => 4
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.5.0: Battlegrounds<\/strong> was released on June 7, 2005.<\/p>\r\n<p><strong>Battlegrounds Arrive!<\/strong><\/p>\r\n<p>The Warsong Gulch and Alterac Valley battlegrounds are now available. The Warsong Gulch entrances may be found in the northern Barrens near the Mor\'Shan Rampart (Horde) and south of Silverwing Outpost in Ashenvale (Alliance). The Alterac Valley entrances may be found east of Sofera\'s Naze in Alterac (Horde), and in the Headlands of Alterac (Alliance).<\/p>","general":["Pet speed has been increased when out of combat and following their master.","Berserking (Troll Racial) - Updated tooltip to clarify ability only usable following a melee critical.","Hardiness (Orc Racial) - Fixed a bug that caused many abilities to ignore the additional resistance."],"pve":[""],"pvp":["Honor System","Dishonorable kills - gained by killing a trivial Civilian NPC - now has a negative impact on a player\'s honor. Enough dishonorable kills will reduce a player\'s rank all the way to zero.","Players may now see an \"estimated contribution point value\" in the combat log for an honorable kill. Note that this value does not take diminishing returns against the same player into account, and is therefore \"estimated\".","\"Team Contribution Points\" has been renamed to \"Honor\"","Players will see their last week\'s kill data in the \"Last Week\" section of the Honor System UI even if they did not achieve the 25 honorable kills required to gain standing or rank.","Dueling is now allowed within Everlook."]}',
                'patch'       => '1.5.0',
                'date'        => '2005-06-07',
                'image'       => '0804b3294cec4a4ba2dbde5e2ff9829f.jpg',
                'order'       => 5
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.6.0: Assault on Blackwing Lair<\/strong> was a major content patch for World of Warcraft. Among other changes, it featured the release of the Blackwing Lair raid instance, the introduction of the Darkmoon Faire to Azeroth, Battlemasters in major cities, and revamps of the Warlock and Warrior talent trees.<\/p>\r\n<p><strong>Blackwing Lair Released<\/strong><\/p>\r\n<p>Nefarian\'s sanctum, Blackwing Lair, can be found at the very height of Blackrock Spire. It is there in the dark recesses of the mountain\'s peak that Nefarian has begun to unfold the final stages of his plan to destroy Ragnaros once and for all and lead his army to undisputed supremacy over all the races of Azeroth. Blackwing Lair is a max-level, 40-player raid dungeon, with many new encounters and tempting rewards awaiting the intrepid adventurer that dares enter its halls.<\/p>\r\n<p><strong>Darkmoon Faire<\/strong><\/p>\r\n<p>A gathering of the exotic from around the world and beyond, Silas Darkmoon has brought together the Darkmoon Faire as a celebration of the wondrous and mysterious found in Azeroth. While the Faire spends most of their time in parts unknown, they do stop from time to time in Mulgore and Elwynn Forest. When the faire is on its way barkers will stop by Orgrimmar and Ironforge to announce its arrival.<\/p>\r\n<p><strong>Battlemasters<\/strong><\/p>\r\n<p>There is a new way to enter the battleground queues. Battlemasters! Located in each of the cities, right-clicking on a battlemaster will allow your character to enter a battleground queue just like you normally would if you touched that battleground\'s entrance portal. The functionality is exactly the same, so when it\'s your character\'s time to enter the chosen battleground, you will be teleported directly in. Local guards can give you directions on how to find the battlemaster that you\'re looking for.<\/p>","general":[""],"pve":["Blackwing Lair introduced","Several spawns removed from Scholomance. This should make for a more enjoyable 5 player experience.","Phase shifted imps are no longer hit by Magmadar\'s Lava Bomb."],"pvp":["There is now a progress bar on the Honor tab of your character window that displays how close you are to your next rank."]}',
                'patch'       => '1.6.0',
                'date'        => '2005-07-12',
                'image'       => '54b2b5cc2d7827e54e9399ea682038bc.jpg',
                'order'       => 6
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.7.0: Rise of the Blood God<\/strong> was released on September 13, 2005.<\/p>\r\n<p><strong>Zul\'Gurub<\/strong><\/p>\r\n<p>Hidden within the jungles of Stranglethorn, an ancient Troll city full of peril has been uncovered. Do you have what it takes to delve into its mysteries with a band of hardy explorers? There\'s only one way to find out! Zul\'Gurub is a high-level, 20-man raid instance with 120 new rare and epic items to uncover. Adventure awaits!<\/p>\r\n<p><strong>Arathi Basin<\/strong><\/p>\r\n<p>Join the League of Arathor or the Forsaken Defilers as they battle for the precious resources stockpiled within the latest Battleground, Arathi Basin! Pitting 15 members of each faction against each other, the race is on to be the first to 2000 resources, capturing strategic landmarks around the Basin to increase your team\'s gain and cripple the enemy. With an all-new set of reputation-based rewards, there\'s never been a better time to join the war!<\/p>","general":["The Stranglethorn Fishing Extravaganza is a grand new event set along the coasts of Stranglethorn Vale. Early on the appointed day, friendly neighborhood goblins will visit Ironforge and Orgrimmar to inform aspiring anglers of the grand tournament and give instructions. At the appropriate time, the shout will ring out across Stranglethorn to bait your hooks and cast your lines!"],"pve":["Zul\'Gurub","You can now no longer avoid Onyxia\'s confuse effect by jumping or moving erratically."],"pvp":["Arathi Basin","Battleground \"holidays\" have been added to Warsong Gulch, Alterac Valley and Arathi Basin. Holidays occur during most weekends, starting on Thursday night at midnight and continuing until Tuesday morning. During a holiday, emissaries from that Battleground will be found in the major cities, and honor\/faction rewards for performing objectives in that battleground are increased."]}',
                'patch'       => '1.7.0',
                'date'        => '2005-09-13',
                'image'       => 'a0187a1f1ea9b4b27226f467fc51d4c8.jpg',
                'order'       => 7
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.8.0: Dragons of Nightmare<\/strong> was released on October 10, 2005.<\/p>\r\n<p><strong>Disturbance at the Great Trees<\/strong><\/p>\r\n<p>Something is amiss in the Emerald Dream. Immense dragons with the shimmering emerald scales of the Green Dragonflight have been sighted guarding the portals at the Great Trees... but these once-noble creatures crawl with a new, strange menace, not the peace for which Ysera is known. Bring many allies should you dare to confront them; their powers are formidable and they will not hesitate to crush any who draw near.<\/p>\r\n<p><strong>The Stirring of the Silithid<\/strong><\/p>\r\n<p>The arid sands of Silithus are shifting. Something is awakening beyond the wall to the south... Aid the Druids of the Cenarion Circle as they delve into the mysteries of the desert. Seek answers behind the Twilight Hammer\'s presence. Discover more about the alien creatures known as the Silithid as you explore their hives. Many new endeavors await the high-level adventurer!<\/p>\r\n<p><strong>Hallow\'s End<\/strong><\/p>\r\n<p>When the decorations of Hallow\'s End light up Azeroth\'s cities, you know there\'s mischief afoot! Seek special vendors in Orgrimmar or Ironforge and get your hands on treats! Aid a sick orphan in a little trick-or-treating! Darkcaller Yanka, attending the Forsaken\'s Wickerman Festival, and Sergeant Hartman of Southshore are seeking your aid in keeping the enemy out of their holiday affairs -- are you up to the challenge?<\/p>","general":["As a direct result of this, many weapons have shifted position in their relative power. In particular, many Epic (purple) quality items are now more powerful than slower Superior (blue) weapons."],"pve":["Dragons of Nightmare - The four corrupted dragons from the Emerald Dream.","Azuregos is now properly resistant to Frost damage."],"pvp":["The percentage of players that may reach ranks 6 through 14 has been increased.","Lower-level players should advance in the Honor System more quickly than they had previously (although this change does not affect the highest ranks they can achieve)."]}',
                'patch'       => '1.8.0',
                'date'        => '2005-10-10',
                'image'       => 'a96937a861cdabe389ce39d4b5e1b9e5.jpg',
                'order'       => 8
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.9.0: The Gates of Ahn\'Qiraj<\/strong>\u00a0was released on January 3, 2006.<\/p>\r\n<p><strong>The Gates of Ahn\'Qiraj<\/strong><\/p>\r\n<p>The Gates of Ahn\'Qiraj will house two massive, unique dungeons -- the Ruins of Ahn\'Qiraj, a 20-man raid dungeon, and the Temple of Ahn\'Qiraj, a 40-man raid dungeon. As players delve deeper into the mysteries of Ahn\'Qiraj, they will discover revelations of the Silithid infestation and their shadowy masters, the Qiraji. Players will have to complete a world event of massive proportions before they can open the Gates of Ahn\'Qiraj on their realm.<\/p>\r\n<p><strong>Multiple Battlegrounds Queues<\/strong><\/p>\r\n<p>Players will be able to enter multiple battleground queues. No longer must you make the hard decision of which queue to join -- when queued for all three, you can join the first one available or hold out for that particular battleground which you\'ve really got your heart set on. Should a queue open while you are already in a battleground, you may switch to the new battle or remain in the current on.<\/p>","general":["The Gates of Ahn\'Qiraj world event."],"pve":["The Ruins of Ahn\'Qiraj (outdoor 20-player instance)","The Temple of Ahn\'Qiraj (indoor 40-player instance)","New Tier2 Epic Armor Models"],"pvp":["Level 48, 38, and 28 versions of the Defiler\'s Talisman and Talisman of Arathor have been added.","Two new rewards have been added for reaching Friendly reputation level with the Silverwing Sentinel and Warsong Outrider factions.","Reputation rewards for the PvP Battlegrounds have been adjusted:","Warsong Gulch and Arathi Basin now offer Battleground-specific rations at Friendly reputation."]}',
                'patch'       => '1.9.0',
                'date'        => '2006-01-03',
                'image'       => '9e60d34f6c52072249c85eefc59923d0.jpg',
                'order'       => 9
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.10.0: Storms of Azeroth<\/strong>\u00a0was released on March 28, 2006.<\/p>\r\n<p><strong>New High-Level Armor Sets!<\/strong><\/p>\r\n<p>Adventurers of Azeroth can now quest to upgrade their previous Rare-quality Dungeon set to a new, higher-quality set, including epic gear! These tasks include all-new boss encounters, so prepare your finest group of dungeon-delvers and prepare for a challenge!<\/p>\r\n<p><strong>Weather!<\/strong><\/p>\r\n<p>Weather has been introduced in the following areas around Azeroth:<\/p>\r\n<ul>\r\n<li>Elwynn Forest<\/li>\r\n<li>Tirisfal Glades<\/li>\r\n<li>Dun Morogh<\/li>\r\n<li>Darkshore<\/li>\r\n<li>Alterac Mountains<\/li>\r\n<li>Stranglethorn Vale<\/li>\r\n<li>Feralas<\/li>\r\n<li>Un\'Goro Crater<\/li>\r\n<li>Tanaris<\/li>\r\n<li>Winterspring<\/li>\r\n<li>Ahn\'Qiraj<\/li>\r\n<\/ul>\r\n<p>We will be adding more weather to the world as time progresses; this is simply the beginning!<\/p>","general":["You will no longer lose your current target when affected by a crowd control spell (e.g.  Fear,  Polymorph etc...).","Stealth and Invisibility effects will now be canceled at the beginning of an action (spellcast, ability use etc...), rather than at the completion of the action."],"pve":["The Four Green Dragons will now spawn as originally intended. They should all now spawn at the same time everytime.","You no longer charge into the lava when charging Ragnaros."],"pvp":["Korrak the Bloodrager and his band of trolls have packed up their bags and left Alterac Valley for greener pastures.","The reputation gain in Warsong Gulch and Arathi Basin has been significantly increased.","Several Civilian NPCs that would assist the guards in attacking players no longer do so."]}',
                'patch'       => '1.10.0',
                'date'        => '2006-03-28',
                'image'       => 'b1f1dc690546b02cf802e92b20d9e023.jpg',
                'order'       => 10
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.11.0: Shadow of the Necropolis<\/strong>\u00a0was released on June 19, 2006.<\/p>\r\n<p><strong>Shadow of the Necropolis<\/strong><\/p>\r\n<p>Floating above the Plaguelands, the necropolis known as Naxxramas serves as the seat of one of the Lich King\'s most powerful officers, the dreaded lich Kel\'Thuzad. Horrors of the past and new terrors yet to be unleashed are gathering inside the necropolis as the Lich King\'s servants prepare their assault. The Scourge marches again...<\/p>\r\n<p>Naxxramas is the new 40-man raid dungeon that will present even the most experienced and powerful players with an epic challenge.<\/p>","general":["The cost to unlearn talents will now decay over time. This cost will be reduced by a rate of 5 gold per month to a minimum of 10 gold.","Logging back in after a disconnect from the server has been greatly improved, and players should now rarely receive the message ","Temporary item buffs (e.g. poisons, sharpening stones and shaman weapon buffs) will persist through zoning or logging out."],"pve":["Naxxramas, (40-player raid instance), a massive necropolis floating above Stratholme","Release timers have been be removed from instances. This includes dungeons, battlegrounds, and raid instances.","Instituted an anti-exploit measure on certain encounters (almost entirely raid bosses). These encounters will prevent people from zoning into the instance while that encounter is engaged. If you attempt to zone into the instance while that encounter is engaged, you will be resurrected at the outside entrance. We will be making adjustments to the entrances to Molten Core and Blackwing Lair to accommodate this change. Combat resurrections, soulstones, reincarnate, etc. will still work fine. This is primarily to combat graveyard rushing in instances."],"pvp":["An updated set of armor rewards have been added to vendors for Honor ranks 7, 8, and 10.","The armor rewards for Honor ranks 12 and 13 have been increased in level and stat point allocation.","New rank 14 weapons have been added! In order to give casters the same diversity of selection that melee currently enjoy we have added new caster items that are available to be purchased by Grand Marshals and High Warlords."]}',
                'patch'       => '1.11.0',
                'date'        => '2006-06-19',
                'image'       => '818c052e627c8d78f078589f0997235b.jpg',
                'order'       => 11
            ),
            array(
                'description' => '{"description":"<p><strong>Patch 1.12.0: Drums of War<\/strong>\u00a0was released on August 22, 2006.<\/p>\r\n<p><strong>World PvP<\/strong><\/p>\r\n<p>The stage is set for intense, objective-based land battles as Horde and Alliance vie for control over important strategic positions and resources around Azeroth. Head out for Silithus and Eastern Plaguelands to engage the enemy on the field!<\/p>","general":["Threat Reduction Effects. This system has been redesigned to eliminate inconsistency in how the effects work. Previously, some were additive (for example: 30% reduction + 20% reduction = 50% reduction) while others were multiplicative (30% reduction and 20% reduction made 44% reduction, from 0.7*0.8). They are now all multiplicative. This also prevents unpredictable behavior when the total reduction percentage was equal to or greater than 100%. Please note that in almost all cases, when stacking multiple threat reduction effects you will experience less threat reduction than previously.","Haste and Slow Effects. Previously Haste and Slow effects worked inconsistently, with spells working differently from weapons, and hastes and slows not acting as inverses of each other. We have revised the system so that all haste and slow effects work the same way, and haste and slow percentages of the same magnitude perfectly cancel each other out (30% haste and 30% slow combine to no change). As a result, we had to change the tooltip numbers on all spell haste effects, and on all melee and range slow effects. The numbers in the tooltips are different, but the game functionality is unchanged (other than slight rounding errors). Those tooltips that changed will now display larger numbers than they used to display. Conceptually, haste values indicate how much more of that activity you can perform in a given time. 30% melee haste means 30% more swings in a given time. Slow values indicate how much longer an activity takes to complete. 30% slow means an action takes 30% longer to finish.","Temporary item buffs (e.g. poisons, sharpening stones and shaman weapon buffs) will no longer persist through zoning or logging out due to technical issues. This feature is anticipated to be activated once more with the expansion."],"pve":[""],"pvp":["Honorable Kills now diminish at a rate 10% per kill rather than 25% per kill.","The deserter debuff will now continue to expire even while you are offline."]}',
                'patch'       => '1.12.0',
                'date'        => '2006-08-22',
                'image'       => 'ae1cd6c35d0457ef061523c1bd987d08.jpg',
                'order'       => 12
            ),
        );
        $this->db->insert_batch('timeline', $data);

        $module = array('name' => 'Timeline', 'status' => '1');
        $this->db->insert('modules', $module);

        $menu = array('name' => 'Timeline', 'url' => 'timeline', 'icon' => 'fas fa-timeline', 'main' => '1', 'child' => '0', 'type' => 1);
        $this->db->insert('menu', $menu);
    }

    public function down()
    {
        $this->dbforge->drop_table('timeline');
    }
}
