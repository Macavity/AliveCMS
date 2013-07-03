<?php

class Settings extends MX_Controller
{
	function __construct()
	{
		//Call the constructor of MX_Controller
		parent::__construct();
		
		//Make sure that we are logged in
		Modules::run('login/is_logged_in');
	}
	
	public function index()
	{
		$this->template->setTitle("Account settings");

		$settings_data = array(
			'nickname' => $this->user->getNickname(),
			'location' => $this->internal_user_model->getLocation()
		);

		$data = array(
			"module" => "default", 
			"headline" => "<span style='cursor:pointer;' onClick='window.location=\"".$this->template->page_url."ucp\"'>UCP</span> &rarr; Account settings", 
			"content" => $this->template->loadPage("settings.tpl", $settings_data)
		);

		$page = $this->template->loadPage("page.tpl", $data);

		//Load the template form
		$this->template->view($page, false, "modules/ucp/js/settings.js");
	}
	
	public function submit()
	{
		$oldPassword = $this->input->post('old_password');
		$newPassword = $this->input->post('new_password');
		
		if($oldPassword && $newPassword)
		{
			// Get the current password
			$currentPassword = $this->user->getPassword();

			// Hash the entered password
			$passwordHash = $this->user->createHash($this->user->getUsername(), $oldPassword);

			// Check if passwords match
			if(strtoupper($currentPassword) == strtoupper($passwordHash))
			{
				$hash = $this->user->createHash($this->user->getUsername(), $newPassword);
				
				$this->user->setPassword($hash);
			}
			else
			{
				die('no');
			}
		}

		die('yes');
	}

	public function submitInfo()
	{
		$this->load->model("settings_model");

		// Gather the values
		$values = array(
			'nickname' => htmlspecialchars($this->input->post("nickname")),
			'location' => htmlspecialchars($this->input->post("location")),
		);

		// Remove the nickname field if it wasn't changed
		if($values['nickname'] == $this->user->getNickname())
		{
			$values = array('location' => $this->input->post("location"));
		}
		elseif(strlen($values['nickname']) < 4
		|| strlen($values['nickname']) > 14
		|| !preg_match("/[A-Za-z0-9]*/", $values['nickname']))
		{
			die("Nickname must be between 4 and 14 characters long and may only contain letters and numbers");
		}
		elseif($this->internal_user_model->nicknameExists($values['nickname']))
		{
			die("2");
		}
		
		if(strlen($values['location']) > 32 && !ctype_alpha($values['location']))
		{
			die("Location may only be up to 32 characters long and may only contain letters");
		}

		$this->settings_model->saveSettings($values);

		die("1");
	}
}
