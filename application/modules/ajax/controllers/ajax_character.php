<?php
    class Ajax_character extends MX_Controller
    {
        private $realm;
        private $realmId;
        private $realmName;

        private $characterName;
        private $characterGuid;
        private $CharacterJsonData = array();
        
        private $items;

        function __construct()
        {
            parent::__construct();

            $this->load->model("armory_model");
            $this->load->config("wow_constants");

            if(false){
                $this->armory_model = new Armory_model();
                $this->template = new Template();
            }
        }
        
        /*
         * Initialize
         */
         public function index($realm = false, $characterName = false)
         {
             $char = utf8_decode(urldecode($characterName));

             $this->setChar($realm, $characterName);

             $this->armory_model->setRealm($this->realmId);
             $this->armory_model->setId($this->characterGuid);

             $char = $this->armory_model->getCharacter();

             //debug("Char", $char);

             if($char["level"] > "0" && $char["level"] < "81")
             {
                 $stats = $this->getCharJson();
                 $this->template->handleJsonOutput($stats);
             }
             else
             {
                 $this->getError(ERROR_CHARACTER_NOT_FOUND);
             }
         }
         
         /**
          * Realm Id und Charakter GUID an das armory model senden
          * @param String $realmName Der vom Router übergebene Name des Realms 
          * @param String $char Der vom Router übergebene Name des Charakters
          */
        private function setChar($realmName, $char)
        {
            $validRealm = FALSE;
            
            /**
             * Sofern gefunden wird hier die Realm ID hinterlegt bevor sie zur Speicherung in Ajax_Character.realmId abgelegt wird
             * @type Integer
             */
            $realmId = -1;

            if(is_string($realmName) && is_string($char))
            {
                foreach($this->realms->getRealms() as $realmRow)
                {
                    //echo "<br>".$realmRow->getName()." =? ".$realmName;
                    if($realmRow->getName() == $realmName)
                    {
                        $realmId = $realmRow->getId();
                        //debug("Valid Realm",$realmId);
                        $validRealm = TRUE;
                    }
                }
            }
            else{
                $this->getError(ERROR_REALM_NOT_FOUND);
            }
             
            if($validRealm){
                
                $realm = $this->realms->getRealm($realmId);
                $charDb = $realm->getCharacters();

                $characterGuid = $charDb->getGuidByName($char);

                //debug("char",$characterGuid);

                if($characterGuid > 0){
                    //debug("Character found");
                    $this->characterName = $char;
                    $this->characterGuid = $characterGuid;

                    $this->realmName = $realmName;
                    $this->realmId = $realmId;
                }
                else{
                    $this->getError(ERROR_CHARACTER_NOT_FOUND);
                }
                
            }
            else{
                $this->getError(ERROR_REALM_NOT_FOUND);
            }

            return true;
        }
         
        /*
         * Fehlerausgabe
         */
        private function getError($type)
        {
            switch($type)
            {
                case ERROR_CHARACTER_NOT_FOUND:
                    echo "Der gesuchte Charakter wurde nicht gefunden.";
                    break;
                case ERROR_REALM_NOT_FOUND:
                    echo "Der angegebene Realm wurde nicht gefunden.";
                    break;
                default:
                    echo "Ein unerwarteter Fehler ist aufgetreten.";
                    break;
            }
            exit();
        }
          
        /*
         * Json erstellen
         */
        private function getCharJson()
        {
            $head = 0;
            $neck = 0;
            $shoulder = 0;
            $shirt = 0;
            $chest = 0;
            $waist = 0;
            $legs = 0;
            $feet = 0;
            $wrists = 0;
            $hands = 0;
            $finger1 = 0;
            $finger2 = 0;
            $trinket1 = 0;
            $trinket2 = 0;
            $back = 0;
            $tabard = 0;
            $mainHand = 0;
            $offHand = 0;

            $power = 0;
            $powerType = 0;

            $stats = $this->armory_model->getStats();
            $char = $this->armory_model->getCharacter();
            $professions = $this->armory_model->getProfessions();
            $items = $this->armory_model->getItems();
            $realm = $this->realms->getRealm(1);
            $world = $realm->getWorld();

            foreach($items as $item)
            {
                $item2 = $world->getItem($item["itemEntry"]);
                switch($item2["InventoryType"])
                {
                    case 1: // Kopf
                        $head = $item["itemEntry"];
                        break;
                    case 2: // Nacken
                        $neck = $item["itemEntry"];
                        break;
                    case 3: // Schulter
                        $shoulder = $item["itemEntry"];
                        break;
                    case 4: // Hemd
                        $shirt = $item["itemEntry"];
                        break;
                    case 5: // Brust
                    case 20:
                        $chest = $item["itemEntry"];
                        break;
                    case 6: // Gürtel
                        $waist = $item["itemEntry"];
                        break;
                    case 7: // Beine
                        $legs = $item["itemEntry"];
                        break;
                    case 8: // Füße
                        $feet = $item["itemEntry"];
                        break;
                    case 9: // Handgelenke
                        $wrists = $item["itemEntry"];
                        break;
                    case 10: // Hände
                        $hands = $item["itemEntry"];
                        break;
                    case 11: // Finger
                        if($finger1 == 0)
                        {
                            $finger1 = $item["itemEntry"];
                            break;
                        }
                        else
                        {
                            $finger2 = $item["itemEntry"];
                            break;
                        }
                    case 12: // Trinket
                        if($trinket1 == 0)
                        {
                            $trinket1 = $item["itemEntry"];
                            break;
                        }
                        else
                        {
                            $trinket2 = $item["itemEntry"];
                            break;
                        }
                    case 16: // Back
                        $back = $item["itemEntry"];
                        break;
                    case 18: // Tabard
                        $tabard = $item["itemEntry"];
                        break;
                    case 21: // MainHand
                        $mainHand = $item["itemEntry"];
                        break;
                    case 22: // Offhand
                    case 23:
                        $offHand = $item["itemEntry"];
                        break;
                    case 14:
                        $offHand = $item["itemEntry"];
                        break;
                    case 13:
                    case 17: // MainHand
                        if($mainHand == 0)
                        {
                            $mainHand = $item["itemEntry"];
                            break;
                        }
                        else
                        {
                            $offHand = $item["itemEntry"];
                            break;
                        }
                    default:
                        break;
                }
            }

            switch($char["class"])
            {
                case CLASS_PALADIN: // paladin
                case CLASS_HUNTER: // hunter
                case CLASS_PRIEST: // priest
                case CLASS_SHAMAN: // shaman
                case CLASS_MAGE: // mage
                case CLASS_WARLOCK: // warlock
                case CLASS_DRUID: // druid
                    $power = $char["power1"];
                    $powerType = "mana";
                    break;
                case CLASS_WARRIOR: // warrior
                    $power = $char["power4"];
                    $powerType = "rage";
                    break;
                case CLASS_ROGUE: // rogue
                    $power = $char["power4"];
                    $powerType = "energy";
                    break;
                case CLASS_DK: // deathknight
                    $power = $char["power4"];
                    $powerType = "runic-power";
                    break;
            }
            
            $CharacterJsonData = array(
                   "name" => $char["name"],
                   "level" => $char["level"],
                   "race" => $char["race"],
                   "class" => $char["class"],
                   "gender" => $char["gender"],
                   "guild" => array(
                       "name" => $this->armory_model->getGuildName($this->armory_model->getGuild()),
                       "realm" => $this->realmName,
                   ),
                   "stats" => array(
                       "health" => $stats["maxhealth"],
                       "power" => $power,
                       "powerType" => $powerType,
                       "str" => $stats["strength"],
                       "agi" => $stats["agility"],
                       "sta" => $stats["stamina"],
                       "int" => $stats["intellect"],
                       "spr" => $stats["spirit"],
                       "attackPower" => $stats["attackPower"],
                       "crit" => $stats["critPct"],
                       "rangedCrit" => $stats["rangedCritPct"],
                       "spellPower" => $stats["spellPower"],
                       "spellCrit" => $stats["spellCritPct"],
                       "armor" => $stats["armor"],
                       "dodge" => $stats["dodgePct"],
                       "parry" => $stats["parryPct"],
                       "block" => $stats["blockPct"],
                       "pvpResilience" => $stats["resilience"],
                   ),
                   "professions" => array(
                       "primary" => array(
                           array(
                               "id" => $professions["0"]["skill"],
                               "name" => $professions["0"]["name"],
                               "rank" => $professions["0"]["value"],
                               "max" => $professions["0"]["max"],
                               "icon" => $professions["0"]["icon"],
                           ),
                           array(
                               "id" => $professions["1"]["skill"],
                               "name" => $professions["1"]["name"],
                               "rank" => $professions["1"]["value"],
                               "max" => $professions["1"]["max"],
                               "icon" => $professions["1"]["icon"],
                           ),
                       ),
                   ),
                   "items" => array(
                       "head" => array(
                           "id" => $head,
                       ),
                       "neck" => array(
                           "id" => $neck,
                       ),
                       "shoulder" => array(
                           "id" => $shoulder,
                       ),
                       "shirt" => array(
                           "id" => $shirt,
                       ),
                       "chest" => array(
                           "id" => $chest,
                       ),
                       "waist" => array(
                           "id" => $waist,
                       ),
                       "legs" => array(
                           "id" => $legs,
                       ),
                       "feet" => array(
                           "id" => $feet,
                       ),
                       "wrist" => array(
                           "id" => $wrists,
                       ),
                       "hands" => array(
                           "id" => $hands,
                       ),
                       "finger1" => array(
                           "id" => $finger1,
                       ),
                       "finger2" => array(
                           "id" => $finger2,
                       ),
                       "trinket1" => array(
                           "id" => $trinket1,
                       ),
                       "trinket2" => array(
                           "id" => $trinket2,
                       ),
                       "back" => array(
                           "id" => $back,
                       ),
                       "mainHand" => array(
                           "id" => $mainHand,
                       ),
                       "offHand" => array(
                           "id" => $offHand,
                       ),
                       /*"tabard" => array(
                           "id" => $items["18"]["itemEntry"],
                       ),*/
                   ),
               );
               
               return $CharacterJsonData;
           }
    }        