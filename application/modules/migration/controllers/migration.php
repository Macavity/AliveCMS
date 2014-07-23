<?php

/**
 * Class Migration
 *
 * @property Migration_model $migration_model
 * @property Realmcopy_model $realmcopy
 */
class Migration extends MY_Controller
{
    
    private $cacheActive = FALSE;
    private $cacheId = "";
    
    private $theme_path = "";
    private $style_path = "";
    private $image_path = "";

    private $races = array();
    private $classes = array();


    /**
     * Contains the template variables
     * @type {Array}
     */
    private $pageData = array();
    
    public function __construct()
    {
        //debug("Server.__construct");
        
        parent::__construct();

        $this->load->helper(array('url','form'));
        $this->load->config('migration_config');

        $this->load->model("migration_model");
        $this->load->model('realmcopy_model', 'realmcopy');

        // Migration (The CI Module)
        $this->load->library('migration');
        $this->migration->current();

        //$this->template->enable_profiler(TRUE);

        $this->theme_path = base_url().APPPATH.$this->template->theme_path;
        $this->style_path = $this->theme_path."css/";
        $this->image_path = $this->theme_path."images/";

        $this->template->setJsAction("migration");
        
        $this->pageData = array_merge($this->pageData, array(
            "theme_path" => $this->theme_path,
            "module" => "migration",
            "extra_css" => "",
        ));

    }

    /**
     * Transferanleitung
     */
    public function index()
    {
        requirePermission("view");

        //debug("Server ($page)");
        $this->template->addBreadcrumb("Transferanleitung", site_url(array("migration", "index")));

        // Set the page title
        $this->template->setTitle("Transferanleitung");
        $this->template->setSectionTitle("Transferanleitung");

        $out = $this->template->loadPage("migration_index.tpl");
            
        $this->template->view($out);
    }

    public function starter(){

        // Has to be logged in.
        $this->user->userArea();

        requirePermission("view");
        /**
         * @TODO Remove later
         */
        requirePermission("canAdministrate");

        $this->template->addBreadcrumb("Starter Paket", site_url(array("migration", "starter")));

        $this->template->setTitle("Starter Paket");
        $this->template->setSectionTitle("Starter Paket");

        $hasError = false;
        $errorMessages = array();

        $activeCharGuid = $this->user->getActiveCharacter();

        $accountMigrationCount = count($this->migration_model->getAccountStarterPackage($this->user->getId()));

        if($accountMigrationCount > $this->config->item("starter_package_max_per_account")){
            $hasError = true;
            $errorMessages[] = "Du hast bereits das Limit an möglichen Starter Paketen (".$this->config->item("starter_package_max_per_account").") erschöpft.";
        }

        $classId = 0;
        $classLabel = "";
        $talentTrees = array();
        $itemData = array();

        $starterBag = 21841;    // 4x
        $starterMoney = 20000000;
        //.char customize, .cheat taxi on,
        $starterSpells = array(33388,33391,34090);

        $talentTreeLabels = array(
            'melee' => 'Nahkampf',
            'tank' => 'Tank',
            'range' => 'Fernkampf',
            'heal' => 'Heilung'
        );

        if($activeCharGuid > 0){
            $activeChar = $this->user->getActiveCharacterData();

            if($this->user->getActiveRealmId() != 1){
                $hasError = true;
                $errorMessages[] = "Du kannst auf diesem Realm kein Starterpaket erwerben.";
            }

            $charLevel = $activeChar['level'];
            $classId = $activeChar['class'];
            $classLabel = $this->realms->getClass($classId, $activeChar['gender']);

            switch($classId){
                case CLASS_DK:
                    $talentTrees['melee'] = array(28225,27771,27403,27497,27870,28288,28393,28375,28371,28318,34789,27551,27460,27904,27416);
                    $talentTrees['tank'] = array(28350,27803,28205,27475,27977,27813,28256,34706,27672,27770,27551,27459,28367,27904,34473);
                    break;
                case CLASS_DRUID:
                    $talentTrees['range'] = array(28348,27737,28202,27468,27873,28254,29330,29322,29288,30787,28269,28398,34707,27683,27483,28190);
                    $talentTrees['melee'] = array(28224,27797,28264,27531,27837,30707,28288,28343,29329,31464,32665,27712,27453,27925,31547);
                    $talentTrees['heal'] = array(28348,27737,28202,27468,27873,28254,29330,29322,29288,30787,28269,28398,34707,27683,27483,28190);
                    break;
                case CLASS_WARLOCK:
                    $talentTrees['range'] = array(28415,27778,28232,27537,27948,28254,29288,29320,28190,31465,31461,28386,27683,27451,30787,28412);
                    break;
                case CLASS_HUNTER:
                    $talentTrees['range'] = array(28275,27801,28228,27474,27874,27865,28384,28263,28263,28288,28343,31462,32665,27453,27925,31547,27987);
                    break;
                case CLASS_WARRIOR:
                    $talentTrees['melee'] = array(28225,27771,27403,27497,27870,28288,28393,28393,28375,28371,28318,34789,27551,27460,27904,27416);
                    $talentTrees['tank'] = array(28350,27803,28205,27475,27977,27813,28256,34706,27672,27770,27551,27459,27490,31491,27904,34473);
                    break;
                case CLASS_MAGE:
                    $talentTrees['range'] = array(28413,27775,28230,27536,27875,28254,29288,29320,28190,31465,31461,28386,27683,27451,30787,28412);
                    break;
                case CLASS_PALADIN:
                    $talentTrees['heal'] = array(28285,27739,28203,27535,27839,37747,29333,29284,31465,28394,32778,27548,27683,28190,27899,27489);
                    $talentTrees['tank'] = array(28350,27803,28205,27475,27977,27813,28256,34706,27672,27770,27551,27459,27490,31491,27904,34473);
                    $talentTrees['melee'] = array(28225,27771,27403,27497,27870,28288,28393,28375,28371,28318,34789,27551,27460,27904,27416);
                    break;
                case CLASS_PRIEST:
                    $talentTrees['heal'] = array(28413,27775,28230,27536,27875,28254,29288,29320,28190,31465,31461,28386,27683,27451,30787,28412);
                    $talentTrees['range'] = array(28413,27775,28230,27536,27875,28254,29288,29320,28190,31465,31461,28386,27683,27451,30787,28412);
                    break;
                case CLASS_SHAMAN:
                    $talentTrees['range'] = array(28349,27802,28231,27510,27909,29284,29320,28254,31465,30787,27683,27743,28190,27845,28194,27772);
                    $talentTrees['melee'] = array(28192,27713,28401,27528,27936,27865,28384,28263,28263,28288,28343,31462,32665,27453,27925,31547);
                    $talentTrees['heal'] = array(28349,27802,28231,27510,27909,29284,29320,28254,31465,30787,27683,27743,28190,27845,28194,27772);
                    break;
                case CLASS_ROGUE:
                    $talentTrees['melee'] = array(28414,27776,28204,27509,27908,30707,28226,28226,28286,28288,28343,31464,32532,27453,27925,27416,27878);
                    break;
                default:
                    //
            }

        }

        $out = $this->template->loadPage("migration_starter.tpl", compact(
            'activeCharGuid',
            'hasError', 'errorMessages',
            'classId', 'classLabel',
            'talentTreeLabels', 'talentTrees',
            'starterBag', 'starterMoney', 'starterSpells',
            'itemData'
        ));

        $this->template->view($out);
    }

    /**
     * Realmkopie - Liste
     */
    public function realmcopy()
    {
        $this->template->addBreadcrumb("Realmkopie", site_url(array("migration", 'realmcopy')));

        $this->template->setTitle("Realmkopie");
        $this->template->setSectionTitle("Realmkopie");

        if(hasPermission("canCopyCharacter") == FALSE){
            $this->denied("norights");
            exit;
        }

        $this->template->addBreadcrumb('Charakterliste', site_url(array('migration', 'realmcopy')));

        $realmChars = $this->realmcopy->getRealmCharacters($this->user->getId());

        $templateData = array(
            'realmChars' => $realmChars,
            'theme_path' => base_url().APPPATH.$this->template->theme_path,
        );

        $out = $this->template->loadPage("realmcopy_list.tpl", $templateData);

        $this->template->view($out);

    }

    /**
     * @param $sourceRealmId
     * @param $sourceGuid
     */
    public function confirm_copy($sourceRealmId, $sourceGuid)
    {
        $this->template->addBreadcrumb("Realmkopie", site_url(array("migration", 'realmcopy')));

        $targetRealmId = $this->user->getActiveRealmId();
        $targetGuid = $this->user->getActiveCharacter();

        // Check all requirements
        $this->realmcopy_requirements($sourceRealmId, $sourceGuid, $targetRealmId, $targetGuid);

        $this->template->setTitle("Realmkopie");
        $this->template->setSectionTitle("Realmkopie");

        $this->template->addBreadcrumb('Charakterliste', site_url(array('migration', 'realmcopy')));
        $this->template->addBreadcrumb('Daten bestätigen');

        $sourceRealm = $this->realms->getRealm($sourceRealmId);
        $targetRealm = $this->realms->getRealm($this->user->getActiveRealmId());

        $sourceCharacters = $sourceRealm->getCharacters();
        $sourceChar = $sourceCharacters->getCharacterByGuid($sourceGuid, "name");

        $targetCharacters = $targetRealm->getCharacters();
        $targetChar = $targetCharacters->getCharacterByGuid($targetGuid, "name");

        $templateData = array(
            'sourceRealmName' => $sourceRealm->getName(),
            'targetRealmName' => $targetRealm->getName(),

            'sourceRealmExpansion' => $sourceRealm->getExpansion(),
            'targetRealmExpansion' => $targetRealm->getExpansion(),

            'sourceCharName' => $sourceChar['name'],
            'targetCharName' => $targetChar['name'],

            'sourceRealmId' => $sourceRealmId,
            'sourceGuid' => $sourceGuid,

            'theme_path' => base_url().APPPATH.$this->template->theme_path,
        );

        $out = $this->template->loadPage("realmcopy_confirm.tpl", $templateData);

        $this->template->view($out);
    }

    private function realmcopy_requirements($sourceRealmId, $sourceGuid, $targetRealmId, $targetGuid)
    {

        if(hasPermission("canCopyCharacter") == FALSE){
            $this->denied("norights");
            exit;
        }

        // Check Source Realm
        if(!$this->realms->realmExists($sourceRealmId) || !in_array($sourceRealmId, $this->realmcopy->getValidSourceRealms()))
        {
            $this->denied("realmcopy_source_realm");
        }

        // Check Target Realm
        $sourceRealm = $this->realms->getRealm($sourceRealmId);

        if(!$this->realms->realmExists($targetRealmId) || !in_array($targetRealmId, $this->realmcopy->getValidTargetRealms()))
        {
            $this->denied("realmcopy_target_realm");
        }

        // Check Source Character Ownership
        $sourceCharacters = $sourceRealm->getCharacters();

        //Open the connection to the databases
        $sourceCharacters->connect();

        if(!$sourceCharacters->characterBelongsToAccount($sourceGuid, $this->user->getId()))
        {
            $this->denied("realmcopy_wrong_source_char");
        }

        // Check if target character is offline
        $targetRealm = $this->realms->getRealm($targetRealmId);
        $targetCharacters = $targetRealm->getCharacters();

        if($targetCharacters->isOnline($targetGuid))
        {
            $this->denied("realmcopy_char_online");
        }
    }

    /**
     * @param $sourceRealmId
     * @param $sourceGuid
     */
    public function copy($sourceRealmId, $sourceGuid)
    {
        $this->template->addBreadcrumb("Realmkopie", site_url(array("migration", 'realmcopy')));

        $targetRealmId = $this->user->getActiveRealmId();
        $targetGuid = $this->user->getActiveCharacter();

        // Check all requirements
        $this->realmcopy_requirements($sourceRealmId, $sourceGuid, $targetRealmId, $targetGuid);

        $this->template->setTitle("Realmkopie");
        $this->template->setSectionTitle("Realmkopie");

        $this->template->addBreadcrumb('Charakterliste', site_url(array('migration', 'realmcopy')));
        $this->template->addBreadcrumb('Realmkopie durchgeführt');

        $sourceGuid = $guid;

        $targetRealm = 2;

        /**
         * Character Infos
         *
         * Gold
         * Taschen
         * Items
         * Erfolge
         * Mounts, Pets
         *
         */

    }

    public function denied($reason = "")
    {
        debug("Denied");
        $this->template->addBreadcrumb("Transfere gesperrt", site_url(array("migration", "denied")));

        // Set the page title
        $this->template->setTitle("Transferformular");
        $this->template->setSectionTitle("Transferformular gesperrt");

        $points = $this->config->item("migration_vote_point_price");

        // Get Open Migrations
        $openMigrations = $this->migration_model->getAccountMigrations($this->user->getId());

        $migrations = array();

        if($this->user->isOnline()){
            foreach($openMigrations as $mig){

                $actions = json_decode($mig["actions"], true);
                $messageText = "";
                if(is_array($actions) && count($actions) > 0){
                    $last_action = array_pop($actions);
                    $messageText = $last_action["by"].( empty($last_action["reason"]) ? "" : ": ".$last_action["reason"] );
                }

                $mig["state_label"] = $this->migration_model->getStateLabel($mig["status"]);
                $mig["message"] = $messageText;

                $migrations[] = $mig;
            }
        }

        $out = $this->template->loadPage("migration_denied.tpl", array(
            "reason" => $reason,
            "cash_needed" => $points,
            'open_migrations' => $migrations,
        ));

        $this->template->view($out);
    }

    public function listing($reason = "")
    {
        $this->user->userArea();

        $this->template->addBreadcrumb("Transferliste", site_url(array("migration", "list")));

        // Set the page title
        $this->template->setTitle("Transfere");
        $this->template->setSectionTitle("Liste meiner Transfere");

        // Get Open Migrations
        $openMigrations = $this->migration_model->getAccountMigrations($this->user->getId());

        $migrations = array();

        foreach($openMigrations as $mig){

            $actions = json_decode($mig["actions"], true);
            $messageText = "";
            if(is_array($actions) && count($actions) > 0){
                $last_action = array_pop($actions);
                $messageText = $last_action["by"].( empty($last_action["reason"]) ? "" : ": ".$last_action["reason"] );
            }

            $mig["state_label"] = $this->migration_model->getStateLabel($mig["status"]);
            $mig["message"] = $messageText;

            $migrations[] = $mig;
        }
        //debug($migrations);

        $out = $this->template->loadPage("migration_listing.tpl", array(
            "reason" => $reason,
            'open_migrations' => $migrations,
        ));

        $this->template->view($out);
    }

    public function form(){
        requirePermission("view");

        // Has to be logged in.
        $this->user->userArea();


        $this->template->addBreadcrumb("Transferanleitung", site_url(array("migration", "index")));
        $this->template->addBreadcrumb("Charaktertransfer", site_url(array("migration", "form")));

        if(hasPermission("canMigrateCharacter") == FALSE){
            $this->denied("norights");
            exit;
        }

        if($this->user->getVp() < $this->config->item("migration_vote_point_price")){
            $this->denied("cash");
            exit;
        }

        /**
         * @var Integer
         */
        $realmId = 1;

        /**
         * @var Object
         */
        $realmObj = $this->realms->getRealm($realmId);

        $accountMigrationCount = count($this->migration_model->getAccountMigrations($this->user->getId()));

        if($accountMigrationCount > $this->config->item("migration_max_per_account") && $this->user->isDev() == false){
            $this->denied("limit");
            exit;
        }


        if(!count($this->races))
        {
            $this->loadConstants();
        }


        $this->loadVars();

        $this->load->library('form_validation');
        $this->lang->load('form_validation', $this->config->item('language'));

        // Set the page title
        $this->template->setTitle("Charaktertransfer");



        $post = array(
            "name" => "",
            "Server" => "",
            "Link" => "",
            "Armory" => "",
            "Download" => "",
            "Bemerkung" => "",
            "icq" => "",
            "skype" => "",
            "race" => "",
            "class" => "",
            "Level" => "",
            "Gold" => "",

            "Riding" => "",
            "Mount_boden" => "",
            "Mount_flug" => "",

            "Beruf1" => "",
            "Beruf2" => "",
            "Beruf1_skill" => "",
            "Beruf2_skill" => "",
            "Cooking" => "",
            "Angling" => "",
            "Firstaid" => "",
/*
            "repA" => $this->reputationsAlliance,
            "repH" => $this->reputationsHorde,
            "repBC" => $this->reputationsBC,
            "repWotlk" => $this->reputationsWotlk,*/
        );


        // Repopulate standard fields
        foreach($post as $key => $value){
            $post[$key] = $this->input->post($key);
        }

        // Repopulate Random Items
        for($i = 1; $i < 11; $i++){
            $post["random_item"][$i] = $this->input->post('random-'.$i);
        }

        $equipmentSlots = $this->migration_model->getEquipmentSlots();

        // Repopulate Equipment Items
        foreach($equipmentSlots as $key => $slot){
            $post["equipment"][$slot] = $this->input->post('equip-'.$key);
        }

        $reputations = $this->migration_model->getReputations();

        // Repopulate Reputations
        foreach($reputations as $repGroup){
            foreach($repGroup["factions"] as $repKey => $repLabel){
                $post['faction'][$repKey] = $this->input->post('faction_'.$repKey);
            }
            if(isset($repGroup['alliance'])){
                foreach($repGroup["alliance"] as $repKey => $repLabel){
                    $post['faction'][$repKey] = $this->input->post('faction_'.$repKey);
                }
            }
            if(isset($repGroup['horde'])){
                foreach($repGroup["horde"] as $repKey => $repLabel){
                    $post['faction'][$repKey] = $this->input->post('faction_'.$repKey);
                }
            }
        }

        //debug("post", $post);

        // Professions
        $professions = $this->migration_model->getProfessions();

        $profLabels = array();
        foreach($professions as $key => $prof){
            $profLabels[$key] = $prof['label'];
        }


        // Rules
        $this->form_validation->set_rules('name', "Charaktername", 'trim|required|alpha');
        $this->form_validation->set_rules('Server', "Servername", 'trim|required');
        $this->form_validation->set_rules('Link', "Serverlink", 'trim|required');
        $this->form_validation->set_rules('Download', "Screenshotdatei", 'trim|required');

        $this->form_validation->set_rules('race', "Charakterrasse", 'required');
        $this->form_validation->set_rules('class', "Charakterklasse", 'required');
        $this->form_validation->set_rules('Level', "Charakterlevel", 'required|is_natural_no_zero|less_than[81]');

        if($post["Level"] > 80){
            $post["Level"] = 80;
        }

        $this->form_validation->set_rules('Gold', "Gold", 'is_natural_no_zero|less_than[10001]');

        if($post["Gold"] > 10000){
            $post["Gold"] = 10000;
        }

        $formErrors = array();

        if(!empty($post['name'])){

            if(!$this->migration_model->checkRaceClassCombination($post['race'], $post['class'])){
                $formErrors[] = "Bitte wähle eine andere Rasse die zu der gewählten Klasse passt.";
            }

            /*
             * Check Equipment for Race/Class Requirements
             */
            foreach($equipmentSlots as $slotId => $slotName){

                $itemId = $post['equipment'][$slotName];

                if(empty($itemId))
                    continue;

                $item = $realmObj->getWorld()->getItem($itemId);

                if(!$item || $item == "empty"){
                    $formErrors[] = "Achtung: ".$slotName." beinhaltet keine gültige Id.";
                }
                elseif($item['AllowableRace'] > 0){
                    $allowableRaces = array_keys($this->realms->getAllowableRaces($item['AllowableRace']));


                    if(count($allowableRaces) > 0 && !in_array($post['race'], $allowableRaces)){
                        $formErrors[] = "Achtung: Dein &lt;".$slotName.'&gt; ist nicht für die gewählte Rasse geeignet.';
                    }
                }
            }

        }

        if ($this->form_validation->run() == FALSE || count($formErrors) > 0){

            $formErrors = implode("<br>",$formErrors);

            $data = array(
                "formAttributes" => array('class' => 'form-horizontal', 'id' => 'migrationForm'),
                "validationErrors" => validation_errors(),
                "formErrors" => $formErrors,
                "races" => $this->races,
                "classes" => $this->classes,
                "post" => $post,
                "profs" => $profLabels,
                "slots" => $equipmentSlots,
                "reputations" => $reputations,
                "reputationStates" => $this->migration_model->getReputationStates(),
                "ridingLevels" => $this->migration_model->getRidingLevels(),
            );

            $out = $this->template->loadPage("migration_form.tpl", $data);
        }
        else{
            $realmId = 1;
            $migrationId = $this->migration_model->createMigrationEntry($realmId, $post);

            $this->user->setVp($this->user->getVp() - $this->config->item("migration_vote_point_price"));

            $data = array(
                "migration_id" => $migrationId,
            );

            $out = $this->template->loadPage("migration_done.tpl", $data);

        }


        $this->template->view($out);

    }

    public function item($realmId = "", $itemId = ""){
        if(!$itemId || !$realmId)
        {
            $this->jsonError("Invalid URL.");
        }

        $realmObj = $this->realms->getRealm($realmId);

        // Get the item SQL data
        $item = $realmObj->getWorld()->getItem($itemId);

        if(!$item || $item == "empty"){
            $this->jsonError(lang("unknown_item", "item"));
        }

        $allowableRaces = array();

        if($item['AllowableRaces'] > 0){
            $allowableRaces = $this->realms->getAllowableRaces($item['AllowableRace']);
        }


        $data = array(
            'status' => 'success',
            'races' => $allowableRaces,
            'item' => $item,
        );

        $this->template->handleJsonOutput($data);

    }

    private function loadConstants(){
        $this->CI->config->load('wow_constants');
    }

    private function loadVars(){
        $races = $this->CI->config->item('races');

        foreach($races as $key => $value){
            $this->races[$key] = is_array($value) ? $value[0] : $value;
        }

        $classes = $this->CI->config->item('classes');

        foreach($classes as $key => $value){
            $this->classes[$key] = is_array($value) ? $value[0]: $value;
        }

        $this->hordeRaces = $this->CI->config->item('horde_races');
        $this->allianceRaces = $this->CI->config->item('alliance_races');

    }


    private function jsonError($message){
        die(json_encode(array(
            "status" => "error",
            "message" => $message,
        )));
    }

}
