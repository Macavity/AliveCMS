<?php

class Teleport extends MX_Controller 
{
	private $teleportLocations;
	private $characters;
	private $total;

	public function __construct()
	{
		parent::__construct();
		
		//Make sure that we are logged in
		$this->user->is_logged_in();
		
		//Load the models
		$this->load->model('teleport_model');
		
		//Init the variables
		$this->init();
	}	
	
	/**
	 * Init every variable
	 */
	private function init()
	{
		/*
		 * Get the teleport locations
		 * Array ( [0] => Array ( [id] => 1 [teleport_name] => Elwynn Forest [x] => -9055.14 [y] => 342.213 [z] => 94.2731 [orientation] => 2.88417 [MapId] => 0 ) ) 1
		 */
		$this->teleportLocations = $this->teleport_model->getTeleportLocations();
		/*
		 * Array ( [0] => Array ( [realmId] => 1 [realmName] => Funserver [characters] => Array ( [0] => Array ( [guid] => 344067 [name] => Theknight [race] => 7 [class] => 6 [gender] => 0 [level] => 255 ) ) ) )
		 */
		$this->characters = $this->user->getCharacters($this->user->getId());

		foreach($this->characters as $realm_key => $realm)
		{
			if(is_array($realm['characters']))
			{
				foreach($realm['characters'] as $character_key => $character)
				{
					$this->characters[$realm_key]['characters'][$character_key]['avatar'] = $this->realms->formatAvatarPath($character);
				}
			}
		}

		$this->total = 0;

		foreach($this->characters as $realm)
		{
			if($realm['characters'])
			{
				$this->total += count($realm['characters']);
			}
		}
	}
	
	/**
	 * Load the page
	 */
	public function index()
	{
		//Set the title to teleport locations
		$this->template->setTitle("Teleport hub");
			
		//Load the content
		$content_data = array(
			"locations" => $this->teleportLocations,
			"characters" => $this->characters,
			"url" => $this->template->page_url,
			"total" => $this->total,
			"vp" => $this->user->getVp(),
			"dp" => $this->user->getDp()
		);
		
		$page_content = $this->template->loadPage("teleport.tpl", $content_data);	
		
		//Load the page
		$page_data = array(
			"module" => "default", 
			"headline" => "<span style='cursor:pointer;' onClick='window.location=\"".$this->template->page_url."ucp\"'>UCP</span> &rarr; Teleport hub", 
			"content" => $page_content
		);
		
		$page = $this->template->loadPage("page.tpl", $page_data);
		
		$this->template->view($page, "modules/teleport/css/teleport.css", "modules/teleport/js/teleport.js");
	}
	
	/**
	 * Submit method
	 */
	public function submit()
	{
		//Get the post variables
		$characterGuid = $this->input->post('guid'); 
		$teleportLocationId = $this->input->post('id'); 
	
		if($teleportLocationId && $characterGuid)
		{
			$realmId = $this->teleport_model->getLocationRealm($teleportLocationId);

			$faction = $this->realms->getRealm($realmId)->getCharacters()->getFaction($characterGuid);

			$teleport_exists = $this->teleport_model->teleportLocationExists($teleportLocationId, $faction);
			
			if($teleport_exists)
			{
				//The location exists
				//TELEPORT THE USER TO THIS LOCATION.
				$location = $teleport_exists;
				$realmConnection = $this->realms->getRealm($location['realm'])->getCharacters();
				$realmConnection->connect();
				
				//MAKE SURE THAT THE CHARACTER EXISTS AND THAT HE IS OFFLINE, ALSO MAKE SURE WE CAN AFFORD IT
				$character_exists = $this->teleport_model->characterExists($characterGuid, $realmConnection->getConnection());
				
				if($character_exists)
				{
					if($this->canPay($this->user->getVp(), $this->user->getDp(), $realmConnection->getGold($this->user->getId(), $characterGuid), $teleport_exists['vpCost'], $teleport_exists['dpCost'], $teleport_exists['goldCost']))
					{
						//Update the vp, dp and gold.
						$this->user->setVp($this->user->getVp() - $teleport_exists['vpCost']);
						$this->user->setDp($this->user->getDp() - $teleport_exists['dpCost']);
						$realmConnection->setGold($this->user->getId(), $characterGuid, ($realmConnection->getGold($this->user->getId(), $characterGuid) - ($teleport_exists['goldCost']*100*100)));
						
						//Change the location of our user 
						//Array ( [0] => Array ( [id] => 1 [teleport_name] => Elwynn Forest [x] => -9055.14 [y] => 342.213 [z] => 94.2731 [orientation] => 2.88417 [MapId] => 0 ) ) 1
		
						$this->teleport_model->setLocation($location['x'], $location['y'], $location['z'], $location['orientation'], $location['mapId'], $characterGuid, $realmConnection->getConnection());	
		
						die("1");
					}
					else 
					{
						die("You can't afford this!");
					}
				}
				else
				{
					die("The character must be offline!");
				}
			}
			else
			{
				die("The location you selected does not exist!");
			}
		}
		else
		{
			die("The teleport location was not set!");
		}
	}

	public function canPay($currentVp, $currentDp, $currentGold, $requiredVp, $requiredDp, $requiredGold)
	{
		//check if we can pay
		if(($currentVp >= $requiredVp) && ($currentDp >= $requiredDp) && ($currentGold >= $requiredGold))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
