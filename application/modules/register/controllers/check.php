<?php

class Check extends MX_Controller
{
	public function __construct()
	{
		//Call the constructor of MX_Controller
		parent::__construct();

		//make sure that we are not yet logged in
		Modules::run('login/is_not_logged_in');
	}
	
	public function username($value = false)
	{
		if($value != false)
		{
			if(!$this->external_account_model->usernameExists($value))
			{
				die("1");
			}
			else
			{
				die("0");
			}
		}
	}

	public function email()
	{
		$value = $this->input->post("email");
		
		if($value != false)
		{
			if(!$this->external_account_model->emailExists($value))
			{
				die("1");
			}
			else
			{
				die("0");
			}
		}
	}
}
