<?php

class Gm extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->user->isGm())
		{
			redirect('error/rank');
			die();
		}

		$this->load->helper('text');
		$this->load->model('gm_model');
		$this->load->config('gm_config');
	}

	public function index()
	{
		$output = "";

		foreach($this->realms->getRealms() as $realm)
		{
			$tickets = $this->gm_model->getTickets($realm);

			if($tickets)
			{
				foreach($tickets as $key => $value)
				{
					$tickets[$key]['name'] = $realm->getCharacters()->getNameByGuid($value['guid']);
					$tickets[$key]['ago'] = $this->template->formatTime(time() - $value['createTime']) . " ago";
					$tickets[$key]['message_short'] = character_limiter($value['message'], 20);
				}
			}

			$data = array(
				'url' => pageURL,
				'tickets' => $tickets,
				'hasConsole' => $realm->getEmulator()->hasConsole(),
				'realmId' => $realm->getId()
			);

			$content = $this->template->loadPage('panel.tpl', $data);

			$output .= $this->template->box($realm->getName(), $content);
		}

		$this->template->view($output, "modules/gm/css/gm.css", "modules/gm/js/gm.js");
	}

	public function sendItem($realmId = false, $id = false)
	{
		if(!$realmId || !$id || !is_numeric($id) || !is_numeric($realmId))
		{
			die("Invalid values");
		}

		//get the realm object
		$realm = $this->realms->getRealm($realmId);

		//Get the ticket from the database
		$ticket = $this->gm_model->getTicket($realm, $id);

		if($ticket)
		{
			//Set parameters
			$itemId = array($this->input->post('item'));
			$title = $this->config->item('gm_senditemtitle');
			$body = $this->config->item('gm_senditembody');

			//Send the email
			$this->realms->getRealm($realmId)->getEmulator()->sendItems($realm->getCharacters()->getNameByGuid($ticket['guid']), $title, $body, $itemId);

			//Finish
			die('1');
		}
		else
		{
			die('2');
		}
		
	}

	public function unstuck($realmId = false, $id = false)
	{
		if(!$realmId || !$id || !is_numeric($id) || !is_numeric($realmId))
		{
			die("Invalid values");
		}

		//Get the realm
		$realm = $this->realms->getRealm($realmId);

		//Get the ticket
		$ticket = $this->gm_model->getTicket($realm, $id);

		if($ticket)
		{
			//Check if the character is offline and exists.
			$character_exists = $this->gm_model->characterExists($ticket['guid'], $realm->getCharacters()->getConnection(), $realm->getId());

			if($character_exists)
			{
				$this->gm_model->setLocation($this->config->item('gm_unstuck_position_x'), $this->config->item('gm_unstuck_position_y'), $this->config->item('gm_unstuck_position_z'), $this->config->item('gm_unstuck_orientation'), $this->config->item('gm_unstuck_map'), $ticket['guid'], $realm->getCharacters()->getConnection(), $realm->getId());
				
				//Die('1') to mark success
				die('1');
			}
			else
			{
				//Die 2 to mark failure because char is online.
				die('2');
			}
		}
		else
		{
			die('2');
		}
	}

	public function answer($realmId = false, $id = false)
	{
		if(!$realmId || !$id || !is_numeric($id) || !is_numeric($realmId))
		{
			die("Invalid values");
		}

		//Get the realm
		$realm = $this->realms->getRealm($realmId);

		//Get the ticket
		$ticket = $this->gm_model->getTicket($realm, $id);

		if($ticket)
		{
			$title = $this->config->item('gm_answertitle');
			$body = $this->input->post('message');

			$realm->getEmulator()->sendMail($realm->getCharacters()->getNameByGuid($ticket['guid']), $title, $body);

			die('1');
		}
		else
		{
			die('2');
		}
	}

	public function close($realmId = false, $id = false)
	{
		if(!$realmId || !$id || !is_numeric($id) || !is_numeric($realmId))
		{
			die("Invalid values");
		}

		//Get the realm
		$realm = $this->realms->getRealm($realmId);

		//Get the ticket
		$ticket = $this->gm_model->getTicket($realm, $id);

		if(column("gm_tickets", "completed"))
		{
			//A row exists, update it
			$this->gm_model->setTicketCompleted($realm->getCharacters()->getConnection(), $id, $realm->getId());
			die('1');
		}
		else
		{
			//Remove it
			$this->gm_model->deleteTicket($realm->getCharacters()->getConnection(), $id, $realm->getId());
			die('1');
		}
	}

	public function kick($realmId = false, $charName = false)
	{
		if(!$realmId || !$charName || !is_numeric($realmId))
		{
			die("Invalid values");
		}

		//Get the realm
		$realm = $this->realms->getRealm($realmId);

		if($realm->getEmulator()->hasConsole() == true)
		{
			$realm->getEmulator()->send($this->config->item('gm_kickcommand')." ".$charName);
		}
		else
		{
			die('2');
		}
	}

	public function ban($username = "")
	{
		if(!$username)
		{
			die("Invalid values");
		}

		$bannedBy = $this->user->getUsername();
		$banReason = $this->input->post('reason');
		
		$ban = $this->gm_model->getBan($this->external_account_model->getConnection(), $this->external_account_model->getId($username));
		
		if($ban['banCount'] == 0)
		{
			$this->gm_model->setBan($this->external_account_model->getConnection(), $this->external_account_model->getId($username), $bannedBy, $banReason, $this->config->item('gm_default_ban_days'));
		}
		else 
		{
			//Update the row.
			$this->gm_model->updateBan($this->external_account_model->getConnection(), $this->external_account_model->getId($username), $bannedBy, $banReason, $this->config->item('gm_default_ban_days'));
		}
	
		die('1');
	}
}