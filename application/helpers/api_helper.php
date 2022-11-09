<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * @param  int  $quality
 *
 * @return string
 */
function itemQuality(int $quality): string
{
    if ($quality === 0) {
        return 'Poor';
    } elseif ($quality === 1) {
        return 'Common';
    } elseif ($quality === 2) {
        return 'Uncommon';
    } elseif ($quality === 3) {
        return 'Rare';
    } elseif ($quality === 4) {
        return 'Epic';
    } elseif ($quality === 5) {
        return 'Legendary';
    } elseif ($quality === 6) {
        return 'Artifact';
    }

    return 'Unknown';
}

/**
 * @param  int  $bonding
 *
 * @return string
 */
function itemBonding(int $bonding): string
{
    if ($bonding === 1) {
        return 'Binds when picked up';
    } elseif ($bonding === 2) {
        return 'Binds when equipped';
    } elseif ($bonding === 3) {
        return 'Binds when used';
    } elseif ($bonding === 4) {
        return 'Quest item';
    }

    return 'Unknown';
}

/**
 * @param  int  $count
 *
 * @return string
 */
function itemCount(int $count): string
{
    if ($count === 1) {
        return 'Unique';
    } elseif ($count > 1) {
        return 'Unique ' . '(' . $count . ')';
    }

    return '';
}

/**
 * @param  int  $class
 *
 * @return string
 */
function itemClass(int $class): string
{
    if ($class === 0) {
        return 'Consumable';
    } elseif ($class === 1) {
        return 'Container';
    } elseif ($class === 2) {
        return 'Weapon';
    } elseif ($class === 4) {
        return 'Armor';
    } elseif ($class === 5) {
        return 'Reagent';
    } elseif ($class === 6) {
        return 'Projectile';
    } elseif ($class === 7) {
        return 'Trade Goods';
    } elseif ($class === 9) {
        return 'Recipe';
    } elseif ($class === 11) {
        return 'Quiver';
    } elseif ($class === 12) {
        return 'Quest';
    } elseif ($class === 13) {
        return 'Key';
    } elseif ($class === 15) {
        return 'Miscellaneous';
    }

    return 'Unknown';
}

/**
 * @param  int  $class
 * @param  int  $subclass
 *
 * @return string
 */
function itemSubClass(int $class, int $subclass): string
{
    if ($class === 0) {
        if ($subclass === 0) {
            return 'Consumable';
        } elseif ($subclass === 1) {
            return 'Potion';
        } elseif ($subclass === 2) {
            return 'Elixir';
        } elseif ($subclass === 3) {
            return 'Flask';
        } elseif ($subclass === 4) {
            return 'Scroll';
        } elseif ($subclass === 5) {
            return 'Food & Drink';
        } elseif ($subclass === 6) {
            return 'Item Enhancement';
        } elseif ($subclass === 7) {
            return 'Bandage';
        } elseif ($subclass === 8) {
            return 'Other';
        }
    } elseif ($class === 1) {
        if ($subclass === 0) {
            return 'Bag';
        } elseif ($subclass === 1) {
            return 'Soul Bag';
        } elseif ($subclass === 2) {
            return 'Herb Bag';
        } elseif ($subclass === 3) {
            return 'Enchanting Bag';
        }
    } elseif ($class === 2) {
        if ($subclass === 0 || $subclass === 1) {
            return 'Axe';
        } elseif ($subclass === 2) {
            return 'Bow';
        } elseif ($subclass === 3) {
            return 'Gun';
        } elseif ($subclass === 4 || $subclass === 5) {
            return 'Mace';
        } elseif ($subclass === 6) {
            return 'Polearm';
        } elseif ($subclass === 7 || $subclass === 8) {
            return 'Sword';
        } elseif ($subclass === 9) {
            return 'Obsolete';
        } elseif ($subclass === 10) {
            return 'Staff';
        } elseif ($subclass === 11 || $subclass === 12) {
            return 'Exotic';
        } elseif ($subclass === 13) {
            return 'Fist Weapon';
        } elseif ($subclass === 14) {
            return 'Miscellaneous';
        } elseif ($subclass === 15) {
            return 'Dagger';
        } elseif ($subclass === 16) {
            return 'Thrown';
        } elseif ($subclass === 17) {
            return 'Spear';
        } elseif ($subclass === 18) {
            return 'Crossbow';
        } elseif ($subclass === 19) {
            return 'Wand';
        } elseif ($subclass === 20) {
            return 'Fishing Pole';
        }
    } elseif ($class === 4) {
        if ($subclass === 0) {
            return 'Miscellaneous';
        } elseif ($subclass === 1) {
            return 'Cloth';
        } elseif ($subclass === 2) {
            return 'Leather';
        } elseif ($subclass === 3) {
            return 'Mail';
        } elseif ($subclass === 4) {
            return 'Plate';
        } elseif ($subclass === 5) {
            return 'Buckler(OBSOLETE)';
        } elseif ($subclass === 6) {
            return 'Shield';
        } elseif ($subclass === 7) {
            return 'Libram';
        } elseif ($subclass === 8) {
            return 'Idol';
        } elseif ($subclass === 9) {
            return 'Totem';
        }
    } elseif ($class === 5) {
        if ($subclass === 0) {
            return 'Reagent';
        }
    } elseif ($class === 6) {
        if ($subclass === 2) {
            return 'Arrow';
        } elseif ($subclass === 3) {
            return 'Bullet';
        }
    } elseif ($class === 7) {
        if ($subclass === 0) {
            return 'Trade Goods';
        } elseif ($subclass === 1) {
            return 'Parts';
        } elseif ($subclass === 2) {
            return 'Explosives';
        } elseif ($subclass === 3) {
            return 'Devices';
        }
    } elseif ($class === 9) {
        if ($subclass === 0) {
            return 'Book';
        } elseif ($subclass === 1) {
            return 'Leatherworking';
        } elseif ($subclass === 2) {
            return 'Tailoring';
        } elseif ($subclass === 3) {
            return 'Engineering';
        } elseif ($subclass === 4) {
            return 'Blacksmithing';
        } elseif ($subclass === 5) {
            return 'Cooking';
        } elseif ($subclass === 6) {
            return 'Alchemy';
        } elseif ($subclass === 7) {
            return 'First Aid';
        } elseif ($subclass === 8) {
            return 'Enchanting';
        } elseif ($subclass === 9) {
            return 'Fishing';
        }
    } elseif ($class === 11) {
        if ($subclass === 2) {
            return 'Quiver';
        } elseif ($subclass === 3) {
            return 'Ammo Pouch';
        }
    } elseif ($class === 12) {
        if ($subclass === 0) {
            return 'Quest';
        }
    } elseif ($class === 13) {
        if ($subclass === 0) {
            return 'Key';
        } elseif ($subclass === 1) {
            return 'Lockpick';
        }
    } elseif ($class === 15) {
        if ($subclass === 0) {
            return 'Junk';
        }
    }

    return 'Unknown';
}

/**
 * @param  int  $inv_type
 *
 * @return string
 */
function itemInventory(int $inv_type): string
{
    $inventoryType = [
        1  => "Head",
        2  => "Neck",
        3  => "Shoulder",
        4  => "Shirt",
        5  => "Chest",
        6  => "Waist",
        7  => "Legs",
        8  => "Feet",
        9  => "Wrist",
        10 => "Hands",
        11 => "Finger",
        12 => "Trinket",
        13 => "One-Hand",
        14 => "Off Hand", /*Shield*/
        15 => "Ranged",
        16 => "Back",
        17 => "Two-Hand",
        18 => "Bag",
        19 => "Tabard",
        20 => "Chest", /*Robe*/
        21 => "Main Hand",
        22 => "Off Hand",
        23 => "Held In Off-Hand",
        24 => "Projectile",
        25 => "Thrown",
        26 => "Wand", /*Ranged2*/
        27 => "Quiver",
        28 => "Relic"
    ];

    return $inventoryType[$inv_type] ?? '';
}

/**
 * @param  int  $class
 *
 * @return bool
 */
function isWeapon(int $class): bool
{
    $weaponArr = [2]; //[2, 4, 6, 7, 12], check

    return in_array($class, $weaponArr, true);
}

/**
 * @param  int  $dmg_min
 * @param  int  $dmg_max
 * @param  int  $dmg_type
 * @param  int  $dmg_order
 *
 * @return string
 */
function weaponDamage(int $dmg_min, int $dmg_max, int $dmg_type, int $dmg_order): string
{
    $damage = '';

    if ($dmg_min > 0 && $dmg_max > 0) {
        if ($dmg_type === 0) {
            $damage = $dmg_min . ' - ' . $dmg_max . ' Damage';
        } elseif ($dmg_type === 1) {
            $damage = $dmg_min . ' - ' . $dmg_max . ' Holy Damage';
        } elseif ($dmg_type === 2) {
            $damage = $dmg_min . ' - ' . $dmg_max . ' Fire Damage';
        } elseif ($dmg_type === 3) {
            $damage = $dmg_min . ' - ' . $dmg_max . ' Nature Damage';
        } elseif ($dmg_type === 4) {
            $damage = $dmg_min . ' - ' . $dmg_max . ' Frost Damage';
        } elseif ($dmg_type === 5) {
            $damage = $dmg_min . ' - ' . $dmg_max . ' Shadow Damage';
        } elseif ($dmg_type === 6) {
            $damage = $dmg_min . ' - ' . $dmg_max . ' Arcane Damage';
        }

        if ($dmg_order > 1) {
            $damage = '+ ' . $damage;
        }
    }

    return $damage;
}

/**
 * @param  int       $dmg_min
 * @param  int       $dmg_max
 * @param  int|null  $speed
 *
 * @return int|string
 */
function weaponDPS(int $dmg_min, int $dmg_max, int $speed = null)
{
    if ($speed) {
        return number_format(($dmg_min + $dmg_max) / (2 * ($speed / 1000)), 1) . ' damage per second';
    }

    return 0;
}

/**
 * @param  int  $stat_type
 * @param  int  $stat_value
 *
 * @return string
 */
function itemStat(int $stat_type, int $stat_value): string
{
    $stat = '';

    if ($stat_type >= 0 && $stat_type < 8) {
        $stat = $stat_value > 0 ? '+' : '-';

        if ($stat_type === 0) {
            $stat .= $stat_value . ' Mana';
        } elseif ($stat_type === 1) {
            $stat .= $stat_value . ' Health';
        } elseif ($stat_type === 3) {
            $stat .= $stat_value . ' Agility';
        } elseif ($stat_type === 4) {
            $stat .= $stat_value . ' Strength';
        } elseif ($stat_type === 5) {
            $stat .= $stat_value . ' Intellect';
        } elseif ($stat_type === 6) {
            $stat .= $stat_value . ' Spirit';
        } elseif ($stat_type === 7) {
            $stat .= $stat_value . ' Stamina';
        }
    }

    return $stat;
}

/**
 * @param  int  $res_val
 * @param  int  $res_type
 *
 * @return string
 */
function itemResistance(int $res_val, int $res_type): string
{
    $resistance_names = [
        0 => 'Holy',
        1 => 'Fire',
        2 => 'Nature',
        3 => 'Frost',
        4 => 'Shadow',
        5 => 'Arcane'
    ];

    return '+' . $res_val . ' ' . ($resistance_names[$res_type] ?? 'Unknown') . ' Resistance';
}

/**
 * @param  int  $skill
 * @param  int  $rank
 *
 * @return string
 */
function requiredSkill(int $skill, int $rank = 1): string
{
    $skills = [
        129 => 'First Aid',
        164 => 'Blacksmithing',
        165 => 'Leatherworking',
        171 => 'Alchemy',
        182 => 'Herbalism',
        185 => 'Cooking',
        186 => 'Mining',
        197 => 'Tailoring',
        202 => 'Engineering',
        333 => 'Enchanting',
        356 => 'Fishing',
        393 => 'Skinning',
    ];

// Shouldn't exist for armory tooltip, just for test remove later
    $riding_keys = [148, 149, 150, 152, 533, 553, 554, 713, 762];
    $riding      = array_fill_keys($riding_keys, 'Riding');

    $skills += $riding;

    return 'Requires ' . ($skills[$skill] ?? 'Unknown') . ' (' . $rank . ')';
}

/**
 * @param  int  $id
 *
 * @return string
 */
function className(int $id): string
{
    if ($id === 1) {
        $class = '<span class="c1">Warrior</span>';
    } elseif ($id === 2) {
        $class = '<span class="c2">Paladin</span>';
    } elseif ($id === 3) {
        $class = '<span class="c3">Hunter</span>';
    } elseif ($id === 4) {
        $class = '<span class="c4">Rogue</span>';
    } elseif ($id === 5) {
        $class = '<span class="c5">Priest</span>';
    } elseif ($id === 7) {
        $class = '<span class="c7">Shaman</span>';
    } elseif ($id === 8) {
        $class = '<span class="c8">Mage</span>';
    } elseif ($id === 9) {
        $class = '<span class="c9">Warlock</span>';
    } elseif ($id === 11) {
        $class = '<span class="c11">Druid</span>';
    } else {
        $class = 'Unknown';
    }

    return $class;
}

/**
 * @param  int   $mask
 * @param  bool  $html
 *
 * @return string
 */
function getAllowableClass(int $mask, bool $html = true): string
{
    $mask &= 0x5DF;
    if ($mask == 0x5DF) {
        return false;
    }

    $tmp = [];
    $cl  = [];
    $i   = 1;

    while ($mask) {
        if ($mask & (1 << ($i - 1))) {
            $tmp[$i]      = (! fMod(count($tmp) + 1, 3) > 10 ? -10 : null) . sprintf('%d', $i);
            $cl[$tmp[$i]] = $html ? className($tmp[$i]) : $tmp[$i];
            $mask         &= ~(1 << ($i - 1));
        }
        $i++;
    }

    return implode(', ', $cl);
}

/**
 * @param  int  $id
 *
 * @return string
 */
function raceName(int $id): string
{
    if ($id === 1) {
        $race = 'Human';
    } elseif ($id === 2) {
        $race = 'Orc';
    } elseif ($id === 3) {
        $race = 'Dwarf';
    } elseif ($id === 4) {
        $race = 'Night Elf';
    } elseif ($id === 5) {
        $race = 'Undead';
    } elseif ($id === 6) {
        $race = 'Tauren';
    } elseif ($id === 7) {
        $race = 'Gnome';
    } elseif ($id === 8) {
        $race = 'Troll';
    } else {
        $race = 'Unknown';
    }

    return $race;
}

/**
 * @param  int  $mask
 *
 * @return string
 */
function getAllowableRace(int $mask): string
{
    $mask          &= 0xFF;
    $mask_horde    = 0xB2;
    $mask_alliance = 0x4D;

    if ($mask == 0xFF) {
        return false;
    }

    if ($mask == $mask_horde) {
        return 'Orc, Undead, Tauren, Troll';
    }

    if ($mask == $mask_alliance) {
        return 'Human, Dwarf, Night Elf, Gnome';
    }

    $tmp  = [];
    $race = [];
    $i    = 1;

    while ($mask) {
        if ($mask & (1 << ($i - 1))) {
            $tmp[$i]        = (! fMod(count($tmp) + 1, 3) > 10 ? -10 : null) . sprintf('%d', $i);
            $race[$tmp[$i]] = raceName($tmp[$i]);
            $mask           &= ~(1 << ($i - 1));
        }
        $i++;
    }

    return implode(', ', $race);
}

/**
 * @param  int  $rank
 *
 * @return string[]
 */
function reqHonorRank(int $rank): array
{
    if ($rank === 5) {
        $title = [0 => 'Private', 1 => 'Scout'];
    } elseif ($rank === 6) {
        $title = [0 => 'Corporal', 1 => 'Grunt'];
    } elseif ($rank === 7) {
        $title = [0 => 'Sergeant', 1 => 'Sergeant'];
    } elseif ($rank === 8) {
        $title = [0 => 'Master Sergeant', 1 => 'Senior Sergeant'];
    } elseif ($rank === 9) {
        $title = [0 => 'Sergeant Major', 1 => 'First Sergeant'];
    } elseif ($rank === 10) {
        $title = [0 => 'Knight', 1 => 'Stone Guard'];
    } elseif ($rank === 11) {
        $title = [0 => 'Knight-Lieutenant', 1 => 'Blood Guard'];
    } elseif ($rank === 12) {
        $title = [0 => 'Knight-Captain', 1 => 'Legionnaire'];
    } elseif ($rank === 13) {
        $title = [0 => 'Knight-Champion', 1 => 'Centurion'];
    } elseif ($rank === 14) {
        $title = [0 => 'Lieutenant Commander', 1 => 'Champion'];
    } elseif ($rank === 15) {
        $title = [0 => 'Commander', 1 => 'Lieutenant General'];
    } elseif ($rank === 16) {
        $title = [0 => 'Marshal', 1 => 'General'];
    } elseif ($rank === 17) {
        $title = [0 => 'Field Marshal', 1 => 'Warlord'];
    } elseif ($rank === 18) {
        $title = [0 => 'Grand Marshal', 1 => 'High Warlord'];
    } else {
        $title = [0 => 'Unknown', 1 => 'Unknown'];
    }

    return $title;
}

/**
 * @param  string  $item_name
 * @param  int     $rank
 *
 * @return string
 */
function getRankByFaction(string $item_name, int $rank): string
{
    $alliance = [
        'Private',
        'Alliance',
        'Sergeant\'s Cape',
        'Master Sergeant\'s',
        'Sergeant Major\'s',
        'Knight\'s',
        'Knight-Lieutenant\'s',
        'Knight-Captain\'s',
        'Lieutenant Commander\'s',
        'Steed',
        'Tiger',
        'Battlestrider',
        'Ram',
        'Marshal\'s',
    ];

    if (preg_match('/\Q' . implode('\E|\Q', $alliance) . '\E/', $item_name)) {
        $result = reqHonorRank($rank)[0];
    } else {
        $result = reqHonorRank($rank)[1];
    }

    return $result;
}

/**
 * @param  int  $rank
 *
 * @return string
 */
function getRepRank(int $rank): string
{
    if ($rank === 0) {
        $rep = "Hated";
    } elseif ($rank === 1) {
        $rep = "Hostile";
    } elseif ($rank === 2) {
        $rep = "Unfriendly";
    } elseif ($rank === 3) {
        $rep = 'Neutral';
    } elseif ($rank === 4) {
        $rep = 'Friendly';
    } elseif ($rank === 5) {
        $rep = 'Honored';
    } elseif ($rank === 6) {
        $rep = 'Revered';
    } elseif ($rank === 7) {
        $rep = 'Exalted';
    } else {
        $rep = 'Unknown';
    }

    return $rep;
}

/**
 * @param  int  $msec
 *
 * @return int[]
 */
function parseTime(int $msec): array
{
    $time = [0, 0, 0, 0, 0];

    if ($_ = ($msec % 1000)) {
        $time[0] = $_;
    }

    $sec = $msec / 1000;

    if ($sec >= 3600 * 24) {
        $time[4] = floor($sec / 3600 / 24);
        $sec     -= $time[4] * 3600 * 24;
    }

    if ($sec >= 3600) {
        $time[3] = floor($sec / 3600);
        $sec     -= $time[3] * 3600;
    }

    if ($sec >= 60) {
        $time[2] = floor($sec / 60);
        $sec     -= $time[2] * 60;
    }

    if ($sec > 0) {
        $time[1] = (int)$sec;
        $sec     -= $time[1];
    }

    return $time;
}

/**
 * @param  int   $msec
 * @param  bool  $short
 *
 * @return string
 */
function formatTime(int $msec, bool $short = false): string
{
    [$ms, $s, $m, $h, $d] = parseTime(abs($msec));
    $sg = ["year", "month", "week", "day", "hour", "minute", "second", "millisecond"];
    $pl = ["years", "months", "weeks", "days", "hours", "minutes", "seconds", "milliseconds"];
    $ab = ["yr", "mo", "wk", "day", "hr", "min", "sec", "ms"];

    if ($short) {
        if ($_ = round($d / 364)) {
            return $_ . ' ' . $ab[0];
        }
        if ($_ = round($d / 30)) {
            return $_ . ' ' . $ab[1];
        }
        if ($_ = round($d / 7)) {
            return $_ . ' ' . $ab[2];
        }
        if ($_ = round($d)) {
            return $_ . ' ' . $ab[3];
        }
        if ($_ = round($h)) {
            return $_ . ' ' . $ab[4];
        }
        if ($_ = round($m)) {
            return $_ . ' ' . $ab[5];
        }
        if ($_ = round($s + $ms / 1000, 2)) {
            return $_ . ' ' . $ab[6];
        }
        if ($ms) {
            return $ms . ' ' . $ab[7];
        }

        return '0 ' . $ab[6];
    } else {
        $_ = $d + $h / 24;
        if ($_ > 1 && ! ($_ % 364))                      // whole years
        {
            return round(($d + $h / 24) / 364, 2) . ' ' . ($d / 364 == 1 && ! $h ? $sg[0] : $pl[0]);
        }
        if ($_ > 1 && ! ($_ % 30))                       // whole month
        {
            return round(($d + $h / 24) / 30, 2) . ' ' . ($d / 30 == 1 && ! $h ? $sg[1] : $pl[1]);
        }
        if ($_ > 1 && ! ($_ % 7))                        // whole weeks
        {
            return round(($d + $h / 24) / 7, 2) . ' ' . ($d / 7 == 1 && ! $h ? $sg[2] : $pl[2]);
        }
        if ($d) {
            return round($d + $h / 24, 2) . ' ' . ($d == 1 && ! $h ? $sg[3] : $pl[3]);
        }
        if ($h) {
            return round($h + $m / 60, 2) . ' ' . ($h == 1 && ! $m ? $sg[4] : $pl[4]);
        }
        if ($m) {
            return round($m + $s / 60, 2) . ' ' . ($m == 1 && ! $s ? $sg[5] : $pl[5]);
        }
        if ($s) {
            return round($s + $ms / 1000, 2) . ' ' . ($s == 1 && ! $ms ? $sg[6] : $pl[6]);
        }
        if ($ms) {
            return $ms . ' ' . ($ms == 1 ? $sg[7] : $pl[7]);
        }

        return '0 ' . ($pl[6]);
    }
}

/**
 * @param  string  $val
 *
 * @return bool
 */
function isInt(string $val): bool
{
    if (is_int($val) || ctype_digit($val)) {
        return true;
    }

    return false;
}

/**
 * @param  string  $var
 *
 * @return int
 */
function getIndex(string $var): int
{
    return abs((int)filter_var($var, FILTER_SANITIZE_NUMBER_INT));
}

/**
 * @param $str
 * @param $s
 * @param $e
 *
 * @return string
 */
function getBetweenStr($str, $s, $e): string
{
    $str = ' ' . $str;
    $ini = strpos($str, $s);
    if ($ini == 0) {
        return '';
    }
    $ini += strlen($s);
    $len = strpos($str, $e, $ini) - $ini;

    return substr($str, $ini, $len);
}

/**
 * @param  int  $patch
 *
 * @return int
 */
function patchToBuild(int $patch): int
{
    $build = [4222, 4297, 4375, 4449, 4544, 4695, 4878, 5086, 5302, 5464, 5875];

    return $build[$patch];
}

/**
 * @param  int  $build
 *
 * @return int
 */
function buildToPatch(int $build): int
{
    $patch = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    if ($build <= 4222) {
        return $patch[0];
    } elseif ($build <= 4297) {
        return $patch[1];
    } elseif ($build <= 4375) {
        return $patch[2];
    } elseif ($build <= 4449) {
        return $patch[3];
    } elseif ($build <= 4544) {
        return $patch[4];
    } elseif ($build <= 4695) {
        return $patch[5];
    } elseif ($build <= 4878) {
        return $patch[6];
    } elseif ($build <= 5086) {
        return $patch[7];
    } elseif ($build <= 5302) {
        return $patch[8];
    } elseif ($build <= 5464) {
        return $patch[9];
    } elseif ($build >= 5875) {
        return $patch[10];
    }

    return 0;
}

/**
 * @param  int  $qty
 *
 * @return string
 */
function formatSellPrice(int $qty): string
{
    $money = '';

    if ($qty >= 10000) {
        $g     = floor($qty / 10000);
        $money .= '<span class="moneygold">' . $g . '</span> ';
        $qty   -= $g * 10000;
    }

    if ($qty >= 100) {
        $s     = floor($qty / 100);
        $money .= '<span class="moneysilver">' . $s . '</span> ';
        $qty   -= $s * 100;
    }

    if ($qty > 0) {
        $money .= '<span class="moneycopper">' . $qty . '</span>';
    }

    return $money;
}

/**
 * @param  int  $value
 *
 * @return string
 */
function getComparisonOperatorName(int $value): string
{
    if ($value === 0) { // ==
        return "Equal To";
    } elseif ($value === 1) { // >=
        return "Equal Or Greater Than";
    } elseif ($value === 2) { // <=
        return "Equal Or Less Than";
    }

    return "";
}

/**
 * @param  int  $id
 *
 * @return string
 */
function getTeamName(int $id): string
{
    if ($id === 1) {
        return "Crossfaction";
    } elseif ($id === 67) {
        return "Horde";
    } elseif ($id === 469) {
        return "Alliance";
    }

    return "";
}

/**
 * @param  int  $id
 *
 * @return string
 */
function getGenderName(int $id): string
{
    if ($id === 0) {
        return "Male";
    } elseif ($id === 1) {
        return "Female";
    } elseif ($id === 2) {
        return "None";
    }

    return "";
}

/**
 * @param  int  $id
 *
 * @return string
 */
function getLootStateName(int $id): string
{
    if ($id === 0) {
        return "Not Ready";
    } elseif ($id === 1) {
        return "Ready";
    } elseif ($id === 2) {
        return "Activated";
    } elseif ($id === 3) {
        return "Just Deactivated";
    }

    return "";
}

/**
 * @param  int  $id
 *
 * @return string
 */
function getGOStateName(int $id): string
{
    if ($id === 0) {
        return "Active";
    } elseif ($id === 1) {
        return "Ready";
    } elseif ($id === 2) {
        return "Alternative";
    }

    return "";
}

/**
 * @param  int  $id
 *
 * @return string
 */
function nearByPlayer(int $id): string
{
    if ($id === 0) {
        return "Any";
    } elseif ($id === 1) {
        return "Hostile";
    } elseif ($id === 2) {
        return "Friendly";
    }

    return "";
}

/**
 * @param  array  $index
 * @param  bool   $show_name
 *
 * @return string
 */
function spellRange(array $index = [], bool $show_name = false): string
{
    if (empty($index)) {
        return '';
    }

    $min  = $index[0];
    $max  = $index[1];
    $name = $show_name ? ' <small>(' . $index[2] . ')</small>' : '';

    if (! $max) {
        if ($show_name) {
            return '0 yards' . $name;
        } else {
            return '';
        }
    }

    // minRange exists; show as range
    if ($min) {
        return sprintf("%s yd range", $min . ' - ' . $max) . $name;
    } // hardcode: "melee range"
    elseif ($max == 5) {
        return "Melee Range" . $name;
    } // hardcode "unlimited range"
    elseif ($max == 50000) {
        return "Unlimited Range" . $name;
    } // regular case
    else {
        return sprintf("%s yd range", $max) . $name;
    }
}

/**
 * @param  int  $id
 *
 * @return string
 */
function powerType(int $id): string
{
    if ($id === 0) {
        return "Mana";
    } elseif ($id === 1) {
        return "Rage";
    } elseif ($id === 2) {
        return "Focus";
    } elseif ($id === 3) {
        return "Energy";
    } elseif ($id === 4294967294) {
        return "Health";
    } else {
        return "Unknown";
    }
}

/**
 * @param  int  $id
 *
 * @return string
 */
function schoolType(int $id): string
{
    if ($id === 0) {
        return "Physical";
    } elseif ($id === 1) {
        return "Holy";
    } elseif ($id === 2) {
        return "Fire";
    } elseif ($id === 3) {
        return "Nature";
    } elseif ($id === 4) {
        return "Frost";
    } elseif ($id === 5) {
        return "Shadow";
    } elseif ($id === 6) {
        return "Arcane";
    }

    return "";
}

/**
 * @param  int  $id
 *
 * @return string
 */
function dispelType(int $id): string
{
    if ($id === 1) {
        return "Magic";
    } elseif ($id === 2) {
        return "Curse";
    } elseif ($id === 3) {
        return "Disease";
    } elseif ($id === 4) {
        return "Poison";
    } elseif ($id === 5) {
        return "Stealth";
    } elseif ($id === 6) {
        return "Invisibility";
    } elseif ($id === 7) {
        return "All";
    } elseif ($id === 8) {
        return "NPC Only";
    } elseif ($id === 9) {
        return "Enrage";
    } elseif ($id === 10) {
        return "ZG Ticket";
    }

    return "";
}

/**
 * @param  int  $type
 * @param  int  $cost
 * @param  int  $cost_per_level
 * @param  int  $cost_per_second
 *
 * @return string
 */
function spellPowerCost(int $type, int $cost, int $cost_per_level, int $cost_per_second): string
{
    $str = '';

    if ($cost > 0 || $cost_per_second > 0 || $cost_per_level > 0) {
        $str .= ($type === 1 ? $cost / 10 : $cost) . ' ' . powerType($type);
    }

    // append periodic cost
    if ($cost_per_second > 0) {
        $str .= sprintf(', plus %s per sec', $cost_per_second);
    }

    // append level cost (todo (low): work in as scaling cost)
    if ($cost_per_level > 0) {
        $str .= sprintf(', plus %s per level', $cost_per_level);
    }

    return $str;
}

/**
 * @param  int  $attr
 *
 * @return int
 */
function isChanneledSpell(int $attr): int
{
    return $attr & (0x00000004 | 0x00000040);
}


/**
 * @param  int  $cast_time
 * @param  int  $attr_ex
 * @param  int  $type
 *
 * @return string
 */
function spellCastTime(int $cast_time, int $attr_ex, int $type): string
{
    $ch = false;
    if (isChanneledSpell($attr_ex)) {
        $ch = true;
    }

    if ($cast_time > 0) {
        if ($ch) {
            return sprintf('Channeled (%s sec cast)', $cast_time / 1000);
        } else {
            return sprintf('%s sec cast', $cast_time / 1000);
        }
    } elseif ($type != 1) {
        return "Instant";
    } else {
        return "Instant cast";
    }
}

/**
 * @param  int  $recovery_time
 *
 * @return string
 */
function spellCD(int $recovery_time): string
{
    if ($recovery_time) {
        return sprintf('%s cooldown', formatTime($recovery_time, true));
    } else {
        return '';
    }
}

/**
 * @return int[]
 */
function getMasks(): array
{
    return [1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096, 8192, 16384, 32768, 65536, 131072, 262144, 524288, 1048576, 2097152, 4194304, 8388608, 16777216, 33554432, 67108864, 134217728, 268435456, 536870912, 1073741824, 2147483648];
}

/**
 * @param $loot
 * @param $newloot
 *
 * @return void
 */
function addLoot(&$loot, $newloot)
{
    $exist = [];
    foreach ($loot as $offset => $item) {
        $exist[$item['entry']] = $offset;
    }

    foreach ($newloot as $newitem) {
        // Shouldn't happen
        if (! is_array($newitem)) {
            return;
        }

        if (isset($exist[$newitem['entry']])) {
            $loot[$exist[$item['entry']]]['mincount']     = min($loot[$exist[$item['entry']]]['mincount'], $newitem['mincount']);
            $loot[$exist[$item['entry']]]['maxcount']     = max($loot[$exist[$item['entry']]]['maxcount'], $newitem['maxcount']);
            $loot[$exist[$item['entry']]]['percent']      += $newitem['percent'];
            $loot[$exist[$item['entry']]]['group']        = 0;
            $loot[$exist[$item['entry']]]['condition_id'] = $newitem['condition_id'];
        } else {
            $loot[] = $newitem;
        }
    }
}

/**
 * @param  int  $val
 *
 * @return int|void
 */
function sign(int $val)
{
    if ($val > 0) {
        return 1;
    }
    if ($val < 0) {
        return -1;
    }
    if ($val === 0) {
        return 0;
    }
}