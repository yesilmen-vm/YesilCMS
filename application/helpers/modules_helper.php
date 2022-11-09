<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * @param $inputSeconds
 *
 * @return string
 */
function secondsToTime($inputSeconds): string
{
    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // Extract days
    $days = floor($inputSeconds / $secondsInADay);

    // Extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours       = floor($hourSeconds / $secondsInAnHour);

    // Extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes       = floor($minuteSeconds / $secondsInAMinute);

    // Extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds          = ceil($remainingSeconds);

    // Format and return
    $timeParts = [];
    $sections  = [
        'day' => (int)$days,
        'hr'  => (int)$hours,
        'min' => (int)$minutes,
        'sec' => (int)$seconds,
    ];

    foreach ($sections as $name => $value) {
        if ($value > 0) {
            $timeParts[] = $value . ' ' . $name . ($value == 1 ? '' : 's');
        }
    }

    return implode(', ', $timeParts);
}

/**
 * @param       $string
 * @param  int  $chars
 *
 * @return mixed|string
 */
function truncateString($string, int $chars = 100)
{
    preg_match('/^.{0,' . $chars . '}(?:.*?)\b/iu', $string, $matches);
    $new_string = $matches[0] ?? '';

    return ($new_string === $string) ? $string : $new_string . '&hellip;';
}

/**
 * @param $class
 * @param $race
 * @param $gender
 * @param $level
 *
 * @return string
 */
function getAvatar($class, $race, $gender, $level): string
{
    return $class . '-' . $race . '-' . ($gender == 0 ? 'm' : 'f') . '-'
           . ($level < 60 ? '1' : '60') . '.png';
}

/**
 * @param $class
 * @param $race
 * @param $gender
 *
 * @return string
 */
function getPlaceholder($class, $race, $gender): string
{
    return $class . '-' . $race . '-' . ($gender == 0 ? 'm' : 'f') . '.png';
}

/**
 * @param $race
 *
 * @return array
 */
function getPowerDetails($race): array
{
    if ($race == 1) {
        $power = 'rage';
    } elseif ($race == 4) {
        $power = 'energy';
    } else {
        $power = 'mana';
    }

    return array($power, ucfirst($power));
}

/**
 * @param $class
 * @param $level
 * @param $str
 * @param $agi
 *
 * @return float|int
 */
function calculateMeleeAP($class, $level, $str, $agi)
{
    $result = 0;

    switch ($class) {
        case 1:
        case 2:
            $result = $level * 3 + $str * 2 - 20;
            break;
        case 3:
        case 4:
            $result = $level * 2 + $str + $agi - 20;
            break;
        case 5:
        case 8:
        case 9:
            $result = $str - 10;
            break;
        case 7:
            $result = $level * 2 + $str * 2 - 20;
            break;
        case 11:
            $result = $str * 2 - 20;
            break;
    }

    return $result;
}

/**
 * @param $class
 * @param $level
 * @param $agi
 *
 * @return float|int
 */
function calculateRangedAP($class, $level, $agi)
{
    $result = 0;

    switch ($class) {
        case 4:
        case 1:
            $result = $level + $agi - 10;
            break;
        case 3:
            $result = $level * 2 + $agi * 2 - 10;
            break;
    }

    return $result;
}

/**
 * @param  int  $class
 * @param  int  $intellect
 *
 * values from theorycraft / http://wow.allakhazam.com/forum.html?forum=21&mid=1157230638252681707
 *
 * @return float
 */
function getAdditionalSpellCrit(int $class, int $intellect): float
{
    $spellCrit = 0.0;

    if ($class === 2) {
        $spellCrit = $intellect / 29.5;
    } elseif ($class === 5) {
        $spellCrit = 0.8 + ($intellect / 59.56);
    } elseif ($class === 7) {
        $spellCrit = 1.8 + ($intellect / 59.2);
    } elseif ($class === 8) {
        $spellCrit = 0.2 + ($intellect / 59.5);
    } elseif ($class === 9) {
        $spellCrit = 1.7 + ($intellect / 60.6);
    } elseif ($class === 11) {
        $spellCrit = 1.8 + ($intellect / 60);
    }

    return round($spellCrit, 2);
}

/**
 * @param  int  $rank
 *
 * @return string[]
 */
function getRankTitle(int $rank): array
{
    if ($rank === 1) {
        $title = [0 => 'Private', 1 => 'Scout'];
    } elseif ($rank === 2) {
        $title = [0 => 'Corporal', 1 => 'Grunt'];
    } elseif ($rank === 3) {
        $title = [0 => 'Sergeant', 1 => 'Sergeant'];
    } elseif ($rank === 4) {
        $title = [0 => 'Master Sergeant', 1 => 'Senior Sergeant'];
    } elseif ($rank === 5) {
        $title = [0 => 'Sergeant Major', 1 => 'First Sergeant'];
    } elseif ($rank === 6) {
        $title = [0 => 'Knight', 1 => 'Stone Guard'];
    } elseif ($rank === 7) {
        $title = [0 => 'Knight-Lieutenant', 1 => 'Blood Guard'];
    } elseif ($rank === 8) {
        $title = [0 => 'Knight-Captain', 1 => 'Legionnaire'];
    } elseif ($rank === 9) {
        $title = [0 => 'Knight-Champion', 1 => 'Centurion'];
    } elseif ($rank === 10) {
        $title = [0 => 'Lieutenant Commander', 1 => 'Champion'];
    } elseif ($rank === 11) {
        $title = [0 => 'Commander', 1 => 'Lieutenant General'];
    } elseif ($rank === 12) {
        $title = [0 => 'Marshal', 1 => 'General'];
    } elseif ($rank === 13) {
        $title = [0 => 'Field Marshal', 1 => 'Warlord'];
    } elseif ($rank === 14) {
        $title = [0 => 'Grand Marshal', 1 => 'High Warlord'];
    } else {
        $title = [0 => 'N/A', 1 => 'N/A'];
    }

    return $title;
}

/**
 * @param  int  $class
 * @param       $spirit
 *
 * It's probably not accurate but should be close.
 * https://vanilla-wow-archive.fandom.com/wiki/Spirit
 * https://github.com/yutsuku/BetterCharacterStats/blob/master/helper.lua#L919
 *
 * @return float|int
 */
function getAdditionalMP5(int $class, int $spirit)
{
    $mp5 = 0.0;

    if ($class === 2 || $class === 3 || $class === 11) {
        $mp5 = 15 + ($spirit / 5);
    } elseif ($class === 5 || $class === 8) {
        $mp5 = 12.5 + ($spirit / 4);
    } elseif ($class === 7) {
        $mp5 = 17 + ($spirit / 5);
    } elseif ($class === 9) {
        $mp5 = 8 + ($spirit / 4);
    }

    return $mp5;
}

/**
 * @param  int  $class
 * @param  int  $sta
 * @param  int  $str
 * @param  int  $agi
 * @param  int  $int
 * @param  int  $spi
 * @param  int  $def
 *
 * @return array|string[]
 */
function detectMainStat(int $class, int $sta, int $str, int $agi, int $int, int $spi, int $def): array
{
    if (($class === 1 || $class === 2 || $class === 11) && ($sta > $str || $sta > $agi) && $def > 25) {
        return ['def_defense', 'Defense', $def];
    }

    $stats = [
        "strength"  => $str,
        "agility"   => $agi,
        "intellect" => $int,
        "spirit"    => $spi,
    ];
    if (empty(array_filter($stats))) {
        return ['unknown', 'N/A', 'Unknown'];
    }
    $maxVal = max($stats);
    $maxKey = array_search($maxVal, $stats);

    return ['stat_' . $maxKey, ucfirst($maxKey), $maxVal];
}

function guessMainSpec(int $class, $charStats): array
{
    $stats = [];

    $powerAtt   = array('icon' => 'power_attack', 'name' => 'Attack Power', 'data' => formatStats($charStats['powerAttack']));
    $powerRan   = array('icon' => 'power_ranged', 'name' => 'Ranged AP', 'data' => formatStats($charStats['powerRangedAttack']));
    $powerSp    = array('icon' => 'power_spell', 'name' => 'Spell Power', 'data' => formatStats($charStats['powerSpell']));
    $powerSpS   = array('icon' => $charStats['powerSpellSecondary']['icon'], 'name' => $charStats['powerSpellSecondary']['name'], 'data' => $charStats['powerSpellSecondary']['data']);
    $powerHp    = array('icon' => 'power_healing', 'name' => 'Healing Power', 'data' => formatStats($charStats['powerHealing']));
    $critAtt    = array('icon' => 'critical_melee', 'name' => 'Melee Crit', 'data' => formatPercentage($charStats['critMelee']));
    $critRan    = array('icon' => 'critical_ranged', 'name' => 'Ranged Crit', 'data' => formatPercentage($charStats['critRanged']));
    $critSp     = array('icon' => 'critical_spell', 'name' => 'Spell Crit', 'data' => formatPercentage($charStats['critSpell']));
    $hitMelee   = array('icon' => 'hit_melee', 'name' => 'Melee Hit', 'data' => formatPercentage($charStats['hitMelee']));
    $hitSpell   = array('icon' => 'hit_spell', 'name' => 'Spell Hit', 'data' => formatPercentage($charStats['hitSpell']));
    $defArmor   = array('icon' => 'def_armor', 'name' => 'Armor', 'data' => formatStats($charStats['defArmor']));
    $defBlock   = array('icon' => 'def_block', 'name' => 'Block', 'data' => formatPercentage($charStats['defBlock']));
    $defDodge   = array('icon' => 'def_dodge', 'name' => 'Dodge', 'data' => formatPercentage($charStats['defDodge']));
    $defDefense = array('icon' => 'def_defense', 'name' => 'Defense', 'data' => formatStats($charStats['defDefense']));
    $defParry   = array('icon' => 'def_parry', 'name' => 'Parry', 'data' => formatPercentage($charStats['defParry']));
    $defMp5     = array('icon' => 'def_mp5', 'name' => 'Mana Regen', 'data' => $charStats['defManaRegen']);
    $statSpr    = array('icon' => 'stat_spirit', 'name' => 'Spirit', 'data' => $charStats['statSpr']);

    if ($class === 1) {
        if ($charStats['defDefense'] > 25 && $charStats['statSta'] > $charStats['statStr']) {
            $stats = [$defDodge, $defParry, $defBlock, $defArmor];
        } else {
            $stats = [$powerAtt, $critAtt, $hitMelee, $defArmor];
        }
    } elseif ($class === 2) {
        if ($charStats['defDefense'] > 25 && $charStats['statSta'] > $charStats['statStr']) {
            $stats = [$defDodge, $defParry, $defBlock, $defArmor];
        } elseif ($charStats['powerHealing'] > 75 && $charStats['statInt'] > $charStats['statStr']) {
            $stats = [$powerHp, $critSp, $defMp5, $defArmor];
        } else {
            $stats = [$powerAtt, $critAtt, $hitMelee, $defArmor];
        }
    } elseif ($class === 3) {
        $stats = [$powerRan, $critRan, $hitMelee, $defArmor];
    } elseif ($class === 4) {
        $stats = [$powerAtt, $critAtt, $hitMelee, $defArmor];
    } elseif ($class === 5) {
        if ($charStats['powerHealing'] > $charStats['powerSpell']) {
            $stats = [$powerHp, $defMp5, $critSp, $statSpr];
        } else {
            $stats = [$powerSp, $powerSpS, $critSp, $hitSpell];
        }
    } elseif ($class === 7) {
        if ($charStats['statStr'] > $charStats['statInt'] || $charStats['statAgi'] > $charStats['statInt']) {
            $stats = [$powerAtt, $critAtt, $hitMelee, $defArmor];
        } elseif ($charStats['powerHealing'] > $charStats['powerSpell']) {
            $stats = [$powerHp, $defMp5, $critSp, $statSpr];
        } else {
            $stats = [$powerSp, $powerSpS, $critSp, $hitSpell];
        }
    } elseif ($class === 8) {
        $stats = [$powerSp, $powerSpS, $critSp, $hitSpell];
    } elseif ($class === 9) {
        $stats = [$powerSp, $powerSpS, $critSp, $hitSpell];
    } elseif ($class === 11) {
        if ($charStats['defDefense'] > 25 && $charStats['statSta'] > $charStats['statAgi'] || $charStats['defDodge'] > 15) {
            $stats = [$powerAtt, $critAtt, $defDodge, $defArmor];
        } elseif ($charStats['statStr'] > $charStats['statInt'] || $charStats['statAgi'] > $charStats['statInt'] || $charStats['critMelee'] > 10) {
            $stats = [$powerAtt, $critAtt, $hitMelee, $defArmor];
        } elseif ($charStats['powerHealing'] > $charStats['powerSpell'] && $charStats['hitSpell'] < 5) {
            $stats = [$powerHp, $defMp5, $critSp, $statSpr];
        } else {
            $stats = [$powerSp, $powerSpS, $critSp, $hitSpell];
        }
    }

    return $stats;
}


/**
 * @param $inp
 *
 * @return string|int
 */
function formatStats($inp)
{
    if (is_numeric($inp)) {
        return number_format($inp);
    }

    return 'Unknown';
}

/**
 * @param $inp
 *
 * @return string
 */
function formatPercentage($inp): string
{
    if (isset($inp)) {
        if ((float)$inp < 0.01) {
            return sprintf('%0.3f', $inp) . '%';
        }

        return sprintf('%0.2f', $inp) . '%';
    }

    return 'Unknown';
}

/**
 * @param $number
 * @param $everything
 *
 * @return int
 */
function percentageOf($number, $everything): int
{
    return intval(($number / $everything) * 100);
}

/**
 * @param $min
 * @param $max
 *
 * @return int|mixed
 * @throws Exception
 */
function cRandSecure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) {
        return $min;
    } // not so random...
    $log    = ceil(log($range, 2));
    $bytes  = (int)($log / 8) + 1; // length in bytes
    $bits   = (int)$log + 1; // length in bits
    $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(random_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);

    return $min + $rnd;
}

/**
 * @param $length
 *
 * @return string
 * @throws Exception
 */
function generateToken($length): string
{
    $token = "";
    $dict  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $dict  .= "abcdefghijklmnopqrstuvwxyz";
    $dict  .= "0123456789-_.";
    $max   = strlen($dict); // edited

    for ($i = 0; $i < $length; $i++) {
        $token .= $dict[cRandSecure(0, $max - 1)];
    }

    return $token;
}

/**
 * @param $patch
 *
 * @return string
 */
function getPatchName($patch): string
{
    $patches = ['1.2', '1.3', '1.4', '1.5', '1.6', '1.7', '1.8', '1.9', '1.10', '1.11', '1.12'];

    if (! empty($patch) || strlen($patch) > 0) {
        return $patches[$patch];
    }

    return '1.12';
}

/**
 * @param          $id
 * @param          $item_patch
 * @param          $selected_patch
 * @param  string  $lang
 *
 * @return string
 */
function showTooltip($id, $item_patch, $selected_patch, string $lang = 'en'): string
{
    if (empty($selected_patch) || $selected_patch === 10) {
        return 'href="' . base_url() . $lang . '/item/' . $id . '"';
    } elseif ($item_patch === 99) {
        return 'class="tooltipLink" data-tooltip="This item does not exists in selected patch: ' . getPatchName($selected_patch) . '"';
    } else {
        return 'href="' . base_url() . $lang . '/item/' . $id . '-' . $item_patch . '"';
    }
}

/**
 * @param $patch
 *
 * @return int
 */
function dataPatch($patch): int
{
    if (! empty($patch) || strlen($patch) > 0) {
        return (int)$patch;
    }

    return 10;
}

/**
 * @param $rank
 *
 * @return string
 */
function creatureRank($rank): string
{
    $ranks = ['Normal', 'Elite ', 'Rare Elite', 'Boss', 'Rare '];

    if (! empty($rank) || strlen($rank) > 0) {
        return $ranks[$rank];
    }

    return 'Unknown';
}

/**
 * @param  string  $text
 * @param  bool    $rt
 * @param  string  $extra
 *
 * @return string
 */
function tableText(string $text, bool $rt = false, string $extra = ''): string
{
    if (! empty($text) || strlen($text) > 0) {
        return $text . ($extra ? ' ' . $extra : '');
    }

    if ($rt) {
        return '<span class="q0">n/a</span>';
    }

    return 'None';
}

function effectAttributes(int $effect, int $id, $patch = 10): ?string
{
    if ($effect === 8 || $effect === 30 || $effect === 62) {
        $powType = [0 => "Mana", 1 => "Rage", 2 => "Focus", 3 => "Energy", 4 => "Happiness"];

        return $powType[$id];
    } elseif ($effect === 16) { //quest complete, later add quest page when implemented
        $CI =& get_instance();

        return $CI->Database_model->getQuestTitle($id, $patch);
    } elseif ($effect === 28 || $effect === 56 || $effect === 74 || $effect === 90 || $effect === 112 || $effect === 134) {
        $CI =& get_instance();

        return $CI->Database_model->getCreatureName($id, $patch); //creature name, later add npc page when implemented
    } elseif ($effect === 33) {
        $CI =& get_instance();

        return $CI->config->item('lock_type')[$id] ?? $id;
    } elseif ($effect === 53 || $effect === 54 || $effect === 92 || $effect === 156) {
        return $id;
    } elseif ($effect === 38 || $effect === 126) {
        $dispType = [null, "Magic", "Curse", "Disease", "Poison", "Stealth", "Invisibility", null, null, "Enrage"];

        return $dispType[$id] ?? $id;
    } elseif ($effect === 39) {
        $lang = [1 => "Orcish", 2 => "Darnassian", 3 => "Taurahe", 6 => "Dwarvish", 7 => "Common", 8 => "Demonic", 11 => "Draconic", 13 => "Gnomish", 14 => "Troll"];

        return $lang[$id] ?? $id;
    } elseif ($effect === 50 || $effect === 76 || $effect === 104 || $effect === 105 || $effect === 106 || $effect === 107) {
        $CI =& get_instance();

        return $CI->Database_model->getGOName($id, $patch); //gameobject name, later add obj page when implemented
    } elseif ($effect === 86) {
        $actions = [
            "None",
            "Animate Custom 0",
            "Animate Custom 1",
            "Animate Custom 2",
            "Animate Custom 3",
            "Disturb / Trigger Trap",
            "Unlock",
            "Lock",
            "Open",
            "Unlock & Open",
            "Close",
            "Toggle Open",
            "Destroy",
            "Rebuild",
            "Creation",
            "Despawn",
            "Make Inert"
        ];

        return $actions[$id] ?? $id;
    } elseif ($effect === 108) {
        $CI =& get_instance();

        return $CI->config->item('spell_mechanics')[$id] ?? $id;
    } elseif ($effect === 44 || $effect === 118) {
        $CI =& get_instance();

        return $CI->config->item('skilline')[$id] ?? $id;
    } elseif ($effect === 103) {
        $CI =& get_instance();

        return $CI->Database_model->getFactionName($id, $patch);
    } elseif ($effect === 123) {
        return $id;
    }

    return null;
}

/**
 * @param  int  $aura
 * @param  int  $id
 * @param  int  $patch
 *
 * @return string
 */
function auraAttributes(int $aura, int $id, int $patch = 10): string
{
    if ($aura === 17) {
        $val = ["General", "Trap"];

        return $val[$id];
    } elseif ($aura === 19) {
        $invType = [null, "General", null, "Trap", null, null, "Drunk", null, null, null, null, null];

        return $invType[$id] ?? $id;
    } elseif ($aura === 21 || $aura === 24 || $aura === 35 || $aura === 85 || $aura === 100 || $aura === 132) {
        $powType = [0 => "Mana", 1 => "Rage", 2 => "Focus", 3 => "Energy", 4 => "Happiness"];

        return $powType[$id];
    } elseif ($aura === 29 || $aura === 80 || $aura === 137) {
        $stats = ["Strength", "Agility", "Stamina", "Intellect", "Spirit"];
        $mask  = $id < 0 ? 0x1F : 1 << $id;
        $res   = [];
        for ($j = 0; $j < 5; $j++) {
            if ($mask & (1 << $j)) {
                $res[] = $stats[$j];
            }
        }

        return $res ? implode(", ", $res) : $stats[$id];
    } elseif ($aura === 36) {
        $shType = [
            "Default",
            "Cat Form",
            "Tree of Life",
            "Travel Form",
            "Aquatic Form",
            "Bear From",
            "Ambient",
            "Ghoul",
            "Dire Bear Form",
            "Steve's Ghoul",
            "Tharon'ja Skeleton",
            "Darkmoon - Test of Strength",
            "BLB Player",
            "Shadowdance",
            "Creature - Bear",
            "Creature - Cat",
            "Ghostwolf",
            "Battle Stance",
            "Defensive Stance",
            "Berserker Stance",
            "Test",
            "Zombie",
            "Metamorphosis",
            null,
            null,
            "Undead",
            "Frenzy",
            "Swift Flight Form",
            "Shadow Form",
            "Flight Form",
            "Stealth",
            "Moonkin Form",
            "Spirit of Redemption"
        ];

        return $shType[$id] ?? $id;
    } elseif ($aura === 37) {
        $CI =& get_instance();

        return $CI->config->item('effect_names')[$id] ?? $id;
    } elseif ($aura === 38) {
        $CI =& get_instance();

        return $CI->config->item('aura_names')[$id] ?? $id;
    } elseif ($aura === 41) {
        $dispType = [null, "Magic", "Curse", "Disease", "Poison", "Stealth", "Invisibility", null, null, "Enrage"];

        return $dispType[$id] ?? $id;
    } elseif ($aura === 44 || $aura === 59 || $aura === 102 || $aura === 131 || $aura === 168 || $aura === 180) {
        $crType = ["Uncategorized", "Beast", "Dragonkin", "Demon", "Elemental", "Giant", "Undead", "Humanoid",];

        $res = [];
        foreach ($crType as $k => $str) {
            if ($k && ($id & (1 << $k - 1))) {
                $res[] = $str;
            }
        }

        return $res ? implode(", ", $res) : $crType[$id] ?? $id;
    } elseif ($aura === 45) {
        $CI =& get_instance();

        return $CI->config->item('lock_type')[$id] ?? $id;
    } elseif ($aura === 56 || $aura === 78) { // Mount
        $CI =& get_instance();

        return $CI->Database_model->getCreatureName($id, $patch); //creature name, later add npc page when implemented
    } elseif ($aura === 75) {
        $lang = [1 => "Orcish", 2 => "Darnassian", 3 => "Taurahe", 6 => "Dwarvish", 7 => "Common", 8 => "Demonic"];

        return $lang[$id] ?? $id;
    } elseif ($aura === 77 || $aura === 117 || $aura === 232 || $aura === 234 || $aura === 255 || $aura === 276) {
        $CI =& get_instance();

        return $CI->config->item('spell_mechanics')[$id] ?? $id;
    } elseif ($aura === 139 || $aura === 190) {
        $CI =& get_instance();

        return $CI->Database_model->getFactionName($id, $patch);
    } elseif ($aura === 147) {
        $CI =& get_instance();

        $mechType = $CI->config->item('spell_mechanics');

        $res = [];
        foreach ($mechType as $k => $str) {
            if ($k && ($id & (1 << $k - 1))) {
                $res[] = $str;
            }
        }

        return $res ? implode(", ", $res) : $mechType[$id] ?? $id;
    } elseif ($aura === 10
              || $aura === 13
              || $aura === 14
              || $aura === 22
              || $aura === 39
              || $aura === 40
              || $aura === 50
              || $aura === 57
              || $aura === 69
              || $aura === 71
              || $aura === 72
              || $aura === 73
              || $aura === 74
              || $aura === 79
              || $aura === 81
              || $aura === 83
              || $aura === 87
              || $aura === 97
              || $aura === 101
              || $aura === 115
              || $aura === 118
              || $aura === 123
              || $aura === 135
              || $aura === 136
              || $aura === 142
              || $aura === 143
              || $aura === 149
              || $aura === 163
              || $aura === 174
              || $aura === 182
              || $aura === 186
              || $aura === 194
              || $aura === 195
              || $aura === 199
              || $aura === 229
              || $aura === 271
              || $aura === 310
              || $aura === 237
              || $aura === 238
              || $aura === 242
              || $aura === 259
              || $aura === 267
              || $aura === 269
              || $aura === 285
              || $aura === 300
              || $aura === 301
    ) {
        return getMagicSchools($id);
    } elseif ($aura === 30 || $aura === 98) {
        $CI =& get_instance();

        return $CI->config->item('skilline')[$id] ?? $id;
    } elseif ($aura === 107 || $aura === 108) {
        $spellModOpTypes = [
            "Damage",
            "Duration",
            "Threat",
            "Effect 1",
            "Charges",
            "Range",
            "Radius",
            "Critical Hit Chance",
            "All Effects",
            "Casting Time loss",
            "Casting Time",
            "Cooldown",
            "Effect 2",
            "Ignore Armor",
            "Cost",
            "Critical Damage Bonus",
            "Chance to Fail",
            "Jump Targets",
            "Proc Chance",
            "Intervall",
            "Multiplier (Damage)",
            "Global Cooldown",
            "Damage over Time",
            "Effect 3",
            "Multiplier (Bonus)",
            null,
            "Procs per Minute",
            "Multiplier (Value)",
            "Chance to Resist Dispel",
            "Critical Damage Bonus2",
            "Refund Cost on Fail"
        ];

        return $spellModOpTypes[$id] ?? $id;
    }

    return (string)$id;
}

/**
 * @param $schoolMask
 *
 * @return string
 */
function getMagicSchools($schoolMask): string
{
    $schoolMask &= 0x7F;
    $sc         = ["Physical", "Holy", "Fire", "Nature", "Frost", "Shadow", "Arcane"];
    $tmp        = [];
    $i          = 0;

    while ($schoolMask) {
        if ($schoolMask & (1 << $i)) {
            $tmp[]      = $sc[$i];
            $schoolMask &= ~(1 << $i);
        }
        $i++;
    }

    return implode(', ', $tmp);
}

/**
 * @param  int  $race
 *
 * @return int
 */
function sideByRaceMask(int $race): int
{
    // Any
    if (! $race || ($race & 0xFF) == 0xFF) {
        return 3;
    }

    // Horde
    if ($race & 0xB2 && ! ($race & 0x4D)) {
        return 2;
    }

    // Alliance
    if ($race & 0x4D && ! ($race & 0xB2)) {
        return 1;
    }

    return 3;
}

/**
 * @param  int  $id
 *
 * @return string
 */
function sideByID(int $id): string
{
    $sides = [1 => "Alliance", -1 => "Alliance only", 2 => "Horde", -2 => "Horde only", 3 => "Both"];

    return $sides[$id] ?? 'Unknown';
}

/**
 * @param  int  $id
 *
 * @return string
 */
function territoryByTeamID(int $id): string
{
    $territory = [0 => "Contested", 2 => "Alliance", 4 => "Horde"];

    return $territory[$id] ?? '';
}

/**
 * @param  int  $id
 *
 * @return string
 */
function zoneCatByMapID(int $id): string
{
    if ($id === 0) {
        return "Eastern Kingdoms";
    } elseif ($id === 1) {
        return "Kalimdor";
    } else {
        return "Instance";
    }
}

/**
 * @param  int  $id
 *
 * @return string
 */
function GOTypeByID(int $id): string
{
    if ($id === 0) {
        return "Door";
    } elseif ($id === 1) {
        return "Button";
    } elseif ($id === 2) {
        return "Questgiver";
    } elseif ($id === 3) {
        return "Chest";
    } elseif ($id === 5) {
        return "Generic";
    } elseif ($id === 6) {
        return "Trap";
    } elseif ($id === 7) {
        return "Chair";
    } elseif ($id === 8) {
        return "Spell Focus";
    } elseif ($id === 9) {
        return "Text";
    } elseif ($id === 10) {
        return "Goober";
    } elseif ($id === 11) {
        return "Transport";
    } elseif ($id === 13) {
        return "Camera";
    } elseif ($id === 14) {
        return "Map Object";
    } elseif ($id === 15) {
        return "MO Transport";
    } elseif ($id === 16) {
        return "Duel Arbitier";
    } elseif ($id === 17) {
        return "Fishing Node";
    } elseif ($id === 18) {
        return "Ritual";
    } elseif ($id === 19) {
        return "Mailbox";
    } elseif ($id === 20) {
        return "Auction House";
    } elseif ($id === 22) {
        return "Spell Caster";
    } elseif ($id === 23) {
        return "Meeting Stone";
    } elseif ($id === 24) {
        return "Flag Stand";
    } elseif ($id === 25) {
        return "Fishing Hole";
    } elseif ($id === 26) {
        return "Flag Drop";
    } elseif ($id === 29) {
        return "Capture Point";
    }

    return "Unknown";
}

function ordinalNumber(int $number, bool $html = false): string
{
    if ($number === 0) {
        return 'n/a';
    }
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
        return $number . ($html ? '<sup>th</sup>' : 'th');
    } else {
        return $number . ($html ? '<sup>' . $ends[$number % 10] . '</sup>' : $ends[$number % 10]);
    }
}