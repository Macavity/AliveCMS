<?php

define("MIGRATION_STATUS_OPEN", 1);
define("MIGRATION_STATUS_IN_PROGRESS", 2);
define("MIGRATION_STATUS_DONE", 3);
define("MIGRATION_STATUS_DECLINED", 4);
define("MIGRATION_STATUS_LEGACY", 5);

class Migration_Model extends CI_Model {

    var $tableName = "migration_entries";

    protected $reputationsAlliance = array();
    protected $reputationsHorde = array();
    protected $reputationsBC = array();
    protected $reputationsWotlk = array();
    protected $reputationStates = array();
    protected $ridingLevels = array();
    protected $reputations = array();
    protected $professions = array();
    protected $equipmentSlots = array();

    public function __construct(){
        parent::__construct();

        if(defined("INCLUDED_WOW_CONSTANTS") == FALSE){
            $this->load->config("wow_constants");
        }

        $this->reputationsAlliance = array(
            "72" => "Sturmwind",
            "930" => "Die Exodar",
            "47" => "Eisenschmiede",
            "54" => "Gnomeregangnome",
            "69" => "Darnassus",
        );

        $this->reputationsHorde = array(
            "76" => "Orgrimmar",
            "911" => "Silbermond",
            "68" => "Unterstadt",
            "81" => "Donnerfels",
            "530" => "Dunkelspeertrolle",
        );

        $this->reputationsBC = array(
            "933" => "Das Konsortium",
            "967" => "Das Violette Auge",
            "1012" => "Die Todeshörigen",
            "990" => "Die Wächter der Sande",
            "946" => "Ehrenfeste",
            "942" => "Expedition des Cenarius",
            "989" => "Hüter der Zeit",
            "978" => "Kurenai",
            "1015" => "Netherschwingen",
            "1038" => "Ogrila",
            "970" => "Sporeggar",
            "947" => "Thrallmar",
        );


        $this->reputationsWotlk = array(
            "1106" => "Argentumkreuzung",
            "1094" => "Der Silberbund",
            "1091" => "Der Wyrmruhpakt",
            "1126" => "Die Frosterben",
            "1067" => "Die Hand der Rache",
            "1073" => "Die Kalu'ak",
            "1105" => "Die Orakel",
            "1119" => "Die Söhne Hodir",
            "1124" => "Die Sonnenhäscher",
            "1064" => "Die Taunka",
            "1052" => "Expedion der Horde",
            "1050" => "Expedion Valianz",
            "1068" => "Forscherliga",
            "1090" => "Kirin Tor",
            "1085" => "Kriegshymnenoffensive",
            "1098" => "Ritter der schwarzen Klinge",
            "809" => "Shen'dralar:",
            "1104" => "Stamm der Wildherzen",
            "1037" => "Vorposten der Allianz",
            "1156" => "Das Äscherne Verdikt",
        );

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
                "factions" => $this->reputationsWotlk,
            ),
            "repBC" => array(
                "label" => "Burning Crusade",
                "factions" => $this->reputationsBC,
            ),
            "repA" => array(
                "label" => "Allianzfraktionen",
                "factions" => $this->reputationsAlliance,
            ),
            "repH" => array(
                "label" => "Hordefraktionen",
                "factions" => $this->reputationsHorde,
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

    }

    /**
     * Imports data entries from migration_archive to migration_entries
     * skips over items & reputation
     */
    public function importMigrationArchive(){

        $this->db->select("*")
            ->from("migration_archive");

        $query = $this->db->get();

        if($query->num_rows() > 0){

            foreach ($query->result() as $row){

                echo "<br>Transfer #".$row->id.": ";

                $this->db->select("id")
                    ->where("id", $row->id)
                    ->from($this->tableName);

                $count = $this->db->count_all_results();


                if($count > 0){
                    echo "Skipped";
                    continue;
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
                    $str = trim(str_replace("Erledigt von","", $row->gm));

                    if(strpos($str, " ") > 0){
                        $array = explode(" ", $str, 2);
                        $actions["by"] = $array[0];
                        $actions["reason"] = $array[1];
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
                        $actions["reason"] = trim($matches[2]);
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
                $skills = array(
                    "Reiten" => (substr_count($row->reiten, "Kaltwetter") > 0) ? 301 : $row->reiten,
                    "Beruf1" => $row->beruf1,
                    "Beruf2" => $row->beruf2,
                    "Beruf1_skill" => $row->beruf1skill,
                    "Beruf2_skill" => $row->beruf2skill,
                    "Kochen" => $row->kochen,
                    "Angeln" => $row->angeln,
                    "Erstehilfe" => $row->erstehilfe,
                );

                /*
                 * Reputations (Legacy)
                 */
                $reputations = array(
                    "archive" => true,
                );

                /*
                 * Items (Legacy)
                 */
                $items = array(
                    "archive" => true,
                );

                $actions = array($actions);

                $importData = array(
                    "id" => $row->id,
                    "date_created" => $row->datum,
                    "date_done" => "",
                    "status" => $state,
                    "account_id" => $accountId,
                    "icq" => $row->icq,
                    "character_name" => $row->char,
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

        $this->db->select("id")
            ->where("account_id", $accountId)
            ->where_in("status", array(MIGRATION_STATUS_DONE, MIGRATION_STATUS_IN_PROGRESS, MIGRATION_STATUS_OPEN))
            ->from($this->tableName);
        $count = $this->db->count_all_results();
        return $count;
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
    public function getRealmMigrations($realmId, $limit = "1000", $from = "0"){
        $this->db->select('id,status,date_created,date_done,account_id,character_name,server_name,actions')
            ->where('target_realm', $realmId)
            ->order_by('id', 'desc')
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

    public function getReputations(){
        return $this->reputations;
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
    public function createMigrationEntry($data){

        $realmObj = $this->realms->getRealm($realmId);

        $worldDb = $realmObj->getWorld();

        $tableData = array(
            "date_created" => time(),
            "status" => MIGRATION_STATUS_OPEN,
            "account_id" => $data,

            "icq" => $data["icq"],
            "skype" => $data["skype"],

            "character_name" => $data["name"],
            "character_race" => $data["race"],
            "character_class" => $data["class"],
            "server_name" => $data["Server"],
            "server_link" => $data["Link"],
            "character_armory" => $data["Armory"],
            "screenshots_link" => $data["Download"],
            "comment" => $data["Bemerkung"],
            "level" => $data["Level"],
            "gold" => $data["Gold"],
        );

        $skills = array(
            "Reiten" => (int) $data["Reiten"],
            "Beruf1" => $data["Beruf1"],
            "Beruf2" => $data["Beruf2"],
            "Beruf1_skill" => (int) $data["Beruf1_skill"],
            "Beruf2_skill" => (int) $data["Beruf2_skill"],
            "Kochen" => (int) $data["Kochen"],
            "Angeln" => (int) $data["Angeln"],
            "Erstehilfe" => (int) $data["Erstehilfe"],
        );

        $reputations = array(

        );

        $items = array(
            "mount_boden" => $data["Mount_boden"],
            "mount_flug" => $data["Mount_flug"],
            "randoms" => array(),
            "equipment" => array(),
        );

        // Randoms
        for($i = 1; $i <= 10; $i++){
            if(!empty($data["random_item"][$i])){
                $items["randoms"][] = $data["random_item"][$i];
            }
        }


        $this->db->insert($this->tableName, $data);

    }


}