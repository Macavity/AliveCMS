<?php

require_once('arsenal_item_model.php');

/**
 * Class Arsenal_Character_model
 * @property CI_Config $config
 */
class Arsenal_Character_model extends CI_Model {

    /**
     * Cache variables
     * @access   private
     **/
    var $cacheTimestamp = 0;
    var $cacheRefreshed = false;

    /**
     * Language code
     * @access   private
     **/
    var $locale = "de_de";

    /**
     * Raw field values
     * @access   private
     **/
    var $raw = array();

    /**
     * Player guid
     * @access   private
     **/
    private $guid = false;

    /**
     * Player name
     * @access   private
     **/
    private $name = false;

    /**
     * Player race id
     * @access   private
     **/
    private $race = false;

    /**
     * Player class id
     * @access   private
     **/
    private $class = false;

    /**
     * Player gender
     * (0 - male, 1 - female)
     * @access   private
     **/
    private $gender = false;

    /**
     * Player level
     * @access   private
     **/
    private $level = false;

    private $professions = array();

    /**
     * Player model display info
     * @access   private
     **/
    private $playerBytes = NULL;
    private $playerBytes2 = NULL;
    private $playerFlags = false;

    public $totalKills = 0;

    /**
     * Player title ID
     * @access   private
     **/
    private $chosenTitle = false;
    private $knownTitles = array();

    /**
     * Player health value
     * @access   private
     **/
    private $health = false;

    /**
     * Player powers values
     * @access   private
     **/
    private $power1 = false;
    private $power2 = false;
    private $power3 = false;

    /**
     * Account ID
     * @access   private
     **/
    private $account = false;

    /**
     * Talent specs count
     * @access   private
     **/
    private $specCount = false;

    /**
     * Active talent spec ID
     * (0 or 1)
     * @access   private
     **/
    private $activeSpec = 0;

    /**
     * Talent Data
     * @access   private
     **/
    private $talentData = 0;

    /**
     * Berufe und andere Daten für den WebService
     * @var Array
     */
    private $fullData = array();

    /**
     * Player faction
     * (1 - Horde, 0 - Alliance)
     * @access   private
     **/
    private $faction = false;

    /**
     * Array with player stats constants
     * (depends on character level)
     * @access   private
     **/
    private $rating = false;

    /**
     * Player title data
     * (prefix, suffix, titleId)
     * @access   private
     **/
    private $character_title = array('prefix' => null, 'suffix' => null, 'titleId' => null);

    /**
     * Player guild ID
     * @access   private
     **/
    private $guildId = false;

    /**
     * Player guild name
     * @access   private
     **/
    private $guildName = false;

    /**
     * Player guild rank ID
     * @access   private
     **/
    private $guild_rank_id = false;

    /**
     * Player guild rank name
     * @access   private
     **/
    private $guild_rank_name = false;

    /**
     * $this->class text
     * @access   private
     **/
    private $classText = false;

    /**
     * $this->race text
     * @access   private
     **/
    private $raceText = false;

    /**
     * Equipped item IDs
     * @access   private
     **/
    private $equipmentCache = false;

    /**
     * Sum of all achievement points
     * @access   private
     **/
    private $achievementPoints = 0;

    /**
     * Avarage equipped item level
     * @access   private
     **/
    private $itemLevelEquipped = 0;

    /**
     * Averave item level record
     * @access   private
     **/
    private $itemLevel = 0;

    /**
     * Database handler for the Portal DB
     * @var CI_DB_active_record
     * @access   private
     **/
    private $portalDb = null;

    /**
     * Database Handler for the Characters DB
     * @var CI_DB_active_record
     * @access   private
     */
    private $charDb = null;

    /**
     * Realm Object
     * @var Realm
     */
    private $realm;
    
    /**
     * Character realm name
     * @access   private
     **/
    private $realmName = "Norgannon";

    /**
     * Character realm ID
     * @access   private
     **/
    private $realmID = 1;

    /**
     * Achievement MGR
     * @access   private
     **/
    private $m_achievementMgr = null;

    /**
     * Equipped items storage
     * @access   private
     **/
    private $m_items;

    /**
     * Character feed data
     **/
    private $feed_data = array();

    private $mode = "simple";

    private $error = array();

    private $achievements = array();
    private $equipment = array();

    public function initialize($guid, $realmObject)
    {
        $this->guid = (int) $guid;

        $this->realm = $realmObject;
    }

    /**
     * Connect to the database if not already connected
     */
    public function connect()
    {
        if(empty($this->portalDb))
        {
            $this->portalDb = &get_instance()->load->database($this->config['cms'], true);
        }

        if(empty($this->charDb))
        {
            $this->charDb = &get_instance()->load->database($this->config['characters'], true);
        }
    }

    public function loadBaseData($detail)
    {
        $this->connect();
        
        $availableDetails = array("small","simple", "advanced");
        
        if(!in_array($detail, $availableDetails))
        {
            $detail = "simple";
        }

        if($detail == "simple" || $detail == "advanced"){
            $this->charDb
                ->select(
                    'name',
                    'race',
                    'class',
                    'gender',
                    'level',
                    'health',
                    'power1','power2','power3',
                    'totalKills');
        }
        else{
            $this->charDb
                ->select(
                    'name',
                    'race',
                    'class',
                    'gender',
                    'level');

        }
        $this->charDb
            ->from('characters')
            ->where('guid', $this->guid);

        $query = $this->charDb->get();

        if($query->num_rows() > 0)
        {
            $row = $query->row_array();

            foreach($row as $key => $value)
            {
                $this->$key = $value;
            }
        }
        else
        {
            $this->setError('character_not_found', __LINE__);
            return false;
        }

        if($detail == "simple" || $detail == "advanced")
        {
            $this->loadGuildData();

            // Achievements
            $this->calcAchievementPoints();

            // Average item level
            $this->calcItemLevel();

            if($cacheRow){
                $values["itemLevel"] = max($values["itemLevelEquipped"],$cacheRow["itemLevel"]);

                $DataDB->query("UPDATE character_cache SET ?a WHERE guid = ?d AND type = ?d;", $values, $this->guid, CACHETYPE_TT_CHARACTER);
            }
            else{
                $values["itemLevel"] = $values["itemLevelEquipped"];
                $result = $DataDB->query("INSERT INTO character_cache (?#) VALUES (?a);", array_keys($values), array_values($values));
            }
        }

    }

    /**
     * @param $errorType
     * @param $line
     * @internal param array $error
     */
    public function setError($errorType, $line)
    {
        $this->error = array(
            'type' => $errorType,
            'line' => $line,
        );
    }

    /**
     * @return array
     */
    public function getError()
    {
        if(empty($this->error))
        {
            return false;
        }
        else
        {
            return $this->error;
        }
    }

    private function loadTalents()
    {
        $this->talentData = $this->CalculateCharacterTalents();

    }

    /**
     * @return array
     */
    private function loadAchievements()
    {
        if(!empty($this->achievements))
        {
            return $this->achievements;
        }

        $this->connect();

        $this->charDb->select('*')
            ->where('guid', $this->guid)
            ->from('character_achievement');

        $query = $this->charDb->get();

        if($query->num_rows())
        {
            foreach($query->result_array as $row)
            {
                $this->achievements[$row['achievement']] = $row['date'];
            }
            return $this->achievements;
        }
        return array();

    }

    private function calcAchievementPoints()
    {

        $this->loadAchievements();

        $achievementIds = array_keys($this->achievements);

        $this->portalDb->select('SUM(aa.points) as sum')
            ->where_in('id', $achievementIds)
            ->from('arsenal_achievement');

        $query = $this->portalDb->get();

        if($query->num_rows())
        {
            $row = $query->row_array();
            $this->achievementPoints = $row['sum'];
        }
        return $this->achievementPoints;
    }

    private function loadEquipment()
    {
        if(!empty($this->equipment))
        {
            return;
        }

        $this->connect();

        $this->charDb->select('item, slot')
            ->where('bag', 0)
            ->where('slot', '<18')
            ->where('slot', '<> 3')
            ->where('guid', $this->guid)
            ->from('character_inventory');

        $query = $this->charDb->get();

        $equipment = array();

        if($query->num_rows())
        {
            $inventoryItems = $query->result_array();

            $inventoryItemIds = array();

            foreach($inventoryItems as $inventoryRow)
            {
                $equipment[$inventoryRow['item']] = array(
                    'slot' => $inventoryRow['slot'],
                );
                $inventoryItemIds[] = $inventoryRow['item'];
            }

            $this->charDb->select('guid, itemEntry')
                ->where_in('guid', $inventoryItemIds)
                ->from('item_instance');

            $query = $this->charDb->get();

            if($query->num_rows())
            {
                $itemEntries = $query->result_array();

                $world = $this->realm->getWorld();

                foreach($itemEntries as $row)
                {
                    $item = $world->getItem($row['itemEntry']);

                    if($item)
                    {
                        $equipment[$row['guid']]['item'] = new Arsenal_Item_model($this->realm, $item);
                    }
                }
            }

        }

        $this->equipment = $equipment;
    }

    private function calcItemLevel()
    {
        $this->loadEquipment();

        $itemCount = 0;
        $itemLevelSum = 0;

        foreach($this->equipment as $itemGuid => $itemRow)
        {
            $itemCount++;

            /** @var Arsenal_Item_model $item */
            $item = $itemRow['item'];

            $itemLevelSum += $item->getItemLevel();
        }

        $this->itemLevelEquipped = round(($itemLevelSum / $itemCount),1);

        return $this->itemLevelEquipped;
    }

    /**
     *
     * Lädt Daten aus dem Cache und frischt diese bei Bedarf auf
     * @param Boolean $forceRefresh Erzwingt einen Refresh der Daten
     * @param Boolean $fullData Wenn true werden auch Berufe und
     */
    public function GetCache($forceRefresh = FALSE, $fullData = FALSE){
        global $DataDB, $CHDB;

        $cacheRow = $DataDB->selectRow('SELECT * FROM character_cache WHERE guid = ?d AND type = ?;',$this->guid, CACHETYPE_TT_CHARACTER);

        $refreshCache = TRUE;
        if($cacheRow){

            $cachingPeriod = 60 * 60 * 24;

            if((time() - $cacheRow["time"]) < $cachingPeriod)
                $refreshCache = FALSE;

            if(empty($cacheRow["talentData"]))
                $refreshCache = TRUE;

            if($fullData == TRUE && empty($cacheRow["fullData"])){
                $refreshCache = TRUE;
            }

            $this->achievementPoints = $cacheRow["achievementPoints"];
            $this->talentIcon = $cacheRow["icon"];
            $talents = explode("/", $cacheRow["talents"]);
            $this->treeOne = $talents[0];
            $this->treeTwo = $talents[1];
            $this->treeThree = $talents[2];
        }



        if($refreshCache || $forceRefresh){
            //debug("character.GetCache() -> Fresh");

            $values = array(
                "guid" => $this->guid,
                "name" => ($this->name),
                "guild_name" => ($this->guildName),
                "class" => $this->class,
                "level" => $this->level,
                "race" => $this->race,
                "gender" => $this->gender,
                "activeSpec" => $this->activeSpec,
                "time" => time(),
                "type" => CACHETYPE_TT_CHARACTER,
            );

            // Talents
            $this->talentData = $this->CalculateCharacterTalents();

            if(!is_array($this->talentData)){
                $this->talentData = array(0 => array());
            }
            $values["talentData"] = json_encode($this->talentData);

            if($fullData == TRUE){
                $this->fullData = $values;

                $this->fullData["professions"] = $this->GetCharacterProfessions();

                foreach($this->fullData["professions"] as $key => $array){
                    $this->fullData["professions"][$key]["name"] = (getProfessionLabel($array["id"]));
                }

                $this->fullData["talentData"] = $this->talentData;

                $this->fullData["name"] = ($this->name);
                $this->fullData["guild_name"] = ($this->guildName);

                $this->fullData["classLabel"] = getClassLabel($this->class, $this->gender);
                $this->fullData["raceLabel"] = getRaceLabel($this->race, $this->gender);

                $values["fullData"] = $this->fullData;

            }

            // Achievements
            $achPoints = $CHDB->selectCell("
				SELECT SUM(aa.points)
					FROM live_char.character_achievement ca JOIN arsenal.armory_achievement aa ON(aa.id = ca.achievement)
					WHERE ca.guid= ?d", $this->guid);
            if($achPoints)
                $values["achievementPoints"] = $achPoints;

            // Average item level
            $itemlevel = $CHDB->select("
				SELECT COUNT(it.entry) AS `count`, SUM(it.itemlevel) as `sum`
					FROM live_char.item_instance ii
						JOIN live_char.character_inventory bag ON (bag.item = ii.guid)
						JOIN live_world.item_template it ON(ii.itemEntry = it.entry)
					WHERE ii.owner_guid = ?d AND bag.bag=0 AND bag.slot < 18 AND bag.slot <> 3;", $this->guid);
            if($itemlevel){
                foreach($itemlevel as $ilRow){
                    if($ilRow["sum"] > 0)
                        $values["itemLevelEquipped"] = round($ilRow["sum"] / $ilRow["count"],1);
                }
            }

            if($cacheRow){
                $values["itemLevel"] = max($values["itemLevelEquipped"],$cacheRow["itemLevel"]);

                $DataDB->query("UPDATE character_cache SET ?a WHERE guid = ?d AND type = ?d;", $values, $this->guid, CACHETYPE_TT_CHARACTER);
            }
            else{
                $values["itemLevel"] = $values["itemLevelEquipped"];
                $result = $DataDB->query("INSERT INTO character_cache (?#) VALUES (?a);", array_keys($values), array_values($values));
            }
            $this->cacheTimestamp = time();
            $this->cacheRefreshed = true;
            $this->itemLevel = $values["itemLevel"];
            $this->itemLevelEquipped = $values["itemLevelEquipped"];
            $this->achievementPoints = $values["achievementPoints"];
        }
        else{
            //debug("character.GetCache() -> Cached");
            $this->cacheTimestamp = $cacheRow["time"];

            $this->itemLevel = $cacheRow["itemLevel"];
            $this->itemLevelEquipped = $cacheRow["itemLevelEquipped"];
            $this->achievementPoints = $cacheRow["achievementPoints"];
            $this->achievementPoints = $cacheRow["achievementPoints"];
            $this->talentData = json_decode($cacheRow["talentData"], TRUE);
            $this->fullData = json_decode($cacheRow["fullData"], TRUE);
            //debug($this->talentData);

        }
    }

    function GetCachedDate(){
        return strftime("%d.%m.%Y", $this->cacheTimestamp);
    }

    function GetCharacterAvatar($data = NULL){

        if(!is_object($data))
            $data = $this;

        if($data->level >= 80)
            return "http://arsenal.wow-alive.de/images/portraits/wow-80/".$data->gender."-".$data->race."-".$data->class.".gif";
        elseif($data->level >= 70)
            return "http://arsenal.wow-alive.de/images/portraits/wow-70/".$data->gender."-".$data->race."-".$data->class.".gif";
        elseif($data->level >= 60)
            return "http://arsenal.wow-alive.de/images/portraits/wow/".$data->gender."-".$data->race."-".$data->class.".gif";
        else
            return "http://arsenal.wow-alive.de/images/portraits/wow-default/".$data->gender."-".$data->race."-".$data->class.".gif";

    }

    function IsCharacterHidden(){
        global $DB;
        $gmLevel = $DB->selectCell("SELECT `gmlevel` FROM `account_access` WHERE `id`= ?d AND `RealmID` = ?d", $this->account, $this->realmID);
        if($gmLevel && $gmLevel > 0)
            return true;
        return false;
    }

    function IsEnchanter(){
        if(in_array(333, array_keys($this->professions)))
            return true;
        else
            return false;
    }

    /**
     * Converts $this->equipmentCache from string to array
     * @access   private
     * @return   bool
     **/
    private function HandleEquipmentCacheData() {
        if(!$this->equipmentCache) {
            self::debug("No Equipment Cache");
            return false;
        }
        $itemscache = explode(' ', $this->equipmentCache);
        if(!$itemscache) {
            self::debug("unable to convert {$this->equipmentCache} from string to array (function.explode). Character items would not be shown.");
            return false;
        }
        $this->equipmentCache = $itemscache;
        $cacheCount = count($this->equipmentCache);
        if($cacheCount < 37) {
            for($i = $cacheCount; $i < 38; $i++) {
                $this->equipmentCache[$i] = null;
            }
        }
        return true;
    }

    /**
     * Constructs character title info
     * @access   private
     * @return   bool
     **/
    private function HandleChosenTitleInfo() {
        global $aDB;
        $title_data = $aDB->selectRow("SELECT ?# AS `titleF`, ?# AS `titleM`, `place` FROM `armory_titles` WHERE `id`=?d", "title_F_".$this->locale, "title_M_".$this->locale, $this->chosenTitle);
        if(!$title_data) {
            self::debug($aDB);
            self::debug('player has wrong chosenTitle id');
            return false;
        }
        switch($this->gender) {
            case 0:
                if($title_data['place'] == 'prefix') {
                    $this->character_title['prefix'] = $title_data['titleM'];
                }
                elseif($title_data['place'] == 'suffix') {
                    $this->character_title['suffix'] = $title_data['titleM'];
                }
                break;
            case 1:
                if($title_data['place'] == 'prefix') {
                    $this->character_title['prefix'] = $title_data['titleF'];
                }
                elseif($title_data['place'] == 'suffix') {
                    $this->character_title['suffix'] = $title_data['titleF'];
                }
                break;
        }
        $this->character_title['titleId'] = $this->chosenTitle;
        return true;
    }

    function getAchievementPoints()
    {
        return $this->achievementPoints;
    }

    function GetTitle(){
        if(isset($this->character_title['prefix']))
            return $this->character_title['prefix'];
        if(isset($this->character_title['suffix']))
            return $this->character_title['suffix'];
    }

    function GetCharacterLink(){
        return "/character/".strtolower($this->realmName)."/".urlencode($this->raw["name"]);
    }

    function GetRaw($field){
        if(isset($this->raw[$field]))
            return trim($this->raw[$field]);
        return false;
    }

    function GetGuildLink(){
        return "/guild/".strtolower($this->realmName)."/".urlencode($this->raw["guild_name"])."/?character=".urlencode($this->raw["name"]);
    }

    function GetCssClass(){
        return "color-c".$this->raw["class"];
    }

    function GetCssFaction(){
        return ($this->faction == FACTION_ALLIANCE) ? "alliance" : "horde";
    }

    /**
     * Checks current player (loaded or not).
     * @access   public
     * @return   bool
     **/
    public function CheckPlayer() {
        if(!$this->guid || !$this->name) {
            return false;
        }
        return true;
    }

    /**
     * Returns player GUID
     * @access   public
     * @return   int
     **/
    public function GetGUID() {
        return $this->guid;
    }

    /**
     * Returns player name
     * @access   public
     * @return   string
     **/
    public function GetName() {
        return $this->name;
    }

    /**
     * Returns player class
     * @access   public
     * @return   int
     **/
    public function GetClass() {
        return $this->class;
    }

    /**
     * Returns player race
     * @access   public
     * @return   int
     **/
    public function GetRace() {
        return $this->race;
    }

    /**
     * Returns talent data
     * @access   public
     * @return   Array
     **/
    public function GetTalentData() {
        return $this->talentData;
    }

    /**
     * Returns full data
     * @access   public
     * @return   Array
     **/
    public function GetFullData() {
        return $this->fullData;
    }

    /**
     * Returns player level
     * @access   public
     * @return   int
     **/
    public function GetLevel() {
        return $this->level;
    }

    /**
     * Returns player gender
     * @access   public
     * @return   int
     **/
    public function GetGender() {
        return $this->gender;
    }

    /**
     * Returns player faction
     * @access   public
     * @return   int
     **/
    public function GetFaction() {
        return $this->faction;
    }

    /**
     * Returns player account ID
     * @access   public
     * @return   int
     **/
    public function GetAccountID() {
        return $this->account;
    }

    /**
     * Returns active talent spec ID
     * @access   public
     * @return   int
     **/
    public function GetActiveSpec() {
        return $this->activeSpec;
    }

    /**
     * Returns the Hair Style
     * @return int
     */
    public function getHairStyle(){

        if($this->playerBytes == NULL)
            return 0;

        return ($this->playerBytes >> 16) % 256;
    }

    /**
     * Returns the Hair Color
     * @return int
     */
    public function getHairColor(){

        if($this->playerBytes == NULL)
            return 0;

        return ($this->playerBytes >> 24) % 256;
    }

    /**
     * Returns the Skin Style
     * @return int
     */
    public function getSkinColor(){

        if($this->playerBytes == NULL)
            return 0;

        return ($this->playerBytes) % 256;
    }

    /**
     * Returns the Face Color
     * @return int
     */
    public function getFaceStyle(){

        if($this->playerBytes == NULL)
            return 0;

        return ($this->playerBytes >> 8) % 256;
    }

    /**
     * Returns the Facial Hair
     * @return int
     */
    public function getFacialHair(){

        if($this->playerBytes2 == NULL)
            return 0;

        return $this->playerBytes2 % 256;
    }

    /**
     * Returns true/false to check if the Helm should be visible
     * @return int
     */
    public function visibleHelm(){
        if($this->playerFlags == NULL)
            return true;

        if($this->playerFlags & 0x00000400)
            return false;
        return true;
    }

    /**
     * Returns true/false to check if the Cloak should be visible
     * @return int
     */
    public function visibleCloak(){
        if($this->playerFlags == NULL)
            return true;

        if($this->playerFlags & 0x00000800)
            return false;
        return true;
    }

    /**
     * Returns talent specs count
     * @access   public
     * @return   int
     **/
    public function GetSpecCount() {
        return $this->specCount;
    }

    /**
     * Returns array with player model info
     * @access   public
     * @return   array
     **/
    public function GetPlayerBytes() {
        return array('playerBytes' => $this->playerBytes, 'playerBytes2' => $this->playerBytes2, 'playerFlags' => $this->playerFlags);
    }

    /**
     * Returns player guild name
     * @access   public
     * @return   string
     **/
    public function GetGuildName() {
        return $this->guildName;
    }

    /**
     * Sucht ob dieser Charakter Mitglied einer Gilde ist.
     * @access private
     */
    private function loadGuildData()
    {

        $this->connect();

        $this->charDb->query('
			SELECT
				`guild_member`.`guildid` AS `guild_id`,
				`guild`.`name` AS `guild_name`
			FROM `guild_member`
				LEFT JOIN `guild` ON (`guild`.`guildid`=`guild_member`.`guildid`)
            WHERE `guild_member`.guid = '.$this->guid.';');

        $query = $this->charDb->get();

        if($query->num_rows()){

            $row = $query->row_array();

            $this->guildId = $row['guild_id'];
            $this->guildName = $row['guild_name'];

//            $this->raw["guild_id"] = $row["guild_id"];
//            $this->raw["guild_name"] = $row["guild_name"];

            return true;
        }
        return false;
    }

    /**
     * Returns player guild ID
     * @access   public
     * @return   int
     **/
    public function getGuildId() {
        return $this->guildId;
    }

    /**
     * Returns array with chosen title info
     * @access   public
     * @return   array
     **/
    public function GetChosenTitleInfo() {
        return $this->character_title;
    }

    /**
     * Returns text string for $this->class ID
     * @access   public
     * @return   string
     **/
    public function GetClassText() {
        return $this->classText;
    }

    /**
     * Returns text string for $this->race ID
     * @access   public
     * @return   string
     **/
    public function GetRaceText() {
        return $this->raceText;
    }

    /**
     * Returns a comma seperated list of all equipment slots
     * @return {String}
     */
    public function getEquipmentListString($equipment = array()){
        $wowhead_slots = array(
            INV_HEAD => INVTYPE_HEAD,
            INV_SHOULDER => INVTYPE_SHOULDERS,
            INV_BACK => INVTYPE_CLOAK,
            INV_CHEST => INVTYPE_CHEST,
            INV_SHIRT => INVTYPE_BODY,
            INV_TABARD => INVTYPE_TABARD,
            INV_BRACERS => INVTYPE_WRISTS,

            INV_GLOVES => INVTYPE_HANDS,
            INV_BELT => INVTYPE_WAIST,
            INV_LEGS => INVTYPE_LEGS,
            INV_BOOTS => INVTYPE_FEET,

            INV_MAIN_HAND => INVTYPE_WEAPONMAINHAND,
            INV_OFF_HAND => INVTYPE_HOLDABLE,
            // INVTYPE_SHIELD, INVTYPE_HOLDABLE, INVTYPE_2HWEAPON
        );
        $model_equip = array();

        $hideCloak = ($this->visibleCloak() == false);
        $hideHelmet = ($this->visibleHelm() == false);

        foreach($equipment as $slot => $item_info) {
            if(isset($item_info["displayInfoId"]) && isset($wowhead_slots[$slot])){
                $wSlot = $wowhead_slots[$slot];
                if($slot == INV_HEAD && $hideHelmet)
                    continue;
                elseif($slot == INV_BACK && $hideCloak)
                    continue;
                elseif($slot == INV_OFF_HAND && ($this->GetClass() == CLASS_PALADIN || $this->GetClass() == CLASS_SHAMAN))
                    $wSlot = INVTYPE_SHIELD;
                elseif($slot == INV_OFF_HAND && ($this->GetClass() == CLASS_WARRIOR || $this->GetClass() == CLASS_ROGUE) )
                    $wSlot = INVTYPE_WEAPONOFFHAND;
                // Gildenwappenrock ausblenden
                elseif($slot == 'tabard' && $item_info['displayInfoId'] == 20621)
                    continue;


                $model_equip[] = $wSlot.",".$item_info['displayInfoId'];
            }
        }

        return implode(",", $model_equip);
    }

    /**
     * Returns model data based on race and gender
     * @requires /core/data/data.races.php
     */
    public function getCharacterModel(){
        global $data_races;

        $race_model_data = $data_races[$this->GetRace()];

        return $race_model_data['modeldata_1'].(($this->GetGender() == 1) ? "female" : "male" );
    }

    /**
     * Returns character URL string (r=realmName&cn=CharName&gn=GuildName)
     * @access   public
     * @return   string
     **/
    public function GetUrlString() {
        $url = sprintf('r=%s&cn=%s', urlencode($this->realmName), urlencode($this->name));
        if($this->guildId > 0) {
            $url .= sprintf('&gn=%s', urlencode($this->guildName));
        }
        return $url;
    }

    public function GetUrl($data = NULL){

        if(!is_object($data)){
            $data = $this;
        }

        return "/character/".$data->realmName."/".urlencode($data->name);
    }

    public function GetItemLevelEquipped(){
        return $this->itemLevelEquipped;
    }

    public function GetItemLevel(){
        return $this->itemLevel;
    }

    /**
     * Returns character realm name
     * @access   public
     * @return   string
     **/
    public function GetRealmName() {
        return $this->realmName;
    }

    /**
     * Returns character realm ID
     * @access   public
     * @return   int
     **/
    public function GetRealmID() {
        return $this->realmID;
    }

    public function GetTotalKills(){
        return $this->totalKills;
    }

    /**
     * Returns server type
     * @access   public
     * @return   int
     **/
    public function GetServerType() {
        return $this->m_server;
    }

    /**
     * Returns money amount
     * @access   public
     * @return   int
     **/
    public function GetMoney() {
        return $this->money;
    }

    public function GetAchievementMgr() {
        global $CHDB;

        if(!is_object($this->m_achievementMgr)) {
            $this->m_achievementMgr = new Achievements();
            $this->m_achievementMgr->InitAchievements($this->GetGUID(), $CHDB, true);
        }
        return $this->m_achievementMgr;
    }

    /**
     * Generates character header (for XML output)
     * @access   public
     * @return   array
     **/
    public function GetHeader() {
        $header = array(
            'battleGroup'  => Armory::$armoryconfig['defaultBGName'],
            'charUrl'      => $this->GetUrlString(),
            'class'        => $this->classText,
            'classId'      => $this->class,
            'classUrl'     => sprintf("c=%s", urlencode($this->classText)),
            'faction'      => null,
            'factionId'    => $this->faction,
            'gender'       => null,
            'genderId'     => $this->gender,
            'guildName'    => ($this->guildId > 0) ? $this->guildName : null,
            'guildUrl'     => ($this->guildId > 0) ? sprintf('r=%s&gn=%s', urlencode($this->GetRealmName()), urlencode($this->guildName)) : null,
            'lastModified' => null,
            'level'        => $this->level,
            'name'         => $this->name,
            'points'       => $this->GetAchievementMgr()->GetAchievementPoints(),
            'prefix'       => $this->character_title['prefix'],
            'race'         => $this->raceText,
            'raceId'       => $this->race,
            'realm'        => $this->GetRealmName(),
            'suffix'       => $this->character_title['suffix'],
            'titleId'      => $this->character_title['titleId'],
        );
        if(Utils::IsWriteRaw()) {
            $header['guildUrl'] = ($this->guildId > 0) ? sprintf('r=%s&amp;gn=%s', urlencode($this->GetRealmName()), urlencode($this->guildName)) : null;
        }
        return $header;
    }

    /**
     * Returns array with additional energy bar data (mana for paladins, mages, warlocks & hunters, etc.)
     * @access   public
     * @return   array
     **/
    public function GetSecondBar() {
        if(!$this->class) {
            return false;
        }
        $mana   = 'm';
        $rage   = 'r';
        $energy = 'e';
        $runic  = 'p';

        $switch = array(
            '1' => $rage,
            '2' => $mana,
            '3' => $mana,
            '4' => $energy,
            '5' => $mana,
            '6' => $runic,
            '7' => $mana,
            '8' => $mana,
            '9' => $mana,
            '11'=> $mana
        );
        switch($this->class) {
            case 2:
            case 3:
            case 5:
            case 7:
            case 8:
            case 9:
            case 11:
                $data['casting']    = 0;
                $data['notCasting'] = '22';
                $data['effective']  = $this->GetMaxMana();
                $data['type']       = $switch[$this->class];
                break;
            case 1:
                $data['casting']    = '-1';
                $data['effective']  = $this->GetMaxRage();
                $data['notCasting'] = '-1';
                $data['perFive']    = '-1';
                $data['type']       = $switch[$this->class];
                break;
            case 4:
                $data['casting']   = '-1';
                $data['effective'] = $this->GetMaxEnergy();
                $data['type']      = $switch[$this->class];
                break;
            case 6:
                $data['casting']   = '-1';
                $data['effective'] = $this->GetMaxEnergy();
                $data['type']      = $switch[$this->class];
                break;
        }
        return $data;
    }

    public function GetCharacterEquipStyle($slot){
        switch($slot){
            case 0:
                return " left: 0px; top: 0px;";
            case 1:
                return " left: 0px; top: 58px;";
            case 2:
                return " left: 0px; top: 116px;";
            case 14:
                return " left: 0px; top: 174px;";
            case 4:
                return " left: 0px; top: 232px;";
            case 3:
                return " left: 0px; top: 290px;";
            case 18:
                return " left: 0px; top: 348px;";
            case 8:
                return " left: 0px; top: 406px;";
            case 9:
                return " top: 0px; right: 0px;";
            case 5:
                return " top: 58px; right: 0px;";
            case 6:
                return " top: 116px; right: 0px;";
            case 7:
                return " top: 174px; right: 0px;";
            case 10:
                return " top: 232px; right: 0px;";
            case 11:
                return " top: 290px; right: 0px;";
            case 12:
                return " top: 348px; right: 0px;";
            case 13:
                return " top: 406px; right: 0px;";
            case 15:
                return ($this->mode == "simple") ? " left: 241px; bottom: 0px;" : " left: -6px; bottom: 0px;";
            case 16:
                return ($this->mode == "simple") ? " left: 306px; bottom: 0px;" : " left: 271px; bottom: 0px;";
            case 17:
                return ($this->mode == "simple") ? " left: 371px; bottom: 0px;" : " left: 548px; bottom: 0px;";
        }
    }

    /**
     * Returns item ID from $slot (head, neck, shoulder, etc.). Requires $this->guid!
     * @category Character class
     * @param    string $slot
     * @return   int
     **/
    public function GetCharacterEquip($slot) {
        if(!$this->guid) {
            //Armory::Log()->writeError('%s : player guid not defined', __METHOD__);
            return 0;
        }
        if(!is_array($this->equipmentCache)) {
            //Armory::Log()->writeError('%s : equipmentCache must have array type!', __METHOD__);
            return 0;
        }

        switch($slot) {
            case INV_HEAD:
                return $this->equipmentCache[0];
                break;
            case INV_NECK:
                return $this->equipmentCache[2];
                break;
            case INV_SHOULDER:
                return $this->equipmentCache[4];
                break;
            case INV_SHIRT:
                return $this->equipmentCache[6];
                break;
            case INV_CHEST:
                return $this->equipmentCache[8];
                break;
            case INV_BRACERS:
                return $this->equipmentCache[16];
                break;
            case INV_LEGS:
                return $this->equipmentCache[12];
                break;
            case INV_BOOTS:
                return $this->equipmentCache[14];
                break;
            case INV_BELT:
                return $this->equipmentCache[10];
                break;
            case INV_GLOVES:
                return $this->equipmentCache[18];
                break;
            case INV_RING_1:
                return $this->equipmentCache[20];
                break;
            case INV_RING_2:
                return $this->equipmentCache[22];
                break;
            case INV_TRINKET_1:
                return $this->equipmentCache[24];
                break;
            case INV_TRINKET_2:
                return $this->equipmentCache[26];
                break;
            case INV_BACK:
                return $this->equipmentCache[28];
                break;
            case INV_MAIN_HAND:
                return $this->equipmentCache[30];
                break;
            case INV_OFF_HAND:
                return $this->equipmentCache[32];
                break;
            case INV_RANGED_RELIC:
                return $this->equipmentCache[34];
                break;
            case INV_TABARD:
                return $this->equipmentCache[36];
                break;
            default:
                //Armory::Log()->writeError('%s : wrong item slot: %s', __METHOD__, $slot);
                return 0;
                break;
        }
    }

    /**
     * Returns enchantment id of item contained in $slot slot. If $guid not provided, function will use $this->guid.
     * @category Character class
     * @access   public
     * @param    string $slot
     * @return   int
     **/
    public function GetCharacterEnchant($slot) {
        if(!is_array($this->equipmentCache)) {
            //Armory::Log()->writeError('%s : equipmentCache must have array type!', __METHOD__);
            return 0;
        }
        switch($slot) {
            case INV_HEAD:
                return $this->equipmentCache[1];
                break;
            case INV_NECK:
                return $this->equipmentCache[3];
                break;
            case INV_SHOULDER:
                return $this->equipmentCache[5];
                break;
            case INV_SHIRT:
                return $this->equipmentCache[7];
                break;
            case INV_CHEST:
                return $this->equipmentCache[9];
                break;
            case INV_BRACERS:
                return $this->equipmentCache[17];
                break;
            case INV_LEGS:
                return $this->equipmentCache[13];
                break;
            case INV_BOOTS:
                return $this->equipmentCache[15];
                break;
            case INV_BELT:
                return $this->equipmentCache[11];
                break;
            case INV_GLOVES:
                return $this->equipmentCache[19];
                break;
            case INV_RING_1:
                return $this->equipmentCache[21];
                break;
            case INV_RING_2:
                return $this->equipmentCache[23];
                break;
            case INV_TRINKET_1:
                return $this->equipmentCache[25];
                break;
            case INV_TRINKET_2:
                return $this->equipmentCache[27];
                break;
            case INV_BACK:
                return $this->equipmentCache[29];
                break;
            case INV_MAIN_HAND:
                return $this->equipmentCache[31];
                break;
            case INV_OFF_HAND:
                return $this->equipmentCache[33];
                break;
            case INV_RANGED_RELIC:
                return $this->equipmentCache[35];
                break;
            case INV_TABARD:
                return $this->equipmentCache[37];
                break;
            default:
                //Armory::Log()->writeLog('%s : wrong item slot: %s', __METHOD__, $slot);
                return 0;
                break;
        }
    }

    /**
     * Returns array with TalentTab IDs for current classID ($this->class)
     * @access   public
     * @param    int $tab_count = -1
     * @return   array
     **/
    public function GetTalentTab($tab_count = -1) {
        if(!$this->class) {
            //Armory::Log()->writeError('%s : player class not defined', __METHOD__);
            return false;
        }
        $talentTabId = array(
            1  => array(161, 164, 163), // Warior
            2  => array(382, 383, 381), // Paladin
            3  => array(361, 363, 362), // Hunter
            4  => array(182, 181, 183), // Rogue
            5  => array(201, 202, 203), // Priest
            6  => array(398, 399, 400), // Death Knight
            7  => array(261, 263, 262), // Shaman
            8  => array( 81,  41,  61), // Mage
            9  => array(302, 303, 301), // Warlock
            11 => array(283, 281, 282), // Druid
        );
        if(!isset($talentTabId[$this->class])) {
            return false;
        }
        $tab_class = $talentTabId[$this->class];
        if($tab_count >= 0) {
            $values = array_values($tab_class);
            return $values[$tab_count];
        }
        return $tab_class;
    }

    public function GetTalentIcon($size = 18){
        if(!isset($this->talentIcon)){
            self::debug("no talent Icon");
            $this->GetCache(true);	// Force Refresh
        }
        GetItemIcon($this->talentIcon, $size);
        return "/images/icons/".$size."/".$this->talentIcon.".jpg";
    }

    public function GetTalentDataString(){
        return $this->treeOne."<ins>/</ins>".$this->treeTwo."<ins>/</ins>".$this->treeThree;
    }

    /**
     * Returns what role a specified spec has
     * @access   public
     * @param    int $tab_count = -1
     * @return   array
     **/
    public function GetTalentSpecRole($spec) {
        if(!$this->class) {
            //Armory::Log()->writeError('%s : player class not defined', __METHOD__);
            return false;
        }

        self::debug("Spec",$spec);

        $talentTabId = array(
            1  => array("dps", "dps", "tank"), // Warior
            2  => array("healer", "tank", "dps"), // Paladin
            3  => array("dps", "dps", "dps"), // Hunter
            4  => array("dps", "dps", "dps"), // Rogue
            5  => array("healer", "healer", "dps"), // Priest
            6  => array("tank-dps", "tank-dps", "tank-dps"), // Death Knight
            7  => array("dps", "dps", "healer"), // Shaman
            8  => array( "dps",  "dps",  "dps"), // Mage
            9  => array("dps", "dps", "dps"), // Warlock
            11 => array("dps", "tank-dps", "healer"), // Druid
        );
        if(!isset($talentTabId[$this->class])) {
            return false;
        }
        $tab_class = $talentTabId[$this->class];
        if($spec >= 0) {
            $values = array_values($tab_class);
            return $values[$spec];
        }
        return $tab_class;
    }

    /**
     * Calculates and returns array with character talent specs. !Required $this->guid and $this->class!
     * @category Character class
     * @access   public
     * @return   array
     **/
    public function CalculateCharacterTalents() {

        if(!$this->class || !$this->guid) {
            return false;
        }

        $talentTree = array();
        $tab_class = self::GetTalentTab();

        if(!is_array($tab_class)) {
            self::debug("no talent tabs found");
            return false;
        }
        $character_talents = $CHDB->select("SELECT * FROM `character_talent` WHERE `guid`= ?d", $this->guid);
        if(!$character_talents) {
            self::debug("Character has no talents");
            return false;
        }

        $class_talents = $aDB->select("SELECT * FROM `armory_talents` WHERE `TalentTab` IN (?a) ORDER BY `TalentTab`, `Row`, `Col`", $tab_class);
        if(!$class_talents) {
            self::debug("Unable to find talents for this class");
            return false;
        }
        $talent_build = array();
        $talent_build[0] = null;
        $talent_build[1] = null;
        $talent_points = array();
        foreach($tab_class as $tab_val) {
            $talent_points[0][$tab_val] = 0;
            $talent_points[1][$tab_val] = 0;
        }
        $num_tabs = array();
        $i = 0;
        foreach($tab_class as $tab_key => $tab_value) {
            $num_tabs[$tab_key] = $i;
            $i++;
        }

        foreach($class_talents as $class_talent) {
            $current_found = false;
            $last_spec = 0;
            foreach($character_talents as $char_talent) {
                for($k = 1; $k < 6; $k++) {
                    if($char_talent['spell'] == $class_talent['Rank_' . $k]) {
                        $talent_build[$char_talent['spec']] .= $k;
                        $current_found = true;
                        $talent_points[$char_talent['spec']][$class_talent['TalentTab']] += $k;
                    }
                }
                $last_spec = $char_talent['spec'];
            }
            if(!$current_found) {
                $talent_build[$last_spec] .= 0;
            }
        }
        $talent_data = array('points' => $talent_points);
        return $talent_data;
    }

    /**
     * Returns character talent build for all specs (2 if character has dual talent specialization)
     * @category Character class
     * @access   public
     * @return   array
     **/
    public function CalculateCharacterTalentBuild() {
        global $CHDB, $aDB;
        if(!$this->guid || !$this->class) {
            self::debug(__METHOD__.": player class or guid not defined");
            return false;
        }
        $build_tree = array(1 => null, 2 => null);
        $tab_class = self::GetTalentTab();
        $specs_talents = array();
        $character_talents = $CHDB->select("SELECT * FROM `character_talent` WHERE `guid`= ?d;", $this->guid);
        $talent_data = array(0 => null, 1 => null); // Talent build
        if(!$character_talents) {
            self::debug(__METHOD__.": unable to get data from DB for player");
            return false;
        }
        foreach($character_talents as $_tal) {
            $specs_talents[$_tal['spec']][$_tal['spell']] = true;
        }
        for($i = 0; $i < 3; $i++) {
            $current_tab = $aDB->select("SELECT * FROM `armory_talents` WHERE `TalentTab`= ?d ORDER BY `TalentTab`, `Row`, `Col`", $tab_class[$i]);
            if(!$current_tab) {
                continue;
            }
            foreach($current_tab as $tab) {
                for($j = 0; $j < 2; $j++) {
                    if(isset($specs_talents[$j][$tab['Rank_5']])) {
                        $talent_data[$j] .= 5;
                    }
                    elseif(isset($specs_talents[$j][$tab['Rank_4']])) {
                        $talent_data[$j] .= 4;
                    }
                    elseif(isset($specs_talents[$j][$tab['Rank_3']])) {
                        $talent_data[$j] .= 3;
                    }
                    elseif(isset($specs_talents[$j][$tab['Rank_2']])) {
                        $talent_data[$j] .= 2;
                    }
                    elseif(isset($specs_talents[$j][$tab['Rank_1']])) {
                        $talent_data[$j] .= 1;
                    }
                    else {
                        $talent_data[$j] .= 0;
                    }
                }
            }
        }
        return $talent_data;
    }

    /**
     * Gets all talents of the specified spec
     * @category Character class
     * @access   public
     * @param    int $tabId (Id specific to each talent tree of each class)
     * @param    int $spec (0 or 1)
     * @return   array
     **/
    public function GetTalentTabCells($tabId, $spec){
        global $CHDB, $aDB;
        if(!$this->guid || !$this->class) {
            self::debug(__METHOD__.": player class or guid not defined");
            return false;
        }

        $charTalentRows = $CHDB->select("SELECT * FROM `character_talent` WHERE `guid`= ?d AND `spec` = ?d;", $this->guid, $spec);
        if(!$charTalentRows) {
            self::debug(__METHOD__.": unable to get data from DB for this talent tree $spec");
            return false;
        }

        $characterTalents = array();

        foreach($charTalentRows as $talent){
            $characterTalents[$talent["spell"]] = 1;
        }

        $talentTreeRows = $aDB->select("SELECT * FROM `armory_talents` WHERE `TalentTab`= ?d ORDER BY `Row`, `Col`", $tabId);
        if(!$talentTreeRows) {
            return false;
        }

        $talentCells = array();

        foreach($talentTreeRows as $talentRow){
            //$talentRow["spell"] = $talentRow["TalentID"];
            if(isset($characterTalents[$talentRow["Rank_5"]])) {
                $talentRow["rank"] = 5;
            }
            elseif(isset($characterTalents[$talentRow["Rank_4"]])) {
                $talentRow["rank"] = 4;
            }
            elseif(isset($characterTalents[$talentRow["Rank_3"]])) {
                $talentRow["rank"] = 3;
            }
            elseif(isset($characterTalents[$talentRow["Rank_2"]])) {
                $talentRow["rank"] = 2;
            }
            elseif(isset($characterTalents[$talentRow["Rank_1"]])) {
                $talentRow["rank"] = 1;
            }
            else {
                $talentRow["rank"] = 0;
            }


            $talentCells[] = $talentRow;

        }
        return $talentCells;
    }

    /**
     * Returns array with glyph data for all specs
     * @category Character class
     * @access   public
     * @param    int $spec = -1
     * @return   array
     **/
    public function GetCharacterGlyphs($spec = -1) {
        global $CHDB, $aDB;

        if(!$this->guid) {
            //Armory::Log()->writeError('%s : player guid not defined', __METHOD__);
            debug(__METHOD__.": keine guid");
            return false;
        }
        if($spec >= 0) {
            $glyphs_data = $CHDB->select("SELECT * FROM `character_glyphs` WHERE `guid`=?d AND `spec`=?d", $this->guid, $spec);
        }
        else {
            $glyphs_data = $CHDB->select("SELECT * FROM `character_glyphs` WHERE `guid`=?d ORDER BY `spec`", $this->guid);
        }

        if(!$glyphs_data) {
            debug(__METHOD__.": keine glyphs_data");
            //debug($CHDB);
            return false;
        }
        $data = array(0 => array(), 1 => array());
        $glyphIds = array(0 => array(), 1 => array());
        $i = 0;
        foreach($glyphs_data as $glyph) {
            for($j = 1; $j < 7; $j++) {
                $current_glyph = $aDB->selectRow("SELECT ?# AS `name`, ?# AS `effect`, `type` FROM `armory_glyphproperties` WHERE `id`=?d", "name_en_gb", "description_en_gb", $glyph['glyph' . $j]);

                if(!$current_glyph) {
                    //debug("skip glyph $j", $aDB);
                    continue;
                }
                $data[$glyph['spec']][$i] = array(
                    'effect' => str_replace('"', '&quot;', $current_glyph['effect']),
                    'id'     => $glyph['glyph' . $j],
                    'name'   => str_replace('"', '&quot;', $current_glyph['name'])
                );
                $glyphIds[$glyph['spec']][$i] = $glyph['glyph' . $j];
                if($current_glyph['type'] == 0) {
                    $data[$glyph['spec']][$i]['type'] = 'major';
                }
                else {
                    $data[$glyph['spec']][$i]['type'] = 'minor';
                }
                $i++;
            }

        }
        $data["glyphString"][0] = implode(", ",$glyphIds[0]);
        $data["glyphString"][1] = implode(", ",$glyphIds[1]);
        return $data;
    }

    /**
     * Returns talent tree name for selected class
     * @category Character class
     * @access   public
     * @param    int $spec
     * @return   string
     **/
    public function ReturnTalentTreesNames($spec) {
        global $aDB;
        if(!$this->class) {
            return false;
        }
        return $aDB->selectCell("SELECT ?# FROM `armory_talent_icons` WHERE `class`= ?d AND `spec` = ?d", "name_".$this->locale, $this->class, $spec);
    }

    /**
     * Returns icon name for selected class & talent tree
     * @category Character class
     * @access   public
     * @param    int $tree
     * @return   string
     * @todo     Move this function to Utils class
     **/
    public function ReturnTalentTreeIcon($tree) {
        global $aDB;
        if(!$this->class) {
            debug(__METHOD__.': class not provided');
            return false;
        }
        return $aDB->selectCell("SELECT `icon` FROM `armory_talent_icons` WHERE `class`=?d AND `spec`=?d LIMIT 1", $this->class, $tree);
    }

    /**
     * Returns array with character professions (name, icon & current skill value)
     * @category Character class
     * @access   public
     * @return   array
     **/
    public function GetCharacterProfessions() {
        global $CHDB, $aDB;
        $skills_professions = array(164, 165, 171, 182, 186, 197, 202, 333, 393, 755, 773);
        $professions = $CHDB->select("SELECT * FROM `character_skills` WHERE `skill` IN (?a) AND `guid`=?d LIMIT 2", $skills_professions, $this->guid);
        if(!$professions) {
            return array();
        }
        $p = array();
        $i = 0;
        foreach($professions as $prof) {
            $p[$i] = $aDB->selectRow("SELECT `id`, ?# AS `name`, `icon_skill` FROM `armory_professions` WHERE `id` = ?d LIMIT 1", "name_".$this->locale, $prof['skill']);
            $p[$i]['name'] = ($p[$i]['name']);
            $p[$i]['value'] = $prof['value'];
            $p[$i]['max'] = 450;
            $icon = $p[$i]['icon_skill'];

            if($prof["value"] < $p[$i]['max']){
                $p[$i]['percent'] = round($prof["value"]/$p[$i]['max']*100);
            }
            else{
                $p[$i]['percent'] = 100;
            }

            if(!file_exists($_SERVER['DOCUMENT_ROOT']."/images/icons/18/".$icon.".jpg"))
                GetItemIcon($icon, 18);
            $p[$i]['icon'] = "/images/icons/18/".$icon.".jpg";
            $this->professions[$p[$i]["id"]] = $p[$i]["value"];
            $i++;
        }
        return $p;
    }

    /**
     * Returns array with character reputation (faction name, description, value)
     * @category Character class
     * @access   public
     * @todo     Make parent sections
     * @return   array
     **/
    public function GetCharacterReputation() {
        if(!$this->guid) {
            return false;
        }
        /*
        // Default categories
        $categories = array(
            // World of Warcraft (Classic)
            1118 => array(
                // Horde
                67 => array(
                    'order' => 1,
                    'side'  => 1
                ),
                // Horde Forces
                892 => array(
                    'order' => 2,
                    'side'  => 1
                ),
                // Alliance
                469 => array(
                    'order' => 1,
                    'side'  => 2
                ),
                // Alliance Forces
                891 => array(
                    'order' => 2,
                    'side'  => 2
                ),
                // Steamwheedle Cartel
                169 => array(
                    'order' => 3,
                    'side'  => -1
                )
            ),
            // The Burning Crusade
            980 => array(
                // Shattrath
                936 => array(
                    'order' => 1,
                    'side'  => -1
                )
            ),
            // Wrath of the Lich King
            1097 => array(
                // Sholazar Basin
                1117 => array(
                    'order' => 1,
                    'side'  => -1
                ),
                // Horde Expedition
                1052 => array(
                    'order' => 2,
                    'side'  => 1
                ),
                // Alliance Vanguard
                1037 => array(
                    'order' => 2,
                    'side'  => 2
                ),
            ),
            // Other
            0 => array(
                // Wintersaber trainers
                589 => array(
                    'order' => 1,
                    'side'  => 2
                ),
                // Syndicat
                70 => array(
                    'order' => 2,
                    'side'  => -1
                )
            )
        );
        */
        $repData = $this->portalDb->select("SELECT `faction`, `standing`, `flags` FROM `character_reputation` WHERE `guid`=%d", $this->guid);
        if(!$repData) {
            return false;
        }
        $i = 0;
        foreach($repData as $faction) {
            if(!($faction['flags']&FACTION_FLAG_VISIBLE) || $faction['flags'] & (FACTION_FLAG_HIDDEN | FACTION_FLAG_INVISIBLE_FORCED)) {
                continue;
            }
            $factionReputation[$i] = Armory::$aDB->selectRow("SELECT `id`, `category`, `name_%s` AS `name`, `key` FROM `armory_faction` WHERE `id`=%d", $this->locale, $faction['faction']);
            if($faction['standing'] > 42999) {
                $factionReputation[$i]['reputation'] = 42999;
            }
            else {
                $factionReputation[$i]['reputation'] = $faction['standing'];
            }
            $i++;
        }
        return $factionReputation;
    }

    private function GetFactionCategories($faction) {
        $path = array();
        $in_process = true;
        $id = $faction;
        while($in_process) {
            $id = Armory::$aDB->selectCell("SELECT `category` FROM `armory_faction` WHERE `id` = %d", $id);
            if($id > 0) {
                $path[] = $id;
            }
            else {
                $in_process = false;
            }
        }
        return $path;
    }

    /**
     * Returns value of $fieldNum field. Requires $this->guid or int $guid as second parameter!
     * @access   public
     * @param    int $fieldNum
     * @param    int $guid = null
     * @return   int
     **/
    public function GetDataField($fieldNum, $guid = null) {
        if($guid == null && $this->guid > 0) {
            $guid = $this->guid;
        }
        if(!$guid) {
            //Armory::Log()->writeError('%s : guid not provided', __METHOD__);
            return false;
        }
        return (isset($this->char_data[$fieldNum])) ? $this->char_data[$fieldNum] : 0;
    }

    public function GetHighestRaidTitle(){

        $title_stack = explode(" ", $this->knownTitles);
        $titles = array();
        $position = 0;
        foreach($title_stack as $stack){
            if($stack == 0){
                $position += 32;
                continue;
            }


        }
        return "";
    }

    /**
     * Returns current health value
     * @category Character class
     * @access   public
     * @return   int
     **/
    public function GetMaxHealth() {
        return $this->health;
    }

    /**
     * Returns current mana value
     * @category Character class
     * @access   public
     * @return   int
     **/
    public function GetMaxMana() {
        return $this->power1;
    }

    /**
     * Returns current rage value
     * @category Character class
     * @access   public
     * @return   int
     **/
    public function GetMaxRage() {
        return 100;
    }

    /**
     * Returns current energy (for Rogues) or Runic power (for Death Knight) value
     * @category Character class
     * @access   public
     * @return   int
     **/
    public function GetMaxEnergy() {
        global $CHDB;

        $maxPower = 100;
        if($this->class == CLASS_DK) {
            // Check for 50147, 49455 spells (Runic power mastery) in current talent spec
            $tRank = $this->HasTalent(2020);
            if($tRank === 0) {
                // Runic power mastery (Rank 1)
                $maxPower = 115;
            }
            elseif($tRank == 1) {
                // Runic power mastery (Rank 2)
                $maxPower = 130;
            }
        }
        elseif($this->class == CLASS_ROGUE) {
            // Check for 14983 spell (Vigor) in current talent spec
            $tRank = $this->HasTalent(382);
            if($tRank) {
                $maxPower = 110;
            }
            // Also, check for Glyph of Vigor (id 408)
            $isGlyphed = $CHDB->selectCell("SELECT 1 FROM `character_glyphs` WHERE `guid`=?d AND (`glyph1`=408 OR `glyph2`=408 OR `glyph3`=408 OR `glyph4`=408 OR `glyph5`=408 OR `glyph6`=408) AND `spec`=?d", $this->guid, $this->activeSpec);
            if($isGlyphed) {
                $maxPower = 120;
            }
        }
        return $maxPower;
    }

    /**
     * Assigns $this->rating variable (or returns it if it was already assigned)
     * @category Character class
     * @access   public
     * @return   array
     **/
    public function SetRating() {
        if(is_array($this->rating)) {
            return $this->rating;
        }
        else {
            $this->rating = Utils::GetRating($this->level);
            return $this->rating;
        }
    }

    /**
     * Calls internal function to calculate character stat
     * @category Character class
     * @access   public
     * @param    string $stat_string
     * @param    int $type
     * @return   int
     **/
    public function GetCharacterStat($stat_string, $type = false) {
        switch($stat_string) {
            case 'strength':
                return $this->GetCharacterStrength();
                break;
            case 'agility':
                return $this->GetCharacterAgility();
                break;
            case 'stamina':
                return $this->GetCharacterStamina();
                break;
            case 'intellect':
                return $this->GetCharacterIntellect();
                break;
            case 'spirit':
                return $this->GetCharacterSpirit();
                break;
            case 'armor':
                return $this->GetCharacterArmor();
                break;
            case 'mainHandDamage':
                return $this->GetCharacterMainHandMeleeDamage();
                break;
            case 'offHandDamage':
                return $this->GetCharacterOffHandMeleeDamage();
                break;
            case 'mainHandSpeed':
                return $this->GetCharacterMainHandMeleeHaste();
                break;
            case 'offHandSpeed':
                return $this->GetCharacterOffHandMeleeHaste();
                break;
            case 'power':
                if($type === false) {
                    return $this->GetCharacterAttackPower();
                }
                elseif($type == 1) {
                    return $this->GetCharacterRangedAttackPower();
                }
                break;
            case 'hitRating':
                if(!$type) {
                    return $this->GetCharacterMeleeHitRating();
                }
                elseif($type == 1) {
                    return $this->GetCharacterRangedHitRating();
                }
                elseif($type == 2) {
                    return $this->GetCharacterSpellHitRating();
                }
                break;
            case 'critChance':
                if(!$type) {
                    return $this->GetCharacterMeleeCritChance();
                }
                elseif($type == 1) {
                    return $this->GetCharacterRangedCritChance();
                }
                elseif($type == 2) {
                    return $this->GetCharacterSpellCritChance();
                }
                break;
            case 'expertise':
                return $this->GetCharacterMainHandMeleeSkill();
                break;
            case 'damage':
                return $this->GetCharacterRangedDamage();
                break;
            case 'speed':
                return $this->GetCharacterRangedHaste();
                break;
            case 'weaponSkill':
                return $this->GetCharacterRangedWeaponSkill();
                break;
            case 'bonusDamage':
                return $this->GetCharacterSpellBonusDamage();
                break;
            case 'bonusHealing':
                return $this->GetCharacterSpellBonusHeal();
                break;
            case 'hasteRating':
                return $this->GetCharacterSpellHaste();
                break;
            case 'penetration':
                return $this->GetCharacterSpellPenetration();
                break;
            case 'manaRegen':
                return $this->GetCharacterSpellManaRegen();
                break;
            case 'defense':
                return $this->GetCharacterDefense();
                break;
            case 'dodge':
                return $this->GetCharacterDodge();
                break;
            case 'parry':
                return $this->GetCharacterParry();
                break;
            case 'block':
                return $this->GetCharacterBlock();
                break;
            case 'resilience':
                return $this->GetCharacterResilence();
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * Returns array with Strength value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterStrength() {
        $tmp_stats = array();
        $tmp_stats['bonus_strenght'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_POSSTAT0), 0);
        $tmp_stats['negative_strenght'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_NEGSTAT0), 0);
        $tmp_stats['effective'] = $this->GetDataField(UNIT_FIELD_STAT0);
        $tmp_stats['attack'] = Utils::GetAttackPowerForStat(STAT_STRENGTH, $tmp_stats['effective'], $this->class);
        $tmp_stats['base'] = $tmp_stats['effective']-$tmp_stats['bonus_strenght'] - $tmp_stats['negative_strenght'];
        if(in_array($this->class, array(CLASS_WARRIOR, CLASS_PALADIN, CLASS_SHAMAN))) {
            $tmp_stats['block'] = max(0, $tmp_stats['effective'] * BLOCK_PER_STRENGTH - 10);
        }
        else {
            $tmp_stats['block'] = -1;
        }
        $player_stats = array(
            'attack'    => $tmp_stats['attack'],
            'base'      => $tmp_stats['base'],
            'block'     => $tmp_stats['block'],
            'effective' => $tmp_stats['effective']
        );
        unset($tmp_stats);
        return $player_stats;
    }

    /**
     * Returns array with Agility value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterAgility() {
        $tmp_stats    = array();
        $rating       = $this->SetRating();
        $tmp_stats['effective'] = $this->GetDataField(UNIT_FIELD_STAT1);
        $tmp_stats['bonus_agility'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_POSSTAT1), 0);
        $tmp_stats['negative_agility'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_NEGSTAT1), 0);
        $tmp_stats['base'] = $tmp_stats['effective'] - $tmp_stats['bonus_agility'] - $tmp_stats['negative_agility'];
        $tmp_stats['critHitPercent'] = floor(Utils::GetCritChanceFromAgility($rating, $this->class, $tmp_stats['effective']));
        $tmp_stats['attack'] = Utils::GetAttackPowerForStat(STAT_AGILITY, $tmp_stats['effective'], $this->class);
        $tmp_stats['armor'] = $tmp_stats['effective'] * ARMOR_PER_AGILITY;
        if($tmp_stats['attack'] == 0) {
            $tmp_stats['attack'] = -1;
        }
        $player_stats = array(
            'armor'          => $tmp_stats['armor'],
            'attack'         => $tmp_stats['attack'],
            'base'           => $tmp_stats['base'],
            'critHitPercent' => $tmp_stats['critHitPercent'],
            'effective'      => $tmp_stats['effective']
        );
        unset($rating, $tmp_stats);
        return $player_stats;
    }

    /**
     * Returns array with Stamina value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterStamina() {
        $tmp_stats = array();
        $tmp_stats['effective'] = $this->GetDataField(UNIT_FIELD_STAT2);
        $tmp_stats['bonus_stamina'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_POSSTAT2), 0);
        $tmp_stats['negative_stamina'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_NEGSTAT2), 0);
        $tmp_stats['base'] = $tmp_stats['effective'] - $tmp_stats['bonus_stamina'] - $tmp_stats['negative_stamina'];
        $tmp_stats['base_stamina'] = min(20, $tmp_stats['effective']);
        $tmp_stats['more_stamina'] = $tmp_stats['effective'] - $tmp_stats['base_stamina'];
        $tmp_stats['health'] = $tmp_stats['base_stamina'] + ($tmp_stats['more_stamina'] * HEALTH_PER_STAMINA);
        $tmp_stats['petBonus'] = Utils::ComputePetBonus(2, $tmp_stats['effective'], $this->class);
        if($tmp_stats['petBonus'] == 0) {
            $tmp_stats['petBonus'] = -1;
        }
        $player_stats = array(
            'base'      => $tmp_stats['base'],
            'effective' => $tmp_stats['effective'],
            'health'    => $tmp_stats['health'],
            'petBonus'  => $tmp_stats['petBonus']
        );
        unset($tmp_stats);
        return $player_stats;
    }

    /**
     * Returns array with Intellect value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterIntellect() {
        $tmp_stats = array();
        $rating    = $this->SetRating();
        $tmp_stats['effective'] =$this->GetDataField(UNIT_FIELD_STAT3);
        $tmp_stats['bonus_intellect'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_POSSTAT3), 0);
        $tmp_stats['negative_intellect'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_NEGSTAT3), 0);
        $tmp_stats['base'] = $tmp_stats['effective']-$tmp_stats['bonus_intellect']-$tmp_stats['negative_intellect'];
        if($this->IsManaUser()) {
            $tmp_stats['base_intellect'] = min(20, $tmp_stats['effective']);
            $tmp_stats['more_intellect'] = $tmp_stats['effective'] - $tmp_stats['base_intellect'];
            $tmp_stats['mana'] = $tmp_stats['base_intellect'] + $tmp_stats['more_intellect'] * MANA_PER_INTELLECT;
            $tmp_stats['critHitPercent'] = round(Utils::GetSpellCritChanceFromIntellect($rating, $this->class, $tmp_stats['effective']), 2);
        }
        else {
            $tmp_stats['base_intellect'] = -1;
            $tmp_stats['more_intellect'] = -1;
            $tmp_stats['mana'] = -1;
            $tmp_stats['critHitPercent'] = -1;
        }
        $tmp_stats['petBonus'] = Utils::ComputePetBonus(7, $tmp_stats['effective'], $this->class);
        if($tmp_stats['petBonus'] == 0) {
            $tmp_stats['petBonus'] = -1;
        }
        $player_stats = array(
            'base' => $tmp_stats['base'],
            'critHitPercent' => $tmp_stats['critHitPercent'],
            'effective'      => $tmp_stats['effective'],
            'mana'           => $tmp_stats['mana'],
            'petBonus'       => $tmp_stats['petBonus']
        );
        unset($rating, $tmp_stats);
        return $player_stats;
    }

    /**
     * Returns array with Spirit value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterSpirit() {
        $tmp_stats = array();
        $rating    = $this->SetRating();
        $tmp_stats['effective'] =$this->GetDataField(UNIT_FIELD_STAT4);
        $tmp_stats['bonus_spirit'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_POSSTAT4), 0);
        $tmp_stats['negative_spirit'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_NEGSTAT4), 0);
        $tmp_stats['base'] = $tmp_stats['effective']-$tmp_stats['bonus_spirit']-$tmp_stats['negative_spirit'];
        $baseRatio = array(0, 0.625, 0.2631, 0.2, 0.3571, 0.1923, 0.625, 0.1724, 0.1212, 0.1282, 1, 0.1389);
        $tmp_stats['base_spirit'] = $tmp_stats['effective'];
        if($tmp_stats['base_spirit'] > 50) {
            $tmp_stats['base_spirit'] = 50;
        }
        $tmp_stats['more_spirit'] = $tmp_stats['effective'] - $tmp_stats['base_spirit'];
        $tmp_stats['healthRegen'] = floor($tmp_stats['base_spirit'] * $baseRatio[$this->class] + $tmp_stats['more_spirit'] * Utils::GetHRCoefficient($rating, $this->class));

        if($this->IsManaUser()) {
            $intellect_tmp = $this->GetCharacterIntellect();
            $tmp_stats['manaRegen'] = sqrt($intellect_tmp['effective']) * $tmp_stats['effective'] * Utils::GetMRCoefficient($rating, $this->class);
            $tmp_stats['manaRegen'] = floor($tmp_stats['manaRegen'] * 5);
        }
        else {
            $tmp_stats['manaRegen'] = -1;
        }
        $player_stats = array(
            'base'        => $tmp_stats['base'],
            'effective'   => $tmp_stats['effective'],
            'healthRegen' => $tmp_stats['healthRegen'],
            'manaRegen'   => $tmp_stats['manaRegen']
        );
        unset($rating, $tmp_stats);
        return $player_stats;
    }

    /**
     * Returns array with Armor value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterArmor() {
        $tmp_stats = array();
        $levelModifier = 0;
        $tmp_stats['effective'] = $this->GetDataField(UNIT_FIELD_RESISTANCES);
        $tmp_stats['bonus_armor'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_RESISTANCEBUFFMODSPOSITIVE), 0);
        $tmp_stats['negative_armor'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_RESISTANCEBUFFMODSNEGATIVE), 0);
        $tmp_stats['base'] = $tmp_stats['effective']-$tmp_stats['bonus_armor']-$tmp_stats['negative_armor'];
        if($this->level > 59 ) {
            $levelModifier = $this->level + (4.5 * ($this->level-59));
        }
        $tmp_stats['reductionPercent'] = 0.1*$tmp_stats['effective']/(8.5*$levelModifier + 40);
        $tmp_stats['reductionPercent'] = round($tmp_stats['reductionPercent']/(1+$tmp_stats['reductionPercent'])*100, 2);
        if($tmp_stats['reductionPercent'] > 75) {
            $tmp_stats['reductionPercent'] = 75;
        }
        if($tmp_stats['reductionPercent'] <  0) {
            $tmp_stats['reductionPercent'] = 0;
        }
        $tmp_stats['petBonus'] = Utils::ComputePetBonus(4, $tmp_stats['effective'], $this->class);
        if($tmp_stats['petBonus'] == 0) {
            $tmp_stats['petBonus'] = '-1';
        }
        $player_stats = array(
            'base'      => $tmp_stats['base'],
            'effective' => $tmp_stats['effective'],
            'percent'   => $tmp_stats['reductionPercent'],
            'petBonus'  => $tmp_stats['petBonus']
        );
        unset($tmp_stats);
        return $player_stats;
    }

    /**
     * Returns array with Expertise (MH melee) value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterMainHandMeleeSkill() {
        $tmp_stats = array();
        $rating    = $this->SetRating();
        $character_data = $this->portalDb->selectCell("SELECT `data` FROM `armory_character_stats` WHERE `guid`=%d", $this->guid);
        $tmp_stats['melee_skill_id'] = Utils::GetSkillIDFromItemID($this->GetCharacterEquip('mainhand'));
        $tmp_stats['melee_skill'] = Utils::GetSkillInfo($tmp_stats['melee_skill_id'], $character_data);
        $tmp_stats['rating'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1+20);
        $tmp_stats['additional'] = $tmp_stats['rating']/Utils::GetRatingCoefficient($rating, 2);
        $buff = $tmp_stats['melee_skill'][4]+$tmp_stats['melee_skill'][5]+intval($tmp_stats['additional']);
        $tmp_stats['value'] = $tmp_stats['melee_skill'][2]+$buff;
        $player_stats = array(
            'value'      => $tmp_stats['value'],
            'rating'     => $tmp_stats['rating'],
            'additional' => $tmp_stats['additional'],
            'percent'    => '0.00'
        );
        unset($tmp_stats, $rating);
        return $player_stats;
    }

    /**
     * Returns array with Expertise (OH melee) value
     * @category Character class
     * @access   private
     * @return   array
     * @todo     correctly handle this stat
     **/
    private function GetCharacterOffHandMeleeSkill() {
        return array('value' => null, 'rating' => null);
    }

    /**
     * Returns array with Main hand melee damage value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterMainHandMeleeDamage() {
        $tmp_stats = array();
        $tmp_stats['min'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_MINDAMAGE), 0);
        $tmp_stats['max'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_MAXDAMAGE), 0);
        $tmp_stats['speed'] = round(Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_BASEATTACKTIME), 2) / 1000, 2);
        $tmp_stats['melee_dmg'] = ($tmp_stats['min'] + $tmp_stats['max']) * 0.5;
        $tmp_stats['dps'] = round((max($tmp_stats['melee_dmg'], 1) / $tmp_stats['speed']), 1);
        if($tmp_stats['speed'] < 0.1) {
            $tmp_stats['speed'] = 0.1;
        }
        $player_stats = array(
            'dps'     => $tmp_stats['dps'],
            'max'     => $tmp_stats['max'],
            'min'     => $tmp_stats['min'],
            'percent' => 0,
            'speed'   => $tmp_stats['speed']
        );
        unset($tmp_stats);
        return $player_stats;
    }

    /**
     * Returns array with Off hand melee damage value
     * @category Character class
     * @access   private
     * @return   array
     * @todo     correctly handle this stat
     **/
    private function GetCharacterOffHandMeleeDamage() {
        return array('speed' => 0, 'min' => 0, 'max'  => 0, 'percent' => 0, 'dps' => '0.0');
    }

    /**
     * Returns array with Main hand melee haste value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterMainHandMeleeHaste() {
        $tmp_stats = array();
        $rating    = $this->SetRating();
        $tmp_stats['value'] = round(Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_BASEATTACKTIME), 2)/1000, 2);
        $tmp_stats['hasteRating'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 17);
        $tmp_stats['hastePercent'] = round($tmp_stats['hasteRating'] / Utils::GetRatingCoefficient($rating, 19), 2);
        unset($rating);
        return $tmp_stats;
    }

    /**
     * Returns array with Off hand melee haste value
     * @category Character class
     * @access   private
     * @return   array
     * @todo     correctly handle this stat
     **/
    private function GetCharacterOffHandMeleeHaste() {
        return array('hastePercent' => 0, 'hasteRating' => 0, 'value' => 0);
    }

    /**
     * Returns array with Attack power value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterAttackPower() {
        $tmp_stats = array();
        $tmp_stats['multipler_melee_ap'] = Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_ATTACK_POWER_MULTIPLIER), 8);
        if($tmp_stats['multipler_melee_ap'] < 0) {
            $tmp_stats['multipler_melee_ap'] = 0;
        }
        else {
            $tmp_stats['multipler_melee_ap']+=1;
        }
        $tmp_stats['base'] = $this->GetDataField(UNIT_FIELD_ATTACK_POWER) * $tmp_stats['multipler_melee_ap'];
        $tmp_stats['bonus_melee_ap'] = $this->GetDataField(UNIT_FIELD_ATTACK_POWER_MODS) * $tmp_stats['multipler_melee_ap'];
        $tmp_stats['effective'] = $tmp_stats['base'] + $tmp_stats['bonus_melee_ap'];
        $tmp_stats['increasedDps'] = floor(max($tmp_stats['effective'], 0) / 14);
        $player_stats = array(
            'base'         => round($tmp_stats['base']),
            'effective'    => round($tmp_stats['effective']),
            'increasedDps' => round($tmp_stats['increasedDps'])
        );
        unset($tmp_stats);
        return $player_stats;
    }

    /**
     * Returns array with Hit rating (melee) value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterMeleeHitRating() {
        $player_stats = array();
        $rating       = $this->SetRating();
        $player_stats['value'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1+5);
        $player_stats['increasedHitPercent'] = floor($player_stats['value'] / Utils::GetRatingCoefficient($rating, 6));
        $player_stats['armorPenetration'] = $this->GetDataField(PLAYER_FIELD_MOD_TARGET_PHYSICAL_RESISTANCE);
        $player_stats['reducedArmorPercent'] = '0.00';
        unset($rating);
        return $player_stats;
    }

    /**
     * Returns array with Melee crit value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterMeleeCritChance() {
        $rating = $this->SetRating();
        $player_stats = array();
        $player_stats['percent'] = Utils::GetFloatValue($this->GetDataField(PLAYER_CRIT_PERCENTAGE), 2);
        $player_stats['rating'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1+8);
        $player_stats['plusPercent'] = floor($player_stats['rating'] / Utils::GetRatingCoefficient($rating, 9));
        unset($rating);
        return $player_stats;
    }

    /**
     * Returns array with Ranged Expertise value
     * @category Character class
     * @access   private
     * @return   array
     * @todo     correctly handle this stat
     **/
    private function GetCharacterRangedWeaponSkill() {
        return array('value' => -1, 'rating' => -1);
    }

    /**
     * Returns array with Ranged weapon damage value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterRangedDamage() {
        $tmp_stats     = array();
        $rangedSkillID = Mangos::GetSkillIDFromItemID($this->GetDataField(PLAYER_VISIBLE_ITEM_18_ENTRYID));
        if($rangedSkillID == SKILL_UNARMED) {
            $tmp_stats['min'] = 0;
            $tmp_stats['max'] = 0;
            $tmp_stats['speed'] = 0;
            $tmp_stats['dps'] = 0;
        }
        else {
            $tmp_stats['min'] =  Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_MINRANGEDDAMAGE), 0);
            $tmp_stats['max'] =  Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_MAXRANGEDDAMAGE), 0);
            $tmp_stats['speed'] = round( Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_RANGEDATTACKTIME), 2)/1000, 2);
            $tmp_stats['ranged_dps'] = ($tmp_stats['min'] + $tmp_stats['max']) * 0.5;
            if($tmp_stats['speed'] < 0.1) {
                $tmp_stats['speed'] = 0.1;
            }
            $tmp_stats['dps'] = round((max($tmp_stats['ranged_dps'], 1) / $tmp_stats['speed']));
        }
        $player_stats = array(
            'speed'   => $tmp_stats['speed'],
            'min'     => $tmp_stats['min'],
            'max'     => $tmp_stats['max'],
            'dps'     => $tmp_stats['dps'],
            'percent' => '0.00'
        );
        unset($tmp_stats, $rangedSkillID);
        return $player_stats;
    }

    /**
     * Returns array with Ranged weapon haste value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterRangedHaste() {
        $player_stats  = array();
        $rating        = $this->SetRating();
        $rangedSkillID = Mangos::GetSkillIDFromItemID($this->GetDataField(PLAYER_VISIBLE_ITEM_18_ENTRYID));
        if($rangedSkillID == SKILL_UNARMED) {
            $player_stats['value'] = 0;
            $player_stats['hasteRating'] = 0;
            $player_stats['hastePercent'] = 0;
        }
        else {
            $player_stats['value'] = round(Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_RANGEDATTACKTIME),2)/1000, 2);
            $player_stats['hasteRating'] = round($this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1+18));
            $player_stats['hastePercent'] = round($player_stats['hasteRating']/ Utils::GetRatingCoefficient($rating, 19), 2);
        }
        unset($rating, $rangedSkillID);
        return $player_stats;
    }

    /**
     * Returns array with Ranged Attack Power value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterRangedAttackPower() {
        $player_stats = array();
        $multipler =  Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_RANGED_ATTACK_POWER_MULTIPLIER), 8);
        if($multipler < 0) {
            $multipler = 0;
        }
        else {
            $multipler += 1;
        }
        $effectiveStat = $this->GetDataField(UNIT_FIELD_RANGED_ATTACK_POWER) * $multipler;
        $buff = $this->GetDataField(UNIT_FIELD_RANGED_ATTACK_POWER_MODS) * $multipler;
        $multiple =  Utils::GetFloatValue($this->GetDataField(UNIT_FIELD_RANGED_ATTACK_POWER_MULTIPLIER), 2);
        $posBuff = 0;
        $negBuff = 0;
        if($buff > 0) {
            $posBuff = $buff;
        }
        elseif($buff < 0) {
            $negBuff = $buff;
        }
        $stat = $effectiveStat+$buff;
        $player_stats['base'] = floor($effectiveStat);
        $player_stats['effective'] = floor($stat);
        $player_stats['increasedDps'] = floor(max($stat, 0) / 14);
        $player_stats['petAttack'] = floor(Utils::ComputePetBonus(0, $stat, $this->class));
        $player_stats['petSpell'] = floor(Utils::ComputePetBonus(1, $stat, $this->class));
        return $player_stats;
    }

    /**
     * Returns array with Ranged Hit Rating value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterRangedHitRating() {
        $player_stats = array();
        $rating       = $this->SetRating();
        $player_stats['value'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 6);
        $player_stats['increasedHitPercent'] = floor($player_stats['value'] / Utils::GetRatingCoefficient($rating, 7));
        $player_stats['reducedArmorPercent'] = $this->GetDataField(PLAYER_FIELD_MOD_TARGET_PHYSICAL_RESISTANCE);
        $player_stats['penetration'] = 0;
        unset($rating);
        return $player_stats;
    }

    /**
     * Returns array with Ranged Crit value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterRangedCritChance() {
        $player_stats = array();
        $rating       = $this->SetRating();
        $player_stats['percent'] =  Utils::GetFloatValue($this->GetDataField(PLAYER_RANGED_CRIT_PERCENTAGE), 2);
        $player_stats['rating'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 9);
        $player_stats['plusPercent'] = floor($player_stats['rating']/ Utils::GetRatingCoefficient($rating, 10));
        unset($rating);
        return $player_stats;
    }

    /**
     * Returns array with Spell Power (damage) value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterSpellBonusDamage() {
        $tmp_stats  = array();
        $holySchool = SPELL_SCHOOL_HOLY;
        $minModifier = Utils::GetSpellBonusDamage($holySchool, $this->guid, $this->portalDb);
        for ($i = 1; $i < 7; $i++) {
            $bonusDamage[$i] = Utils::GetSpellBonusDamage($i, $this->guid, $this->portalDb);
            $minModifier = min($minModifier, $bonusDamage);
        }
        $tmp_stats['holy']   = round($bonusDamage[1]);
        $tmp_stats['fire']   = round($bonusDamage[2]);
        $tmp_stats['nature'] = round($bonusDamage[3]);
        $tmp_stats['frost']  = round($bonusDamage[4]);
        $tmp_stats['shadow'] = round($bonusDamage[5]);
        $tmp_stats['arcane'] = round($bonusDamage[6]);
        $tmp_stats['attack'] = -1;
        $tmp_stats['damage'] = -1;
        if($this->class == CLASS_HUNTER || $this->class == CLASS_WARLOCK) {
            $shadow = Utils::GetSpellBonusDamage(5, $this->guid, $this->portalDb);
            $fire   = Utils::GetSpellBonusDamage(2, $this->guid, $this->portalDb);
            $tmp_stats['attack'] = Utils::ComputePetBonus(6, max($shadow, $fire), $this->class);
            $tmp_stats['damage'] = Utils::ComputePetBonus(5, max($shadow, $fire), $this->class);
        }
        $tmp_stats['fromType'] = 0;
        return $tmp_stats;
    }

    /**
     * Returns array with Spell Crit value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterSpellCritChance() {
        $player_stats = array();
        $spellCrit    = array();
        $rating       = $this->SetRating();
        $player_stats['rating'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 10);
        $player_stats['spell_crit_pct'] = $player_stats['rating'] / Utils::GetRatingCoefficient($rating, 11);
        $minCrit = $this->GetDataField(PLAYER_SPELL_CRIT_PERCENTAGE1 + 1);
        for($i = 1; $i < 7; $i++) {
            $scfield = PLAYER_SPELL_CRIT_PERCENTAGE1 + $i;
            $s_crit_value = $this->GetDataField($scfield);
            $spellCrit[$i] =  Utils::GetFloatValue($s_crit_value, 2);
            $minCrit = min($minCrit, $spellCrit[$i]);
        }
        $player_stats['holy']   = $spellCrit[1];
        $player_stats['fire']   = $spellCrit[2];
        $player_stats['nature'] = $spellCrit[3];
        $player_stats['frost']  = $spellCrit[4];
        $player_stats['arcane'] = $spellCrit[5];
        $player_stats['shadow'] = $spellCrit[6];
        unset($rating, $spellCrit, $player_stats['spell_crit_pct']);
        return $player_stats;
    }

    /**
     * Returns array with Spell Hit value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterSpellHitRating() {
        $player_stats = array();
        $rating       = $this->SetRating();
        $player_stats['value'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 7);
        $player_stats['increasedHitPercent'] = floor($player_stats['value'] / Utils::GetRatingCoefficient($rating, 8));
        $player_stats['penetration'] = $this->GetDataField(PLAYER_FIELD_MOD_TARGET_RESISTANCE);
        $player_stats['reducedResist'] = 0;
        unset($rating);
        return $player_stats;
    }

    /**
     * Returns array with Spell Power (heal) value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterSpellBonusHeal() {
        return array('value' => $this->GetDataField(PLAYER_FIELD_MOD_HEALING_DONE_POS));
    }

    /**
     * Returns array with Spell Haste value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterSpellHaste() {
        $player_stats = array();
        $rating       = $this->SetRating();
        $player_stats['hasteRating'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 19);
        $player_stats['hastePercent'] = round($player_stats['hasteRating']/ Utils::GetRatingCoefficient($rating, 20), 2);
        unset($rating);
        return $player_stats;
    }

    /**
     * Returns array with Spell Penetration value
     * @category Character class
     * @access   private
     * @return   array
     * @todo     correctly handle this stat
     **/
    private function GetCharacterSpellPenetration() {
        return array('value' => 0);
    }

    /**
     * Returns array with Mana Regeneration value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterSpellManaRegen() {
        $player_stats = array();
        $player_stats['notCasting'] = $this->GetDataField(UNIT_FIELD_POWER_REGEN_FLAT_MODIFIER);
        $player_stats['casting'] = $this->GetDataField(UNIT_FIELD_POWER_REGEN_INTERRUPTED_FLAT_MODIFIER);
        $player_stats['notCasting'] =  floor(Utils::GetFloatValue($player_stats['notCasting'], 2) * 5);
        $player_stats['casting'] =  round(Utils::GetFloatValue($player_stats['casting'], 2) * 5, 2);
        return $player_stats;
    }

    /**
     * Returns array with Defense value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterDefense() {
        $tmp_stats = array();
        $rating    = $this->SetRating();
        $gskill    = $this->portalDb->selectRow("SELECT * FROM `character_skills` WHERE `guid`=%d AND `skill`=95", $this->guid);
        $tmp_value = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 1);
        $tmp_stats['defense_rating_skill'] = $gskill['value'];
        $tmp_stats['plusDefense'] = round($tmp_value / Utils::GetRatingCoefficient($rating, 2));
        $tmp_stats['value'] = $gskill['value'];
        $tmp_stats['rating'] = $tmp_value;
        $tmp_stats['increasePercent'] = DODGE_PARRY_BLOCK_PERCENT_PER_DEFENSE * ($tmp_stats['rating'] - $this->level * 5);
        $tmp_stats['decreasePercent'] = $tmp_stats['increasePercent'];
        if($tmp_stats['increasePercent'] < 0) {
            $tmp_stats['increasePercent'] = 0;
        }
        if($tmp_stats['decreasePercent'] < 0) {
            $tmp_stats['decreasePercent'] = 0;
        }
        unset($rating, $gskill, $tmp_stats['defense_rating_skill']);
        return $tmp_stats;
    }

    /**
     * Returns array with Dodge value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterDodge() {
        $tmp_stats = array();
        $rating    = $this->SetRating();
        $tmp_stats['percent'] = Utils::GetFloatValue($this->GetDataField(PLAYER_DODGE_PERCENTAGE), 2);
        $tmp_stats['rating'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 2);
        $tmp_stats['increasePercent'] = floor($tmp_stats['rating']/Utils::GetRatingCoefficient($rating, 3));
        unset($rating);
        return $tmp_stats;
    }

    /**
     * Returns array with Parry value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterParry() {
        $tmp_stats = array();
        $rating    = $this->SetRating();
        $tmp_stats['percent'] = Utils::GetFloatValue($this->GetDataField(PLAYER_PARRY_PERCENTAGE), 2);
        $tmp_stats['rating'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 3);
        $tmp_stats['increasePercent'] = floor($tmp_stats['rating']/Utils::GetRatingCoefficient($rating, 4));
        unset($rating);
        return $tmp_stats;
    }

    /**
     * Returns array with Block value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterBlock() {
        $tmp_stats = array();
        $blockvalue = $this->GetDataField(PLAYER_BLOCK_PERCENTAGE);
        $tmp_stats['percent'] =  Utils::GetFloatValue($blockvalue, 2);
        $tmp_stats['increasePercent'] = $this->GetDataField(PLAYER_FIELD_COMBAT_RATING_1 + 4);
        $tmp_stats['rating'] = $this->GetDataField(PLAYER_SHIELD_BLOCK);
        return $tmp_stats;
    }

    /**
     * Returns array with Resilence value
     * @category Character class
     * @access   private
     * @return   array
     **/
    private function GetCharacterResilence() {
        $tmp_stats = array();
        $rating    = $this->SetRating();
        $tmp_stats['melee_resilence'] = $this->GetDataField(PLAYER_FIELD_CRIT_TAKEN_MELEE_RATING);
        $tmp_stats['ranged_resilence'] = $this->GetDataField(PLAYER_FIELD_CRIT_TAKEN_RANGED_RATING);
        $tmp_stats['spell_resilence'] = $this->GetDataField(PLAYER_FIELD_CRIT_TAKEN_SPELL_RATING);
        $tmp_stats['value'] = min($tmp_stats['melee_resilence'], $tmp_stats['ranged_resilence'], $tmp_stats['spell_resilence']);
        $tmp_stats['damagePercent'] = $tmp_stats['melee_resilence']/Utils::GetRatingCoefficient($rating, 15);
        $tmp_stats['ranged_resilence_pct'] = $tmp_stats['ranged_resilence']/Utils::GetRatingCoefficient($rating, 16);
        $tmp_stats['hitPercent'] = $tmp_stats['spell_resilence']/Utils::GetRatingCoefficient($rating, 17);
        $player_stats = array(
            'value'         => $tmp_stats['value'],
            'hitPercent'    => round($tmp_stats['hitPercent'], 2),
            'damagePercent' => round($tmp_stats['damagePercent'], 2)
        );
        unset($rating, $tmp_stats);
        return $player_stats;
    }

    /**
     * Returns skill info for $skill. If $guid not provided, function will use $this->guid. Not used now.
     * @category Character class
     * @access   public
     * @param    int $skill
     * @param    int $guid = 0
     * @return   array
     **/
    public function GetCharacterSkill($skill, $guid = 0) {
        if($guid == 0 && $this->guid > 0) {
            $guid = $this->guid;
        }
        if($guid == 0) {
            //Armory::Log()->writeError('%s : guid not provided', __METHOD__);
            return false;
        }
        return $this->portalDb->selectRow("SELECT * FROM `character_skill` WHERE `guid`=%d AND `skill`=%d", $guid, $skill);
    }

    /**
     * Returns data for 2x2, 3x3 and 5x5 character arena teams (if exists).
     * If $check == true, function will return boolean type.
     * Used by character-*.php to check show or not 'Arena' button
     * @category Character class
     * @access   public
     * @param    bool $check = false
     * @return   array
     **/
    public function GetCharacterArenaTeamInfo($check = false) {
        if(!$this->guid) {
            //Armory::Log()->writeError('%s : player guid not defined', __METHOD__);
            return false;
        }
        $arenaTeamInfo = array();
        $tmp_info = $this->portalDb->select(
            "SELECT
            `arena_team_member`.`arenateamid`,
            `arena_team_member`.`guid`,
            `arena_team_member`.`personal_rating`,
            `arena_team`.`name`,
            `arena_team`.`type`,
            `arena_team_stats`.`rating`,
            `arena_team_stats`.`rank`
            FROM `arena_team_member` AS `arena_team_member`
            LEFT JOIN `arena_team_stats` AS `arena_team_stats` ON `arena_team_stats`.`arenateamid`=`arena_team_member`.`arenateamid`
            LEFT JOIN `arena_team` AS `arena_team` ON `arena_team`.`arenateamid`=`arena_team_member`.`arenateamid`
            WHERE `arena_team_member`.`guid`=%d", $this->guid);
        if(!$tmp_info) {
            return false;
        }
        if($check == true && is_array($tmp_info)) {
            return true;
        }
        for($i = 0; $i < 3; $i++) {
            switch($tmp_info[$i]['type']) {
                case 2:
                    $arenaTeamInfo['2x2'] = array(
                        'name' => $tmp_info[$i]['name'],
                        'rank' => $tmp_info[$i]['rank'],
                        'rating' => $tmp_info[$i]['rating'],
                        'personalrating' => $tmp_info[$i]['personal_rating']
                    );
                    break;
                case 3:
                    $arenaTeamInfo['3x3'] = array(
                        'name' => $tmp_info[$i]['name'],
                        'rank' => $tmp_info[$i]['rank'],
                        'rating' => $tmp_info[$i]['rating'],
                        'personalrating' => $tmp_info[$i]['personal_rating']
                    );
                    break;
                case 5:
                    $arenaTeamInfo['5x5'] = array(
                        'name' => $tmp_info[$i]['name'],
                        'rank' => $tmp_info[$i]['rank'],
                        'rating' => $tmp_info[$i]['rating'],
                        'personalrating' => $tmp_info[$i]['personal_rating']
                    );
                    break;
                default:
                    return false;
                    break;
            }
            return $arenaTeamInfo;
        }
    }

    /**
     * Loads character feed data from DB
     * @access   private
     * @return   bool
     **/
    private function LoadFeedData() {
        if($this->feed_data) {
            return true;
        }
        $this->feed_data = $this->portalDb->select("SELECT * FROM `character_feed_log` WHERE `guid` = %d AND `date` > 0 ORDER BY `date` DESC", $this->GetGUID());
        if(!$this->feed_data) {
            //Armory::Log()->writeLog('%s : unable to load feed data for character %s (GUID: %d).', __METHOD__, $this->GetName(), $this->GetGUID());
            return false;
        }
        $count = count($this->feed_data);
        for($i = 0; $i < $count; $i++) {
            if($this->feed_data[$i]['type'] == TYPE_ACHIEVEMENT_FEED) {
                $this->feed_data[$i]['date'] = $this->GetAchievementMgr()->GetAchievementDate($this->feed_data[$i]['data'], true);
            }
        }
        return true;
    }

    /**
     * Returns info about last character activity. Requires MaNGOS/Trinity core patch (tools/character_feed)!
     * bool $full used only in character-feed.php
     * @access   public
     * @param    bool $full = false
     * @return   array
     * @todo     Some bosses kills/achievement gains are not shown or shown with wrong date
     **/
    public function GetCharacterFeed($full = false) {
        if(!$this->guid) {
            //Armory::Log()->writeError('%s : player guid not defined', __METHOD__);
            return false;
        }
        if(!$this->feed_data) {
            // Must be loaded from Character::BuildCharacter()
            return false;
        }
        $limit = ($full == true) ? 50 : 10;
        $currently_added = 0;
        $i = 0;
        $key = 0;
        $feed_data = array();
        // Strings
        $feed_strings = Armory::$aDB->select("SELECT `id`, `string_%s` AS `string` FROM `armory_string` WHERE `id` IN (13, 14, 15, 16, 17, 18)", $this->locale);
        if(!$feed_strings) {
            //Armory::Log()->writeError('%s : unable to load strings from armory_string (current locale: %s; locId: %d)', __METHOD__, $this->locale, Armory::GetLoc());
            return false;
        }
        $_strings = array();
        foreach($feed_strings as $str) {
            $_strings[$str['id']] = $str['string'];
        }
        foreach($this->feed_data as $event) {
            if($currently_added == $limit) {
                break;
            }
            $event_date = $event['date'];
            $event_type = $event['type'];
            $event_data = $event['data'];
            $date_string = date('d.m.Y', $event_date);
            $feed_data[$i]['hard_date'] = $event_date;
            $feed_data[$i]['hard_data'] = $event_data;
            if(date('d.m.Y') == $date_string) {
                $sort = 'today';
                $diff = time() - $event_date;
                if($this->locale == 'ru_ru') {
                    $periods = array('сек.', 'мин.', 'ч.');
                    $ago_str = 'назад';
                }
                else {
                    $periods = array('seconds', 'minutes', 'hours');
                    $ago_str = 'ago';
                }
                $lengths = array(60, 60, 24);
                for($j = 0; $diff >= $lengths[$j]; $j++) {
                    $diff /= $lengths[$j];
                }
                $diff = round($diff);
                $date_string = sprintf('%s %s %s', $diff, $periods[$j], $ago_str);
            }
            elseif(date('d.m.Y', strtotime('yesterday')) == $date_string) {
                $sort = 'yesterday';
            }
            else {
                $sort = 'earlier';
            }
            switch($event_type) {
                case TYPE_ACHIEVEMENT_FEED:
                    $send_data = array('achievement' => $event_data, 'date' => $event_date);
                    $achievement_info = $this->GetAchievementMgr()->GetAchievementInfo($send_data);
                    if(!$achievement_info || !isset($achievement_info['title']) || !$achievement_info['title'] || empty($achievement_info['title'])) {
                        // Wrong achievement ID or achievement not found in DB.
                        continue;
                    }
                    if(date('d/m/Y', $event_date) != $this->GetAchievementMgr()->GetAchievementDate($event['data'])) {
                        // Wrong achievement date, skip. Related to Vasago's issue.
                        continue;
                    }
                    if(!isset($achievement_info['points'])) {
                        $achievement_info['points'] = 0; // Feat of Strength has no points.
                    }
                    $feed_data[$i]['event'] = array(
                        'type'   => 'achievement',
                        'date'   => $date_string,
                        'time'   => date('H:i:s', $event_date),
                        'id'     => $event_data,
                        'points' => $achievement_info['points'],
                        'sort'   => $sort
                    );
                    $achievement_info['desc'] = str_replace("'", "\'", $achievement_info['desc']);
                    $achievement_info['title'] = str_replace("'", "\'", $achievement_info['title']);
                    $tooltip = sprintf('&lt;div class=\&quot;myTable\&quot;\&gt;&lt;img src=\&quot;wow-icons/_images/51x51/%s.jpg\&quot; align=\&quot;left\&quot; class=\&quot;ach_tooltip\&quot; /\&gt;&lt;strong style=\&quot;color: #fff;\&quot;\&gt;%s (%d)&lt;/strong\&gt;&lt;br /\&gt;%s', $achievement_info['icon'], $achievement_info['title'], $achievement_info['points'], $achievement_info['desc']);
                    if($achievement_info['categoryId'] == 81) {
                        // Feats of strenght
                        $feed_data[$i]['title'] = sprintf('%s [%s].', $_strings[14], $achievement_info['title']);
                        $feed_data[$i]['desc'] = sprintf('%s [<a class="achievement staticTip" href="character-achievements.xml?r=%s&amp;cn=%s" onMouseOver="setTipText(\'%s\')">%s</a>]', $_strings[14], urlencode($this->GetRealmName()), urlencode($this->name), $tooltip, $achievement_info['title']);
                    }
                    else {
                        $points_string = sprintf($_strings[18], $achievement_info['points']);
                        $feed_data[$i]['title'] = sprintf('%s [%s].', $_strings[13], $achievement_info['title']);
                        $feed_data[$i]['desc'] = sprintf('%s [<a class="achievement staticTip" href="character-achievements.xml?r=%s&amp;cn=%s" onMouseOver="setTipText(\'%s\')">%s</a>] %s.', $_strings[13], urlencode($this->GetRealmName()), urlencode($this->name), $tooltip, $achievement_info['title'], $points_string);
                    }
                    $feed_data[$i]['tooltip'] = $tooltip;
                    break;
                case TYPE_ITEM_FEED:
                    $item = Armory::$wDB->selectRow("SELECT `displayid`, `InventoryType`, `name`, `Quality` FROM `item_template` WHERE `entry`=%d LIMIT 1", $event_data);
                    if(!$item) {
                        continue;
                    }
                    $item_icon = Armory::$aDB->selectCell("SELECT `icon` FROM `armory_icons` WHERE `displayid`=%d", $item['displayid']);
                    // Is item equipped?
                    if($this->IsItemEquipped($event_data)) {
                        $item_slot = $item['InventoryType'];
                    }
                    else {
                        $item_slot = -1;
                    }
                    $feed_data[$i]['event'] = array(
                        'type' => 'loot',
                        'date' => $date_string,
                        'time' => date('H:i:s', $event_date),
                        'icon' => $item_icon,
                        'id'   => $event_data,
                        'slot' => $item_slot,
                        'sort' => $sort,
                    );
                    if($this->locale != 'en_gb' && $this->locale != 'en_us') {
                        $item['name'] = Items::GetItemName($event_data);
                    }
                    $feed_data[$i]['title'] = sprintf('%s [%s].', $_strings[15], $item['name']);
                    $feed_data[$i]['desc'] = sprintf('%s <a class="staticTip itemToolTip" id="i=%d" href="item-info.xml?i=%d"><span class="stats_rarity%d">[%s]</span></a>.', $_strings[15], $event_data, $event_data, $item['Quality'], $item['name']);
                    $feed_data[$i]['tooltip'] = $feed_data[$i]['desc'];
                    break;
                case TYPE_BOSS_FEED:
                    // Get criterias
                    $achievement_ids = array();
                    $dungeonDifficulty = $event['difficulty'];
                    if($dungeonDifficulty <= 0) {
                        $DifficultyEntry = $event_data;
                    }
                    else {
                        // Search for difficulty_entry_X
                        $DifficultyEntry = Armory::$wDB->selectCell("SELECT `entry` FROM `creature_template` WHERE `difficulty_entry_%d` = %d", $event['difficulty'], $event_data);
                        if(!$DifficultyEntry || $DifficultyEntry == 0) {
                            $DifficultyEntry = $event['data'];
                        }
                    }
                    $criterias = Armory::$aDB->select("SELECT `referredAchievement` FROM `armory_achievement_criteria` WHERE `data` = %d", $DifficultyEntry);
                    if(!$criterias || !is_array($criterias)) {
                        continue;
                    }
                    foreach($criterias as $crit) {
                        $achievement_ids[] = $crit['referredAchievement'];
                    }
                    if(!$achievement_ids || !is_array($achievement_ids)) {
                        continue;
                    }
                    $achievement = Armory::$aDB->selectRow("SELECT `id`, `name_%s` AS `name` FROM `armory_achievement` WHERE `id` IN (%s) AND `flags`=1 AND `dungeonDifficulty`=%d", $this->locale, $achievement_ids, $dungeonDifficulty);
                    if(!$achievement || !is_array($achievement)) {
                        continue;
                    }
                    $feed_data[$i]['event'] = array(
                        'type' => 'bosskill',
                        'date'   => $date_string,
                        'time'   => date('H:i:s', $event_date),
                        'id'     => $event_data,
                        'points' => 0,
                        'sort'   => $sort
                    );
                    $feed_data[$i]['title'] = sprintf('%s [%s] %d %s', $_strings[16], $achievement['name'], $event['counter'], $_strings[17]);
                    $feed_data[$i]['desc'] = sprintf('%d %s.', $event['counter'], $achievement['name']);
                    $feed_data[$i]['tooltip'] = $feed_data[$i]['desc'];
                    break;
                default:
                    continue;
                    break;
            }
            $i++;
            $currently_added++;
        }
        return $feed_data;
    }

    /**
     * Returns array with data for item placed in $slot['slot']
     * @category Character class
     * @access   public
     * @param    int $slot
     * @return   array
     **/
    public function GetCharacterItemInfo($slot_id) {
        global $WSDB, $aDB;

        if(!$this->guid) {
            //Armory::Log()->writeError('%s : player guid not provided', __METHOD__);
            return false;
        }
        if(!isset($this->m_items[$slot_id])) {
            self::debug("Slot ".$slot_id." is empty.");
            return false;
        }
        $item = $this->m_items[$slot_id];

        $gems = array(
            'g0' => $item->GetSocketInfo(1),
            'g1' => $item->GetSocketInfo(2),
            'g2' => $item->GetSocketInfo(3)
        );

        $durability = $item->GetItemDurability();
        $item_data = $WSDB->selectRow("SELECT `name`, `displayid`, `ItemLevel`, `InventoryType`, `itemset`, `Quality`,
        	socketColor_1, socketColor_2, socketColor_3
        	FROM `item_template`
        	WHERE `entry`=?d", $item->GetEntry());
        $enchantment = $item->GetEnchantmentId();
        $originalSpell = 0;
        $socketCount = 0;
        $enchItemData = array();
        $enchItemDisplayId = 0;

        if($item->GetEntry() == 52572){
            debug("XXX");
        }

        if($enchantment > 0 && $this->mode == "advanced") {
            debug("Enc",$enchantment);
            $spellRows = $aDB->select("SELECT `id`,`SpellName_en_gb` FROM `armory_spell` WHERE `EffectMiscValue_1`=?d", $enchantment);
            if(count($spellRows) > 1){
                foreach($spellRows as $spellRow){
                    if(substr_count($spellRow["SpellName_en_gb"],"Enchant") > 0)
                        $originalSpell = $spellRow["id"];
                }
            }
            else{
                foreach($spellRows as $spellRow)
                    $originalSpell = $spellRow["id"];
            }
            if($originalSpell > 0) {
                debug("Spell",$originalSpell);
                $enchItemData = $WSDB->selectRow("SELECT `entry`, name, `displayid` FROM `item_template` WHERE `spellid_1`=?d LIMIT 1", $originalSpell);
                if($enchItemData) {
                    // Item
                    //$enchItemDisplayId = $aDB->selectCell("SELECT `icon` FROM `armory_icons` WHERE `displayid`=?d", $enchItemData['displayid']);
                    $enchName = $enchItemData["name"];
                    if(substr_count($enchName, "Rolle") > 0){
                        debug($enchName);
                        $enchName = preg_replace("/.*Rolle([^-]+)(-|-)/","",$enchName);
                    }
                    $enchItemData['name'] = trim($enchName);

                }
                else {
                    // Spell
                    debug("Spell");
                    $spellEnchData = Item::GenerateEnchantmentSpellData($originalSpell);
                }
            }
        }

        $paramData = array();
        if(!empty($enchantment)){
            $paramData["e"] = $enchantment;
        }
        if(!empty($durability['current'])){
            $paramData["d"] = $durability['current'];
        }
        if(!empty($gems["g0"]))
            $paramData["g0"] = $gems["g0"]["item"];
        if(!empty($gems["g1"]))
            $paramData["g1"] = $gems["g1"]["item"];
        if(!empty($gems["g2"]))
            $paramData["g2"] = $gems["g2"]["item"];
        if(!empty($gems["g2"]))
            $paramData["g2"] = $gems["g2"]["item"];

        //e=4209&amp;g0=68778&amp;g1=52211&amp;re=140&amp;set=71047,71045&amp;d=87


        $item_info = array(
            "empty"                  => false,
            'displayInfoId'          => $item_data['displayid'],
            'durability'             => $durability['current'],
            'inventoryType'          => $item_data['InventoryType'],
            'icon'                   => Item::getItemIcon($item->GetEntry(), $item_data['displayid']),
            'id'                     => $item->GetEntry(),
            'level'                  => $item_data['ItemLevel'],
            'set'                    => $item_data['itemset'],
            'maxDurability'          => $durability['max'],
            'name'                   => ($this->locale == 'en_gb' || $this->locale == 'en_us') ? $item_data['name'] : GetItemName($item->GetEntry()),
            'permanentenchant'       => $enchantment,
            'pickUp'                 => 'PickUpLargeChain',
            'putDown'                => 'PutDownLArgeChain',
            'randomPropertiesId'     => 0,
            'rarity'                 => $item_data['Quality'],
            'seed'                   => $item->GetGUID(),
            'gemCount'		         => 0,
            'socketCount'            => $socketCount,
            'slot'                   => $slot_id,
            "slot_style"             => $this->GetCharacterEquipStyle($slot_id),
            "paramData"                 => $paramData,
        );
        // Sockets
        for($i = 0; $i < 3; $i++) {
            if($item_data["socketColor_".($i+1)] > 0){
                //debug("socket $i Color");
                $item_info["socketCount"]++;
                $item_info['socket' . $i . 'Color'] = $item_data["socketColor_".($i+1)];
            }
            else{
                //debug("socketColor_".($i+1));
            }
            self::debug("socket ".$i+1, $item_data["socketColor_".$i+1]);
        }
        //if($item_data["InventoryType"] == INV_TYPE_WAIST){
        debug("item_info",$item_info);
        //debug("Waist: info",$item_info);
        //}
        if(is_array($gems)) {
            for($i = 0; $i < 3; $i++) {
                if($gems['g' . $i]['item'] > 0) {
                    $item_info["gemCount"]++;
                    $item_info['gem' . $i . 'Id'] = $gems['g' . $i]['item'];
                    $item_info['gem' . $i . 'Icon'] = $gems['g' . $i]['icon'];
                    $item_info['gem' . $i . 'Color'] = $gems['g' . $i]['color'];
                    $item_info['gem' . $i . 'Name'] = $gems['g' . $i]['name'];

                    if($item_data["socketColor_".($i+1)] > 0){
                        $item_info['gem' . $i . 'SocketColor'] = $item_data["socketColor_".($i+1)];
                    }
                    else{
                        $item_info['gem' . $i . 'SocketColor'] = 14;
                    }

                    GetItemIcon($gems['g' . $i]['icon'], 18);
                }
                self::debug("socket ".$i+1, $item_data["socketColor_".$i+1]);

            }
        }
        debug($item_info);
        if(is_array($enchItemData) && isset($enchItemData['entry'])) {
            $item_info['permanentEnchantItemId'] = $enchItemData['entry'];
            $item_info['permanentEnchantSpellName'] = $enchItemData['name'];
        }
        elseif(isset($spellEnchData) && is_array($spellEnchData) && isset($spellEnchData['name'])) {
            $item_info['permanentEnchantItemId'] = $spellEnchData['item'];
            $item_info['permanentEnchantSpellName'] = $spellEnchData['name'];
        }


        //self::debug("ii",$item_info);
        return $item_info;
    }

    /**
     * Returns InventoryType ID by slot id
     * @category Items class
     * @access   public
     * @param    int $slotId
     * @return   int
     **/
    function GetInventoryTypeBySlotId($slotId) {
        switch($slotId) {
            case INV_HEAD:
                $slot_id = INV_TYPE_HEAD;
                break;
            case INV_NECK:
                $slot_id = INV_TYPE_NECK;
                break;
            case INV_SHOULDER:
                $slot_id = INV_TYPE_SHOULDER;
                break;
            case INV_BACK:
                $slot_id = INV_TYPE_BACK;
                break;
            case INV_CHEST:
                $slot_id = INV_TYPE_CHEST;
                break;
            case INV_SHIRT:
                $slot_id = INV_TYPE_SHIRT;
                break;
            case INV_TABARD:
                $slot_id = INV_TYPE_TABARD;
                break;
            case INV_BRACERS:
                $slot_id = INV_TYPE_WRISTS;
                break;
            case INV_GLOVES:
                $slot_id = INV_TYPE_HANDS;
                break;
            case INV_BELT:
                $slot_id = INV_TYPE_WAIST;
                break;
            case INV_LEGS:
                $slot_id = INV_TYPE_LEGS;
                break;
            case INV_BOOTS:
                $slot_id = INV_TYPE_FEET;
                break;
            case INV_RING_1:
            case INV_RING_2:
                $slot_id = INV_TYPE_FINGER;
                break;
            case INV_TRINKET_1:
            case INV_TRINKET_2:
                $slot_id = INV_TYPE_TRINKET;
                break;
            case INV_MAIN_HAND:
                $slot_id = INV_TYPE_MAINHAND;
                break;
            case INV_OFF_HAND:
                $slot_id = INV_TYPE_OFFHAND;
                break;
            case INV_RANGED_RELIC:
                switch($this->class){
                    case CLASS_PALADIN:
                    case CLASS_DRUID:
                    case CLASS_DK:
                    case CLASS_SHAMAN:
                        $slot_id = INV_TYPE_RELIC; break;
                    default:
                        $slot_id = INV_TYPE_RANGED; break;
                }
                break;
            default:
                $slot_id = 0;
                break;
        }
        return $slot_id;
    }
    /**
     * Checks is item with entry $itemID currently equipped on character.
     * @category Character class
     * @access   public
     * @param    int $itemID
     * @return   bool
     **/
    public function IsItemEquipped($itemID) {
        if(!is_array($this->equipmentCache)) {
            return false;
        }
        if(in_array($itemID, $this->equipmentCache)) {
            return true;
        }
        return false;
    }

    /**
     * Returns currently equipped item's GUID (depends on $slot_id)
     * @category Character class
     * @access   public
     * @param    string $slot_id
     * @return   int
     **/
    public function GetEquippedItemGuidBySlot($slot_id) {
        if(!$this->guid) {
            //Armory::Log()->writeError('%s : player guid not provided', __METHOD__);
            return 0;
        }
        switch($slot_id) {
            case 'head':
                return $this->GetDataField(PLAYER_SLOT_ITEM_HEAD);
                break;
            case 'neck':
                return $this->GetDataField(PLAYER_SLOT_ITEM_NECK);
                break;
            case 'shoulder':
                return $this->GetDataField(PLAYER_SLOT_ITEM_SHOULDER);
                break;
            case 'shirt':
                return $this->GetDataField(PLAYER_SLOT_ITEM_SHIRT);
                break;
            case 'chest':
                return $this->GetDataField(PLAYER_SLOT_ITEM_CHEST);
                break;
            case 'belt':
                return $this->GetDataField(PLAYER_SLOT_ITEM_BELT);
                break;
            case 'legs':
                return $this->GetDataField(PLAYER_SLOT_ITEM_LEGS);
                break;
            case 'wrist':
                return $this->GetDataField(PLAYER_SLOT_ITEM_WRIST);
                break;
            case 'boots':
                return $this->GetDataField(PLAYER_SLOT_ITEM_FEET);
                break;
            case 'gloves':
                return $this->GetDataField(PLAYER_SLOT_ITEM_GLOVES);
                break;
            case 'ring1':
                return $this->GetDataField(PLAYER_SLOT_ITEM_FINGER1);
                break;
            case 'ring2':
                return $this->GetDataField(PLAYER_SLOT_ITEM_FINGER2);
                break;
            case 'trinket1':
                return $this->GetDataField(PLAYER_SLOT_ITEM_TRINKET1);
                break;
            case 'trinket2':
                return $this->GetDataField(PLAYER_SLOT_ITEM_TRINKET2);
                break;
            case 'back':
                return $this->GetDataField(PLAYER_SLOT_ITEM_BACK);
                break;
            case 'stave':
            case 'mainhand':
                return $this->GetDataField(PLAYER_SLOT_ITEM_MAIN_HAND);
                break;
            case 'offhand':
                return $this->GetDataField(PLAYER_SLOT_ITEM_OFF_HAND);
                break;
            case 'gun':
            case 'relic':
                return $this->GetDataField(PLAYER_SLOT_ITEM_RANGED);
                break;
            case 'tabard':
                return $this->GetDataField(PLAYER_SLOT_ITEM_TABARD);
                break;
            default:
                //Armory::Log()->writeLog('%s : wrong item_slot: %s', __METHOD__, $slot_id);
                return 0;
                break;
        }
    }

    /**
     * Returns database handler instance
     * @access   public
     * @return   object
     **/
    public function GetDB() {
        return $this->portalDb;
    }

    /**
     * Returns array with player model scales according with player race
     * @access   public
     * @return   array
     **/
    public function GetModelData() {
        if(!$this->guid) {
            //Armory::Log()->writeError('%s : player guid not provided', __METHOD__);
            return false;
        }
        switch($this->race) {
            case RACE_HUMAN:
                if($this->gender == 1) {
                    return array('baseY' => 1.05, 'facedY' => 1.625, 'scale' => 1.65);
                }
                return array('baseY' => 1.05, 'facedY' => 1.5, 'scale' => 1.65);
                break;
            case RACE_ORC:
                if($this->gender == 1) {
                    return array('baseY' => 1.05, 'facedY' => 1.7, 'scale' => 1.7);
                }
                return array('baseY' => 1.25, 'facedY' => 1.7, 'scale' => 1.3);
                break;
            case RACE_DWARF:
                return array('baseY' => 0.75, 'facedY' => 1.45, 'scale' => 1.75);
                break;
            case RACE_NIGHTELF:
                if($this->gender == 1) {
                    return array('baseY' => 1.15, 'facedY' => 2.0, 'scale' => 1.5);
                }
                return array('baseY' => 1.25, 'facedY' => 2.0, 'scale' => 1.4);
                break;
            case RACE_UNDEAD:
                return array('baseY' => 0.95, 'facedY' => 1.5, 'scale' => 1.8);
                break;
            case RACE_TAUREN:
                return array('baseY' => 1.05, 'facedY' => 1.7, 'scale' => 1.55);
                break;
            case RACE_GNOME:
                return array('baseY' => 0.55, 'facedY' => 0.7, 'scale' => 2.7);
                break;
            case RACE_TROLL:
                return array('baseY' => 1.2, 'facedY' => 1.9, 'scale' => 1.45);
                break;
            case RACE_BLOODELF:
                if($this->gender == 1) {
                    return array('baseY' => 0.97, 'facedY' => 1.6, 'scale' => 1.7);
                }
                return array('baseY' => 1.05, 'facedY' => 1.75, 'scale' => 1.7);
                break;
            case RACE_DRAENEI:
                return array('baseY' => 1.275, 'facedY' => 1.9, 'scale' => 1.375);
                break;
            default:
                //Armory::Log()->writeError('%s : wrong character raceId: %d (player: %s, realm: %s)', __METHOD__, $this->race, $this->name, $this->realmName);
                return false;
                break;
        }
    }

    /**
     * Checks for spell ID in character's spellbook
     * @access   public
     * @param    int $spell_id
     * @return   bool
     **/
    public function HasSpell($spell_id) {
        return (bool) $this->portalDb->selectCell("SELECT 1 FROM `character_spell` WHERE `spell`=%d AND `guid`=%d AND `active`=1 AND `disabled`=0 LIMIT 1", $spell_id, $this->guid);;
    }

    /**
     * Checks for talent ID in active or all specs
     * @access   public
     * @param    int $talent_id
     * @param    bool $active_spec = true
     * @param    int $rank = -1
     * @return   bool
     **/
    public function HasTalent($talent_id, $active_spec = true, $rank = -1) {
        switch($this->GetServerType()) {
            case SERVER_MANGOS:
                $sql_data = array(
                    'activeSpec' => array(
                        sprintf('SELECT `current_rank` + 1 FROM `character_talent` WHERE `talent_id`=%d AND `guid`=%%d AND `spec`=%%d', $talent_id),
                        sprintf('SELECT 1 FROM `character_talent` WHERE `talent_id`=%d AND `guid`=%%d AND `spec`=%%d AND `current_rank`=%d', $talent_id, $rank)
                    ),
                    'spec' => array(
                        sprintf('SELECT `current_rank` + 1 FROM `character_talent` WHERE `talent_id`=%d AND `guid`=%%d', $talent_id),
                        sprintf('SELECT 1 FROM `character_talent` WHERE `talent_id`=%d AND `guid`=%%d AND `current_rank`=%d', $talent_id, $rank)
                    )
                );
                break;
            case SERVER_TRINITY:
                $talent_spells = Armory::$aDB->selectRow("SELECT `Rank_1`, `Rank_2`, `Rank_3`, `Rank_4`, `Rank_5` FROM `armory_talents` WHERE `TalentID` = %d LIMIT 1", $talent_id);
                if(!$talent_spells || !is_array($talent_spells) || ($rank >= 0 && !isset($talent_spells['Rank_' . $rank + 1]))) {
                    //Armory::Log()->writeError('%s : talent ranks for talent %d was not found in DB!', __METHOD__, $talent_id);
                    return false;
                }
                $sql_data = array(
                    'activeSpec' => array(
                        sprintf('SELECT `spell` FROM `character_talent` WHERE `spell` IN (%s) AND `guid`=%%d AND `spec`=%%d LIMIT 1', $talent_spells['Rank_1'] . ', ' . $talent_spells['Rank_2'] . ', ' . $talent_spells['Rank_3'] . ', ' . $talent_spells['Rank_4'] . ', ' . $talent_spells['Rank_5']),
                        sprintf('SELECT 1 FROM `character_talent` WHERE `spell`=%d AND `guid`=%%d AND `spec`=%%d', $rank == -1 ? $talent_spells['Rank_1'] : $talent_spells['Rank_' . ($rank + 1)])
                    ),
                    'spec' => array(
                        sprintf('SELECT `spell` FROM `character_talent` WHERE `spell` IN (%s) AND `guid`=%%d LIMIT 1', $talent_spells['Rank_1'] . ', ' . $talent_spells['Rank_2'] . ', ' . $talent_spells['Rank_3'] . ', ' . $talent_spells['Rank_4'] . ', ' . $talent_spells['Rank_5']),
                        sprintf('SELECT 1 FROM `character_talent` WHERE `spell`=%d AND `guid`=%%d', $rank == -1 ? $talent_spells['Rank_1'] : $talent_spells['Rank_' . ($rank + 1)])
                    )
                );
                break;
            default:
                //Armory::Log()->writeError('%s : unknown server type %d!', __METHOD__, $this->GetServerType());
                return false;
                break;
        }

        if($active_spec) {
            if($rank == -1) {
                $has = $this->portalDb->selectCell($sql_data['activeSpec'][0], $this->guid, $this->activeSpec);
            }
            elseif($rank >= 0) {
                $has = $this->portalDb->selectCell($sql_data['activeSpec'][1], $this->guid, $this->activeSpec);
            }
        }
        else {
            if($rank == -1) {
                $has = $this->portalDb->selectCell($sql_data['spec'][0], $this->guid);
            }
            elseif($rank >= 0) {
                $has = $this->portalDb->selectCell($sql_data['spec'][1], $this->guid);
            }
        }
        if($this->GetServerType() == SERVER_TRINITY && $rank == -1 && $has != false) {
            for($i = 0; $i < 5; $i++) {
                if(isset($talent_spells['Rank_' . ($i + 1)]) && $talent_spells['Rank_' . ($i + 1)] == $has) {
                    return $i;
                }
            }
        }
        if($this->GetServerType() == SERVER_MANGOS && $has) {
            return $has - 1;
        }
        return $has;
    }

    /**
     * Returns talent rank by talent ID (if player has this talent)
     * @access   public
     * @param    int $talent_id
     * @param    bool $active_spec = true
     * @return   int
     **/
    public function GetTalentRankByID($talent_id, $active_spec = true) {
        return $this->HasTalent($talent_id, $active_spec);
    }

    /**
     * Returns skill value by skill ID (if player has this skill)
     * @access   public
     * @param    int $skill
     * @return   int
     **/
    public function GetSkillValue($skill) {
        return $this->portalDb->selectCell("SELECT `value` FROM `character_skills` WHERE `guid`=%d AND `skill`=%d", $this->guid, $skill);
    }

    /**
     * Returns reputation value with selected faction. If $returnAsRank == true, function will return reputation rank ID.
     * @access   public
     * @param    int $faction_id
     * @param    bool $returnAsRank = false
     * @return   int
     **/
    public function GetReputationWith($faction_id, $returnAsRank = false) {
        $standing = $this->portalDb->selectCell("SELECT `standing` FROM `character_reputation` WHERE `faction`=%d AND `guid`=%d", $faction_id, $this->guid);
        if($returnAsRank == true) {
            $PointsInRank = array(36000, 3000, 3000, 3000, 6000, 12000, 21000, 1000);
            $RepRanks  = array(REP_HATED, REP_HOSTILE, REP_UNFRIENDLY, REP_NEUTRAL, REP_FRIENDLY, REP_HONORED, REP_REVERED, REP_EXALTED);
            $limit = 43000;
            for($i = 7; $i >= 0; $i--) {
                $limit -= $PointsInRank[$i];
                if($standing >= $limit) {
                    return $RepRanks[$i];
                }
            }
        }
        return $standing;
    }

    /**
     * Load character inventory (equipped items only).
     * @access   private
     * @return   bool
     **/
    private function LoadInventory() {
        global $CHDB;
        if(!$this->guid) {
            self::debug("Player is not defined");
            return false;
        }
        $inv = $CHDB->select("SELECT `item`, `slot`, `bag` FROM `character_inventory` WHERE `bag` = 0 AND `slot` < ?d AND `guid` = ?d", INV_MAX, $this->guid);
        if(!$inv) {
            self::debug("Char DB",$CHDB);
            return false;
        }
        foreach($inv as $item) {
            $item['enchants'] = $this->GetCharacterEnchant($item['slot']);
            $this->m_items[$item['slot']] = new Item();
            $this->m_items[$item['slot']]->LoadFromDB($item, $this->guid);
            // Do not load itemproto from here!
        }
        return true;
    }

    /**
     * Load equipped item from character_inventory (by SLOT ID)
     * @access   public
     * @param    int $slotID
     * @param    bool $addToInventoryStorage = true
     * @return   mixed
     **/
    private function LoadItemFromDBBySlotID($slotID, $addToInventoryStorage = true) {
        global $CHDB;
        if(!$this->guid) {
            //Armory::Log()->writeError('%s : player guid is not defined.', __METHOD__);
            return false;
        }
        $inv = $CHDB->selectRow("SELECT `item`, `slot`, `bag` FROM `character_inventory` WHERE `bag` = 0 AND `slot` = ?d AND `guid` = ?d LIMIT 1", $slotID, $this->guid);;
        if(!$inv) {
            return false;
        }
        if($addToInventoryStorage == true) {
            $inv['enchants'] = $this->GetCharacterEnchant(Utils::GetItemSlotTextBySlotId($item['slot']));
            $this->m_items[$inv['slot']] = new Item();
            $this->m_items[$inv['slot']]->LoadFromDB($inv, $this->guid);
            // Do not load itemproto from here!
        }
        return $inv;
    }

    /**
     * Return Item by SlotID
     * @access   public
     * @param    int $slot
     * @return   object
     **/
    public function GetItemBySlot($slot) {
        if(!isset($this->m_items[$slot])) {
            //Armory::Log()->writeError('%s : slot %d is empty (character: %s, GUID: %d).', __METHOD__, $slot, $this->name, $this->guid);
            return null;
        }
        elseif(!is_object($this->m_items[$slot])) {
            // Try to reload item
            $item_temporary = $this->LoadItemFromDBBySlotID($slot, true);
            if($item_temporary->IsCorrect()) {
                return $item_temporary;
            }
            //Armory::Log()->writeError('%s : slot %d is not an object (character: %s, GUID: %d).', __METHOD__, $slot, $this->name, $this->guid);
            return null;
        }
        elseif(!$this->m_items[$slot]->IsCorrect()) {
            //Armory::Log()->writeError('%s : item in slot %d has wrong data (Item::IsCorrect() fail)', __METHOD__, $slot);
            return null;
        }
        return $this->m_items[$slot];
    }

    /**
     * Return item handler by item entry (from item storage)
     * Note: m_items must be initialized in Characters::BuildCharacter()!
     * @access   public
     * @param    int $entry
     * @return   object
     **/
    public function GetItemByEntry($entry) {
        if(!is_array($this->m_items)) {
            return false;
        }
        foreach($this->m_items as $mItem) {
            if($mItem->GetEntry() == $entry) {
                return $mItem;
            }
        }
        return false;
    }

    /**** DEVELOPMENT SECTION ****/

    /**
     * Checks if player has any active pet
     * @access   public
     * @return   bool
     **/
    public function IsHaveAnyPet() {
        global $CHDB;
        if(!$this->IsCanHavePet()) {
            return false;
        }
        return $CHDB->selectCell("SELECT 1 FROM `character_pet` WHERE `owner` = ?d AND `PetType` = 1", $this->GetGUID());
    }

    /**
     * Checks if player mana user
     * @access   private
     * @return   bool
     **/
    public function IsManaUser() {
        if(!in_array($this->class, array(CLASS_DK, CLASS_ROGUE, CLASS_WARRIOR/*, CLASS_HUNTER*/))) {
            return true;
        }
        return false;
    }

    /**
     * Checks if player can has pet.
     * Note: self::CalculatePetTalents() must has internal check (CLASS_HUNTER)
     * @access   private
     * @return   bool
     **/
    private function IsCanHavePet() {
        if(in_array($this->class, array(CLASS_DK, CLASS_HUNTER, CLASS_WARLOCK))) {
            return true;
        }
        return false;
    }

}