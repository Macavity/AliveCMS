<?php

class Login extends MX_Controller
{
	function __construct()
	{
		//Call the constructor of MX_Controller
		parent::__construct();
		
		//Load url and form library
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		$this->template->setTitle("Login");

        $templateFile = ($this->input->is_ajax_request()) ? "views/empty.tpl" : "views/page.tpl";
    
		//check if we are already logged in.
		if($this->user->isOnline())
		{
			redirect($this->template->page_url . "ucp");
		}
		
		$data = array(
					"url" => $this->template->page_url,
					"username" => "",
					"username_error" => "",
					"password_error" => "",
					"class" => array("class" => "page_form"),
					"has_smtp" => $this->config->item('has_smtp')
				);

		// Form not submitted
		if(count($_POST) == 0)
		{
			$this->template->view($this->template->loadPage($templateFile, array(
				"module" => "default", 
				"headline" => "Log in",
				"class" => array("class" => "page_form"),
				"content" => $this->template->loadPage("login.tpl", $data)
			)));
		}
		else
		{
			$sha_pass_hash = $this->user->createHash($this->input->post('login_username'), $this->input->post('login_password'));
			
			//LOG THEM IN AND FILL IN OUR USER OBJECT!
			$check = $this->user->setUserDetails($this->input->post('login_username'), $sha_pass_hash);

			// No errors
			if($check == 0)
			{
				if($this->input->post('login_remember'))
				{
					// Remember me
					$this->input->set_cookie("fcms_username", $this->input->post('login_username'), 60*60*24*365);
					$this->input->set_cookie("fcms_password", $sha_pass_hash, 60*60*24*365);
				}

				// Redirect to the user panel
				redirect($this->template->page_url."ucp");
			}
			else
			{
				$data['username'] = $this->input->post('login_username');

				// Wrong username
				if($check == 1)
				{
					$data['username_error'] = '<img src="'.$this->template->page_url.'application/images/icons/exclamation.png" data-tip="User doesn\'t exist" />';
					$data['password_error'] = '<img src="'.$this->template->page_url.'application/images/icons/exclamation.png" data-tip="Password doesn\'t match" />';
				}

				// Wrong password
				elseif($check == 2)
				{
					$data['password_error'] = '<img src="'.$this->template->page_url.'application/images/icons/exclamation.png" data-tip="Password doesn\'t match" />';
					$data['username_error'] = '<img src="'.$this->template->page_url.'application/images/icons/accept.png" />';
				}

				$this->template->view($this->template->loadPage($templateFile, array(
					"module" => "default", 
					"headline" => "Log in", 
					"content" => $this->template->loadPage("login.tpl", $data)
				)));
			}
		}
	}
    
    
	public function is_logged_in()
	{
		//A check so it requires you to be logged in.
		if(!$this->session->userdata('online'))
		{
			$this->template->view($this->template->loadPage("page.tpl", array(
				"module" => "default", 
				"headline" => "You need to be logged in", 
				"content" => "<center style='margin:10px;font-weight:bold;'>You need to be logged in to use this feature!</center>"
			)));
		}
		
		return;
	}
	
	public function is_not_logged_in()
	{
		//A check so it requires you to be logged out.
		if($this->session->userdata('online'))
		{
			$this->template->view($this->template->loadPage("page.tpl", array(
				"module" => "default", 
				"headline" => "Already Logged In", 
				"content" => "<center style='margin:10px;font-weight:bold;'>You are already logged in!</center>"
			)));
		}
		
		return;
	}
    
    /**
     * Delivers the data required to build the personalized menu and userplate
     */
    public function userplate(){
        $data = array(
            "url" => $this->template->page_url,
            "username" => "",
            "username_error" => "",
            "password_error" => "",
            "class" => array("class" => "page_form"),
            "has_smtp" => $this->config->item('has_smtp')
        );
            

        //check if we are already logged in.
        if($this->user->isOnline()){
            
        }
        else{
    
        }
        
        
        // Form not submitted
        if(count($_POST) == 0)
        {
            $this->template->view($this->template->loadPage("page.tpl", array(
                "module" => "default", 
                "headline" => "Log in",
                "class" => array("class" => "page_form"),
                "content" => $this->template->loadPage("login.tpl", $data)
            )));
        }
        else
        {
            $sha_pass_hash = $this->user->createHash($this->input->post('login_username'), $this->input->post('login_password'));
            
            //LOG THEM IN AND FILL IN OUR USER OBJECT!
            $check = $this->user->setUserDetails($this->input->post('login_username'), $sha_pass_hash);

            // No errors
            if($check == 0)
            {
                if($this->input->post('login_remember'))
                {
                    // Remember me
                    $this->input->set_cookie("fcms_username", $this->input->post('login_username'), 60*60*24*365);
                    $this->input->set_cookie("fcms_password", $sha_pass_hash, 60*60*24*365);
                }

                // Redirect to the user panel
                redirect($this->template->page_url."ucp");
            }
            else
            {
                $data['username'] = $this->input->post('login_username');

                // Wrong username
                if($check == 1)
                {
                    $data['username_error'] = '<img src="'.$this->template->page_url.'application/images/icons/exclamation.png" data-tip="User doesn\'t exist" />';
                    $data['password_error'] = '<img src="'.$this->template->page_url.'application/images/icons/exclamation.png" data-tip="Password doesn\'t match" />';
                }

                // Wrong password
                elseif($check == 2)
                {
                    $data['password_error'] = '<img src="'.$this->template->page_url.'application/images/icons/exclamation.png" data-tip="Password doesn\'t match" />';
                    $data['username_error'] = '<img src="'.$this->template->page_url.'application/images/icons/accept.png" />';
                }

                $this->template->view($this->template->loadPage("page.tpl", array(
                    "module" => "default", 
                    "headline" => "Log in", 
                    "content" => $this->template->loadPage("login.tpl", $data)
                )));
            }
        }
    }
}
