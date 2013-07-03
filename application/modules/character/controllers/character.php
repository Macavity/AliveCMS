<?php

class Character extends MX_Controller
{
	private $canCache;

	private $js;
	private $css;
	private $id;
	private $realm;
	private $realmName;

	private $name;
	private $class;
	private $className;
	private $race;
	private $raceName;
	private $level;
	private $accountId;
	private $account;
	private $gender;

	private $stats;
	private $items;
    
    private $charData;

	function __construct()
	{
		parent::__construct();
		
		// Set JS and CSS paths
		$this->js = "modules/character/js/character.js";
		$this->css = "modules/character/css/character.css";

		$this->load->model("armory_model");

		$this->canCache = true;
		$this->items = array();
	}

	/**
	 * Initialize
	 */
	public function index($realm = false, $id = false)
	{
		$this->setId($realm, $id);
		
		if($this->id != false)
		{
			$this->getProfile();
		}
		else
		{
			$this->getError();
		}
	}

	public function getItem($id = false)
	{
		if($id != false)
		{
			$cache = $this->cache->get("items/item_".$this->realm."_".$id);

			if($cache !== false)
			{
				$cache2 = $this->cache->get("items/display_".$cache['displayid']);

				if($cache2 != false)
				{
					return "<a href='" . $this->template->page_url . "item/" . $this->realm . "/" . $id . "' rel='item=".$id."' data-realm='".$this->realm."'></a><img src='https://wow.zamimg.com/images/wow/icons/large/".$cache2.".jpg' />";
				}
				else
				{
					return "<a href='" . $this->template->page_url . "item/" . $this->realm . "/" . $id . "' rel='item=".$id."' data-realm='".$this->realm."'></a><img src='https://wow.zamimg.com/images/wow/icons/large/inv_misc_questionmark.jpg' />";
				}
			}
			else
			{
				$this->canCache = false;
				return $this->template->loadPage("icon_ajax.tpl", array('id' => $id, 'realm' => $this->realm, 'url' => $this->template->page_url));
			}
		}
	}

	/**
	 * Determinate which Id to assign
	 */
	public function setId($realm, $id)
	{

		// Check if valid X-Y format
		if(is_numeric($realm)
		&& is_numeric($id))
		{
			$this->realm = $realm;
			$this->id = $id;

			$this->armory_model->setRealm($this->realm);
			$this->armory_model->setId($this->id);
		}
		else
		{
			$this->realm = false;
			$this->id = false;
		}
	}

	/**
	 * Get character info
	 */
	private function getInfo()
	{
		$character_data = $this->armory_model->getCharacter();

		if($this->realms->getRealm($this->realm)->getEmulator()->hasStats())
		{
			$character_stats = $this->armory_model->getStats();
		}
		else
		{
			$character_stats = array('maxhealth' => "Unknown");
		}

		$this->pvp = array(
						'kills' => (array_key_exists("totalKills", $character_data)) ? $character_data['totalKills'] : false,
						'honor' => (array_key_exists("totalHonorPoints", $character_data)) ? $character_data['totalHonorPoints'] : false,
						'arena' => (array_key_exists("arenaPoints", $character_data)) ? $character_data['arenaPoints'] : false
					);

		// Assign the character data as real variables
		foreach($character_data as $key=>$value)
		{
			$this->$key = $value;
		}

		// Assign the character stats
		$this->stats = $character_stats;
	
		// Get the account username
		$this->accountName = $this->internal_user_model->getNickname($this->account);

		$this->guild = $this->armory_model->getGuild();
		$this->guildName = $this->armory_model->getGuildName($this->guild);

		if(in_array($this->race, array(4,10)))
		{
			if($this->race == 4)
			{
				$this->raceName = "Night elf";
			}
			else
			{
				$this->raceName = "Blood elf";
			}
		}
		else
		{
			$this->raceName = $this->armory_model->realms->getRace($this->race, $this->gender);
		}

		$this->className = $this->armory_model->realms->getClass($this->class, $this->gender);
		$this->realmName = $this->armory_model->realm->getName();
		
		if($this->realms->getRealm($this->realm)->getEmulator()->hasStats())
		{
			// Find out which power field to use
			switch($this->className)
			{
				default:
					$this->secondBar = "mana";
					$this->secondBarValue = $this->stats['maxpower1'];
				break;

				case "Warrior":
					$this->secondBar = "rage";
					$this->secondBarValue = $this->stats['maxpower2'];
				break;

				case "Hunter":
					$this->secondBar = "focus";
					$this->secondBarValue = $this->stats['maxpower3'];
				break;

				case "Deathknight":
					$this->secondBar = "runic";
					$this->secondBarValue = $this->stats['maxpower7'];
				break;
			}
		}
		else
		{
			$this->secondBar = "mana";
			$this->secondBarValue = "Unknown";
		}

		// Load the items
		$items = $this->armory_model->getItems();

		// Item slots
		$slots = array(
					0 => "head",
					1 => "neck",
					2 => "shoulders",
					3 => "body",
					4 => "chest",
					5 => "waist",
					6 => "legs",
					7 => "feet",
					8 => "wrists",
					9 => "hands",
					10 => "finger1",
					11 => "finger2",
 					12 => "trinket1",
					13 => "trinket2",
					14 => "back",
					15 => "mainhand",
					16 => "offhand",
					17 => "ranged",
					18 => "tabard"
				);

		if(is_array($items))
		{
			// Loop through to assign the items
			foreach($items as $item)
			{
				$this->items[$slots[$item['slot']]] = $this->getItem($item['itemEntry']);
			}
		}

		// Loop through to make sure none are empty
		foreach($slots as $key=>$value)
		{
			if(!array_key_exists($value, $this->items))
			{
				switch($value)
				{
					default: $image = $value; break;
					case "trinket1": $image = "trinket"; break;
					case "trinket2": $image = "trinket"; break;
					case "finger1": $image = "finger"; break;
					case "finger2": $image = "finger"; break;
					case "back": $image = "chest"; break;
				}

				$this->items[$value] = "<div class='item'><img src='".$this->template->page_url."application/images/armory/default/".$image.".gif' /></div>";
			}
		}
	}

	private function getBackground()
	{
		switch($this->raceName)
		{
			default: return "shattrath"; break;
			case "Human": return "stormwind"; break;
			case "Blood elf": return "silvermoon"; break;
			case "Night elf": return "darnassus"; break;
			case "Dwarf": return "ironforge"; break;
			case "Gnome": return "ironforge"; break;
			case "Orc": return "orgrimmar"; break;
			case "Draenei": return "theexodar"; break;
			case "Tauren": return "thunderbluff"; break;
			case "Undead": return "undercity"; break;
			case "Troll": return "orgrimmar"; break;
		}
	}

	/**
	 * Load the profile
	 * @return String
	 */
	private function getProfile()
	{
		$cache = $this->cache->get("character_".$this->realm."_".$this->id);

		if($cache !== false)
		{
			$this->template->setTitle($cache['name']);
			$this->template->setDescription($cache['description']);
			$this->template->setKeywords($cache['keywords']);

			$page = $cache['page'];
		}
		else
		{
			if($this->armory_model->characterExists())
			{
				// Load all items and info
				$this->getInfo();

				$this->template->setTitle($this->name);

				$avatarArray = array(
							'class' => $this->class,
							'race' => $this->race,
							'level' => $this->level,
							'gender' => $this->gender
						);

				$this->charData = array(
					"name" => $this->name,
					"race" => $this->race,
					"class" => $this->class,
					"level" => $this->level,
					"gender" => $this->gender,
					"items" => $this->items,
					"guild" => $this->guild,
					"guildName" => $this->guildName,
					"pvp" => $this->pvp,
					"url" => $this->template->page_url,
					"raceName" => $this->raceName,
					"className" => $this->className,
					"realmName" => $this->realmName,
					"avatar" => $this->armory_model->realms->formatAvatarPath($avatarArray),
					"stats" => $this->stats,
					"secondBar" => $this->secondBar,
					"secondBarValue" => $this->secondBarValue,
					"bg" => $this->getBackground(),
					"realmId" => $this->realm,
					"fcms_tooltip" => $this->config->item("use_fcms_tooltip"),
					"has_stats" => $this->realms->getRealm($this->realm)->getEmulator()->hasStats()
				);

				$character = $this->template->loadPage("character.tpl", $this->charData);

				$data = array(
					"module" => "default", 
					"headline" => "<span style='cursor:pointer;' data-tip='View profile' onClick='window.location=\"".$this->template->page_url."profile/".$this->account."\"'>".$this->accountName."</span> &rarr; ".$this->name,
					"content" => $character
				);

				$page = $this->template->loadPage("page.tpl", $data);
			}
			else
			{
				$page = $this->getError(true);
			}

			$keywords = "armory,".$this->charData['name'].",lv".$this->charData['level'].",".$this->charData['raceName'].",".$this->charData['className'].",".$this->charData['realmName'];
			$description = $this->charData['name']." - level ".$this->charData['level']." " .$this->charData['raceName']." ".$this->charData['className']." on ".$this->charData['realmName'];

			$this->template->setDescription($description);
			$this->template->setKeywords($keywords);


			if($this->canCache)
			{
				// Cache for 30 min
				$this->cache->save("character_".$this->realm."_".$this->id, array('page' => $page, 'name' => $this->name, 'keywords' => $keywords, 'description' => $description), 60*30);
			}
		}

		$this->template->view($page, $this->css, $this->js);
	}

	/**
	 * Show "character doesn't exist" error
	 */
	private function getError($get = false)
	{
		$this->template->setTitle("Character not found");

		$data = array(
			"module" => "default", 
			"headline" => "Character doesn't exist", 
			"content" => "<center style='margin:10px;font-weight:bold;'>The requested character does not exist.</center>"
		);

		$page = $this->template->loadPage("page.tpl", $data);

		if($get)
		{
			return $page;
		}
		else
		{
			$this->template->view($page);
		}
	}
}
