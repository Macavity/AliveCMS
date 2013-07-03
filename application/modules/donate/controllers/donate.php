<?php

class Donate extends MX_Controller
{
	function __construct()
	{
		//Call the constructor of MX_Controller
		parent::__construct();
		
		//Make sure that we are logged in
		$this->user->is_logged_in();
		
		$this->load->config('donate');
	}
	
	public function index()
	{
		$this->template->setTitle("Donate");

		$donate_paypal = $this->config->item('donate_paypal');
		$donate_paygol = $this->config->item('donate_paygol');
		
		$user_id = $this->user->getId();
		
		$data = array(
			"donate_paypal" => $donate_paypal, 
			"donate_paygol" => $donate_paygol,
			"user_id" => $user_id,
			"server_name" => $this->config->item('server_name'),
			"currency" => $this->config->item('donation_currency'),
			"currency_sign" => $this->config->item('donation_currency_sign'),
			"multiplier" => $this->config->item('donation_multiplier'),
			"multiplier_paygol" => $this->config->item('donation_multiplier_paygol'),
			"url" => pageURL
		);

		$output = $this->template->loadPage("donate.tpl", $data);

		$this->template->box("<span style='cursor:pointer;' onClick='window.location=\"".$this->template->page_url."ucp\"'>UCP</span> &rarr; Donate panel", $output, true, "modules/donate/css/donate.css", "modules/donate/js/donate.js");
	}

	public function success()
	{
		$this->user->getUserData();

		$page = $this->template->loadPage("success.tpl", array('url' => $this->template->page_url));

		$this->template->box("Thanks for your donation!", $page, true);
	}
}
