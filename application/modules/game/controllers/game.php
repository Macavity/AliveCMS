<?php

class Game extends MX_Controller
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
            
        $this->template->view($out);
    }

    public function zone($zoneName, $tooltip = "")
    {
        if(false){
            $this->zone_model = new Zone_Model();
        }

        $tooltip = (empty($tooltip)) ? false : true;

        $this->load->model('zone_model');

        if($this->zone_model->getZone($zoneName)){
            $data = array(
                "zone" => $this->zone_model->getZoneData(),

            );

            $out = $this->template->loadPage("zone_detail.tpl", $data);

            $this->template->view($out);
        }
        else{
            show_404();
        }


    }
    
}
