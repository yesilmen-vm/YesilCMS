<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

/**
 * @property General_model  $wowgeneral
 * @property Armory_model   $armory_model
 * @property Database_model $Database_model
 */
class Api_v1 extends
    REST_Controller
{

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->model('Database/Database_model');
    }

    /**
     * Hello
     */
    public function index_get()
    {
        $data = 'Ready for action.';
        $this->response([
            'status'  => true,
            'message' => $data
        ], REST_Controller::HTTP_OK);
    }

    public function classic_displayid_get($id = 0)
    {
        if ($id > 0) {
            $classicDisplayCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('itemClassicDisplayID_' . $id) : false;
            if ($classicDisplayCache) {
                $status = REST_Controller::HTTP_OK;
                $data   = ['newDisplayId' => (int)$classicDisplayCache];
            } else {
                $appearanceId = $this->wowgeneral->getModifiedItemAppearance($id, 10);
                if ($appearanceId > 0) {
                    $displayId = $this->wowgeneral->getItemAppearance($appearanceId, 10);;
                    if ($displayId > 0) {
                        $status = REST_Controller::HTTP_OK;
                        $data   = ['newDisplayId' => (int)$displayId];
                        if ($this->wowgeneral->getRedisCMS()) {
                            // Cache for 1 day
                            $this->cache->redis->save('itemClassicDisplayID_' . $id, $displayId, 86400);
                        }
                    } else {
                        $status = REST_Controller::HTTP_NOT_FOUND;
                        $data   = [
                            'status'       => $status,
                            'errorMessage' => 'Item display info ID not found.'
                        ];
                    }
                } else {
                    $status = REST_Controller::HTTP_NOT_FOUND;
                    $data   = [
                        'status'       => $status,
                        'errorMessage' => 'Item appearance ID not found.'
                    ];
                }
            }
        } else {
            $status = REST_Controller::HTTP_BAD_REQUEST;
            $data   = [
                'status'       => $status,
                'errorMessage' => $id . ' is not supported for item_id field.'
            ];
        }
        $this->response($data, $status);
    }

    public function item_get(int $id = 0, int $patch = 10)
    {
        $this->load->config("shared_dbc");

        if ($id <= 0) {
            $status = REST_Controller::HTTP_BAD_REQUEST;
            $data   = [
                'status'       => $status,
                'errorMessage' => $id . ' is not supported for item_id field.'
            ];
        } elseif ($patch < 0 || $patch > 10) {
            $status = REST_Controller::HTTP_BAD_REQUEST;
            $data   = [
                'status'       => $status,
                'errorMessage' => 'Patch ' . $patch . ' is not supported for patch field.'
            ];
        } else {
            $item = $this->Database_model->getItem($id, $patch);

            if ($item) {
                $item_info = [];

                $item_info['id']          = $item['entry'];
                $item_info['name']        = $item['name'];
                $item_info['description'] = $item['description'] === '' ? null : $item['description'];
                $item_info['patch']       = $item['patch'];
                $item_info['added_patch'] = $this->Database_model->getAddedPatch($id);
                $item_info['icon']        = $this->Database_model->getIconName($id, $patch);
                $item_info['quality']     = ['id' => $item['quality'], 'name' => itemQuality($item['quality'])];
                $item_info['flags']       = $item['flags'];

                $item_info['buy_count']  = $item['buy_count'];
                $item_info['buy_price']  = $item['buy_price'];
                $item_info['sell_price'] = $item['sell_price'];


                if (! empty($item['max_count']) && strlen($item['max_count']) > 0) {
                    $item_info['max_count'] = itemCount($item['max_count']);
                }

                $item_info['item_class']    = ['id' => $item['class']];
                $item_info['item_subclass'] = ['id' => $item['subclass']];

                if (in_array($item['class'], [2, 4, 6])) {
                    $item_info['item_class']['name']    = itemClass($item['class']);
                    $item_info['item_subclass']['name'] = itemSubClass($item['class'], $item['subclass']);
                }

                $inv_type                   = itemInventory($item['inventory_type']);
                $item_info['item_inv_type'] = ['id' => $item['inventory_type'], 'name' => $inv_type ?: null];

                if (isWeapon($item['class'])) {
                    $dmg_min = $dmg_max = 0;
                    for ($i = 1; $i <= 5; $i++) {
                        if ($item['dmg_min' . $i] <= 0 || $item['dmg_max' . $i] <= 0) {
                            continue;
                        }

                        $item_info['weapon_damage_list'][$i] = ['min' => $item['dmg_min' . $i], 'max' => $item['dmg_max' . $i], 'type' => $item['dmg_type' . $i]];

                        $dmg_min += $item['dmg_min' . $i];
                        $dmg_max += $item['dmg_max' . $i];
                    }
                    $dps                     = number_format(($dmg_min + $dmg_max) / (2 * ($item['delay'] / 1000)), 1);
                    $item_info['weapon_dps'] = $dps ?? null;
                }

                $item_info['range_mod'] = $item['range_mod'];
                $item_info['ammo_type'] = $item['ammo_type'];

                if ($item['armor']) {
                    $item_info['item_armor'] = $item['armor'];
                }

                if ($item['block']) {
                    $item_info['item_block'] = $item['block'];
                }

                for ($j = 1; $j <= 10; $j++) {
                    if ($item['stat_type' . $j] < 0 || ! $item['stat_value' . $j]) { //val can be negative
                        continue;
                    }

                    $item_info['stat_list'][$j] = ['stat_id' => $item['stat_type' . $j], 'value' => $item['stat_value' . $j]];
                }

                $resistance_list = [
                    'holy_res',
                    'fire_res',
                    'nature_res',
                    'frost_res',
                    'shadow_res',
                    'arcane_res'
                ];

                foreach ($resistance_list as $resistance) {
                    if ($resistance && $item[$resistance] != 0) {
                        $item_info['resistance_list'][$resistance] = $item[$resistance];
                    }
                }

                if ($item['random_property']) {
                    $item_info['random_ench'] = '&lt;Random Enchantment&gt';
                }

                if ($item['max_durability']) {
                    $item_info['durability'] = $item['max_durability'];
                }

                if ($item['required_level'] > 1) {
                    $item_info['req_level'] = $item['required_level'];
                }

                if (in_array($item['class'], [2, 4])) {
                    $item_info['item_level'] = $item['item_level'];
                }

                if ($item['required_spell'] > 0) {
                    $item_info['required_spell'] = $item['required_spell'];
                }

                if ($item['required_skill'] > 0) {
                    $item_info['required_skill'] = ['skill' => $item['required_skill'], 'rank' => ['required_skill_rank']];
                }

                $allowedClass = getAllowableClass($item['allowable_class'], false);
                if ($item['allowable_class'] > 0 && $allowedClass) {
                    $item_info['allowed_class_list'] = $allowedClass;
                }

                $allowedRace = getAllowableRace($item['allowable_race']);
                if ($item['allowable_race'] > 0 && $allowedRace) {
                    $item_info['allowed_race_list'] = $allowedRace;
                }

                if ($item['required_honor_rank']) {
                    $item_info['required_honor'] = $item['required_honor_rank'];
                }

                if ($item['required_reputation_faction']) {
                    $item_info['required_rep'] = ['faction' => $item['required_reputation_faction'], 'rank' => $item['required_reputation_rank']];
                }

                $itemSpellsAndTrigger = [];
                for ($k = 1; $k <= 5; $k++) {
                    if ($item['spellid_' . $k] == 0) {
                        continue;
                    }

                    $cd = $item['spellcooldown_' . $k];
                    if ($cd < $item['spellcategorycooldown_' . $k]) {
                        $cd = $item['spellcategorycooldown_' . $k];
                    }

                    $extra = [];
                    if ($cd >= 5000) {
                        $extra[] = sprintf('%s cooldown', formatTime($cd, true));
                    }
                    if ($item['spelltrigger_' . $k] == 2) {
                        if ($ppm = $item['spellppmrate_' . $k]) {
                            $extra[] = sprintf('%s procs per minute', $ppm);
                        }
                    }

                    $itemSpellsAndTrigger[$item['spellid_' . $k]] = [$item['spelltrigger_' . $k], $extra ? ' (' . implode(', ', $extra) . ')' : ''];
                }

                if ($itemSpellsAndTrigger) {
                    $itemSpells = array_keys($itemSpellsAndTrigger);

                    foreach ($itemSpells as $sid) {
                        $output[] = $this->config->item('trigger')[$itemSpellsAndTrigger[$sid][0]] . $this->Database_model->getSpellDetails($sid, $patch) . $itemSpellsAndTrigger[$sid][1];
                    }
                    $item_info['spell_list'] = $output ?? [];
                }

                // Item Set
                if ($item['set_id'] && $this->config->item('item_set')[$item['set_id']]) {
                    $item_info['item_set']['name'] = $this->config->item('item_set')[$item['set_id']][0];

                    for ($i = 0; $i < 10; $i++) {
                        if ($this->config->item('item_set')[$item['set_id']][1][$i]) {
                            $item_info['item_set']['item_list'][$this->config->item('item_set')[$item['set_id']][1][$i]]
                                = $this->Database_model->getItemName($this->config->item('item_set')[$item['set_id']][1][$i], $patch);
                        }
                    }

                    $set_key   = [];
                    $set_spell = [];
                    for ($j = 0; $j < 8; $j++) {
                        if ($this->config->item('item_set')[$item['set_id']][2][$j]) {
                            $set_spell[] = $this->Database_model->getSpellDetails($this->config->item('item_set')[$item['set_id']][2][$j]);
                        }

                        if ($this->config->item('item_set')[$item['set_id']][3][$j]) {
                            $set_key[] = $this->config->item('item_set')[$item['set_id']][3][$j];
                        }
                    }

                    $tmp = array_combine($set_key, $set_spell);
                    unset($i, $j, $set_key, $set_spell);
                    ksort($tmp);

                    if ($tmp) {
                        foreach ($tmp as $sid => $spell) {
                            $item_info['item_set']['set_list'][$sid] = $spell;
                        }
                    }
                    unset($tmp);
                }

                $item_info['start_quest'] = $item['start_quest'];

                // Convert string - int
                $item_info = json_encode($item_info, JSON_NUMERIC_CHECK);
                $item_info = json_decode($item_info);

                $status = REST_Controller::HTTP_OK;
                $data   = $item_info;
            } else {
                $status = REST_Controller::HTTP_NOT_FOUND;
                $data   = [
                    'status'       => $status,
                    'errorMessage' => 'Item not found. Item ' . $id . ' does not exists in the database or not implemented in Patch ' . getPatchName($patch)
                ];
            }
        }
        $this->response($data, $status);
    }

    public function tooltip_item_get(int $id = 0, int $patch = 10)
    {
        $this->load->config("shared_dbc");

        if ($id <= 0) {
            $status = REST_Controller::HTTP_BAD_REQUEST;
            $data   = [
                'status'       => $status,
                'errorMessage' => $id . ' is not supported for item_id field.'
            ];
        } elseif ($patch < 0 || $patch > 10) {
            $status = REST_Controller::HTTP_BAD_REQUEST;
            $data   = [
                'status'       => $status,
                'errorMessage' => 'Patch ' . $patch . ' is not supported for patch field.'
            ];
        } else {
            $itemTooltipCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('itemTooltipID_' . $id . '-P_' . ($patch ?? '10')) : false;

            if ($itemTooltipCache) {
                $status = REST_Controller::HTTP_OK;
                $data   = $itemTooltipCache;
            } else {
                $item = $this->Database_model->getItem($id, $patch);

                if ($item) {
                    $item_info = [];

                    $item_info['id']      = $item['entry'];
                    $item_info['type']    = 'item';
                    $item_info['name']    = $item['name'];
                    $item_info['icon']    = $this->Database_model->getIconName($id, $patch);
                    $item_info['quality'] = itemQuality($item['quality']);

                    $item_info['tooltip'] = '<div class="yesilcms-dyn" style="max-width:20rem;">';
                    $item_info['tooltip'] .= '<span class="q' . $item['quality'] . '" style="font-size: 16px">' . $item['name'] . '</span><br />';

                    if (in_array($item['class'], [2, 4])) {
                        $item_info['tooltip'] .= '<span class="q">' . sprintf('Item Level %d', $item['item_level']) . '</span><br />';
                    }

                    if ($item['bonding']) {
                        $item_info['tooltip'] .= itemBonding($item['bonding']) . '<br />';
                    }

                    if (! empty($item['max_count']) && strlen($item['max_count']) > 0) {
                        $item_info['tooltip'] .= itemCount($item['max_count'] ?? '') . '<br />';
                    }

                    $inv_type             = itemInventory($item['inventory_type']);
                    $item_info['tooltip'] .= $inv_type ? '<div style="float:left;">' . $inv_type . '</div>' : '';

                    if (in_array($item['class'], [2, 4, 6])) {
                        if ($item['class'] == 2 && $item['subclass'] > 0) {
                            $item_info['tooltip'] .= '<div style="float:right;">' . itemSubClass($item['class'], $item['subclass']) . '</div>';
                        }
                        $item_info['tooltip'] .= '<div style="clear:both;"></div>';
                    }

                    if ($item['armor']) {
                        $item_info['tooltip'] .= $item['armor'] . ' Armor<br />';
                    }

                    if ($item['block']) {
                        $item_info['tooltip'] .= $item['block'] . ' Block<br />';
                    }

                    if (isWeapon($item['class'])) {
                        $dmg_min = $dmg_max = 0;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($item['dmg_min' . $i] <= 0 || $item['dmg_max' . $i] <= 0) {
                                continue;
                            }

                            $dmg_list[] = weaponDamage($item['dmg_min' . $i], $item['dmg_max' . $i], $item['dmg_type' . $i], $i);

                            $dmg_min += $item['dmg_min' . $i];
                            $dmg_max += $item['dmg_max' . $i];
                        }
                        $dps = weaponDPS($dmg_min, $dmg_max, $item['delay']);

                        foreach ($dmg_list as $key => $dmg) {
                            if ($key === 0) {
                                $item_info['tooltip'] .= '<div style="float:left;">' . $dmg . '</div>';
                                $item_info['tooltip'] .= '<div style="float:right;margin-left:15px;">Speed ' . number_format($item['delay'] / 1000, 2) . '</div><br />';
                            } else {
                                $item_info['tooltip'] .= $dmg . '<br />';
                            }
                        }
                        $item_info['tooltip'] .= '(' . $dps . ')<br />';
                    }

                    for ($j = 1; $j <= 10; $j++) {
                        if ($item['stat_type' . $j] < 0 || ! $item['stat_value' . $j]) { //val can be negative
                            continue;
                        }

                        $item_info['tooltip'] .= itemStat($item['stat_type' . $j], $item['stat_value' . $j]) . '<br />';
                    }

                    $resistance_list = [
                        'holy_res',
                        'fire_res',
                        'nature_res',
                        'frost_res',
                        'shadow_res',
                        'arcane_res'
                    ];

                    foreach ($resistance_list as $key => $resistance) {
                        if ($resistance && $item[$resistance] != 0) {
                            $item_info['tooltip'] .= itemResistance($item[$resistance], $key) . '<br />';
                        }
                    }

                    if ($item['random_property']) {
                        $item_info['tooltip'] .= '<span class="q2">&lt;Random Enchantment&gt;</span><br/>';
                    }

                    $item_info['tooltip'] .= '<div class="q2" id="tooltip-item-enchantments"></div>';

                    if ($item['max_durability']) {
                        $item_info['tooltip'] .= sprintf('Durability %d / %d', $item['max_durability'], $item['max_durability']) . '<br />';
                    }

                    if ($item['required_level'] > 1) {
                        $item_info['tooltip'] .= sprintf('Requires Level %d', $item['required_level']) . '<br />';
                    }

                    if ($item['required_spell'] > 0) {
                        $item_info['tooltip'] .= 'Requires ' . $this->Database_model->getReqSpellName($item['required_spell'], $patch) . '<br />';
                    }

                    if ($item['required_skill'] > 0) {
                        $item_info['tooltip'] .= requiredSkill($item['required_skill'], $item['required_skill_rank']) . '<br />';
                    }

                    $allowedClass = getAllowableClass($item['allowable_class']);
                    if ($item['allowable_class'] > 0 && $allowedClass) {
                        $item_info['tooltip'] .= 'Classes: ' . $allowedClass . '<br />';
                    }

                    $allowedRace = getAllowableRace($item['allowable_race']);
                    if ($item['allowable_race'] > 0 && $allowedRace) {
                        $item_info['tooltip'] .= 'Races: ' . $allowedRace . '<br />';
                    }

                    if ($item['required_honor_rank']) {
                        $item_info['tooltip'] .= '<span class="q10">Requires ' . getRankByFaction($item['name'], $item['required_honor_rank']) . '</span><br />';
                    }

                    if ($item['required_reputation_faction']) {
                        $item_info['tooltip'] .= '<span class="q10">Requires ' . $this->Database_model->getFactionName($item['required_reputation_faction']) .
                                                 ' - ' . getRepRank($item['required_reputation_rank']) . '</span><br />';
                    }

                    $itemSpellsAndTrigger = [];
                    for ($k = 1; $k <= 5; $k++) {
                        if ($item['spellid_' . $k] == 0) {
                            continue;
                        }

                        $cd = $item['spellcooldown_' . $k];
                        if ($cd < $item['spellcategorycooldown_' . $k]) {
                            $cd = $item['spellcategorycooldown_' . $k];
                        }

                        $extra = [];
                        if ($cd >= 5000) {
                            $extra[] = sprintf('%s cooldown', formatTime($cd, true));
                        }
                        if ($item['spelltrigger_' . $k] == 2) {
                            if ($ppm = $item['spellppmrate_' . $k]) {
                                $extra[] = sprintf('%s procs per minute', $ppm);
                            }
                        }

                        $itemSpellsAndTrigger[$item['spellid_' . $k]] = [$item['spelltrigger_' . $k], $extra ? ' (' . implode(', ', $extra) . ')' : ''];
                    }

                    if ($itemSpellsAndTrigger) {
                        $itemSpells = array_keys($itemSpellsAndTrigger);

                        foreach ($itemSpells as $sid) {
                            $item_info['tooltip'] .= '<a class="q2" href="' . base_url() . '/spell/' . $sid . '" target="_blank">' . $this->config->item('trigger')[$itemSpellsAndTrigger[$sid][0]] . $this->Database_model->getSpellDetails($sid, $patch) . $itemSpellsAndTrigger[$sid][1] . '</a><br />';
                        }
                    }

                    // Item Set
                    if ($item['set_id'] && $this->config->item('item_set')[$item['set_id']]) {
                        $itemset_name = $this->config->item('item_set')[$item['set_id']][0];

                        for ($i = 0; $i < 10; $i++) {
                            if ($this->config->item('item_set')[$item['set_id']][1][$i]) {
                                $itemset_item_list[$this->config->item('item_set')[$item['set_id']][1][$i]]
                                    = $this->Database_model->getItemName($this->config->item('item_set')[$item['set_id']][1][$i], $patch);
                            }
                        }

                        $set_key   = [];
                        $set_spell = [];
                        for ($j = 0; $j < 8; $j++) {
                            if ($this->config->item('item_set')[$item['set_id']][2][$j]) {
                                $set_spell[] = $this->Database_model->getSpellDetails($this->config->item('item_set')[$item['set_id']][2][$j]);
                            }

                            if ($this->config->item('item_set')[$item['set_id']][3][$j]) {
                                $set_key[] = $this->config->item('item_set')[$item['set_id']][3][$j];
                            }
                        }

                        $tmp = array_combine($set_key, $set_spell);
                        unset($i, $j, $set_key, $set_spell);
                        ksort($tmp);

                        if ($tmp) {
                            foreach ($tmp as $sid => $spell) {
                                $itemset_set_list[$sid] = $spell;
                            }
                        }
                        unset($tmp);

                        $item_info['tooltip'] .= '<div id="tooltip-item-set" style="padding-top: 10px;">';
                        $item_info['tooltip'] .= '<div class="q" id="tooltip-item-set-name">' . $itemset_name . ' (<span id="tooltip-item-set-count">0</span>/' . count($itemset_item_list) . ')</div>';
                        $item_info['tooltip'] .= '<div id="tooltip-item-set-pieces" style="padding-left: .6em">';
                        $item_info['tooltip'] .= '<div class="q0 indent">';
                        foreach ($itemset_item_list as $item_id => $piece) {
                            $item_info['tooltip'] .= '<span class="item-set-piece" data-itemset-item-entry="' . $item_id . '" data-possible-entries="' . $item_id . '">' . $piece . '</span> <br/>';
                        }
                        $item_info['tooltip'] .= '</div></div><div id="tooltip-item-set-bonuses" style="padding-top: 10px;"><div class="q0">';
                        foreach ($itemset_set_list as $threshold => $set) {
                            $item_info['tooltip'] .= '<span class="item-set-bonus" data-bonus-required-items="' . $threshold . '">(' . $threshold . ') Set: <span id="set-bonus-text">' . $set . '</span></span> <br/>';
                        }
                        $item_info['tooltip'] .= '</div></div></div>';
                    }

                    if ($item['description'] !== '') {
                        $item_info['tooltip'] .= '<span class="q">"' . $item['description'] . '"</span>';
                    }

                    $item_info['tooltip'] .= '</div>';

                    $status = REST_Controller::HTTP_OK;
                    $data   = $item_info;

                    if ($this->wowgeneral->getRedisCMS() && $item_info['tooltip']) {
                        // Cache for 30 day
                        $this->cache->redis->save('itemTooltipID_' . $id . '-P_' . ($patch ?? '10'), $data, 60 * 60 * 24 * 30);
                    }
                } else {
                    $status = REST_Controller::HTTP_NOT_FOUND;
                    $data   = [
                        'status'       => $status,
                        'errorMessage' => 'Item not found. Item ' . $id . ' does not exists in the database or not implemented in Patch ' . getPatchName($patch)
                    ];
                }
            }
        }
        $this->response($data, $status);
    }

    public function tooltip_spell_get(int $id = 0, int $patch = 10)
    {
        $this->load->config("shared_dbc");
        $this->load->model("Armory/armory_model");

        if ($id <= 0) {
            $status = REST_Controller::HTTP_BAD_REQUEST;
            $data   = [
                'status'       => $status,
                'errorMessage' => $id . ' is not supported for spell_id field.'
            ];
        } elseif ($patch < 0 || $patch > 10) {
            $status = REST_Controller::HTTP_BAD_REQUEST;
            $data   = [
                'status'       => $status,
                'errorMessage' => 'Patch ' . $patch . ' is not supported for patch field.'
            ];
        } else {
            $spellTooltipCache = $this->wowgeneral->getRedisCMS() ? $this->cache->redis->get('spellTooltipID' . $id . '-P_' . ($patch ?? '10')) : false;

            if ($spellTooltipCache) {
                $status = REST_Controller::HTTP_OK;
                $data   = $spellTooltipCache;
            } else {
                $spell = $this->Database_model->getSpell($id, $patch);

                if ($spell) {
                    $spell_info = [];

                    $spell_info['id']   = $spell['entry'];
                    $spell_info['type'] = 'spell';
                    $spell_info['name'] = $spell['name'];
                    $spell_info['icon'] = $this->config->item('spell_icons')[$spell['spellIconId']] ?? 'Trade_Engineering';
                    $spell['cost']      = spellPowerCost($spell['powerType'], $spell['manaCost'], $spell['manCostPerLevel'], $spell['manaPerSecond']);
                    $spell['range']     = spellRange($this->config->item('range_index')[$spell['rangeIndex']]);
                    $spell['cast']      = spellCastTime($this->config->item('cast_index')[$spell['castingTimeIndex']], $spell['attributesEx'], $spell['powerType']);
                    $spell['cooldown']  = spellCD($spell['recoveryTime']);
                    $spell['desc']      = $this->Database_model->getSpellDetails($spell['entry'], $patch);

                    //Tools
                    $spell['tools'] = [];
                    for ($i = 1; $i <= 2; $i++) {
                        // Tools
                        if (! $spell['totem' . $i]) {
                            continue;
                        }

                        $spell['tools'][$i - 1] = [
                            'id'      => $spell['totem' . $i],
                            'name'    => $this->Database_model->getItemName($spell['totem' . $i], $patch),
                            'icon'    => $this->Database_model->getIconName($spell['totem' . $i], $patch),
                            'quality' => $this->armory_model->getCharEquipmentQualityPatch($spell['totem' . $i], $patch)['quality'] //$this->relItems->getField('quality')
                        ];
                    }

                    //Reagents
                    $spell['reagents'] = [];

                    for ($i = 1; $i <= 8; $i++) {
                        if ($spell['reagent' . $i] > 0 && $spell['reagentCount' . $i]) {
                            //$spell['reagents'][$spell['reagent' . $i]] = [$spell['reagent' . $i], $spell['reagentCount' . $i]];

                            $spell['reagents'][$i - 1] = [
                                'id'      => $spell['reagent' . $i],
                                'count'   => $spell['reagentCount' . $i],
                                'name'    => $this->Database_model->getItemName($spell['reagent' . $i], $patch),
                                'icon'    => $this->Database_model->getIconName($spell['reagent' . $i], $patch),
                                'quality' => $this->armory_model->getCharEquipmentQualityPatch($spell['reagent' . $i], $patch)['quality'] //$this->relItems->getField('quality')
                            ];
                        }
                    }

                    $spell_info['tooltip'] = '<div class="yesilcms-dyn" style="max-width:20rem;"><table><tr><td>';

                    if ($spell['nameSubtext']) {
                        $spell_info['tooltip'] .= '<table width="100%"><tr><td><b>' . $spell['name'] . '</b></td><th style="float:right"><b class="q0">' . $spell['nameSubtext'] . '</b></th></tr></table>';
                    } else {
                        $spell_info['tooltip'] .= '<b>' . $spell['name'] . '</b><br/>';
                    }

                    if ($spell['cost'] && $spell['range']) {
                        $spell_info['tooltip'] .= '<table width="100%"><tr><td>' . $spell['cost'] . '</td><th style="float:right">' . $spell['range'] . '</th></tr></table>';
                    } elseif ($spell['cost'] || $spell['range']) {
                        $spell_info['tooltip'] .= $spell['range'] . $spell['cost'];
                    }
                    if (($spell['cost'] xor $spell['range']) && ($spell['cast'] xor $spell['cooldown'])) {
                        $spell_info['tooltip'] .= '<br/>';
                    }
                    if ($spell['cast'] && $spell['cooldown']) {
                        $spell_info['tooltip'] .= '<table width="100%"><tr><td>' . $spell['cast'] . '</td><th style="float:right">' . $spell['cooldown'] . '</th></tr></table>';
                    } else {
                        $spell_info['tooltip'] .= $spell['cast'] . $spell['cooldown'];
                    }

                    if ($spell['tools'] || $spell['reagents']) {
                        $spell_info['tooltip'] .= '<table><tr><td>';

                        if ($spell['tools']) {
                            $spell_info['tooltip'] .= 'Tools: <br/><div class="indent q1">';
                            $numItems              = count($spell['tools']);
                            $i                     = 0;
                            foreach ($spell['tools'] as $t) {
                                if (isset($t['id'])) {
                                    $spell_info['tooltip'] .= '<a href="' . base_url() . '/item/' . $t['id'] . '/' . $patch . '"><span class="q' . $t['quality'] . '">' . $t['name'] . '</span></a>';
                                } else {
                                    $spell_info['tooltip'] .= $t['name'];
                                }
                                $spell_info['tooltip'] .= empty(++$i !== $numItems) ? '<br />' : ', ';
                            }
                            $spell_info['tooltip'] .= '</div><br/>';
                        }
                        if ($spell['reagents']) {
                            $spell_info['tooltip'] .= 'Reagents: <br/><div class="indent q1">';
                            $numItems              = count($spell['reagents']);
                            $i                     = 0;
                            foreach ($spell['reagents'] as $r) {
                                $spell_info['tooltip'] .= '<a href="' . base_url() . '/item/' . $r['id'] . '/' . $patch . '"><span class="q' . $r['quality'] . '">' . $r['name'] . '</span></a>';
                                if ($r['count'] > 1) {
                                    $spell_info['tooltip'] .= '(' . $r['count'] . ')';
                                }
                                $spell_info['tooltip'] .= empty(++$i !== $numItems) ? '<br />' : ', ';
                            }
                            $spell_info['tooltip'] .= '</div></td></tr></table>';
                        }
                    }

                    if ($spell['desc']) {
                        $spell_info['tooltip'] .= '<table><tr><td><span class="q">' . $spell['desc'] . '</span><br/></td></tr></table>';
                    }

                    $spell_info['tooltip'] .= '</td></tr></table></div>';

                    $status = REST_Controller::HTTP_OK;
                    $data   = str_replace(array("\n", "\r"), '', $spell_info); //get rid of nl2br

                    if ($this->wowgeneral->getRedisCMS() && $spell_info['tooltip']) {
                        // Cache for 30 day
                        $this->cache->redis->save('spellTooltipID' . $id . '-P_' . ($patch ?? '10'), $data, 60 * 60 * 24 * 30);
                    }
                } else {
                    $status = REST_Controller::HTTP_NOT_FOUND;
                    $data   = [
                        'status'       => $status,
                        'errorMessage' => 'Spell not found. Spell ' . $id . ' does not exists in the database or not implemented in Patch ' . getPatchName($patch)
                    ];
                }
            }
        }
        $this->response($data, $status);
    }

    public function search_db_post()
    {
        $this->load->config("shared_dbc"); // for icons
        $search       = $this->input->post('q');
        $patch        = ($this->input->post('p') !== null && $this->input->post('p') >= 0 && $this->input->post('p') <= 10) ? $this->input->post('p') : 10; //noone dies from extra security
        $search_item  = [];
        $search_spell = [];

        if (isset($search) && strlen($search) >= 3 && ! preg_match("/[^A-Za-z0-9 '&,._-]/", $search)) {
            $search_item  = $this->Database_model->searchItem($search, $patch, 10);
            $search_spell = $this->Database_model->searchSpell($search, $patch, 10);

            $status = REST_Controller::HTTP_OK;
            if ($search_item || $search_spell) {
                $data['result'] = array_merge($search_item, $search_spell);
            } else {
                $data['result'] = [];
            }
            $data['token'] = $this->security->get_csrf_hash();
        } else {
            $status = REST_Controller::HTTP_BAD_REQUEST;
            $data   = [
                'status'       => $status,
                'errorMessage' => $search . ' has illegal search characters.',
                'token'        => $this->security->get_csrf_hash()
            ];
        }
        $this->response($data, $status);
    }

    // Need to use this instead of file_get_contents thanks to OpenSSL bug (0A000126:SSL)
    private function getUrlContents($url)
    {
        if (! function_exists('curl_init')) {
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
}
