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
        //debug("Server ($page)");
        $this->template->addBreadcrumb("Transferanleitung", site_url(array("migration", "index")));

        // Set the page title
        $this->template->setTitle("Transferanleitung");
        $this->template->setSectionTitle("Transferanleitung");

        $out = $this->template->loadPage("migration_index.tpl");
            
        $this->template->view($out);
    }

    /**
     * Realmkopie - Liste
     */
    public function realmcopy()
    {
        $this->template->addBreadcrumb("Realmkopie", site_url(array("migration", 'realmcopy')));

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

    public function copy($guid)
    {
        $this->template->addBreadcrumb("Realmkopie", site_url(array("migration", 'realmcopy')));

        if(hasPermission("canCopyCharacter") == FALSE){
            $this->denied("norights");
            exit;
        }

        $this->template->addBreadcrumb('Charakterkopie');

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
        //debug("Server ($page)");
        $this->template->addBreadcrumb("Transfere gesperrt", site_url(array("migration", "denied")));

        // Set the page title
        $this->template->setTitle("Transferformular");
        $this->template->setSectionTitle("Transferformular gesperrt");

        $points = $this->config->item("migration_vote_point_price");

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
        debug($migrations);

        $out = $this->template->loadPage("migration_denied.tpl", array(
            "reason" => $reason,
            "cash_needed" => $points,
            'open_migrations' => $migrations,
        ));

        $this->template->view($out);
    }

    public function form(){
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

        if($accountMigrationCount > $this->config->item("migration_max_per_account")){
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
                else{
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

        $allowableRaces = $this->realms->getAllowableRaces($item['AllowableRace']);


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
