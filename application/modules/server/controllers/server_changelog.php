<?php

class Server_Changelog extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

        $this->theme_path = base_url().APPPATH.$this->template->theme_path;

        $this->template->addBreadcrumb("Server", site_url(array("server")));
        $this->template->addBreadcrumb("Changelog", site_url(array("server", 'changelog')));

    }

    public function index($type)
    {
        // Section Title
        $this->template->setTitle("Server Changelog");

        $pageData = array();

        $out = $this->template->loadPage("server_changelog.tpl", $pageData);
        $this->template->view($out, $this->pageData["extra_css"]);
    }


}
