<?php

class Pay extends MX_Controller
{
	private $vp;
	private $dp;

	public function __construct()
	{
		parent::__construct();
		
		$this->vp = 0;
		$this->dp = 0;

		$this->user->userArea();

		$this->load->model("store_model");
		$this->load->config("store");

		requirePermission("view");
	}

	/**
	 * Main method to serve the checkout action
	 */
	public function index()
	{
		$cart = $this->input->post("data");
        $cart = json_decode($cart, true);

        // Make sure they sent us a cart object
		if(!$cart){
			$this->show_error("Please provide a cart object");
		}

		// Make sure they don't submit an empty array
		if(count($cart) == 0){
            $this->show_error(lang("empty_cart", "store"));
		}

		$items = array();

		// Load all items
		foreach($cart as $item){

            if(empty($item['id'])){
                continue;
            }

			// Load the item
			$items[$item['id']] = $this->store_model->getItem($item['id']);

			// Make sure the item exists
			if($items[$item['id']] != false && in_array($item['type'], array('vp', 'dp'))){

				// Keep track of how much it costs
				if($item['type'] == "vp" && !empty($items[$item['id']]['vp_price'])){
					$this->vp += $items[$item['id']]['vp_price'];
				}
				elseif($item['type'] == "dp" && !empty($items[$item['id']]['dp_price'])){
					$this->dp += $items[$item['id']]['dp_price'];
				}
				else{
                    $this->show_error(lang("free_items", "store"));
				}
			}
			else{
                $this->show_error('Der Einkaufswagen enthält ungültige Items');
			}
		}

		// Make sure the user can afford it
		if(!$this->canAfford()){
            $this->show_error(lang("cant_afford", "store"));
		}

		// An array to hold all items in a sub-array for each realm
		$realmItems = array();

        // Make sure all realms are online
		foreach($cart as $item){

			$realm = $this->realms->getRealm($items[$item['id']]['realm']);

			// Create a realm item array if it doesn't exist
			if(!isset($realmItems[$realm->getId()])){
				$realmItems[$realm->getId()] = array();
			}

			if(!$realm->isOnline(true)){
				$this->show_error(lang("error_offline", "store"));
			}
		}

		// Send all items
		foreach($cart as $item)
		{

            $storeItem = $items[$item['id']];
            $recipientCharGuid = $item['charGuid'];

            $charDb = $this->realms->getRealm($storeItem['realm'])->getCharacters();

			// Is it a query or command?
			if(empty($storeItem['query']) && empty($storeItem['command']))
			{
				// Make sure they enter a character
				if(!isset($item['character'])){
                    $this->show_error(lang("error_character", "store"));
				}

				// Make sure the character exists
				if(!$charDb->characterExists($recipientCharGuid)){
                    $this->show_error(str_replace('{0}', $item['character'], lang("error_character_exists", 'store')));
				}

				// Make sure the character belongs to this account
				if(!$charDb->characterBelongsToAccount($recipientCharGuid, $this->user->getId())){
                    $this->show_error(lang("error_character_not_mine", "store"));
				}

				// Make sure the character array exists in the realm array
				if(!isset($realmItems[$storeItem['realm']][$recipientCharGuid])){
					$realmItems[$storeItem['realm']][$recipientCharGuid] = array();
				}
				
				// Check for multiple items
				if(preg_match("/,/", $storeItem['itemid'])){
					// Split it per item ID
					$temp = explode(",", $storeItem['itemid']);

					// Loop through the item IDs
					foreach($temp as $id){
						// Add them individually to the array
                        $itemCount = $item['count'];
                        while($itemCount-- >= 0){
                            array_push($realmItems[$storeItem['realm']][$recipientCharGuid], array('id' => $id));
                        }
					}
				}
				else{
                    $itemCount = $item['count'];
                    while($itemCount-- >= 0){
    					array_push($realmItems[$storeItem['realm']][$recipientCharGuid], array('id' => $storeItem['itemid']));
                    }
				}
			}
			else if(!empty($storeItem['command'])){
				// Make sure the realm actually supports console commands
				if(!$this->realms->getRealm($storeItem['realm'])->getEmulator()->hasConsole()){
                    $this->show_error(lang("error_no_console", "store"));
				}
			}

			// Make sure the character is offline, if this item requires it
			if($storeItem['require_character_offline'] && $this->realms->getRealm($storeItem['realm'])->getCharacters()->isOnline($item['character'])){
                $this->show_error(lang("error_character_not_offline", "store"));
			}
		}

        // Let the user pay before we start sending any items!
		$this->subtractPoints();

		$this->store_model->logOrder($this->vp, $this->dp, $cart);

        foreach($cart as $item){
            // Is it a query?
			if(!empty($items[$item['id']]['query']))
			{
                $itemCount = $item['count'];
                while($itemCount-- >= 0){
                    //debug("handle query", $item);
                    $this->handleQuery($items[$item['id']]['query'], $items[$item['id']]['query_database'], (isset($item['charGuid']) ? $item['character'] : false), $items[$item['id']]['realm']);
                }
            }
			// Or a command?
			else if(!empty($items[$item['id']]['command']))
			{
				$commands = preg_split('/\r\n|\r|\n/', $items[$item['id']]['command']);

				foreach($commands as $command)
				{
					$command = preg_replace("/\{ACCOUNT\}/", $this->external_account_model->getUsername(), $command);
					$command = preg_replace("/\{CHARACTER\}/", (isset($item['charGuid']) ? $this->realms->getRealm($items[$item['id']]['realm'])-> getCharacters()->getNameByGuid($item['charGuid']) : false), $command);

                    $itemCount = $item['count'];
                    while($itemCount-- >= 0){
                        $this->realms->getRealm($items[$item['id']]['realm'])->getEmulator()->sendCommand($command);
			     	}
                }
			}
		}

		// Loop through all realms
		foreach($realmItems as $realm => $characters)
		{

            // Loop through all characters
			foreach($characters as $character => $items)
			{
                //debug("realmItems", $items);
				$characterName = $this->realms->getRealm($realm)->getCharacters()->getConnection()->query(query("get_charactername_by_guid"), array($character));
				$characterName = $characterName->result_array();
				
				$this->realms->getRealm($realm)->getEmulator()->sendItems($characterName[0]['name'], $this->config->item("store_subject"), $this->config->item("store_body"), $items);
			}
		}
        //debug("items done");
		
		$this->store_model->completeOrder();

		$this->plugins->onCompleteOrder($cart);

		// Output the content
        $message = array(
            'type' => 'success',
            'msg' => $this->config->item('success_message'),
        );
        $this->template->handleJsonOutput($message);
		return;
	}

    private function show_error($message){
        $message = array(
            'type' => 'error',
            'msg' => $message,
        );
        $this->template->handleJsonOutput($message);
        die();
    }

	/**
	 * Update the user's VP and DP
	 */
	private function subtractPoints()
	{
		$this->user->setVp($this->user->getVp() - $this->vp);
		$this->user->setDp($this->user->getDp() - $this->dp);
	}

	/**
	 * Handle custom queries
	 * @param String $query
	 * @param String $database
	 * @param Int $character
	 * @param Int $realm
	 */
	private function handleQuery($query_raw, $database, $character, $realm)
	{
		$queries = explode(";", $query_raw);

		foreach($queries as $query)
		{
			switch($database)
			{
				case "cms":
					$db = $this->load->database("cms", true);
				break;

				case "realmd":
					$db = $this->external_account_model->getConnection();
				break;

				case "realm":
					$db = $this->realms->getRealm($realm)->getCharacters()->getConnection();
				break;
				
				//When none of the above were entered return false.
				default:
					return false;
			}

			$data = array(
				0 => $this->user->getId(),
				1 => $character,
				2 => $realm
			);

			$positions = array(
				'account' => strpos($query, "{ACCOUNT}"),
				'character' => strpos($query, "{CHARACTER}"),
				'realm' => strpos($query, "{REALM}")
			);

			asort($positions);
			$positions = array_reverse($positions);

			foreach($positions as $key => $value)
			{
				if(!is_numeric($value) || empty($value))
				{
					switch($key)
					{
						case "account":
							array_splice($data, 0, 1);
						break;

						case "character":
							array_splice($data, 1, 1);
						break;

						case "realm":
							array_splice($data, 2, 1);
						break;
					}
				}
			}

			$query = preg_replace("/\{ACCOUNT\}/", "?", $query);
			$query = preg_replace("/\{CHARACTER\}/", "?", $query);
			$query = preg_replace("/\{REALM\}/", "?", $query);

            if ($query != ''){
                // Disable the CI DB Debug
                $db->db_debug = false;

                $query = $db->query($query, $data);

                if($db->_error_message())
                {
                    die($db->_error_message());
                }
            }
		}
	}

	/**
	 * Check if the user can afford what he's trying to buy
	 * @return Boolean
	 */
	private function canAfford()
	{
		if($this->vp > 0 && $this->vp > $this->user->getVp())
		{
			return false;
		}
		elseif($this->dp > 0 && $this->dp > $this->user->getDp())
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}