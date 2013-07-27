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
        }

        $this->load->helper(array('url','form'));
        
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
        
        $out = $this->template->loadPage("migration_index.tpl");
            
        $this->template->view($out);
    }

    public function form(){
        $this->template->addBreadcrumb("Transferanleitung", site_url(array("migration", "index")));
        $this->template->addBreadcrumb("Charaktertransfer", site_url(array("migration", "form")));

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

        // Repopulate Equipment Items
        foreach($this->equipmentSlots as $key => $slot){
            $post["equipment"][$slot] = $this->input->post('equip-'.$key);
        }

        // Repopulate Reputations
        foreach($this->reputations as $repGroup){
            foreach($repGroup["factions"] as $repKey => $repLabel){
                $post['faction'][$repKey] = $this->input->post('faction_'.$repKey);
            }
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


        if ($this->form_validation->run() == FALSE){
            $data = array(
                "formAttributes" => array('class' => 'form-horizontal', 'id' => 'migrationForm'),
                "validationErrors" => validation_errors(),
                "races" => $this->races,
                "classes" => $this->classes,
                "post" => $post,
                "profs" => $this->proffessions,
                "slots" => $this->equipmentSlots,
                "reputations" => $this->reputations,
                "reputationStates" => $this->reputationStates,
                "ridingLevels" => $this->ridingLevels
            );

            $out = $this->template->loadPage("migration_form.tpl", $data);
        }
        else{
            $data = array(
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

        $this->proffessions = array(
            'Alchemie',
            'Schmiedekunst',
            'Verzauberungskunst',
            'Ingenieurskunst',
            'Kräutersammeln',
            'Lederer',
            'Bergbau',
            'Kürschnerei',
            'Schneiderei',
            'Inschriftenkunde',
            'Juwelenschleifen'
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


    private function jsonError($message){
        die(json_encode(array(
            "status" => "error",
            "message" => $message,
        )));
    }

}
