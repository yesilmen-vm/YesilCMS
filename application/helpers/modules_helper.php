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
 * @param $string
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
 * Its probably not accurate but should be close.
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
 * @return mixed|string
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
