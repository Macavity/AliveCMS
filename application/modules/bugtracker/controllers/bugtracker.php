<?php

define("BUGTYPE_NONE", 0);
define("BUGTYPE_QUEST", 1);
define("BUGTYPE_INSTANCE", 2);
define("BUGTYPE_NPC", 3);
define("BUGTYPE_CHARACTER", 4);
define("BUGTYPE_ACHIEVEMENT", 5);
define("BUGTYPE_ITEM", 6);
define("BUGTYPE_PAGE", 7);

class Bugtracker extends MX_Controller{
    
    private $css = array();
    private $moduleTitle = "Bugtracker";
    private $modulePath = "modules/bugtracker/";
    
    private $bugTypes = array(
        BUGTYPE_NONE => '-',
        BUGTYPE_QUEST => '[Quest]', 
        BUGTYPE_INSTANCE => '[Instanz]', 
        BUGTYPE_NPC => '[NPC]', 
        BUGTYPE_CHARACTER => '[Charakter]', 
        BUGTYPE_ACHIEVEMENT => '[Erfolg]', 
        BUGTYPE_ITEM => '[Item]', 
        BUGTYPE_PAGE => '[Homepage]',
    );
    
    public function __construct(){
        //Call the constructor of MX_Controller
        parent::__construct();
        
        // Requires login
        $this->user->userArea();
        
        $this->load->model('bug_model');
        $this->load->helper('string');
        
        // Breadcrumbs
        $this->template->addBreadcrumb("Server", site_url("server/index"));
        $this->template->addBreadcrumb("Bugtracker", site_url("bugtracker/index"));
        
        $this->css = array(
            base_url().APPPATH.$this->modulePath."css/bugtracker.css",
            base_url().APPPATH.$this->template->theme_path."css/wiki.css",
        );
    }
    
    public function index(){
        
        $this->template->setTitle($this->moduleTitle);
        $this->template->setTopHeader($this->moduleTitle);
        
        $bugRows = $this->bug_model->getBugs();
        
        
        foreach($bugRows as $i => $row){
            $row["css"] = alternator("row1", "row2");
            $row["title"] = htmlentities($row["title"], ENT_QUOTES, 'UTF-8');
            
            // Changed Date
            if($row["createdTimestamp"] == 0){
                $date = explode(".", $row["createdDate"]);
                // 30.10.2011 => array( 0 => 30, 1 => 10, 2 => 2011)
                $row["createdTimestamp"] = strtotime($date[2]."-".$date[1]."-".$date[0]);
            }
            
            if($row["changedTimestamp"] == 0 && !empty($row["changedDate"])){
                $date = explode(".", $row["changedDate"]);
                // 30.10.2011 => array( 0 => 30, 1 => 10, 2 => 2011)
                $row["changedTimestamp"] = strtotime($date[2]."-".$date[1]."-".$date[0]);
            }
            
            // No change yet? then use same date for both fields
            if($row["changedTimestamp"] == 0){
                $row["changedTimestamp"] = $row["createdTimestamp"];
            }
            
            if(empty($row["changedDate"])){
                $row["changedDate"] = strftime("%d.%m.%Y", $row["changedTimestamp"]);
            }
            
            $row["changedSort"] = strftime("%Y-%m-%d", $row["changedTimestamp"]);
            
            
            switch($row["state"]){
                case "Erledigt":
                    $row["css"] .= " done"; 
                    $row["status"] = 2; 
                    break;
                case "Bearbeitung":
                    $row["css"] .= " inprogress"; 
                    $row["status"] = 1; 
                    break;
                case "nicht umsetzbar":
                case "Nicht umsetzbar":
                case "Abgewiesen":
                    $row["css"] .= " disabled"; 
                    $row["status"] = 3; 
                    break;
                case "Offen":
                default:
                    $row["css"] .= " fresh"; 
                    $row["status"] = 0; 
                    break;
            }
            
            $bugRows[$i] = $row;
            
        }
        
        $page_data = array(
            "module" => "bugtracker", 
            "bugRows" => $bugRows,
            "rowCount" => count($bugRows),
            "js_path" => $this->template->js_path,
            "image_path" => $this->template->image_path,
        );
        
        $out = $this->template->loadPage("list.tpl", $page_data);
        
        $this->template->view($out, $this->css);
    }

    public function create(){
        
        $this->template->setTitle($this->moduleTitle);
        $this->template->setTopHeader("Neuen Bug eintragen");
        $this->template->addBreadcrumb("Neuen Bug eintragen", site_url('bugtracker/create'));
        
        $this->load->helper('form');
        
        $page_data = array(
            "module" => "bugtracker", 
            "js_path" => $this->template->js_path,
            "image_path" => $this->template->image_path,
            "bugTypes" => $this->bugTypes,
        );
        
        $out = $this->template->loadPage("create.tpl", $page_data);
        
        $this->template->view($out, $this->css);
    }
    
}
    