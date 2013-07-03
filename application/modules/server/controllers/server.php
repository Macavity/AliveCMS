<?php

class Server extends MX_Controller
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
    
    /**
     * Contains the template variables
     * @type {Array}
     */
    private $pageData = array();
    
    public function __construct()
    {
        //debug("Server.__construct");
        
        parent::__construct();
        
        $this->load->helper('url');
        
        $this->CI = &get_instance();
        
        $this->theme_path = base_url().APPPATH.$this->template->theme_path;
        $this->style_path = $this->theme_path."css/";
        $this->image_path = $this->theme_path."images/";
        
        $this->pageData = array_merge($this->pageData, array(
            "theme_path" => $this->theme_path,
            "module" => "server",
            "extra_css" => array($this->style_path."server.css"),
        ));
        
        $this->pageTitle = $this->config->item("server_name");
        
    }
    
    public function index($page = "index")
    {
        //debug("Server ($page)");
        
        /**
         * Identifier for the cache
         * @type {String}
         */
        $this->cacheId = "server_".$page;
        
        $cache = $this->cache->get($this->cacheId);

        if($this->cacheActive && $cache !== false)
        {
            $this->user->requireRank($cache['rank']);

            $this->template->setTitle($cache['title']);
            $out = $cache['content'];
        }
        else
        {
            $this->template->addBreadcrumb("Server", site_url(array("server")));
            
            switch($page){
                
                case "howtoplay":
                    $this->pageTitle .= " - Online Spielerliste";
                    $this->template->addBreadcrumb("Spieler Online", site_url(array("server", $page)));
                    $this->templateFile = "server.tpl";
                    break;
                
                case "playersonline":
                    $this->pageTitle .= " - Online Spielerliste";
                    $this->template->addBreadcrumb("Spieler Online", site_url(array("server", $page)));
                    $this->templateFile = "playersonline.tpl";
                    $this->playersonline();
                    $this->pageData["extra_css"][] = $this->style_path."wiki.css";
                    $this->template->hideSidebar();
                    break;
                
                case "playermap":
                    $this->pageTitle .= " - Spielerkarte";
                    $this->template->addBreadcrumb("Online Spielerkarte", site_url(array("server", $page)));
                    $this->templateFile = "playermap.tpl";
                    $this->template->hideSidebar();
                    break;
                
                
                default:
                    $this->pageTitle .= " - Der Server";
                    $this->templateFile = "server.tpl";
                    $this->pageData["extra_css"][] = $this->style_path."server-index.css";
            }
            
            // save the generated content to the cache
            /*$this->cache->save($this->cacheId, array(
                "title" => $this->pageTitle, 
                "content" => $this->out, 
                "rank" => $page_content['rank_needed']
            ));*/
            
        }
        
        // Set the page title
        $this->template->setTitle($this->pageTitle);
        
        $out = $this->template->loadPage($this->templateFile, $this->pageData);
            
        $this->template->view($out, $this->pageData["extra_css"]);
    }
    
    private function howtoplay(){
        $page_content = $this->cms_model->getPage($page);
        
    }
    
    private function playersonline(){
        //SELECT guid, name, race, class, gender, level, zone  FROM `characters` WHERE `online`='1' AND (NOT `extra_flags` & 1 AND NOT `extra_flags` & 16) ORDER BY `name`
        
        //debug("realms", $this->realms);
        
        // nicht ideal, aber wir haben ja nur einen Realm also wozu das Leben schwer machen
        $realms = $this->realms->getRealms();
        $realm = $realms[0];
        
        $characters = $realm->getCharacters()->getOnlinePlayers();
        
        $onlineCharData = array();
        
        foreach($characters as $char){
            
            $zone = $this->realms->getZone($char["zone"]);
            
            $classes = "class-".$char["class"]." zone-".$char["zone"];
            
            if($char["level"] > 79){
                $classes .= " is-80";
            }
            
            $className = $this->realms->getClass($char["class"], $char["gender"]);
            
            $onlineCharData[] = array(
                "name" => $char["name"],
                "class" => $char["class"],
                "race" => $char["race"],
                "gender" => $char["gender"],
                "level" => $char["level"],
                "zone" => $zone,
                "css" => $classes,
                "class_name" => $className,
            );
            
        }
        
        $this->pageData["characters"] = $onlineCharData;
        $this->pageData["sumPlayers"] = count($characters);
        
    }
    
}
