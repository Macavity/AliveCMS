<?php

define("RACE_HUMAN", 1);
define("RACE_ORC", 2);
define("RACE_DWARF", 3);
define("RACE_NIGHTELF", 4);
define("RACE_UNDEAD", 5);
define("RACE_TAUREN", 6);
define("RACE_GNOME", 7);
define("RACE_TROLL", 8);
define("RACE_BLOODELF", 10);
define("RACE_DRAENEI", 11);

define("FACTION_ALLIANCE", 0);
define("FACTION_HORDE", 1);

define('CLASS_WARRIOR', 0x01);
define('CLASS_PALADIN', 0x02);
define('CLASS_HUNTER',  0x03);
define('CLASS_ROGUE',   0x04);
define('CLASS_PRIEST',  0x05);
define('CLASS_DK',      0x06);
define('CLASS_SHAMAN',  0x07);
define('CLASS_MAGE',    0x08);
define('CLASS_WARLOCK', 0x09);
define('CLASS_DRUID',   0x0B);

$config['races'] = lang("races", "wow_constants");
$config['classes'] = lang("classes", "wow_constants");

$config['alliance_races'] = array(1,3,4,7,11);
$config['horde_races'] = array(2,5,6,8,10);

// Do not edit these unless you edit the corrosponding files names in:
// application/images/avatars/
$config['races_en'] = array(
	1 => "Human",
	2 => "Orc",
	3 => "Dwarf",
	4 => "Night elf",
	5 => "Undead",
	6 => "Tauren",
	7 => "Gnome",
	8 => "Troll",
	9 => "Goblin",
	10 => "Blood elf",
	11 => "Draenei",
	22 => "Worgen"
);
$config['classes_en'] = array(
	1 => "Warrior",
	2 => "Paladin",
	3 => "Hunter",
	4 => "Rogue",
	5 => "Priest",
	6 => "Death knight",
	7 => "Shaman",
	8 => "Mage",
	9 => "Warlock",
	11 => "Druid"
);
