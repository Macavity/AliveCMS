<?php
/*

Config
- Wieviele Migrations pro Account?


1. Statische Seite mit der Anleitung
    -> Link zum Formular
2. Formularseite (migration/formular)
    permission: canCreateMigrations

3. Listenansicht für GMs
    permission: canEditMigrations


 */

class Migration extends MX_Controller
{
    
    private $cacheActive = FALSE;
    private $cacheId = "";
    private $CI;
    
    private $theme_path = "";
    private $style_path = "";
    private $image_path = "";
    private $templateFile = "";
    
    private $pageTitle = "";
    //private $realms = array();

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

        if(false){
            $this->template = new Template();
            $this->migration_model = new Migration_Model();
        }

        $this->load->helper(array('url','form'));
        $this->load->config('migration');

        $this->load->model("migration_model");

        $this->template->enable_profiler(TRUE);

        
        $this->CI = &get_instance();
        
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
    
    public function index($page = "index")
    {
        //debug("Server ($page)");
        $this->template->addBreadcrumb("Transferanleitung", site_url(array("migration", "index")));

        // Set the page title
        $this->template->setTitle("Transferanleitung");
        $this->template->setSectionTitle("Transferanleitung");

        $out = $this->template->loadPage("migration_index.tpl");
            
        $this->template->view($out);
    }

    public function denied($reason = "")
    {
        //debug("Server ($page)");
        $this->template->addBreadcrumb("Transfere gesperrt", site_url(array("migration", "denied")));

        // Set the page title
        $this->template->setTitle("Transferformular");
        $this->template->setSectionTitle("Transferformular gesperrt");

        $points = $this->config->item("migration_vote_point_price");

        $out = $this->template->loadPage("migration_denied.tpl", array("reason" => $reason, "cash_needed" => $points));

        $this->template->view($out);
    }


    public function form(){
        $this->template->addBreadcrumb("Transferanleitung", site_url(array("migration", "index")));
        $this->template->addBreadcrumb("Charaktertransfer", site_url(array("migration", "form")));

        if(hasPermission("canMigrateCharacter") == FALSE){
            $this->denied("norights");
            exit;
        }

        $accountMigrationCount = $this->migration_model;

        if($accountMigrationCount){

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

            "Reiten" => "",
            "Mount_boden" => "",
            "Mount_flug" => "",

            "Beruf1" => "",
            "Beruf2" => "",
            "Beruf1_skill" => "",
            "Beruf2_skill" => "",
            "Kochen" => "",
            "Angeln" => "",
            "Erstehilfe" => "",
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
        }

        //debug("post", $post);

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


        if ($this->form_validation->run() == FALSE){
            $data = array(
                "formAttributes" => array('class' => 'form-horizontal', 'id' => 'migrationForm'),
                "validationErrors" => validation_errors(),
                "races" => $this->races,
                "classes" => $this->classes,
                "post" => $post,
                "profs" => $this->migration_model->getProfessions(),
                "slots" => $equipmentSlots,
                "reputations" => $reputations,
                "reputationStates" => $this->migration_model->getReputationStates(),
                "ridingLevels" => $this->migration_model->getRidingLevels(),
            );

            $out = $this->template->loadPage("migration_form.tpl", $data);
        }
        else{



            // Save Data to Database
            $data = array();
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

        $data = array(
            'status' => 'success',
            'item' => $item
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
