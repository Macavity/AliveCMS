<?php 
/**
 * @package FusionCMS
 * @version 6.0
 * @author Jesper Lindström
 * @author Xavier Geerinck
 * @link http://raxezdev.com/fusioncms
 */

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
 
$config["races"] = array(
    RACE_HUMAN      => "Mensch",
    RACE_DWARF      =>  array("Zwerg", "Zwerg"),
    RACE_NIGHTELF   =>  array("Nachtelf", "Nachtelf"),
    RACE_GNOME      =>  array("Gnom", "Gnom"),
    RACE_DRAENEI    =>  "Draenei",
    
    RACE_ORC        =>  "Ork",
    RACE_UNDEAD     =>  array("Untoter", "Untote"),
    RACE_TAUREN     =>  array("Tauren", "Tauren"),
    RACE_TROLL      =>  array("Troll", "Troll"),
    RACE_BLOODELF   =>  array("Blutelf", "Blutelf"),
);

$config["classes"] = array(
    CLASS_WARRIOR      =>  array("Krieger", "Kriegerin"),
    CLASS_PALADIN      =>  array("Paladin", "Paladin"),
    CLASS_HUNTER       =>  array("J&auml;ger", "J&auml;gerin"),
    CLASS_ROGUE        =>  array("Schurke", "Schurkin"),
    CLASS_PRIEST       =>  array("Priester", "Priesterin"),
    CLASS_DK           =>  array("Todesritter", "Todesritterin"),
    CLASS_SHAMAN       =>  array("Schamane", "Schamanin"),
    CLASS_MAGE         =>  array("Magier", "Magierin"),
    CLASS_WARLOCK      =>  array("Hexenmeister", "Hexenmeisterin"),
    CLASS_DRUID        =>  array("Druide", "Druidin"),
);

$config['alliance_races'] = array(1,3,4,7,11);
$config['horde_races'] = array(2,5,6,8,10);
