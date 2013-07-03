<?php

class Password_recovery extends MX_Controller
{	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->config('password_recovery');
		$this->load->model('password_recovery_model');

		$this->user->is_not_logged_in();

		if(!$this->config->item('has_smtp'))
		{
			die("This feature is disabled because the server doesn't have SMTP installed.");
		}
	}
	
	public function index()
	{
		$this->template->setTitle("Password recovery");

		if($this->input->post('recover_username'))
		{
			//They set recovery username send email
			//Get the email
			$email = $this->password_recovery_model->getEmail($this->input->post('recover_username'));
			
			if($email)
			{
				$link = base_url().'password_recovery/requestPassword/'.$this->generateKey($this->input->post('recover_username'), $email);
				$this->sendMail($email, $this->config->item('password_recovery_sender_email'), $this->config->item('server_name').': reset your password', 'You have requested to reset your password, to complete the request please navigate to <a href="'.$link.'">'.$link.'</a>');

				$this->template->view($this->template->loadPage("page.tpl", array(
					"module" => "default", 
					"headline" => "Password recovery", 
					"content" => "An email has been sent to you with further information. Please check your inbox to proceed."
				)));
			}
			else
			{
				//Wrong username or an error occured
				$this->template->view($this->template->loadPage("page.tpl", array(
					"module" => "default", 
					"headline" => "Password recovery", 
					"content" => "The user doesn't exist. <a href=''>Go back</a>",
				)));
			}
		}
		else
		{
			//Nothing in the email so they didnt filled in a username
			$this->template->view($this->template->loadPage("page.tpl", array(
				"module" => "default", 
				"headline" => "Password recovery", 
				"content" => $this->template->loadPage("password_recovery.tpl", array("class" => array("class" => "page_form")))
			)));
		}	
	}
	
	private function sendMail($receiver, $sender, $subject, $message)
	{
		$this->load->config('smtp');

		if($this->config->item('use_own_smtp_settings'))
		{
			$config['protocol'] = "smtp";
			$config['smtp_host'] = $this->config->item('smtp_host');
			$config['smtp_user'] = $this->config->item('smtp_user');
			$config['smtp_pass'] = $this->config->item('smtp_pass');
			$config['smtp_port'] = $this->config->item('smtp_port');
			$config['crlf'] = "\r\n";
			$config['newline'] = "\r\n";
		}

		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';

		$this->load->library('email', $config);

		$this->email->from($sender, $this->config->item('server_name'));
		$this->email->to($receiver); 

		$this->email->subject($subject);
		$this->email->message($message);	

		$this->email->send();
	}
	
	public function requestPassword($key = "")
	{
		if($key)
		{
			$key_valid = $this->password_recovery_model->getKey($key);
			//Make sure a key is entered and make sure that it is the right key
			if($key_valid && $key_valid != '')
			{
				//Reset password
				$username = $key_valid; //Username
				$newPassword = $this->generatePassword(); //New password
				
				//Hash password for the database
				$newPasswordHash = sha1(strtoupper($username).':'.strtoupper($newPassword));
				
				//Change the password
				$this->password_recovery_model->changePassword($username, $newPasswordHash);
				
				//Send a mail with the new password
				$this->sendMail($this->password_recovery_model->getEmail($username), $this->config->item('password_recovery_sender_email'), $this->config->item('server_name').': your new password', 'Your new password is <b>'.$newPassword.'</b>');
				
				//Show a new message
				$this->template->view($this->template->loadPage("page.tpl", array(
					"module" => "default", 
					"headline" => "Password recovery", 
					"content" => "Your password has been changed! The new password has been sent to your email adress."
				)));
				
				//Remove the key from the database
				$this->password_recovery_model->deletekey($key);
			}
			else
			{
				//Error occurred
				$this->template->view($this->template->loadPage("page.tpl", array(
					"module" => "default", 
					"headline" => "Password recovery", 
					"content" => "Invalid key."
				)));
			}
		}
		else
		{
			$this->template->view($this->template->loadPage("page.tpl", array(
				"module" => "default", 
				"headline" => "Password recovery", 
				"content" => "No key entered."
			)));
		}
	}

	private function generateKey($username, $email)
	{
		$key = sha1($username.":".$email.":".time());
		if(!$this->password_recovery_model->insertKey($key, $username, $_SERVER['REMOTE_ADDR']))
		{
			$this->template->view($this->template->loadPage("page.tpl", array(
				"module" => "default", 
				"headline" => "Password recovery", 
				"content" => "Error while inserting the key."
			)));
		}	
		
		return $key;
	}
	
	private function generatePassword()
	{
		return substr(sha1(time()), 0, 10);
	}
}
