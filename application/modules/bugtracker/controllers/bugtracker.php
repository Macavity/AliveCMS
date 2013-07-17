<?php

class Bugtracker extends MX_Controller{
    
    private $css = array();
    private $moduleTitle = "Bugtracker";
    private $modulePath = "modules/bugtracker/";
    
    public function __construct(){
        //Call the constructor of MX_Controller
        parent::__construct();
        
        requirePermission("view");

        // Dummys
        if(false){
            $this->bug_model = new Bug_model();
            $this->project_model = new Project_Model();
            $this->template = new Template();
        }
        
        $this->load->model('bug_model');
        $this->load->model('project_model');
        $this->load->helper('string');
        
        // Breadcrumbs
        $this->template->addBreadcrumb("Server", site_url("server/index"));
        $this->template->addBreadcrumb("Bugtracker", site_url("bugtracker/index"));

        $this->template->setJsAction("bugtracker");
    }
    
    public function index(){

        requirePermission("view");
        
        $this->template->setTitle($this->moduleTitle);
        $this->template->setSectionTitle($this->moduleTitle);

        $bugRows = $this->bug_model->getBugs();
        
        
        foreach($bugRows as $i => $row){
            $row["title"] = htmlentities($row["title"], ENT_QUOTES, 'UTF-8');

            $row["css"] = "";

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

            $row["type_string"] = $this->bug_model->getTypeLabel($row["bug_type"]);
            
            switch($row["bug_state"]){
                case BUGSTATE_DONE:
                    $row["css"] = "done";
                    break;
                case BUGSTATE_ACTIVE:
                    $row["css"] = "inprogress";
                    break;
                case BUGSTATE_REJECTED:
                    $row["css"] = "disabled";
                    break;
                case BUGSTATE_OPEN:
                default:
                    $row["css"] = "fresh";
                    $row["status"] = 0; 
                    break;
            }
            
            $bugRows[$i] = $row;
            
        }

        $page_data = array(
            "module" => "bugtracker",
            "bugRows" => $bugRows,
            "rowCount" => count($bugRows),
            "rowMax" => min($bugRows,50),
            "rowMin" => ($bugRows == 0) ? 0 : 1,
            "js_path" => $this->template->js_path,
            "image_path" => $this->template->image_path,
        );

        $out = $this->template->loadPage("list.tpl", $page_data);
        
        $this->template->view($out, $this->css);
    }

    /**
     * Show detail page for a bug
     * @param $bugId
     */
    public function bug($bugId){
        requirePermission("view");

        if(!is_numeric($bugId)){
            show_404("Dieser Link ist ungültig");
            return;
        }

        $bug = $this->bug_model->getBug($bugId);

        if($bug === false){
            show_error("Der gesuchte Bug wurde nicht gefunden.");
            return;
        }

        $this->template->setTitle("Bug #".$bugId);
        $this->template->addBreadcrumb("Bug #".$bugId, site_url("bugtracker/bug/".$bugId));

        /*
         * Base Data
         */

        $class = $bug['class'];
        $title = htmlentities($bug['title']);
        $desc = $bug['desc'];
        $state = $bug['state'];
        $complete = str_replace("%","",$bug['complete']);
        $complete .= "%";
        $by = $bug['by'];
        $date = $bug['date'];
        $date2 = $bug['date2'];
        $link = (substr_count($bug['link'], "Hier den") > 0) ? "" : $bug['link'];
        $links = array();
        $createdDetail = "";
        $changedDetail = "";
        $accountComments = array();

        /**
         * Log of all actions
         * @type {Array}
         */
        $bugLog = array();

        /**
         * Similar Bugs
         * @type {Array}
         */
        $otherBugs = array();

        /*
         * Link
         */
        if(!empty($link)){
            if(preg_match("@http://(de|www|old).wowhead.com/\??([^=]+)=(\d+).*@i", $link, $matches)){
                $links = array();
                debug("link matches",$matches);
                $links[] = '<a href="http://de.wowhead.com/'.$matches[2]."=".$matches[3].'" target="_blank">WoWHead</a>';
                if( $matches[2] == "zone" ){
                    $links[] = '<a href="http://portal.wow-alive.de/game/zone/'.$matches[3].'" target="_blank" data-zone="'.$matches[3].'">Alive</a>';
                }
                if( $matches[2] == "item" ){
                    $links[] = '<a href="http://portal.wow-alive.de/item/'.$matches[3].'" target="_blank" data-item="'.$matches[3].'">Alive</a>';
                }
            }
        }

        // Find other bugs to the same link
        $search = str_replace("http://","", $link);
        $search = str_replace("de.wowhead.com/","",$search);
        $search = str_replace("www.wowhead.com/","",$search);
        $search = str_replace("old.wowhead.com/","",$search);

        $similarBugs = $this->bug_model->findSimilarBugs($search, $bugId);

        if(!empty($link)){
            foreach($similarBugs as $row){
                $otherBugs[] = '<a href="/bugtracker/bug/'.$row["id"].'/" target"_blank">'.htmlentities($row["title"]).'</a>';
            }
        }

        // Find links in the description
        $desc = htmlentities($desc);
        $desc = makeWowheadLinks($desc);


        switch($state){
            case "Erledigt":
                $cssState = "color-q2"; break;
            case "Offen":
            case "Bearbeitung":
                $cssState = "color-q1"; break;
            case "Abgewiesen":
            case "nicht umsetzbar":
            case "Nicht umsetzbar":
                $cssState = "color-q0"; break;
        }

        if($bug["createdTimestamp"] > 0){
            $createdDetail = sec_to_dhms(time()-$bug["createdTimestamp"], true);
            if(!empty($createdDetail))
                $createdDetail = "vor ".$createdDetail;
        }
        if($bug["changedTimestamp"] > 0){
            $changedDetail = sec_to_dhms(time()-$bug["changedTimestamp"], true);
            if(!empty($changedDetail))
                $changedDetail = "vor ".$changedDetail;
        }

        if(!empty($bug["posterData"])){
            $posterData = json_decode($bug["posterData"]);
            debug("posterData",$posterData);
            $bugPoster = array(
                "details" => true,
                "name" => $posterData->name,
                "class" => $posterData->class,
                "url" => $char->GetUrl($posterData),
            );
        }
        else{
            $bugPoster = array(
                "details" => false,
            );
        }

        $commentRows = $DataDB->select("SELECT * FROM kommentar WHERE `postid` = ?d ORDER BY `id` ASC", $bugId);

        $counter = 1;
        $rowclass = "row1";
        foreach($commentRows as $i => $row){
            $rowclass = cycle($rowclass, array("row1", "row2"));
            $actionLog = array();

            $commentRows[$i]["id"] = $row["id"];
            $commentRows[$i]["n"] = $counter++;
            $commentRows[$i]["gm"] = false;
            $commentRows[$i]["css-row"] = $rowclass;
            $commentRows[$i]["avatar"] = "";
            $commentRows[$i]["action"] = "";
            $commentRows[$i]["lastEdit"] = "";
            $commentRows[$i]["name"] = htmlentities($row["name"]);
            $commentRows[$i]["text"] = makeWowheadLinks(htmlentities($row["text"]));
            $commentRows[$i]["date"] = ($row["timestamp"] > 60) ? "vor ".sec_to_dhms(time()-$row["timestamp"],true):"";

            // State changes
            if(!empty($row["action"])){
                $actions = json_decode($row["action"]);
                if(isset($actions->state)){
                    $actionLog[] = "Status => ".$actions->state;
                }
            }
            // Content changes
            if(!empty($row["actions"])){
                $actions = json_decode($row["actions"]);
                $lastEdit = "";
                foreach($actions as $action){
                    if($action->action == "change"){
                        $name = ($action->gm) ? "[GM] ".$action->name : $action->name;
                        $lastEdit = '<span class="time">von '.$name.' bearbeitet vor <span data-tooltip="'.strftime("%d.%m.%Y",$action->ts).'">'.sec_to_dhms(time()-$action->ts,true).'</span></span>';
                    }
                }
                if(!empty($lastEdit)){
                    $actionLog[] = $lastEdit;
                }
            }
            $commentRows[$i]["action"] = implode("<br/>", $actionLog);

            if(!empty($row["posterData"])){
                $posterData = json_decode($row["posterData"]);
                $commentRows[$i]["details"] = true;
                $commentRows[$i]["avatar"] = $char->GetCharacterAvatar($posterData);
                $commentRows[$i]["char_url"] = $char->GetUrl($posterData);
                $commentRows[$i]["char_class"] = $posterData->class;
            }

            if(!empty($row["posterAccountId"])){
                $gm = $DB->selectRow("SELECT * FROM account_access WHERE id = ?d AND gmlevel > 0 AND RealmID = 1", $row["posterAccountId"]);
                if($gm){
                    $commentRows[$i]["gm"] = true;
                }
            }

            $bugLog[$row["timestamp"]] = $commentRows[$i];

        }

        // Combine Actions and Comments (later)
        $bugActionLog = array();

        if(!empty($bug["actions"])){
            $actions = json_decode($bug["actions"]);

            foreach($actions as $action){
                $ts = $action->ts;
                $bugLog[$ts] = '<span class="time">'.sec_to_dhms(time()-$ts, true, "vor ")."</span> ".$action->name." => Bug Report bearbeitet";
            }

        }

        ksort($bugLog);

        /*
         * Template Generation
         */
        $page_data = array(
            "module" => "bugtracker",
        );

        $out = $this->template->loadPage("detail.tpl", $page_data);

        $this->template->view($out, $this->css);
    }

    public function create(){

        requirePermission("canCreateBugs");

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
    