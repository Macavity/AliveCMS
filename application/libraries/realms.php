<?php

/**
 * Class Realms
 *
 * @package FusionCMS
 * @author Jesper Lindström
 * @author Xavier Geerinck
 * @author Elliott Robbins
 * @author Marvin Wichmann
 * @link http://fusion-hub.com
 */
class Realms
{
	// Objects
	private $realms;
	private $CI;

	// Runtime values
	private $races;
	private $classes;
	private $races_en;
	private $classes_en;
	private $zones;
	private $hordeRaces;
	private $allianceRaces;

	private $defaultEmulator = "trinity_soap";

	public function __construct()
	{
		$this->CI = &get_instance();
		
		$this->races = array();
		$this->classes = array();
		$this->zones = array();
		$this->realms = array();

		// Load the realm object
		require_once('application/libraries/realm.php');
		
		// Load the emulator interface
		require_once('application/interfaces/emulator.php');
		
		// Get the realms
		$this->CI->load->model('cms_model');
		
		$realms = $this->CI->cms_model->getRealms();

		if($realms != false)
		{
			foreach($realms as $realm)
			{
				// Prepare the database Config
				$config = array(

					// Console settings
                    "console_host" => $realm['console_host'],
					"console_username" => $realm['console_username'],
					"console_password" => $realm['console_password'],
					"console_port" => $realm['console_port'],

					"hostname" => $realm['hostname'],
					"realm_port" => $realm['realm_port'],

					// Database settings
					"world" => array(
						"hostname" => (array_key_exists("override_hostname_world", $realm) && !empty($realm['override_hostname_world'])) ? $realm['override_hostname_world'] : $realm['hostname'],
						"username" => (array_key_exists("override_username_world", $realm) && !empty($realm['override_username_world'])) ? $realm['override_username_world'] : $realm['username'],
						"password" => (array_key_exists("override_password_world", $realm) && !empty($realm['override_password_world'])) ? $realm['override_password_world'] : $realm['password'],
						"database" => $realm['world_database'],
						"dbdriver" => "mysql",
						"port" => (array_key_exists("override_port_world", $realm) && !empty($realm['override_port_world'])) ? $realm['override_port_world'] : 3306,
						"pconnect" => false,
					),

					"characters" => array(
						"hostname" => (array_key_exists("override_hostname_char", $realm) && !empty($realm['override_hostname_char'])) ? $realm['override_hostname_char'] : $realm['hostname'],
						"username" => (array_key_exists("override_username_char", $realm) && !empty($realm['override_username_char'])) ? $realm['override_username_char'] : $realm['username'],
						"password" => (array_key_exists("override_password_char", $realm) && !empty($realm['override_password_char'])) ? $realm['override_password_char'] : $realm['password'],
						"database" => $realm['char_database'],
						"dbdriver" => "mysql",
						"port" => (array_key_exists("override_port_char", $realm) && !empty($realm['override_port_char'])) ? $realm['override_port_char'] : 3306,
						"pconnect" => false,
					)
				);

				// Initialize the realm object
				array_push($this->realms, new Realm($realm['id'], $realm['realmName'], $realm['cap'], $config, $realm['emulator']));
			}
		}
	}
	
	/**
	 * Get the realm objects
	 * @return Realm[]
	 */
	public function getRealms()
	{
		return $this->realms;
	}

    /**
     * Get one specific realm object
     *
     * @param $id
     *
     * @return Realm
     */
	public function getRealm($id)
	{
		foreach($this->realms as $key => $realm)
		{
            /** @var Realm $realm */
			if($realm->getId() == $id)
			{
				return $this->realms[$key];
			}
		}

		show_error("There is no realm with ID ".$id);
	}

    /**
     * Searches for a realm by its name
     *
     * @alive
     * @param $realmName
     * @return mixed
     */
    public function getRealmByName($realmName)
    {

        foreach($this->realms as $key => $realm)
        {
            $compareName = $realm->getName();
            if($compareName == $realmName || strtolower($compareName) == strtolower($realmName))
            {
                return $this->realms[$key];
            }
        }

        return FALSE;
    }

	/**
	 * Check if there's a realm with the specified ID
	 * @return Boolean
	 */
	public function realmExists($id)
	{
		foreach($this->realms as $key => $realm)
		{
			if($realm->getId() == $id)
			{
				return true;
			}
		}

		return false;
	}
	
	/**
	 * Get the total amount of characters owned by one account
	 */
	public function getTotalCharacters($account = false)
	{
		if(!$account)
		{
			$account = $this->CI->user->getId();
		}

		$count = 0;

		foreach($this->getRealms() as $realm)
		{
			$count += $realm->getCharacters()->getCharacterCount($account);
		}

		return $count;
	}

	/**
	 * Load the wow_constants config and populate the arrays
	 */
	private function loadConstants()
	{
		$this->CI->config->load('wow_constants');

		$this->races = $this->CI->config->item('races');
		$this->hordeRaces = $this->CI->config->item('horde_races');
		$this->allianceRaces = $this->CI->config->item('alliance_races');
		$this->classes = $this->CI->config->item('classes');

		$this->races_en = $this->CI->config->item('races_en');
		$this->classes_en = $this->CI->config->item('classes_en');

    }

	/**
	 * Load the wow_zones config and populate the zones array
	 */
	private function loadZones()
	{
		$this->CI->config->load('wow_zones');

		$this->zones = $this->CI->config->item('zones');
	}

    /**
	 * Get the alliance race IDs
	 * @return Array
	 */
	public function getAllianceRaces()
	{
		if(!count($this->allianceRaces))
		{
			$this->loadConstants();
		}

		return $this->allianceRaces;
	}

	/**
	 * Get the horde race IDs
	 * @return Array
	 */
	public function getHordeRaces()
	{
		if(!count($this->hordeRaces))
		{
			$this->loadConstants();
		}

		return $this->hordeRaces;
	}

    /**
     * Returns the faction to a given race id
     * @alive
     * @param Int $race
     * @return String
     */
    public function getFactionString($race){
        if(!count($this->hordeRaces)){
            $this->loadConstants();
        }

        return (in_array($race, $this->hordeRaces) ? "horde" : "alliance");
    }

    public function getFaction($race){
        if(!count($this->hordeRaces)){
            $this->loadConstants();
        }
        return (in_array($race, $this->hordeRaces) ? FACTION_HORDE : FACTION_ALLIANCE);
    }


    /**
	 * Get the name of a race
     * @alive
	 * @param Int $id
	 * @return String
	 */
	public function getRace($id, $gender = 0)
	{
		if(!count($this->races))
		{
			$this->loadConstants();
		}

		if(array_key_exists($id, $this->races))
		{
            $label = $this->races[$id];

            if(is_string($label)){
                return $label;
            }
            else if(is_array($label)){
                return $label[$gender];
            }
            return $this->races[$id];
		}
		return "Unknown";
	}

    public function getRaceIcon($raceId, $gender = 0)
    {
        $label = $this->getRace($raceId, $gender);

        $imgPath = 'application/images/icons/18/race_'.$raceId.'_'.$gender.'.jpg';

        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$imgPath))
        {
            return img(array(
                'src' => $imgPath,
                'title' => $label,
                'width' => 18,
                'height' => 18
            ));
        }

        return "<!-- missing $imgPath -->";
    }

	/**
	 * Get the name of a class
     * @alive
	 * @param Int $id
	 * @return String
	 */
	public function getClass($id, $gender = 0)
	{
		if(!count($this->classes))
		{
			$this->loadConstants();
		}

		if(array_key_exists($id, $this->classes))
		{
            $label = $this->classes[$id];

            if(is_string($label)){
                return $label;
            }
            else if(is_array($label)){
                return $label[$gender];
            }
        }
		return "Unknown";
	}

    public function getClassIcon($classId, $gender)
    {
        $label = $this->getClass($classId, $gender);

        $imgPath = 'application/images/icons/18/class_'.$classId.'.jpg';

        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$imgPath))
        {
            return img(array(
                'src' => $imgPath,
                'title' => $label,
                'width' => 18,
                'height' => 18
            ));
        }

        return "\n<!-- missing $imgPath -->";
    }

    /**
     * Goes through a bitmask of AllowableRaces
     * Returns zero if the mask is empty of all races are active
     * @param $mask
     * @return array|int
     */
    public function getAllowableRaces($mask){

        //debug($mask);
        $mask &= 0x7FF;
        //debug($mask);

        // Return zero if for all class (or for none)
        if($mask == 0x7FF || $mask == 0) {
            return array("mask" => $mask);
        }

        if(!count($this->races_en)){
            $this->loadConstants();
        }

        $i = 1;
        $raceMask = array();

        while($mask) {
            if($mask & 1) {
                $raceMask[] = $i;
            }
            $mask >>= 1;
            $i++;
        }
        return $raceMask;
    }

    /**
     * Goes through a bitmask of AllowableClasses
     * Returns zero if the mask is empty of all classes are active
     * @param $mask
     * @return array|int
     */
    public function getAllowableClasses($mask){
        $mask &= 0x5FF;

        // Return zero if for all class (or for none)
        if($mask == 0x5FF || $mask == 0) {
            return array("none");
        }

        if(!count($this->classes_en)){
            $this->loadConstants();
        }

        $i = 1;
        $classMask = array();

        while($mask) {
            if($mask & 1) {
                $classMask[] = $i;
            }
            $mask>>=1;
            $i++;
        }
        return $classMask;
    }

	/**
	 * Get the zone name by zone ID
	 * @param Int $zoneId
	 * @return String
	 */
	public function getZone($zoneId)
	{
		if(!count($this->zones))
		{
			$this->loadZones();
		}

		if(array_key_exists($zoneId, $this->zones))
		{
			return $this->zones[$zoneId];
		}
		else
		{
			return "Unknown location";
		}
	}

	/**
	 * Load the general emulator, from the first realm
	 */
	public function getEmulator()
	{
		if ($this->realms)
		{
			return $this->realms[0]->getEmulator();
		}

		// Make sure the emulator is installed
		if(file_exists('application/emulators/'.$this->defaultEmulator.'.php'))
		{
			require_once('application/emulators/'.$this->defaultEmulator.'.php');
		}
		else
		{
			show_error("The entered emulator (".$this->defaultEmulator.") doesn't exist in application/emulators/");
		}

		$config = array();
		$config['id'] = 1;

		// Initialize the objects
		$emulator = new $this->defaultEmulator($config);

		return $emulator;
	}

	/**
	 * Get enabled expansions
	 */
	public function getExpansions()
	{
		$expansions = $this->getEmulator()->getExpansions();
		$return = array();

		foreach($expansions as $key => $value)
		{
			if(!in_array($key, $this->CI->config->item("disabled_expansions")))
			{
				$return[$key] = $value;
			}
		}

		return $return;
	}

    /**
     * Format an avatar path as in Class-Race-Gender-Level
     * Modified version for Alive
     *
     * @alive
     * @param $character
     * @return String
     */
	public function formatAvatarPath($character)
	{
		$class = $character['class'];
		$race = $character['race'];

		//$gender = ($character['gender']) ? "f" : "m";
        $gender = $character['gender'];
        $level = $character['level'];
        $folder = "wow";

        if($level < 60){
            $folder = "wow-default";
        }
        elseif($level < 70){
            $folder = "wow";
        }
        elseif($level < 80){
            $folder = "wow-70";
        }
        elseif($level >= 80){
            $folder = "wow-80";
        }

        $file = "application/images/avatars/".$folder."/".$gender."-".$race."-".$class.".gif";

		if(!file_exists($file))
		{
			return "default"."?".$file;
		}
		else
		{
			return $file;
		}
	}

    /**
     * Get the link to the detail page of a certain character
     * @alive
     * @param $character
     * @return string
     */
    public function getArmoryUrl($characterName, $realmId = 1){

        $string = "character/".$realmId."/".$characterName;
        return $string;
    }
}