<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property               $multiRealm
 * @property Armory_model  $armory_model
 * @property General_model $wowgeneral
 */
class Armory_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $MultiRealm
     * @param $id
     *
     * @return mixed
     */
    public function getPlayerInfo($MultiRealm, $id)
    {
        $this->multiRealm = $MultiRealm;

        return $this->multiRealm->select('*')->where('guid', $id)->get('characters')->row_array();
    }

    public function getPlayerRace($MultiRealm, $id)
    {
        $this->multiRealm = $MultiRealm;

        return $this->multiRealm->select('race')->where('guid', $id)->get('characters')->row('race');
    }

    public function getCharEquipments($MultiRealm, $id, $patch = null)
    {
        $this->multiRealm = $MultiRealm;
        $equipmentCache   = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('equipmentArrayID_' . $id . '-P_' . ($patch ?? '10')) : false;

        if ($equipmentCache) {
            $itemList = $equipmentCache;
        } else {
            $result = $this->multiRealm->select('slot, item_id')->where('guid', $id)->where('bag', '0')->where('slot >=', '0')->where('slot <=', '18')->get('character_inventory')->result_array();

            // Item slots
            $slots = [
                0  => "head",
                1  => "neck",
                2  => "shoulders",
                3  => "body",
                4  => "chest",
                5  => "waist",
                6  => "legs",
                7  => "feet",
                8  => "wrists",
                9  => "hands",
                10 => "finger1",
                11 => "finger2",
                12 => "trinket1",
                13 => "trinket2",
                14 => "back",
                15 => "mainhand",
                16 => "offhand",
                17 => "ranged",
                18 => "tabard"
            ];

            $itemList = [];

            foreach ($result as $item) {
                $qualityPatch                    = $this->getCharEquipmentQualityPatch($item['item_id'], $patch);
                $itemList[$slots[$item['slot']]] = [
                    'item_id'      => $item['item_id'],
                    'item_slot_id' => $item['slot'],
                    'item_quality' => $qualityPatch['quality'] ?? 0,
                    'item_patch'   => $qualityPatch['patch'] ?? 10,
                    'item_icon'    => $this->getCharEquipDisplayIcon($item['item_id'], $patch)
                ];
            }

            if ($this->wowgeneral->getRedisCMS()) {
                // Cache for 15 minutes
                $this->cache->redis->save('equipmentArrayID_' . $id . '-P_' . ($patch ?? '10'), $itemList, 60 * 15);
            }
        }

        return $itemList;
    }

    public function getCharEquipmentQualityPatch($id, $patch = null)
    {
        $world = $this->load->database('world', true);

        if (isset($id) && ! empty($id) && ctype_digit($id)) {
            $qualityPatchCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('itemQualityPatchID_' . $id . '-P_' . ($patch ?? '10')) : false;

            if ($qualityPatchCache) {
                $itemQualityPatch = $qualityPatchCache;
            } else {
                $itemQualityPatch = $world->select('quality, MAX(patch) AS patch')->where('entry', $id)->where('patch <=', $patch ?? '10')->get('item_template')->row_array('patch, quality');

                if (! $itemQualityPatch['quality'] || $itemQualityPatch['quality'] > 6 || $itemQualityPatch['patch'] > 10) {
                    $itemQualityPatch['quality'] = 99; //let user know that the equipped item doesn't exist in selected patch
                }

                if ($this->wowgeneral->getRedisCMS()) {
                    // Cache for 30 day
                    $this->cache->redis->save('itemQualityPatchID_' . $id . '-P_' . ($patch ?? '10'), $itemQualityPatch, 60 * 60 * 24 * 30);
                }
            }
        }

        return $itemQualityPatch ?? [];
    }

    public function getCharStats($MultiRealm, $id, $patch = null)
    {
        $this->multiRealm = $MultiRealm;
        $chatStatCache    = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('charStatArrayID_' . $id . '-P_' . ($patch ?? '10')) : false;

        if ($chatStatCache) {
            $charStat = $chatStatCache;
        } else {
            $charStat = $this->multiRealm->select('*')->where('guid', $id)->get('character_stats');

            if ($charStat->num_rows() > 0) {
                $charStat = $charStat->row();

                if ($this->wowgeneral->getRedisCMS()) {
                    // Cache for 15 minutes
                    $this->cache->redis->save('charStatArrayID_' . $id . '-P_' . ($patch ?? '10'), $charStat, 60 * 15);
                }
            } else {
                $charStat = null;
            }
        }

        return $charStat;
    }

    public function calculateAuras($MultiRealm, $id, int $race, int $class, int $level, $equippedItemIDs, $patch = null): array
    {
        $this->multiRealm = $MultiRealm;
        $savedStats       = $this->getCharStats($MultiRealm, $id, $patch) ?? null;
        $world            = $this->load->database('world', true);
        $subQ             = $world->select("max(patch)", false)->where("t1.entry=t2.entry")->where("patch <=", $patch ?? '10')->get_compiled_select("item_template t2");
        $auras            = [];
        $finalStats       = [];

        $finalStats['powerSpell']          = 0;
        $finalStats['powerHealing']        = 0;
        $finalStats['defManaRegen']        = 0;
        $finalStats['defArmor']            = 0;
        $finalStats['defBlock']            = 0;
        $finalStats['defDodge']            = 0;
        $finalStats['defDefense']          = 0;
        $finalStats['defParry']            = 0;
        $finalStats['hitSpell']            = 0;
        $finalStats['hitMelee']            = 0;
        $finalStats['critSpell']           = 0;
        $finalStats['critMelee']           = 0;
        $finalStats['critRanged']          = 0;
        $finalStats['spellNormal']         = 0;
        $finalStats['spellNature']         = 0;
        $finalStats['spellHoly']           = 0;
        $finalStats['spellFire']           = 0;
        $finalStats['spellFrost']          = 0;
        $finalStats['spellShadow']         = 0;
        $finalStats['spellArcane']         = 0;
        $finalStats['powerSpellSecondary'] = ['icon' => 'stat_unknown', 'name' => 'N/A', 'data' => 0];

        if ($savedStats) {
            $finalStats['maxHealth']         = $savedStats->max_health;
            $finalStats['maxPower']          = ($class == 1 || $class == 4) ? "100" : $savedStats->max_power1;
            $finalStats['statStr']           = $savedStats->strength;
            $finalStats['statAgi']           = $savedStats->agility;
            $finalStats['statSta']           = $savedStats->stamina;
            $finalStats['statInt']           = $savedStats->intellect;
            $finalStats['statSpr']           = $savedStats->spirit;
            $finalStats['defArmor']          = $savedStats->armor;
            $finalStats['defBlock']          = $savedStats->block_chance;
            $finalStats['defDodge']          = $savedStats->dodge_chance;
            $finalStats['defParry']          = $savedStats->parry_chance;
            $finalStats['critMelee']         = $savedStats->crit_chance;
            $finalStats['critRanged']        = $savedStats->ranged_crit_chance;
            $finalStats['powerAttack']       = $savedStats->attack_power;
            $finalStats['powerRangedAttack'] = $savedStats->ranged_attack_power;
        } else {
            $baseStatsCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('baseStatsArrayRCID_' . $race . '_' . $class) : false;

            if ($baseStatsCache) {
                $baseStats = $baseStatsCache;
            } else {
                //assuming character_stats is enabled on VMaNGOS config, this part is just a fallback for initial stats and can be implemented for entire solution
                $baseStats = $world->select('str, agi, sta, inte, spi')->where('race', $race)->where('class', $class)->where('level', $level)->get('player_levelstats')->row_array();

                if ($this->wowgeneral->getRedisCMS()) {
                    // Cache for 30 day
                    $this->cache->redis->save('baseStatsArrayRCID_' . $race . '_' . $class, $baseStats, 60 * 60 * 24 * 30);
                }
            }

            $finalStats['maxHealth']         = null;
            $finalStats['maxPower']          = ($class == 1 || $class == 4) ? "100" : null;
            $finalStats['statStr']           = $baseStats['str'];
            $finalStats['statAgi']           = $baseStats['agi'];
            $finalStats['statSta']           = $baseStats['sta'];
            $finalStats['statInt']           = $baseStats['inte'];
            $finalStats['statSpr']           = $baseStats['spi'];
            $finalStats['defArmor']          = $baseStats['agi'] * 2;
            $finalStats['powerAttack']       = calculateMeleeAP($class, $level, $baseStats['str'], $baseStats['agi']);
            $finalStats['powerRangedAttack'] = calculateRangedAP($class, $level, $baseStats['agi']);
        }

        if ($equippedItemIDs) {
            $itemStatsCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('itemStatsArrayID_' . $id . '-P_' . ($patch ?? '10')) : false;

            if ($itemStatsCache) {
                $itemStats = $itemStatsCache;
            } else {
                $selectSpells = 'spellid_1, spellid_2, spellid_3, spellid_4, spellid_5';
                $selectStats  = 'stat_type1, stat_value1, stat_type2, stat_value2, stat_type3, stat_value3, stat_type4, stat_value4, stat_type5, stat_value5,
                             stat_type6, stat_value6, stat_type7, stat_value7, stat_type8, stat_value8, stat_type9, stat_value9, stat_type10, stat_value10';

                $itemStats = $world->select($selectSpells . ',' . $selectStats)->where_in('entry', $equippedItemIDs)->where('patch=(' . $subQ . ')')->get('item_template t1')->result();

                if ($this->wowgeneral->getRedisCMS()) {
                    // Cache for 15 minutes
                    $this->cache->redis->save('itemStatsArrayID_' . $id . '-P_' . ($patch ?? '10'), $itemStats, 60 * 15);
                }
            }

            foreach ($itemStats as $item) {
                $spellIDs = [
                    $item->spellid_1,
                    $item->spellid_2,
                    $item->spellid_3,
                    $item->spellid_4,
                    $item->spellid_5
                ];
                $statIDs  = [
                    $item->stat_type1  => $item->stat_value1,
                    $item->stat_type2  => $item->stat_value2,
                    $item->stat_type3  => $item->stat_value3,
                    $item->stat_type4  => $item->stat_value4,
                    $item->stat_type5  => $item->stat_value5,
                    $item->stat_type6  => $item->stat_value6,
                    $item->stat_type7  => $item->stat_value7,
                    $item->stat_type8  => $item->stat_value8,
                    $item->stat_type9  => $item->stat_value9,
                    $item->stat_type10 => $item->stat_value10
                ];

                foreach ($statIDs as $key => $val) {
                    if ($key > 2 && $savedStats == null) { // 0 = ITEM_MOD_MANA, 1 = ITEM_MOD_HEALTH, 2 = NULL
                        $key = (int)$key;

                        if ($key === 3) {
                            $finalStats['statAgi'] += $val;
                        } elseif ($key === 4) {
                            $finalStats['statStr'] += $val;
                        } elseif ($key === 5) {
                            $finalStats['statInt'] += $val;
                        } elseif ($key === 6) {
                            $finalStats['statSpr'] += $val;
                        } elseif ($key === 7) {
                            $finalStats['statSta'] += $val;
                        }
                    }
                }

                foreach ($spellIDs as $aura) {
                    if ($aura > 0) {
                        $auras[] = $aura;
                    }
                }
            }

            if (! empty($auras)) {
                $spellTemplateCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('spellTemplateArrayID_' . $id . '-P_' . ($patch ?? '10')) : false;

                if ($spellTemplateCache) {
                    $spells = $spellTemplateCache;
                } else {
                    $spells = $world->select('entry, build, school, description, effectApplyAuraName1, effectBasePoints1, effectMiscValue1')->where_in('entry', $auras)->where('(entry, build) IN (SELECT entry, MAX(build) FROM spell_template GROUP BY entry)', null, false)->where('spellIconId <=', 1)->get('spell_template')->result();

                    if ($this->wowgeneral->getRedisCMS()) {
                        // Cache for 15 minutes
                        $this->cache->redis->save('spellTemplateArrayID_' . $id . '-P_' . ($patch ?? '10'), $spells, 60 * 15);
                    }
                }

                foreach ($spells as $spell) {
                    $count     = count(array_keys($auras, $spell->entry));
                    $auraConst = (int)$spell->effectApplyAuraName1; //https://github.com/vmangos/core/blob/development/src/game/Spells/SpellAuraDefines.h#L43

                    if ($auraConst === 13) {
                        if (stripos($spell->description, 'Increases damage and healing done by magical spells and effects by up to $s1.') !== false) {
                            $finalStats['powerSpell'] += ($spell->effectBasePoints1 + 1) * $count;
                        } elseif (stripos($spell->description, 'Increases damage done by Holy spells and effects by up to $s1.') !== false) {
                            $finalStats['spellHoly'] += ($spell->effectBasePoints1 + 1) * $count;
                        } elseif (stripos($spell->description, 'Increases damage done by Fire spells and effects by up to $s1.') !== false) {
                            $finalStats['spellFire'] += ($spell->effectBasePoints1 + 1) * $count;
                        } elseif (stripos($spell->description, 'Increases damage done by Nature spells and effects by up to $s1.') !== false) {
                            $finalStats['spellNature'] += ($spell->effectBasePoints1 + 1) * $count;
                        } elseif (stripos($spell->description, 'Increases damage done by Frost spells and effects by up to $s1.') !== false) {
                            $finalStats['spellFrost'] += ($spell->effectBasePoints1 + 1) * $count;
                        } elseif (stripos($spell->description, 'Increases damage done by Shadow spells and effects by up to $s1.') !== false) {
                            $finalStats['spellShadow'] += ($spell->effectBasePoints1 + 1) * $count;
                        } elseif (stripos($spell->description, 'Increases damage done by Arcane spells and effects by up to $s1.') !== false) {
                            $finalStats['spellArcane'] += ($spell->effectBasePoints1 + 1) * $count;
                        }
                    } elseif ($auraConst === 24 || $auraConst === 85) {
                        $finalStats['defManaRegen'] += ($spell->effectBasePoints1 + 1) * $count;
                    } elseif ($auraConst === 30 && (int)$spell->effectMiscValue1 === 95) {
                        $finalStats['defDefense'] += ($spell->effectBasePoints1 + 1) * $count;
                    } /*elseif ($auraConst === 49) {
                        $finalStats['defDodge'] += ($spell->effectBasePoints1 + 1) * $count;
                    }*/ elseif ($auraConst === 54) {
                        $finalStats['hitMelee'] += ($spell->effectBasePoints1 + 1) * $count;
                    } elseif ($auraConst === 55) {
                        $finalStats['hitSpell'] += ($spell->effectBasePoints1 + 1) * $count;
                    } elseif ($auraConst === 71) {
                        $finalStats['critSpell'] += ($spell->effectBasePoints1 + 1) * $count;
                    } elseif ($auraConst === 99) {
                        $finalStats['powerAttack']       += ($spell->effectBasePoints1 + 1) * $count;
                        $finalStats['powerRangedAttack'] += ($spell->effectBasePoints1 + 1) * $count;
                    } elseif ($auraConst === 124) {
                        $finalStats['powerRangedAttack'] += ($spell->effectBasePoints1 + 1) * $count;
                    } elseif ($auraConst === 135) {
                        $finalStats['powerHealing'] += ($spell->effectBasePoints1 + 1) * $count;
                    }
                }
            }

            // Secondary spells calculation
            $finalStats['powerSpellSecondary'] = [
                "holy"   => $finalStats['spellHoly'],
                "fire"   => $finalStats['spellFire'],
                "nature" => $finalStats['spellNature'],
                "frost"  => $finalStats['spellFrost'],
                "shadow" => $finalStats['spellShadow'],
                "arcane" => $finalStats['spellArcane']
            ];

            $equippedItemIDsByPatch = $world->select('entry')->where('patch=(' . $subQ . ')')->where_in('entry', $equippedItemIDs)->get('item_template t1')->result_array('entry');
            $equippedItemIDsByPatch = array_map('intval', array_column($equippedItemIDsByPatch, 'entry'));

            $enchAuras = $this->getEnchantInfo($MultiRealm, $id, $equippedItemIDsByPatch);

            if (! empty($enchAuras)) {
                // Just getting spell damage, defense and hit enchants since other stats are saved in character_stats table already.
                foreach ($enchAuras as $enchID) {
                    $enchID = (int)$enchID;
                    if ($enchID === 923) {
                        $finalStats['defDefense'] += 3;
                    } elseif ($enchID === 2503) {
                        $finalStats['defDefense'] += 3;
                    } elseif ($enchID === 2504) {
                        $finalStats['powerSpell'] += 30;
                    } elseif ($enchID === 2505) {
                        $finalStats['powerHealing'] += 55;
                    } elseif ($enchID === 2523) {
                        $finalStats['hitMelee'] += 3;
                    } elseif ($enchID === 2544) {
                        $finalStats['powerSpell'] += 8;
                    } elseif ($enchID === 2583) {
                        $finalStats['defDefense'] += 7;
                    } elseif ($enchID === 2584) {
                        $finalStats['powerHealing'] += 24;
                        $finalStats['defDefense']   += 7;
                    } elseif ($enchID === 2585) {
                        $finalStats['powerAttack']       += 28;
                        $finalStats['powerRangedAttack'] += 28;
                    } elseif ($enchID === 2586) {
                        $finalStats['hitMelee']          += 1;
                        $finalStats['powerRangedAttack'] += 24;
                    } elseif ($enchID === 2587) {
                        $finalStats['powerSpell'] += 13;
                    } elseif ($enchID === 2588) {
                        $finalStats['powerSpell'] += 18;
                        $finalStats['hitSpell']   += 1;
                    } elseif ($enchID === 2589) {
                        $finalStats['powerSpell'] += 18;
                    } elseif ($enchID === 2590) {
                        $finalStats['powerHealing'] += 24;
                        $finalStats['defManaRegen'] += 4;
                    } elseif ($enchID === 2591) {
                        $finalStats['powerHealing'] += 24;
                    } elseif ($enchID === 2604) {
                        $finalStats['powerHealing'] += 33;
                    } elseif ($enchID === 2605) {
                        $finalStats['powerSpell'] += 18;
                    } elseif ($enchID === 2606) {
                        $finalStats['powerAttack']       += 30;
                        $finalStats['powerRangedAttack'] += 30;
                    } elseif ($enchID === 2614) {
                        $finalStats['powerSpellSecondary']['shadow'] += 20;
                    } elseif ($enchID === 2615) {
                        $finalStats['powerSpellSecondary']['frost'] += 20;
                    } elseif ($enchID === 2616) {
                        $finalStats['powerSpellSecondary']['fire'] += 20;
                    } elseif ($enchID === 2617) {
                        $finalStats['powerHealing'] += 30;
                    } elseif ($enchID === 2715) {
                        $finalStats['powerHealing'] += 31;
                        $finalStats['defManaRegen'] += 5;
                    } elseif ($enchID === 2717) {
                        $finalStats['powerAttack']       += 26;
                        $finalStats['powerRangedAttack'] += 26;
                    } elseif ($enchID === 2721) {
                        $finalStats['powerSpell'] += 15;
                        $finalStats['critSpell']  += 1;
                    }
                }
            }

            if ($class === 5 || $class === 7 || $class === 8 || $class === 9 || $class === 11) {
                $maxEl = max($finalStats['powerSpellSecondary']);

                if ($maxEl > 0) {
                    $key                               = array_search($maxEl, $finalStats['powerSpellSecondary']);
                    $finalStats['powerSpellSecondary'] = ['icon' => 'power_' . $key, 'name' => ucfirst($key) . ' Power', 'data' => $maxEl + $finalStats['powerSpell']];
                } else {
                    if ($class === 5 || $class === 9) { //just for the sake of views
                        $finalStats['powerSpellSecondary'] = ['icon' => 'power_shadow', 'name' => 'Shadow Power', 'data' => $finalStats['powerSpell']];
                    } elseif ($class === 7 || $class === 11) {
                        $finalStats['powerSpellSecondary'] = ['icon' => 'power_nature', 'name' => 'Nature Power', 'data' => $finalStats['powerSpell']];
                    } elseif ($class === 8) {
                        $finalStats['powerSpellSecondary'] = ['icon' => 'power_frost', 'name' => 'Frost Power', 'data' => $finalStats['powerSpell']];
                    }
                }
            } else {
                $finalStats['powerSpellSecondary'] = ['icon' => 'stat_unknown', 'name' => 'N/A', 'data' => 0];
            }

            // Finalize stats
            $finalStats['powerHealing'] = $finalStats['powerSpell'] + $finalStats['powerHealing'];
            $finalStats['critSpell']    = $finalStats['critSpell'] + getAdditionalSpellCrit($class, $finalStats['statInt']);
            $finalStats['defManaRegen'] = $finalStats['defManaRegen'] + getAdditionalMP5($class, $finalStats['statSpr']);
        }

        return $finalStats;
    }

    public function getCharProfessions($MultiRealm, $id, $type = false)
    {
        $professionPCache = [];
        $professionSCache = [];

        // Ordered by pair for view by specific type
        if ($type == 'P') {
            $professionIDs    = [164, 202, 186, 165, 393, 171, 182, 197, 333];
            $professionPCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('professionPArrayID_' . $id) : false;
        } elseif ($type == 'S') {
            $professionIDs    = [129, 185, 356];
            $professionSCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('professionSArrayID_' . $id) : false;
        } else {
            $professionIDs = [164, 202, 186, 165, 393, 171, 182, 197, 333, 129, 185, 356];
        }

        if ($type == 'P' && $professionPCache) {
            $result = $professionPCache;
        } elseif ($type == 'S' && $professionSCache) {
            $result = $professionSCache;
        } else {
            $this->multiRealm = $MultiRealm;
            $this->multiRealm->select('*')->where_in('skill', $professionIDs)->where('guid', $id);
            $this->multiRealm->protect_identifiers = false;
            $order                                 = sprintf('FIELD(skill, %s)', implode(', ', $professionIDs));
            $this->multiRealm->order_by($order);
            $this->multiRealm->protect_identifiers = true;

            $result = $this->multiRealm->get('character_skills')->result_array();
            // $result = json_decode(json_encode($result), true);

            foreach ($result as $key => $row) {
                if ($info = $this->getProfessionInfo((int)$row['skill'])) {
                    $result[$key] = [
                        'guid'  => $row['guid'],
                        'skill' => $row['skill'],
                        'value' => $row['value'],
                        'max'   => $row['max'],
                        'name'  => $info['name'],
                        'icon'  => $info['icon'],
                    ];
                }
            }

            if ($this->wowgeneral->getRedisCMS() && $result) {
                // Cache for 6 hour
                if ($type == 'P') {
                    $this->cache->redis->save('professionPArrayID_' . $id, $result, 60 * 60 * 6);
                } elseif ($type == 'S') {
                    $this->cache->redis->save('professionSArrayID_' . $id, $result, 60 * 60 * 6);
                }
            }
        }

        return $result;
    }

    public function getEnchantInfo($MultiRealm, $id, $equippedItemIDs): array
    {
        $this->multiRealm = $MultiRealm;
        $result           = [];

        if ($equippedItemIDs) {
            $equippedItemGuidList = array_column(
                $this->multiRealm->select('item_id, item_guid')->where('guid', $id)->where('slot >=', 0)->where('slot <=', 18)->where('bag', 0)
                                 ->where_in('item_id', $equippedItemIDs)->order_by('slot', 'ASC')->get('character_inventory')->result_array(), 'item_guid'
            );
            if (! empty($equippedItemGuidList)) {
                $enchants = $this->multiRealm->select('item_id, enchantments')->where('owner_guid', $id)->where_in('guid', $equippedItemGuidList)->get('item_instance')->result_array();

                foreach ($enchants as $ench) {
                    $enchAura = strtok($ench['enchantments'], ' ');
                    $enchAura == 0 ?: $result[$ench['item_id']] = $enchAura;
                    // effectMiscValue1 on spell_template to automate the process
                }
            }
        }

        return $result;
    }

    private function getProfessionInfo($id)
    {
        $data = [
            //Primary
            164 => ['name' => 'Blacksmithing', 'icon' => 'Trade_BlackSmithing'],
            165 => ['name' => 'Leatherworking', 'icon' => 'Trade_LeatherWorking'],
            171 => ['name' => 'Alchemy', 'icon' => 'Trade_Alchemy'],
            182 => ['name' => 'Herbalism', 'icon' => 'Trade_Herbalism'],
            186 => ['name' => 'Mining', 'icon' => 'Trade_Mining'],
            197 => ['name' => 'Tailoring', 'icon' => 'Trade_Tailoring'],
            202 => ['name' => 'Engineering', 'icon' => 'Trade_Engineering'],
            333 => ['name' => 'Enchanting', 'icon' => 'Trade_Engraving'],
            393 => ['name' => 'Skinning', 'icon' => 'INV_Misc_Pelt_Wolf_01'],
            //Secondary
            129 => ['name' => 'First Aid', 'icon' => 'Spell_Holy_SealOfSacrifice'],
            185 => ['name' => 'Cooking', 'icon' => 'INV_Misc_Food_15'],
            356 => ['name' => 'Fishing', 'icon' => 'Trade_Fishing'],
        ];

        if (isset($data[(int)$id])) {
            return $data[(int)$id];
        }

        return false;
    }

    public function getTotalHK($MultiRealm, $id)
    {
        $this->multiRealm = $MultiRealm;

        return $this->multiRealm->select('honor_stored_hk')->where('guid', $id)->get('characters')->row('honor_stored_hk') +
               $this->multiRealm->select('count(guid)')->where('guid', $id)->where('type', 1)->get('character_honor_cp')->row('count(guid)');
    }

    public function getTotalDK($MultiRealm, $id)
    {
        $this->multiRealm = $MultiRealm;

        return $this->multiRealm->select('honor_stored_dk')->where('guid', $id)->get('characters')->row('honor_stored_dk') +
               $this->multiRealm->select('count(guid)')->where('guid', $id)->where('type', 2)->get('character_honor_cp')->row('count(guid)');
    }

    public function getCurrentPVPRank($MultiRealm, $id)
    {
        $this->multiRealm    = $MultiRealm;
        $honorRankPointCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('honorRankPointFArrayID_' . $id) : false;

        if ($honorRankPointCache) {
            $rank = $honorRankPointCache;
        } else {
            $honorDetails   = $this->multiRealm->select('honor_rank_points, honor_stored_hk')->where('guid', $id)->get('characters');
            $honorRankPoint = $honorDetails->row('honor_rank_points') ?? 0;

            if ($honorDetails->row('honor_stored_hk') > 15 && $honorRankPoint < 2000) {
                $rank = ['rank' => 1, 'icon' => 'PvP_R1', 'a_title' => 'Private', 'h_title' => 'Scout'];
            } elseif ($honorRankPoint >= 2000 && $honorRankPoint < 5000) {
                $rank = ['rank' => 2, 'icon' => 'PvP_R2', 'a_title' => 'Corporal', 'h_title' => 'Grunt'];
            } elseif ($honorRankPoint >= 5000 && $honorRankPoint < 10000) {
                $rank = ['rank' => 3, 'icon' => 'PvP_R3', 'a_title' => 'Sergeant', 'h_title' => 'Sergeant'];
            } elseif ($honorRankPoint >= 10000 && $honorRankPoint < 15000) {
                $rank = ['rank' => 4, 'icon' => 'PvP_R4', 'a_title' => 'Master Sergeant', 'h_title' => 'Senior Sergeant'];
            } elseif ($honorRankPoint >= 15000 && $honorRankPoint < 20000) {
                $rank = ['rank' => 5, 'icon' => 'PvP_R5', 'a_title' => 'Sergeant Major', 'h_title' => 'First Sergeant'];
            } elseif ($honorRankPoint >= 20000 && $honorRankPoint < 25000) {
                $rank = ['rank' => 6, 'icon' => 'PvP_R6', 'a_title' => 'Knight', 'h_title' => 'Stone Guard'];
            } elseif ($honorRankPoint >= 25000 && $honorRankPoint < 30000) {
                $rank = ['rank' => 7, 'icon' => 'PvP_R7', 'a_title' => 'Knight-Lieutenant', 'h_title' => 'Blood Guard'];
            } elseif ($honorRankPoint >= 30000 && $honorRankPoint < 35000) {
                $rank = ['rank' => 8, 'icon' => 'PvP_R8', 'a_title' => 'Knight-Captain', 'h_title' => 'Legionnaire'];
            } elseif ($honorRankPoint >= 35000 && $honorRankPoint < 40000) {
                $rank = ['rank' => 9, 'icon' => 'PvP_R9', 'a_title' => 'Knight-Champion', 'h_title' => 'Centurion'];
            } elseif ($honorRankPoint >= 40000 && $honorRankPoint < 45000) {
                $rank = ['rank' => 10, 'icon' => 'PvP_R10', 'a_title' => 'Lieutenant Commander', 'h_title' => 'Champion'];
            } elseif ($honorRankPoint >= 45000 && $honorRankPoint < 50000) {
                $rank = ['rank' => 11, 'icon' => 'PvP_R11', 'a_title' => 'Commander', 'h_title' => 'Lieutenant General'];
            } elseif ($honorRankPoint >= 50000 && $honorRankPoint < 55000) {
                $rank = ['rank' => 12, 'icon' => 'PvP_R12', 'a_title' => 'Marshal', 'h_title' => 'General'];
            } elseif ($honorRankPoint >= 55000 && $honorRankPoint < 60000) {
                $rank = ['rank' => 13, 'icon' => 'PvP_R13', 'a_title' => 'Field Marshal', 'h_title' => 'Warlord'];
            } elseif ($honorRankPoint >= 60000) {
                $rank = ['rank' => 14, 'icon' => 'PvP_R14', 'a_title' => 'Grand Marshal', 'h_title' => 'High Warlord'];
            } else {
                $rank = ['rank' => 0, 'icon' => 'PvP_R0', 'a_title' => 'N/A', 'h_title' => 'N/A'];
            }

            if ($this->wowgeneral->getRedisCMS()) {
                // Cache for 6 hour
                $this->cache->redis->save('honorRankPointFArrayID_' . $id, $rank, 60 * 60 * 6);
            }
        }

        return $rank;
    }

    public function getCharEquipDisplayModel($id, $equipment, $class, $fallback, $patch = false): array
    {
        $world              = $this->load->database('world', true);
        $subQ               = $world->select("max(patch)", false)->where("t1.entry=t2.entry")->where("patch <=", $patch ?? '10')->get_compiled_select("item_template t2");
        $ignoredItems       = [2, 11, 12, 15, 24, 25, 26, 27, 28];
        $displayModelFCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('displayModelFArrayID_' . $id . '-P_' . ($patch ?? '10')) : false;
        $displayModelCache  = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('displayModelArrayID_' . $id . '-P_' . ($patch ?? '10')) : false;


        if ($class == 3) { //Hunter, to show ranged instead of weapon
            $ignoredItems = [2, 11, 12, 13, 17, 21, 22, 24, 25, 27, 28];
        }
        if ($fallback) {
            if ($displayModelFCache) {
                $itemArr = $displayModelFCache;
            } else {
                $itemArr = [];
                $result  = $world->select('entry, display_id, inventory_type')->where('patch=(' . $subQ . ')')->where_in('entry', $equipment)->where_not_in('inventory_type', $ignoredItems)->order_by('inventory_type')->get('item_template t1')->result_array();

                if ($result) {
                    foreach ($result as $item) {
                        $items       = new stdClass();
                        $items->item = (object)['entry' => $item['entry'], 'displayid' => $item['display_id']];
                        $items->slot = $item['inventory_type'];
                        $itemArr[]   = $items;
                    }

                    if ($this->wowgeneral->getRedisCMS()) {
                        // Cache for 1 hour
                        $this->cache->redis->save('displayModelFArrayID_' . $id . '-P_' . ($patch ?? '10'), $itemArr, 60 * 60);
                    }
                }
            }

            return $itemArr;
        } else {
            if ($displayModelCache) {
                $itemObj = $displayModelCache;
            } else {
                $itemObj = $world->select('inventory_type, display_id')->where('patch=(' . $subQ . ')')->where_in('entry', $equipment)->where_not_in('inventory_type', $ignoredItems)->order_by('inventory_type')->get('item_template t1')->result();

                if ($this->wowgeneral->getRedisCMS()) {
                    // Cache for 1 hour
                    $this->cache->redis->save('displayModelArrayID_' . $id . '-P_' . ($patch ?? '10'), $itemObj, 60 * 60);
                }
            }
        }

        return $itemObj;
    }

    public function getCharEquipDisplayIconName($id, $patch = null)
    {
        $world = $this->load->database('world', true);
        $world->select('i.icon, MAX(t.patch) AS patch');
        $world->from('item_display_info i');
        $world->join('item_template t', 'i.ID = t.display_id', 'left');
        $world->where('t.entry', $id);
        $world->where('patch <=', $patch ?? '10');
        $result = $world->get();

        if ($result->num_rows() > 0) {
            return $result->row('icon');
        }

        return false;
    }

    public function getCharEquipDisplayIcon($id, $patch = null): string
    {
        if (isset($id) && ! empty($id) && ctype_digit($id)) {
            $displayCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('itemDisplayID_' . $id . '-P_' . ($patch ?? '10')) : false;

            if ($displayCache) {
                $displayName = $displayCache;
            } else {
                $displayName = $this->armory_model->getCharEquipDisplayIconName($id, $patch);

                if (! $displayName) {
                    $displayName = 'INV_Misc_QuestionMark';
                }
                if ($this->wowgeneral->getRedisCMS()) {
                    // Cache for 7 day
                    $this->cache->redis->save('itemDisplayID_' . $id . '-P_' . ($patch ?? '10'), $displayName, 60 * 60 * 24 * 7);
                }
            }

            return base_url() . 'application/modules/database/assets/images/icons/' . $displayName . '.png"';
        }

        return base_url() . 'application/modules/database/assets/images/icons/INV_Misc_QuestionMark.png"';
    }

    public function searchChar($MultiRealm, $search)
    {
        $this->multiRealm = $MultiRealm;

        return $this->multiRealm->select('*')->like('LOWER(name)', strtolower($search))->get('characters');
    }

    public function searchGuild($MultiRealm, $search)
    {
        $this->multiRealm = $MultiRealm;

        return $this->multiRealm->select('*')->like('LOWER(name)', strtolower($search))->get('guild');
    }

    public function getGuildInfo($MultiRealm, $guildid)
    {
        $this->multiRealm = $MultiRealm;

        return $this->multiRealm->select('*')->where('guild_id', $guildid)->get('guild');
    }

    public function getGuildMembers($MultiRealm, $guildid)
    {
        $this->multiRealm = $MultiRealm;
        $this->multiRealm->select('a.guid,a.name,a.race,a.class, a.gender,a.level,b.guild_id');
        $this->multiRealm->from('characters a');
        $this->multiRealm->join('guild_member b', 'a.guid = b.guid', 'left');
        $this->multiRealm->where('guild_id', $guildid);

        return $this->multiRealm->get();
    }

    public function getGuildInfoByPlayerID($MultiRealm, $playerid): array
    {
        $this->multiRealm = $MultiRealm;
        $this->multiRealm->select('g.guild_id, g.name');
        $this->multiRealm->from('guild g');
        $this->multiRealm->join('guild_member gm', 'g.guild_id = gm.guild_id ', 'left');
        $this->multiRealm->where('guid', $playerid);
        $result = $this->multiRealm->get();

        if ($result->num_rows() > 0) {
            return $result->row_array();
        }

        return [];
    }
}
