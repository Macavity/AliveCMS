<?php

class Connect extends MX_Controller {

    function __construct(){

        //Call the constructor of MX_Controller
        parent::__construct();

        //Make sure that we are logged in
        $this->user->userArea();

        if(false){
            $this->template = new Template();
        }

    }
    public function index()
    {
        requirePermission("canUpdateAccountSettings");

        // Title
        $this->template->setTitle("Forum und Spielaccount verbinden");

        // Breadcrumbs
        $this->template->addBreadcrumb("Account", "/ucp/");
        $this->template->addBreadcrumb("Forum und Spielaccount verbinden", "/ucp/connect");

        $data = array(
        );

        $page = $this->template->loadPage("connect.tpl", $data);

        //Load the template form
        $this->template->view($page, false, "");

    }
}