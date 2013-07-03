<?php

class Expansion extends MX_Controller
{
	private $out;
	
	function __construct()
	{
		//Call the constructor of MX_Controller
		parent::__construct();
		
		//Make sure that we are logged in
		Modules::run('login/is_logged_in');
		
		$this->load->helper('form');

		if(count($_POST) > 0)
		{
			$this->out = $this->changeExpansion($this->input->post('expansion'));
		}
	}
	
	public function index()
	{
		$this->template->setTitle("Change expansion");

		if(isset($this->out))
		{
			//We submitted our form already, show the output.
			$this->template->view($this->template->loadPage("page.tpl", array(
				"module" => "default", 
				"headline" => "<span style='cursor:pointer;' onClick='window.location=\"".$this->template->page_url."ucp\"'>UCP</span> &rarr; Change Expansion",
				"content" => $this->out
			)));
		}
		else 
		{
			$data = array("expansions" => $this->realms->getExpansions(), "my_expansion" => $this->user->getExpansion());

			$page_data = array(
						"module" => "default", 
						"headline" => "<span style='cursor:pointer;' onClick='window.location=\"".$this->template->page_url."ucp\"'>UCP</span> &rarr; Change Expansion", 
						"content" => $this->template->loadPage("change_expansion.tpl", $data),
					);

			//Load the template form
			$this->template->view($this->template->loadPage("page.tpl", $page_data));
		}
		
	}
	
	public function changeExpansion($expansion = "")
	{
		if(array_key_exists($expansion, $this->realms->getExpansions()))
		{
			//Change the expansion.
			$this->user->setExpansion($expansion);
			
			return "<center style='margin:10px;font-weight:bold;'>Your expansion has been changed. <a href='".$this->template->page_url."ucp'>Click here to go back to the User panel!</a></center>";
		}
		else
		{
			return "The expansion you selected does not exists!";
		}
	}
}
