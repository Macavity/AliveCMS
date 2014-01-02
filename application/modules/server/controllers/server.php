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
            "extra_css" => "",
        ));

        $this->template->addBreadcrumb("Server", site_url(array("server")));

    }
    
    public function index()
    {
        // Site Title
        $this->template->setTitle("Der Server");

        // Template File
        $this->templateFile = "server.tpl";

        $out = $this->template->loadPage("server.tpl", $this->pageData);
            
        $this->template->view($out, $this->pageData["extra_css"]);
    }

    public function playermap()
    {
        // Section Title
        $this->template->setTitle("Online Spielerkarte");

        // Breadcrumb
        $this->template->addBreadcrumb("Spielerkarte", site_url(array("server", "playermap")));

        // Hide Sidebar
        $this->template->hideSidebar();

        $out = $this->template->loadPage("playermap.tpl", $this->pageData);
        $this->template->view($out, $this->pageData["extra_css"]);
    }

    public function playersonline()
    {

        $this->cacheId = "server_playersonline";

        $cache = $this->cache->get($this->cacheId);

        // Section Title
        $this->template->setTitle("Online Spielerliste");

        // Breadcrumb
        $this->template->addBreadcrumb("Spieler Online", site_url(array("server", "playeronline")));

        // Hide Sidebar
        $this->template->hideSidebar();

        if($this->cacheActive && $cache !== false){
            $out = $cache['content'];
        }
        else{
            $realmData = array();

            $realms = $this->realms->getRealms();

            foreach($realms as $realm){
                if($realm->isOnline()){

                    $realmCharacters = $realm->getCharacters()->getOnlinePlayers();

                    $onlineCharData = array();

                    if($realmCharacters != false){

                        foreach($realmCharacters as $char){

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
                    }

                    $onlineCount = $realm->getOnline();

                    $realmData[] = array(
                        "id" => $realm->getId(),
                        "name" => $realm->getName(),
                        "count" => $onlineCount,
                        "characters" => $onlineCharData,
                        "shownCount" => ($onlineCount > 50) ? 50 : $onlineCount,
                    );

                }
            }

            $this->pageData["realms"] = $realmData;

            $out = $this->template->loadPage("playersonline.tpl", $this->pageData);

            // save the generated content to the cache
            /*$this->cache->save($this->cacheId, array(
                "title" => $this->pageTitle,
                "content" => $this->out,
                "rank" => $page_content['rank_needed']
            ));*/
        }
        $this->template->view($out, $this->pageData["extra_css"]);


        //$this->pageData["sumPlayers"] = count($characters);
        
    }

    public function realmstatus(){
        // Site Title
        $this->template->setTitle("Realmstatus");
        $this->template->setSectionTitle("Realmstatus");

        // Breadcrumb
        $this->template->addBreadcrumb("Realmstatus", site_url("server/realmstatus"));

        $realms = $this->realms->getRealms();

        $realmData = array();

        foreach($realms as $realm){

            $values = array(
                "gm" => $realm->getOnline("gm"),
                "horde" => $realm->getOnline("horde"),
                "alliance" => $realm->getOnline("alliance"),
            );

            foreach($values as $key => $value){
                $values[$key] = intval($value);
            }

            $emulator = $realm->getEmulatorType();

            $cssClass = '';

            if(substr_count($emulator, 'trinity') > 0){
                $cssClass = 'color-ex2';
            }

            $realmData[] = array(
                "name" => $realm->getName(),
                "isOnline" => (bool) $realm->isOnline(),
                'playerOnline' => $realm->getOnline(),
                'uptimeDHMS' => sec_to_dhms($realm->getUptime()),
                'cssClass' => $cssClass,
                'type' => '',
            );
        }

        // Prepare data
        $this->pageData['realmData'] = $realmData;


        $out = $this->template->loadPage("realmstatus.tpl", $this->pageData);

        $this->template->view($out, $this->pageData["extra_css"]);

    }
    
}
