<?php

class Accounts extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('administrator');
		$this->load->model('accounts_model');
	}

	/**
	 * Display the admin panel if we have access
	 */
	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Accounts");

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
		);

		// Load my view
		$output = $this->template->loadPage("accounts_search.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Accounts', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/admin_extra/js/accounts.js");
	}
	
	public function search()
	{
		$value = $this->input->post('value');
		$data = false;
		
		if(preg_match("/^[a-zA-Z0-9]*$/", $value) && strlen($value) > 3 && strlen($value) < 15)
		{
			//It's a username
			$data = $this->accounts_model->getByUsername($value);
		}
		elseif(filter_var($value, FILTER_VALIDATE_EMAIL))
		{
			//It's a email
			$data = $this->accounts_model->getByEmail($value);
		}
		
		if($data)
		{
			$internal_details = $this->accounts_model->getInternalDetails($data['id']);
	
			// Prepare my data
			$page_data = array(
				'internal_details' => $internal_details,
				'external_details' => $data,
				'access_id'  => $this->accounts_model->getAccessId($data['id']),
				'expansions' => $this->realms->getExpansions()
			);
	
			// Load my view
			$output = $this->template->loadPage("accounts_found.tpl", $page_data);
	
			die($output);
		}
		else
		{
			die("<span>No results</span>");
		}
	}
	
	public function save($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$external_account_data[column("account", "expansion")] = $this->input->post("expansion");
		$external_account_data[column("account", "email")] = $this->input->post("email");
		
		//Make sure to check if we got something filled in here.
		if($this->input->post("password"))
		{
			$external_account_data[column("account", "password")] = $this->realms->getEmulator()->encrypt($this->user->getUsername($id), $this->input->post("password"));
		}
		
		$external_account_access_data[column("account_access", "gmlevel")] = $this->input->post("gm_level");
		
		$internal_account_data["vp"] = $this->input->post("vp");
		$internal_account_data["dp"] = $this->input->post("dp");
		$internal_account_data["nickname"] = $this->input->post("nickname");
		

		if(!$external_account_data[column("account", "email")] || !$internal_account_data["nickname"])
		{
			die("UI.alert('The fields can\'t be empty')");
		}

		$this->accounts_model->save($id, $external_account_data, $external_account_access_data, $internal_account_data);

		die('UI.alert("The account has been saved")');
	}
}