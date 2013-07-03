<?php

//TODO let the vote url change the {user_id} or something by the api
class Vote extends MX_Controller
{
	private $js;
	private $css;

	function __construct()
	{
		parent::__construct();
		
		//Make sure that we are logged in
		Modules::run('login/is_logged_in');
		
		// Set JS and CSS paths
		$this->js = "modules/vote/js/vote.js";
		$this->css = "modules/vote/css/vote.css";

		//Load the model and config
		$this->load->config('vote');
		$this->load->model('vote_model');
	}
	
	public function index()
	{
		$this->template->setTitle("Vote");

		// Load the topsite page and format the page contents
		$data = array(
			"module" => "default", 
			"headline" => "<span style='cursor:pointer;' onClick='window.location=\"".$this->template->page_url."ucp\"'>UCP</span> &rarr; Vote panel", 
			"content" => $this->template->loadPage("vote.tpl", array('path' => $this->template->page_url,'vote_sites' => $this->vote_model->getVoteSites()))
		);

		$page = $this->template->loadPage("page.tpl", $data);

		//Load the template form
		$this->template->view($page, $this->css, $this->js);
	}

	public function site($id = 0)
	{
		//-------
		//API
		//-------
		$api = array(
			"user_id" => $this->user->getId(),
			"username" => $this->user->getUsername(),
			"user_ip" => $_SERVER['REMOTE_ADDR']
		);

		$vote_site_id = $id; //The site where we are voting for.
		
		//Get the vote site info, returns false if the site does not exists!!
		$vote_site = $this->vote_model->getVoteSite($vote_site_id);
		
		//Check if they already voted with that ip.
		$can_vote = $this->vote_model->canVote($vote_site_id);
		
		//Check if that site exists and that the user didn't voted for it yet.
		if($vote_site && $can_vote)
		{	
			//Update the vp if needed or else just go to the url if we got api enabled.
			if($vote_site['api_enabled'])
			{
				$custom_callback_url = $this->formatCallbackUrl($vote_site['vote_url'], $vote_site);
		
				redirect($custom_callback_url);
			}
			else
			{
				$this->vote_model->vote_log($api['user_id'], $api['user_ip'], $vote_site_id);

				$this->vote_model->updateVp($this->user->getId(), $vote_site['points_per_vote']);

				redirect($vote_site['vote_url']);
			}
			
		}
		else
		{
			die('You have already voted.');
		}
	}

	public function formatCallbackUrl($url, $vote_site)
	{
		$normal_url = preg_replace("/\{account_id\}/", $this->user->getId(), $url);

		return $normal_url;
	}
}
