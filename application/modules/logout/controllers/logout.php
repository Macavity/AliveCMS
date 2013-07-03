<?php

class Logout extends MX_Controller
{
	public function __construct()
	{
		//Call the constructor of MX_Controller
		parent::__construct();
		
		//make sure that we are logged in
		Modules::run('login/is_logged_in');
		
		$this->load->helper('cookie');
	}
	
	public function index()
	{
		$this->input->set_cookie("fcms_username", false);
		$this->input->set_cookie("fcms_password", false);
		
		delete_cookie("fcms_username");
		delete_cookie("fcms_password");

		$this->session->sess_destroy();

		redirect($this->template->page_url);
	}
}
