<?php

define("MIGRATION_STATUS_OPEN", 1);
define("MIGRATION_STATUS_IN_PROGRESS", 2);
define("MIGRATION_STATUS_DONE", 3);
define("MIGRATION_STATUS_DECLINED", 4);
define("MIGRATION_STATUS_LEGACY", 5);

class Migration_Model extends CI_Model {

    var $tableName = "migration_entries";

    protected $factionsAlliance = array();
    protected $factionsHorde = array();
    protected $factionsBC = array();
    protected $factionsWotlk = array();
    protected $factionsAll = array();
    protected $reputationStates = array();
    protected $ridingLevels = array();
    protected $reputations = array();
    protected $professions = array();
    protected $equipmentSlots = array();
    protected $migrationStates = array();


    public function __construct(){
        parent::__construct();

        if(defined("INCLUDED_WOW_CONSTANTS") == FALSE){
            $this->load->config("wow_constants");
        }

        $this->factionsAlliance = array(
            72 => "Sturmwind",
            930 => "Die Exodar",
            47 => "Eisenschmiede",
            54 => "Gnomeregangnome",
            69 => "Darnassus",
        );

        $this->factionsHorde = array(
            76 => "Orgrimmar",
            911 => "Silbermond",
            68 => "Unterstadt",
            81 => "Donnerfels",
            530 => "Dunkelspeertrolle",
        );

        $this->factionsBC = array(
            933 => "Das Konsortium",
            967 => "Das Violette Auge",
            1012 => "Die Todeshörigen",
            990 => "Die Wächter der Sande",
            946 => "Ehrenfeste",
            942 => "Expedition des Cenarius",
            989 => "Hüter der Zeit",
            978 => "Kurenai",
            1015 => "Netherschwingen",
            1038 => "Ogrila",
            970 => "Sporeggar",
            947 => "Thrallmar",
        );

        $this->factionsWotlk = array(
            809 => "Shen'dralar:",
            1073 => "Die Kalu'ak",
            1090 => "Kirin Tor",
            1091 => "Der Wyrmruhpakt",
            1098 => "Ritter der schwarzen Klinge",
            1104 => "Stamm der Wildherzen",
            1105 => "Die Orakel",
            1106 => "Argentumkreuzung",
            1119 => "Die Söhne Hodir",
            1156 => "Das Äscherne Verdikt",
        );

        $this->factionsWotlkA = array(
            1050 => "Expedition Valianz",
            1068 => "Forscherliga",
            1094 => "Der Silberbund",
            1126 => "Die Frosterben",
        );

        $this->factionsWotlkH = array(
            1064 => "Die Taunka",
            1067 => "Die Hand der Rache",
            1085 => "Kriegshymnenoffensive",
            1124 => "Die Sonnenhäscher",
        );



        $this->factionsAll += $this->factionsAlliance
            + $this->factionsHorde
            + $this->factionsBC
            + $this->factionsWotlk
            + $this->factionsWotlkA
            + $this->factionsWotlkH;

        $this->factionsAll += array(
            469 => "Allianz (Classic)",
            67 => "Horde (Classic)",
            1037 => "Vorposten der Allianz",
            1052 => "Expedition der Horde",
        );

        //debug($this->factionsAll);

        $this->reputationStates = array(
            0 => "-",
            1 => "Freundlich",
            2 => "Wohlwollend",
            3 => "Respektvoll",
            4 => "Ehrfürchtig",
        );

        $this->ridingLevels = array(
            0 => 0,
            75 => '75',
            150 => '150',
            225 => '225',
            300 => '300',
            301 => '300+Kaltwetter'
        );

        $this->reputations = array(
            "repWotlk" => array(
                "label" => "Wrath of the Lich King",
                "factions" => $this->factionsWotlk,
                "alliance" => $this->factionsWotlkA,
                "horde" => $this->factionsWotlkH,
            ),
            "repBC" => array(
                "label" => "Burning Crusade",
                "factions" => $this->factionsBC,
            ),
            "repClassic" => array(
                "label" => "Classic",
                "factions" => array(),
                "alliance" => $this->factionsAlliance,
                "horde" => $this->factionsHorde,
            ),
        );

        $this->professions = array(
            171 => array(
                'label' => 'Alchemie',
                'base' => 2259,
            ),
            164 => array(
                'label' => 'Schmiedekunst',
                'base' => 2018,
            ),
            333 => array(
                'label' => 'Verzauberungskunst',
                'base' => 7411,
            ),
            202 => array(
                'label' => 'Ingenieurskunst',
                'base' => 4036,
            ),
            182 => array(
                'label' => 'Kräutersammeln',
                'base' => 2366,
            ),
            165 => array(
                'label' => 'Lederer',
                'base' => 2108,
            ),
            186 => array(
                'label' => 'Bergbau',
                'base' => 2575,
            ),
            393 => array(
                'label' => 'Kürschnerei',
                'base' => 8613,
            ),
            197 => array(
                'label' => 'Schneiderei',
                'base' => 3908,
            ),
            773 => array(
                'label' => 'Inschriftenkunde',
                'base' => 45357,
            ),
            755 => array(
                'label' => 'Juwelenschleifen',
                'base' => 25229,
            ),
        );

        $this->equipmentSlots = array(
            INV_HEAD => 'Kopf',
            INV_NECK => 'Hals',
            INV_SHOULDER => 'Schulter',
            INV_BACK => 'Rücken',
            INV_CHEST => 'Brust',
            INV_TABARD => 'Wappenrock',
            INV_BRACERS => 'Handgelenke',
            INV_GLOVES => 'Hände',
            INV_BELT => 'Taille',
            INV_LEGS => 'Beine',
            INV_BOOTS => 'Füsse',
            INV_RING_1 => 'Ring 1',
            INV_RING_2 => 'Ring 2',
            INV_TRINKET_1 => 'Schmuck 1',
            INV_TRINKET_2 => 'Schmuck 2',
            INV_MAIN_HAND => 'Waffenhand',
            INV_OFF_HAND => 'Nebenhand',
            INV_RANGED_RELIC => 'Distanzwaffe/etc',
        );

        $this->migrationStates = array(
            MIGRATION_STATUS_OPEN => array(
                "label" => "Offen"
            ),
            MIGRATION_STATUS_IN_PROGRESS => array(
                "label" => "In Bearbeitung"
            ),
            MIGRATION_STATUS_DECLINED => array(
                "label" => "Abgewiesen"
            ),
            MIGRATION_STATUS_DONE => array(
                "label" => "Erledigt"
            ),
        );

    }

    public function calcAllianceRep($reputations){
        $array = array(72,930,47,54,69);

        $sum = 0;
        foreach($array as $key){
            $sum += isset($reputations[$key], $reputations[$key]['standing']) ? $reputations[$key]['standing'] : 0;
        }

        return round($sum/5);
    }

    public function calcAllianceWotlkRep($reputations){
        $array = array(1094,1126,1050,1068);

        $sum = 0;
        foreach($array as $key){
            $sum += isset($reputations[$key], $reputations[$key]['standing']) ? $reputations[$key]['standing'] : 0;
        }

        return round($sum/4);
    }

    public function calcHordeRep($reputations){
        $array = array(76,911,68,81,530);

        $sum = 0;
        foreach($array as $key){
            $sum += isset($reputations[$key], $reputations[$key]['standing']) ? $reputations[$key]['standing'] : 0;
        }

        return round($sum/5);
    }

    public function calcHordeWotlkRep($reputations){
        $array = array(1067,1124,1064,1085);

        $sum = 0;
        foreach($array as $key){
            $sum += isset($reputations[$key], $reputations[$key]['standing']) ? $reputations[$key]['standing'] : 0;
        }

        return round($sum/4);
    }

    /**
     * Imports data entries from migration_archive to migration_entries
     * skips over items & reputation
     */
    public function importMigrationArchive(){

        $this->db->select("*")
            ->from("formular_basis");

        $query = $this->db->get();

        if($query->num_rows() > 0){

            foreach ($query->result() as $row){


                $this->db->select("id")
                    ->where("id", $row->id)
                    ->from($this->tableName);

                $count = $this->db->count_all_results();

                if($count > 0){
                    continue;
                }

                echo "<br>Transfer #".$row->id.": ";

                /**
                 * Items
                 */
                $items = array(
                    'equipment' => array(),
                    'mounts' => array(),
                    'random' => array(),
                );

                $itemQuery = $this->db->select('*')
                    ->where('id', $row->id)
                    ->from('formular_item')->get();

                if($itemQuery->num_rows() > 0){
                    $results = $itemQuery->result_array();

                    $itemRow = $results[0];

                    $items['equipment'] = array(
                        INV_HEAD => $itemRow['kopf'],
                        INV_NECK => $itemRow['hals'],
                        INV_SHOULDER => $itemRow['schulter'],
                        INV_BACK => $itemRow['ruecken'],
                        INV_CHEST => $itemRow['brust'],
                        INV_TABARD => $itemRow['wappenrock'],
                        INV_BRACERS => $itemRow['handgelenke'],
                        INV_GLOVES => $itemRow['haende'],
                        INV_BELT => $itemRow['taille'],
                        INV_LEGS => $itemRow['beine'],
                        INV_BOOTS => $itemRow['fuesse'],
                        INV_RING_1 => $itemRow['ring1'],
                        INV_RING_2 => $itemRow['ring2'],
                        INV_TRINKET_1 => $itemRow['schmuck1'],
                        INV_TRINKET_2 => $itemRow['schmuck2'],
                        INV_MAIN_HAND => $itemRow['waffenhand'],
                        INV_OFF_HAND => $itemRow['nebenhand'],
                        INV_RANGED_RELIC => $itemRow['distanzwaffe'],
                    );

                    $items['mounts']['fly'] = $itemRow['mount_f'];
                    $items['mounts']['floor'] = $itemRow['mount_b'];
                }

                $randomQuery = $this->db->select('*')
                    ->where('id', $row->id)
                    ->from('formular_randomitem')->get();

                if($randomQuery->num_rows() > 0){
                    $results = $randomQuery->result_array();

                    $randomRow = $results[0];

                    for($i = 1; $i <= 10; $i++){
                        $items['random'][] = $randomRow['ri'.$i];
                    }

                }

                /*
                 * Actions
                 */
                $actions = array();

                /*
                 * Status
                 */
                if(substr_count($row->gm, "Erledigt von") > 0){
                    $state = MIGRATION_STATUS_DONE;

                    if(preg_match("/Erledigt von ([A-Za-z]+)(.*)/",$row->gm, $matches)){
                        $array = explode(" ", $str, 2);
                        $actions["by"] = $matches[1];
                        $actions["reason"] = $matches[2];
                    }
                    else{
                        $actions["by"] = $str;
                    }
                }
                elseif(substr_count($row->gm, "Ist in Bearbeitung") > 0){
                    $state = MIGRATION_STATUS_IN_PROGRESS;
                    $actions["by"] = str_replace("Ist in Bearbeitung","", $row->gm);
                }
                elseif(substr_count($row->gm, "Gel") > 0){
                    $state = MIGRATION_STATUS_DECLINED;

                    if(preg_match("/Gel[^\s]+ von ([A-Za-z]+)(.*)/",$row->gm, $matches)){
                        $actions["by"] = $matches[1];
                        $actions["reason"] = $matches[2];
                    }
                }
                elseif($row->id < 2000){
                    // Too old anyway.
                    $state = MIGRATION_STATUS_DECLINED;
                }
                else{
                    $state = MIGRATION_STATUS_OPEN;
                }

                if(!empty($actions["by"])){
                    $actions["by"] = ucfirst(strtolower(trim($actions["by"])));
                }
                if(!empty($actions["reason"])){
                    $actions["reason"] = trim($actions["reason"]);
                }

                /*
                 * Account ID
                 */
                if(is_numeric($row->account_id) && $row->account_id > 0) {
                    $accountId = $row->account_id;
                }
                elseif(is_numeric($row->name) && $row->name > 0){
                    $accountId = $row->name;
                }
                else{
                    $username = $row->name;

                    $accountId = $this->external_account_model->getId($username);
                }

                /*
                 * Skills
                 */
                $profs = array(
                    "Beruf1" => $row->beruf1,
                    "Beruf2" => $row->beruf2,
                    "Beruf1_skill" => $row->beruf1skill,
                    "Beruf2_skill" => $row->beruf2skill,
                );

                $skills = array(
                    "Riding" => (substr_count($row->reiten, "Kaltwetter") > 0) ? 301 : $row->reiten,
                    "Cooking" => $row->kochen,
                    "Angling" => $row->angeln,
                    "Firstaid" => $row->erstehilfe,
                    "professions" => array(),
                );

                for($i = 1; $i <= 2; $i++){
                    $profName = $profs['Beruf'.$i];
                    $profSkill = $profs['Beruf'.$i.'_skill'];

                    switch($profName){
                        case "Schmiedekunst":
                            $spell_skill = 164;
                            break;
                        case "Verzauberungskunst":
                            $spell_skill = 333;
                            break;
                        case "Ingeneurskunst":
                            $spell_skill = 202;
                            break;
                        case "Kraeutersammeln":
                            $spell_skill = 182;
                            break;
                        case "Juwelenschleifen":
                            $spell_skill = 755;
                            break;
                        case "Lederer":
                            $spell_skill = 165;
                            break;
                        case "Bergbau":
                            $spell_skill = 186;
                            break;
                        case "Kuerschnerei":
                            $spell_skill = 393;
                            break;
                        case "Schneiderei":
                            $spell_skill = 197;
                            break;
                        case "Inschriftenkunde":
                            $spell_skill = 773;
                            break;
                        case "Alchemie":
                            $spell_skill = 171;
                            break;
                    }

                    $skills["professions"][$i] = array(
                        'skill' => $spell_skill,
                        'skill_level' => $profSkill,
                    );

                }

                /*
                 * Reputations (Legacy)
                 */
                $reputations = array(
                    "archive" => true,
                );

                /**
                 * Race
                 */
                $race = null;
                switch($row->rasse){
                    case 'Allianz - Dranei':
                        $race = RACE_DRAENEI;
                        break;
                    case 'Allianz - Gnom':
                        $race = RACE_GNOME;
                        break;
                    case 'Allianz - Mensch':
                        $race = RACE_HUMAN;
                        break;
                    case 'Allianz - Nachtelf':
                        $race = RACE_NIGHTELF;
                        break;
                    case 'Allianz - Zwerg':
                        $race = RACE_DWARF;
                        break;
                    case 'Horde - Blutelf':
                        $race = RACE_BLOODELF;
                        break;
                    case 'Horde - Orc':
                        $race = RACE_ORC;
                        break;
                    case 'Horde - Tauren':
                        $race = RACE_TAUREN;
                        break;
                    case 'Horde - Troll':
                        $race = RACE_TROLL;
                        break;
                    case 'Horde - Untoter':
                        $race = RACE_UNDEAD;
                        break;
                }

                /**
                 * Class
                 */
                $class = null;
                switch($row->klasse){
                    case 'Druide':
                        $class = CLASS_DRUID;
                        break;
                    case 'Hexenmeister':
                        $class = CLASS_WARLOCK;
                        break;
                    case 'Jaeger':
                        $class = CLASS_HUNTER;
                        break;
                    case 'Krieger':
                        $class = CLASS_WARRIOR;
                        break;
                    case 'Magier':
                        $class = CLASS_MAGE;
                        break;
                    case 'Paladin':
                        $class = CLASS_PALADIN;
                        break;
                    case 'Priester':
                        $class = CLASS_PRIEST;
                        break;
                    case 'Schamane':
                        $class = CLASS_SHAMAN;
                        break;
                    case 'Schurke':
                        $class = CLASS_ROGUE;
                        break;
                    case 'Todesritter':
                        $class = CLASS_DK;
                        break;
                }

                $actions = array($actions);

                $importData = array(
                    "id" => $row->id,
                    "date_created" => $row->datum,
                    "date_done" => "",
                    "status" => $state,
                    "account_id" => $accountId,
                    "icq" => $row->icq,
                    "character_name" => $row->char,
                    "character_class" => $class,
                    "character_race" => $race,
                    "server_name" => $row->server,
                    "server_link" => $row->serverlink,
                    "character_armory" => $row->armorylink,
                    "screenshots_link" => $row->screen,
                    "comment" => $row->bemerkung,
                    "level" => $row->level,
                    "gold" => $row->gold,
                    "skills" => json_encode($skills),
                    "reputations" => json_encode($reputations),
                    "items" => json_encode($items),
                    "actions" => json_encode($actions)
                );
                $this->db->insert($this->tableName, $importData);

                echo " <b>Importiert</b>";

            }
        }
    }

    /**
     * Counts all previously not declined Migrations
     * @param $accountId
     * @return mixed
     */
    public function getAccountMigrations($accountId){

        $this->db->select('id,server_name,character_name,status,actions')
            ->where("account_id", $accountId)
            ->where_in("status", array(MIGRATION_STATUS_DONE, MIGRATION_STATUS_IN_PROGRESS, MIGRATION_STATUS_OPEN, MIGRATION_STATUS_DECLINED))
            ->from($this->tableName);
        $query = $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->result_array();
            return $result;
        }

        return array();
    }

    /**
     * Get all data of a migration entry
     * @param $migrationId
     * @return array|false
     */
    public function getMigration($migrationId){
        $this->db->select('*')
            ->where('id', $migrationId)
            ->from($this->tableName);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->result_array();
            return $result[0];
        }
        return false;
    }

    public function getRealmMigrationCount($realmId){
        $this->db->select('id')
            ->where('target_realm', $realmId)
            ->order_by('id', 'desc');

        $count = $this->db->count_all($this->tableName);

        return $count;
    }

    /**
     * Get a specific amount of migrations from a specified Realm.
     * @param $realmId
     * @param string $limit
     * @param string $from
     * @return mixed
     */
    public function getRealmMigrations($realmId, $limit = '1000', $from = '0', $sort = 'desc'){
        $this->db->select('id,status,date_created,date_done,account_id,character_name,server_name,actions')
            ->where('target_realm', $realmId)
            ->order_by('id', $sort)
            ->limit($limit, $from)
            ->from($this->tableName);

        $query = $this->db->get();

        return $query;
    }

    public function getEquipmentSlots(){
        return $this->equipmentSlots;
    }

    public function getProfessions(){
        return $this->professions;
    }

    public function getMigrationStates(){
        return $this->migrationStates;
    }

    public function getReputations(){
        return $this->reputations;
    }

    public function getFactionLabel($factionId){
        return (isset($this->factionsAll["$factionId"])) ? $this->factionsAll["$factionId"] : "";
    }

    public function getReputationStates(){
        return $this->reputationStates;
    }

    public function getRidingLevels(){
        return $this->ridingLevels;
    }

    /**
     * Creates a new entry in the database with all data
     * @param $data
     */
    public function createMigrationEntry($realmId, $post){

        $realmObj = $this->realms->getRealm($realmId);

        $worldDb = $realmObj->getWorld();

        /*
         * Skills
         */
        $dataSkills = array(
            "Riding" => $post['Riding'],
            "Cooking" => $post['Cooking'],
            "Angling" => $post['Angling'],
            "Firstaid" => $post['Firstaid'],
            'professions' => array(),
        );

        for($i = 1; $i <= 2; $i++){
            $dataSkills["professions"][$i] = array(
                'skill' => $post['Beruf'.$i],
                'skill_level' => $post['Beruf'.$i.'_skill'],
            );
        }

        /*
         * Items
         */
        $dataEquipment = array();

        $equipmentSlots = $this->getEquipmentSlots();

        foreach($equipmentSlots as $key => $slot){
            $dataEquipment[$key] = $this->input->post('equip-'.$key);
        }

        $dataItems = array(
            "equipment" => $dataEquipment,
            "random" => $post["random_item"],
            "mounts" => array(
                "fly" => $post['Mount_flug'],
                "floor" => $post['Mount_boden'],
            )
        );

        /*
         * Faction reputations
         */
        $dataReputations = $post['faction'];

        debug($dataReputations);

        // Save Data to Database
        $data = array(
            "date_created" => strftime("%d.%m.%Y %H:%M:%S"),
            "status" => MIGRATION_STATUS_OPEN,
            "account_id" => $this->user->getId(),
            "icq" => $post['icq'],
            "skype" => $post['skype'],
            "character_name" => $post['name'],
            "character_class" => $post['class'],
            "character_race" => $post['race'],
            "server_name" => $post['Server'],
            "server_link" => $post['Link'],
            "character_armory" => $post['Armory'],
            "screenshots_link" => $post['Download'],
            "comment" => $post['Bemerkung'],
            "level" => $post['Level'],
            "gold" => $post['Gold'],
            "skills" => json_encode($dataSkills),
            "reputations" => json_encode($dataReputations),
            "items" => json_encode($dataItems),
            "actions" => ""
        );

        $this->db->insert($this->tableName, $data);

        return $this->db->insert_id();

    }

    public function updateMigrationDetail($migrationId, $status = MIGRATION_STATUS_OPEN, $characterGuid = "", $actions = array()){
        $data = array(
            "character_guid" => $characterGuid,
            "status" => $status,
            "actions" => json_encode($actions),
        );
        $this->db->where('id', $migrationId)
            ->update($this->tableName, $data);
        $this->logger->createLog('Migration Update', 'Id: '.$migrationId.', Status: '.$this->getStateLabel($status).', CharGUID: '.$characterGuid);
    }

    public function getStateLabel($state){
        return $this->migrationStates[$state]['label'];
    }

    public function getProfessionLabel($profId){
        return $this->professions[$profId]['label'];
    }

    public function getProfessionBaseSpell($profId){
        return $this->professions[$profId]['base'];
    }

    public function checkRaceClassCombination($race,$class){


        $map = array(
                //      CLASS_DK    CLASS_DRUID CLASS_HUNTER    CLASS_MAGE  CLASS_PALADIN CLASS_PRIEST  CLASS_ROGUE CLASS_SHAMAN    CLASS_WARLOCK   CLASS_WARRIOR
RACE_ORC      => array( CLASS_DK,               CLASS_HUNTER,                                           CLASS_ROGUE,CLASS_SHAMAN,   CLASS_WARLOCK,  CLASS_WARRIOR   ),
RACE_UNDEAD   => array( CLASS_DK,                               CLASS_MAGE,               CLASS_PRIEST, CLASS_ROGUE,                CLASS_WARLOCK,  CLASS_WARRIOR   ),
RACE_TAUREN   => array( CLASS_DK,   CLASS_DRUID,CLASS_HUNTER,                                                       CLASS_SHAMAN,                   CLASS_WARRIOR   ),
RACE_TROLL    => array( CLASS_DK,               CLASS_HUNTER,   CLASS_MAGE,               CLASS_PRIEST, CLASS_ROGUE,CLASS_SHAMAN,                   CLASS_WARRIOR   ),
RACE_BLOODELF => array( CLASS_DK,               CLASS_HUNTER,   CLASS_MAGE, CLASS_PALADIN,CLASS_PRIEST, CLASS_ROGUE,                CLASS_WARLOCK,                  ),

RACE_HUMAN    => array( CLASS_DK,                               CLASS_MAGE, CLASS_PALADIN,CLASS_PRIEST, CLASS_ROGUE,                CLASS_WARLOCK,  CLASS_WARRIOR   ),
RACE_DWARF    => array( CLASS_DK,               CLASS_HUNTER,               CLASS_PALADIN,CLASS_PRIEST, CLASS_ROGUE,                                CLASS_WARRIOR   ),
RACE_NIGHTELF => array( CLASS_DK,   CLASS_DRUID,CLASS_HUNTER,                             CLASS_PRIEST, CLASS_ROGUE,                                CLASS_WARRIOR   ),
RACE_GNOME    => array( CLASS_DK,                               CLASS_MAGE,                             CLASS_ROGUE,                CLASS_WARLOCK,  CLASS_WARRIOR   ),
RACE_DRAENEI  => array( CLASS_DK,               CLASS_HUNTER,   CLASS_MAGE,               CLASS_PRIEST,             CLASS_SHAMAN,                   CLASS_WARRIOR   ),
        );

        if(isset($map[$race]) && in_array($class, $map[$race])){
            return true;
        }
        return false;

    }


}