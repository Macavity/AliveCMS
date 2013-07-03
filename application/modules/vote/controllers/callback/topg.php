<?php
 
class Topg extends MX_Controller
{
	private $url = "topg.org";
	
	public function __construct()
	{
		parent::__construct();
		
		//Load the vote model
		$this->load->model('vote_model');
	}
	
	public function index()
	{
		if($this->input->get('p_resp') && $_SERVER['REMOTE_ADDR'] == '174.36.33.242')
		{
			//Get the account id of the guy that voted, this id was sended through the openwow site.
			$account_id = $this->input->get('p_resp');
			
			$vote_site = $this->vote_model->getVoteSiteByUrl($this->url);
			
			if($this->vote_model->canVote($vote_site['id']))
			{
				//Give him the amount of vote points that he gets for it.
				$this->vote_model->vote_log($account_id, $_SERVER['REMOTE_ADDR'], $vote_site['id']);
				$this->vote_model->updateVp($account_id, $vote_site['points_per_vote']);
				
				die("Points given");
			}

			die("No points given");
		}
		else
		{
			die('No access');
		}
	}
}
