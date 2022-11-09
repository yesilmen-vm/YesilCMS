<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property General_model  $wowgeneral
 * @property Realm_model    $wowrealm
 * @property Module_model   $wowmodule
 * @property Template       $template
 * @property Armory_model   $armory_model
 * @property Database_model $Database_model
 */
class Database extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Database_model');

        if ($this->wowgeneral->getMaintenance()) {
            redirect(base_url('maintenance'), 'refresh');
        }

        if (! $this->wowmodule->getArmoryStatus()) {
            redirect(base_url(), 'refresh');
        }
    }

    public function index()
    {
        $this->load->helper('cookie');
        $patch = 10;

        if ($this->input->method() == 'post' && $this->input->post('globpatch') >= 0 && $this->input->post('globpatch') <= 10) {
            set_cookie("glob_patch", $this->input->post('globpatch'), 86400);
            //$_COOKIE['glob_patch'] = $this->input->post('globpatch'); // -- Keep it here, just incase.
            redirect($this->uri->uri_string());
        }

        if (get_cookie('glob_patch') !== null && get_cookie('glob_patch') >= 0 && get_cookie('glob_patch') <= 10) {
            $patch = (int)get_cookie('glob_patch');
        }

        $data = [
            'pagetitle' => 'YesilCMS Database',
            'lang'      => $this->lang->lang(),
            'patch'     => $patch
        ];

        $this->template->build('index', $data);
    }

    public function result()
    {
        $this->load->config("shared_dbc");
        $this->load->helper('cookie');
        $patch = 10;

        // Need to set here too to keep query strings while we are changing the global patch.
        if ($this->input->method() == 'post' && $this->input->post('globpatch') >= 0 && $this->input->post('globpatch') <= 10) {
            set_cookie("glob_patch", $this->input->post('globpatch'), 86400);
            redirect($this->uri->uri_string() . ($_SERVER['QUERY_STRING'] ? ('?' . $_SERVER['QUERY_STRING']) : ''));
        }

        if (get_cookie('glob_patch') !== null && get_cookie('glob_patch') >= 0 && get_cookie('glob_patch') <= 10) {
            $patch = (int)get_cookie('glob_patch');
        }

        $data = [
            'pagetitle' => 'Armory Search',
            'lang'      => $this->lang->lang(),
            'realms'    => $this->wowrealm->getRealms()->result(),
            'patch'     => $patch,
            'search'    => $this->input->get('search'),
            'items'     => [],
            'spells'    => []
        ];

        $search = $this->input->get('search');

        if (! empty($search) && strlen($search) >= 3) {
            $data['items']  = $this->Database_model->searchItem($search, $patch);
            $data['spells'] = $this->Database_model->searchSpell($search, $patch);
        }

        $this->template->build('result', $data);
    }

    public function item(int $entry = 0, int $patch = 10)
    {
        if ($entry <= 0) {
            redirect(base_url(), 'refresh');
        }

        if (! empty($patch) && $patch > 10) {
            redirect(base_url('404'), 'refresh');
        }

        $item = $this->Database_model->getItem($entry, $patch);
        $this->load->config("shared_dbc");

        if ($item) {
            // Additional data - start
            $item['icon'] = $this->Database_model->getIconName($entry, $patch);

            if (in_array($item['class'], [2, 4, 6])) {
                $item['iclass']    = itemClass($item['class']) ?? '';
                $item['isubclass'] = itemSubClass($item['class'], $item['subclass']) ?? '';
            }

            $inv_type         = itemInventory($item['inventory_type']);
            $item['inv_type'] = $inv_type ?: '';

            if ($item['required_spell'] > 0) {
                $item['req_spell'] = $this->Database_model->getReqSpellName($item['required_spell'], $patch);
            }

            if ($item['required_reputation_faction']) {
                $item['req_fact_rep'] = $this->Database_model->getFactionName($item['required_reputation_faction']);
            }

            if (isWeapon($item['class'])) {
                $dmg_min = $dmg_max = 0;
                for ($i = 1; $i <= 5; $i++) {
                    if ($item['dmg_min' . $i] <= 0 || $item['dmg_max' . $i] <= 0) {
                        continue;
                    }

                    $item['dmg_list'][] = weaponDamage($item['dmg_min' . $i], $item['dmg_max' . $i], $item['dmg_type' . $i], $i);

                    $dmg_min += $item['dmg_min' . $i];
                    $dmg_max += $item['dmg_max' . $i];
                }
                $item['dps'] = weaponDPS($dmg_min, $dmg_max, $item['delay']);
            }

            $item['stats'] = [];
            for ($j = 1; $j <= 10; $j++) {
                if ($item['stat_type' . $j] < 0 || ! $item['stat_value' . $j]) { //val can be negative
                    continue;
                }

                $item['stats'][] = itemStat($item['stat_type' . $j], $item['stat_value' . $j]);
            }

            $resistance_list = [
                'holy_res',
                'fire_res',
                'nature_res',
                'frost_res',
                'shadow_res',
                'arcane_res'
            ];

            $item['resistances'] = [];
            foreach ($resistance_list as $key => $resistance) {
                if ($resistance && $item[$resistance] != 0) {
                    $item['resistances'][] = itemResistance($item[$resistance], $key);
                }
            }

            $item['allowed_classes'] = getAllowableClass($item['allowable_class']);
            $item['allowed_races']   = getAllowableRace($item['allowable_race']);
            $item['trigger_text']    = $config['trigger'] = ["Use: ", "Equip: ", "Chance on hit: ", "", "", "", ""];

            $item['item_spells_trigger'] = [];
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

                $item['item_spells_trigger'][$item['spellid_' . $k]] = [$item['spelltrigger_' . $k], $extra ? ' (' . implode(', ', $extra) . ')' : ''];
            }

            // Item Set
            $pieces = [];
            if ($item['set_id'] && $this->config->item('item_set')[$item['set_id']]) {
                $item['itemset_set_name'] = $this->config->item('item_set')[$item['set_id']][0];

                for ($i = 0; $i < 10; $i++) {
                    if ($this->config->item('item_set')[$item['set_id']][1][$i]) {
                        $item['itemset_item_list'][$this->config->item('item_set')[$item['set_id']][1][$i]]
                            = $this->Database_model->getItemName($this->config->item('item_set')[$item['set_id']][1][$i], $patch);
                    }
                }

                $set_key   = [];
                $set_spell = [];
                for ($j = 0; $j < 8; $j++) {
                    if ($this->config->item('item_set')[$item['set_id']][2][$j]) {
                        $set_spell[] = [
                            $this->Database_model->getSpellDetails($this->config->item('item_set')[$item['set_id']][2][$j]),
                            $this->config->item('item_set')[$item['set_id']][2][$j]
                        ];
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
                        $item['itemset_set_list'][$sid] = $spell;
                    }
                }
                unset($tmp);
            }

            $item['added_patch'] = getPatchName($this->Database_model->getAddedPatch($entry));
            $item['patch_list']  = $this->Database_model->getPatchList($entry);
            $item['obtainable']  = (bool)($item['extra_flags'] & 0x04);
            $item['openable']    = (bool)($item['flags'] & 0x00000004);
            $item['partyloot']   = (bool)($item['flags'] & 0x00000800);
            $item['rtduration']  = (bool)($item['flags'] & 0x00010000);

            //Dropped by, Pickpocket-ed, Skinned
            $item['creature_list'] = $this->Database_model->getCreatureRelatedList($entry, $patch);
            // GO List (gathered, mined, contained)
            $item['go_list'] = $this->Database_model->getGORelatedList($entry, $patch);
            // Fished in
            $item['fished_list'] = $this->Database_model->getFishedInList($entry, $patch);
            // Contains, Disenchanted
            $item['item_list'] = $this->Database_model->getItemRelatedList($entry, $patch);
            // Contains (this item contains those items)
            $contains = $this->Database_model->getContainsList($entry, $patch);
            if ($contains) {
                $item['item_list']['contains'] = $contains;
            }
            // Disenchant List (output of disenchant)
            if ((int)$item['disenchant_id'] > 0) {
                $item['item_list']['disenchanting'] = $this->Database_model->getDisenchantList($item['disenchant_id'], $patch);
            }
            // Reward from
            $item['reward_list'] = $this->Database_model->getRewardList($entry, $patch);
            // Sold by
            $item['vendor_list'] = $this->Database_model->getVendorList($entry);
            // Starts Quest
            $item['start_q_info'] = [];
            if ($item['start_quest']) {
                $item['start_q_info'] = $this->Database_model->getQuest($item['start_quest'], $patch);

                for ($j = 1; $j <= 4; ++$j) {
                    if (($item['start_q_info']['RewItemId' . $j] != 0) && ($item['start_q_info']['RewItemCount' . $j] != 0)) {
                        $item['start_q_info']['itemrewards'][] = array_merge(
                            ['entry' => (int)$item['start_q_info']['RewItemId' . $j], 'icon' => $this->Database_model->getIconName($item['start_q_info']['RewItemId' . $j], $patch)],
                            array('count' => (int)$item['start_q_info']['RewItemCount' . $j])
                        );
                    }
                }
            }
            // Additional data - end

            $data = [
                'item'      => $item,
                'patch'     => $patch,
                'pagetitle' => 'Item > ' . $item['name'],
                'lang'      => $this->lang->lang(),
                'realms'    => $this->wowrealm->getRealms()->result(),
            ];
        } else {
            $data = [
                'item'      => false,
                'lang'      => $this->lang->lang(),
                'pagetitle' => 'Item not found',

            ];
        }

        $this->template->build('item', $data);
    }

    public function spell(int $entry = 0, int $patch = 10)
    {
        if ($entry <= 0) {
            redirect(base_url(), 'refresh');
        }

        if (! empty($patch) && $patch > 10) {
            redirect(base_url('404'), 'refresh');
        }

        $this->load->model('Armory/armory_model');
        $spell = $this->Database_model->getSpell($entry, $patch);
        $this->load->config("shared_dbc");
        $this->load->config("shared_dbc_enchants");

        if ($spell) {
            // Additional data - start
            $spell['icon']     = $this->config->item('spell_icons')[$spell['spellIconId']] ?? 'Trade_Engineering';
            $spell['range']    = spellRange($this->config->item('range_index')[$spell['rangeIndex']]);
            $spell['range_t']  = spellRange($this->config->item('range_index')[$spell['rangeIndex']], true);
            $spell['cost']     = spellPowerCost($spell['powerType'], $spell['manaCost'], $spell['manCostPerLevel'], $spell['manaPerSecond']);
            $spell['cast']     = spellCastTime($this->config->item('cast_index')[$spell['castingTimeIndex']], $spell['attributesEx'], $spell['powerType']);
            $spell['cooldown'] = spellCD($spell['recoveryTime']);
            $spell['cat']      = $this->config->item('spell_categories')[$spell['category']] ?? '';
            $spell['gcd']      = formatTime($spell['startRecoveryTime']);
            $spell['gcd_cat']  = $this->config->item('spell_categories')[$spell['startRecoveryCategory']] ?? '';

            // Duration
            $spell['duration'] = '';
            if ($spell['durationIndex']) {
                $spell['duration'] = $this->config->item('duration')[$spell['durationIndex']];
                if ($spell['duration'] < 0) {
                    $spell['duration'] = 'Until cancelled'; //or n/a
                } else {
                    $spell['duration'] = formatTime($spell['duration']);
                }
            }

            // Effects
            $spell['effects'] = [];
            for ($i = 1; $i < 4; $i++) {
                $effect = (int)$spell['effect' . $i];

                if (! $effect) {
                    continue;
                }

                $spell['effects'][$i - 1] = []; //default

                $aura      = (int)$spell['effectApplyAuraName' . $i];
                $trigger   = (int)$spell['effectTriggerSpell' . $i];
                $item      = (int)$spell['effectItemType' . $i];
                $misc      = (int)$spell['effectMiscValue' . $i];
                $base      = (int)$spell['effectBasePoints' . $i];
                $radius    = (int)$spell['effectRadiusIndex' . $i];
                $amplitude = (int)$spell['effectAmplitude' . $i];
                $mechanic  = (int)$spell['effectMechanic' . $i];

                // Where we don't want value to be displayed
                if (in_array($aura, [11, 12, 36, 77]) || $effect == 132) {
                    $value = '';
                } else {
                    if ($spell['effectDieSides' . $i] > 1) {
                        $value = $base + 1 . ' to ' . ($base + $spell['effectDieSides' . $i]);
                    } else {
                        $value = $base + 1;
                    }
                }

                $spell['effects'][$i - 1] = [
                    'id'       => $effect,
                    'type'     => 0, //default                                    //if it's not aura, check effect attributes and add if exists
                    'eff_name' => $this->config->item('effect_names')[$effect] . (($value || $misc) && effectAttributes($effect, $misc, $patch) !== null ? ': (' . effectAttributes($effect, $misc, $patch) . ') ' : ''),
                    'value'    => $value,
                    'radius'   => $this->config->item('radius')[$radius] ?? '',
                    'interval' => $amplitude ? formatTime($amplitude) : '',
                    'mechanic' => $this->config->item('spell_mechanics')[$mechanic] ?? ''
                ];

                // Enchant
                if (in_array($effect, [53, 54])) {
                    $value = $this->config->item('enchants')[$misc] ?? '';

                    $spell['effects'][$i - 1]['type']  = 1;
                    $spell['effects'][$i - 1]['value'] = $value;
                }

                // Auras
                if ($aura) {
                    $aura_d = [
                        'a_id'    => $aura,
                        'type'    => 2,
                        'eff_val' => auraAttributes($aura, $misc, $patch),
                        'a_name'  => $this->config->item('aura_names')[$aura],
                    ];

                    foreach ($aura_d as $key => $data) {
                        $spell['effects'][$i - 1][$key] = $data;
                    }
                }

                // Triggers
                if ($trigger) {
                    $trigger_spell = $this->Database_model->getSpell($trigger, $patch, 'name, build, spellIconId');
                    $trigger       = [
                        'id'    => $trigger,
                        'type'  => 3,
                        'name'  => $trigger_spell['name'],
                        'build' => $trigger_spell['build'],
                        'icon'  => $this->config->item('spell_icons')[$trigger_spell['spellIconId']] ?? 'Trade_Engineering',
                    ];

                    foreach ($trigger as $key => $data) {
                        $spell['effects'][$i - 1][$key] = $data;
                    }
                }

                // Creates
                if ($item && $aura === 86) {
                    $created_item = $this->Database_model->getItem($item, $patch);
                    $item         = [
                        'id'      => $item,
                        'type'    => 4,
                        'name'    => $created_item['name'],
                        'patch'   => $created_item['patch'],
                        'quality' => $created_item['quality'],
                        'icon'    => $this->Database_model->getIconName($item, $patch),
                    ];

                    foreach ($item as $key => $data) {
                        $spell['effects'][$i - 1][$key] = $data;
                    }
                }

                // Affects (check spell 20937 for example)
                if (in_array($aura, [107, 108, 109, 112])) {
                    $affected_spells = $this->Database_model->getAffectedSpellList($entry, $patch);
                    if ($affected_spells) {
                        foreach ($affected_spells as $key => $data) {
                            $affected_spells[$key]['icon'] = $this->config->item('spell_icons')[$data['spellIconId']] ?? 'Trade_Engineering';
                        }

                        $affected = [
                            'type' => 5,
                            'list' => $affected_spells
                        ];

                        foreach ($affected as $key => $data) {
                            $spell['effects'][$i - 1][$key] = $data;
                        }
                    }
                }
            }
            unset($effect, $aura, $trigger, $trigger_spell, $misc, $base, $radius, $amplitude, $mechanic, $key, $data, $value);

            // Flags
            $masks          = getMasks();
            $spell['flags'] = [];

            for ($i = 0; $i < 4; $i++) {
                $attribute = $spell['attributes' . ($i ? ($i === 1 ? 'Ex' : 'Ex' . $i) : '')];
                if (! $attribute) {
                    continue;
                }

                foreach ($masks as $key => $mask) {
                    if ($attribute & $mask) {
                        $spell['flags'][] = $this->config->item('attributes')[$i][$key];
                    }
                }
            }

            //Mechanics
            $spell['mechanics'] = $this->config->item('spell_mechanics')[$spell['mechanic']] ?? '';
            for ($i = 1; $i <= 2; $i++) {
                if (! $spell['effectMechanic' . $i]) {
                    continue;
                }
                if (strpos($spell['mechanics'], $this->config->item('spell_mechanics')[$spell['effectMechanic' . $i]]) === false) {
                    $spell['mechanics'] .= (empty($spell['mechanics']) ? '' : ', ') . $this->config->item('spell_mechanics')[$spell['effectMechanic' . $i]];
                }
            }

            $spell['school_name'] = schoolType($spell['school']);
            $spell['dispel_type'] = dispelType($spell['dispel']);
            $spell['desc']        = $this->Database_model->getSpellDetails($spell['entry'], $patch);
            $build                = $this->Database_model->getAddedBuild($entry);
            $spell['added_build'] = $build . ' (Patch: ' . ($this->config->item('build_list')[$build] ?? 'N/A') . ')';
            $spell['build_list']  = $this->Database_model->getBuildList($entry);

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
                    'quality' => $this->armory_model->getCharEquipmentQualityPatch($spell['totem' . $i], $patch)['quality']
                ];
            }

            //Reagents
            $spell['reagents'] = [];

            for ($i = 1; $i <= 8; $i++) {
                if ($spell['reagent' . $i] > 0 && $spell['reagentCount' . $i]) {
                    $spell['reagents'][$i - 1] = [
                        'id'      => $spell['reagent' . $i],
                        'count'   => $spell['reagentCount' . $i],
                        'name'    => $this->Database_model->getItemName($spell['reagent' . $i], $patch),
                        'icon'    => $this->Database_model->getIconName($spell['reagent' . $i], $patch),
                        'quality' => $this->armory_model->getCharEquipmentQualityPatch($spell['reagent' . $i], $patch)['quality']
                    ];
                }
            }
            // Additional data - end

            $data = [
                'spell'     => $spell,
                'patch'     => $patch,
                'pagetitle' => 'Spell > ' . $spell['name'],
                'lang'      => $this->lang->lang(),
                'realms'    => $this->wowrealm->getRealms()->result(),
            ];

            $this->template->build('spell', $data);
        } else {
            $data = [
                'spell'     => false,
                'pagetitle' => 'Spell not found',

            ];

            $this->template->build('spell', $data);
        }
    }
}
