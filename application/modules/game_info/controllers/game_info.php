<?php

class Game_Info extends MX_Controller
{
    
    private $cacheActive = FALSE;
    private $cacheId = "";
    private $CI;
    
    /**
     * Contains the template variables
     * @type {Array}
     */
    private $pageData = array();
    
    public function __construct()
    {

        parent::__construct();
        
        $this->load->helper('url');
        
        $this->CI = &get_instance();
        
        $this->template->addBreadcrumb("Spiel", site_url(array("game")));

        // Hide Sidebar
        $this->template->hideSidebar();

    }
    
    public function index()
    {
        $out = $this->template->loadPage("game_index.tpl", $this->pageData);
            
        $this->template->view($out, $this->pageData["extra_css"]);
    }
    
}
