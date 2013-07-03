<?php
/**
 * @package FusionCMS
 * @version 6.0
 * @author Jesper Lindström
 * @author Xavier Geerinck
 * @link http://raxezdev.com/fusioncms
 */
class User
{
	private $CI;
	private $id;
	private $username;
	private $password;
	private $email;
	private $expansion;
	private $online;
	private $rank;
	private $cms_rank;
	private $vp;
	private $dp;
	private $register_date;
	private $last_ip;
	private $nickname;
	private $isGm;
	private $isDev;
	private $isAdmin;
	private $isOwner;
	private $activeChar = 0;
    private $activeRealm = 0;
    
	public function __construct()
	{
		//Get the instance of the CI
		$this->CI = &get_instance();

		//Set the default user values;
		$this->getUserData();
	}
	
	/**
	 * When they log in this should be called to set all the user details.
	 * @param String $username
	 * @param String $sha_pass_hash
	 * @return Int
	 */
	public function setUserDetails($username, $sha_pass_hash)
	{
		$check = $this->CI->external_account_model->initialize($username);
		
		if(!$check)
		{
			return 1;
		}
		elseif(strtoupper($this->CI->external_account_model->getShaPassHash()) == strtoupper($sha_pass_hash))
		{
			// Load the internal values (vp, dp etc.)
			$this->CI->internal_user_model->initialize($this->CI->external_account_model->getId());

			$userdata = array(
	            'id' => $this->CI->external_account_model->getId(),
	            'username' => $this->CI->external_account_model->getUsername(),
	            'password' => $this->CI->external_account_model->getShaPassHash(),
	            'email' => $this->CI->external_account_model->getEmail(),
	            'expansion' => $this->CI->external_account_model->getExpansion(),
	            'rank' => $this->CI->external_account_model->getRank($username, true),
	            'cms_rank' => $this->getRank($this->CI->external_account_model->getId()),
	            'online' => true,
	            'register_date' => preg_replace("/\s.*/", "", $this->CI->external_account_model->getJoinDate()),
	            'last_ip' => $this->CI->external_account_model->getLastIp(),
	            'nickname' => $this->CI->internal_user_model->getNickname(),
	            'activeChar' => $this->CI->internal_user_model->getActiveChar(),
                'activeRealm' => $this->CI->internal_user_model->getActiveRealm(),
	        );

	        $permissions = $this->CI->internal_user_model->getRankPermissions($userdata['cms_rank']);

			$userdata['isGm'] = $permissions['is_gm'];
			$userdata['isDev'] = $permissions['is_dev'];
			$userdata['isAdmin'] = $permissions['is_admin'];
			$userdata['isOwner'] = $permissions['is_owner'];

			//Set the session with the above data
			$this->CI->session->set_userdata($userdata);

			//Reload this object.
			$this->getUserData();

			return 0;
		}
		else
		{
			//Return an error
			return 2;
		}
	}

	/**
	 * Creates a hash of the password we enter
	 * @param String $username
	 * @param String $password in plain text
	 * @return String hashed password
	 */
	public function createHash($username = "", $password = "")
	{
		return $this->CI->realms->getEmulator()->encrypt($username, $password);
	}
	
	/**
	 * Require the user to be signed in to proceed
	 */
	public function userArea()
	{
		//A check so it requires you to be logged in.
		if(!$this->online)
		{
			$this->CI->template->view($this->CI->template->loadPage("page.tpl", array(
				"module" => "default", 
				"headline" => "Permission denied", 
				"content" => "<center style='margin:10px;font-weight:bold;'>You must be signed in to view this page!</center>"
			)));
		}
		
		return;
	}
	
	/**
	 * Require the user to be signed out to proceed
	 */
	public function guestArea()
	{
		//A check so it requires you to be logged out.
		if($this->online)
		{
			$this->CI->template->view($this->CI->template->loadPage("page.tpl", array(
				"module" => "default", 
				"headline" => "Permission denied", 
				"content" => "<center style='margin:10px;font-weight:bold;'>You are already signed in!</center>"
			)));
		}
		
		return;
	}

	/**
	 * Please see userArea() instead
	 * @deprecated 6.05
	 */
	public function is_logged_in()
	{
		$this->userArea();
	}
	
	/**
	 * Please see guestArea() instead
	 * @deprecated 6.05
	 */
	public function is_not_logged_in()
	{
		$this->guestArea();
	}

	/**
	 * Whether the user is online or not
	 * @return Boolean
	 */
	public function isOnline()
	{
		return $this->online;
	}

	/**
	 * Check if the user has permission to do a certain task
	 * @param Int $requiredRank rank ID column
	 * @param Boolean $die
	 * @return Boolean
	 */
	public function requireRank($requiredRank, $die = true)
	{
		$requiredRank = $this->CI->internal_user_model->getAccessId($requiredRank);

		if($this->online)
		{
			$rank = $this->CI->external_account_model->getRank();
		}
		else 
		{
			$rank = $this->CI->config->item('default_guest_rank');
		}

		if($this->rankBiggerThan($rank, $requiredRank))
		{
			if($die)
			{
				if($this->isOnline())
				{
					$this->CI->template->view($this->CI->template->loadPage("page.tpl", array(
						"module" => "default", 
						"headline" => "Permission denied", 
						"content" => "<center style='margin:10px;font-weight:bold;'>You do not have permission to view this page.</center>"
					)));
			}
			else
			{
					$this->CI->template->view($this->CI->template->loadPage("page.tpl", array(
						"module" => "default", 
						"headline" => "Member area", 
						"content" => "<center style='margin:10px;font-weight:bold;'><a href='".pageURL."login'>Please click here to sign in.</a></center>"
					)));
				}
			}
			else
			{
				return false;
			}
		}
		elseif(!$die)
		{
			return true;
		}
	}

	/**
	 * Check if rank A is bigger than rank B
	 * Necessary to compare number-based ranks
	 * with "az" and "a" ranks in ArcEmu.
	 * @param Mixed $a
	 * @param Mixed $b
	 * @return Boolean
	 */
	private function rankBiggerThan($a, $b)
	{
		$a = ($a == "") ? 0 : $a;
		$b = ($b == "") ? 0 : $b;

		if($a === $b)
		{
			return false;
		}

		// Return true if b is bigger than a
		if(is_numeric($a) && is_numeric($b))
		{
			if($a < $b)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		elseif(!is_numeric($a) && !is_numeric($b) && in_array($a, array("az", "a")) && in_array($b, array("az", "a")))
		{
			switch($a)
			{
				case "az": $a = 1; break;
				case "a": $a = 0; break;
			}

			switch($b)
			{
				case "az": $b = 1; break;
				case "a": $b = 0; break;
			}

			if($a < $b)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		elseif(in_array($a, array("az", "a")) && is_numeric($b))
		{
			return false;
		}
		else
		{
			// Unknown
			return true;
		}
	}
	
	/*
	| -------------------------------------------------------------------
	|  Getters
	| -------------------------------------------------------------------
	*/

	/**
	 * Check if the user rank has any staff permissions
	 * @return Boolean
	 */
	public function isStaff($id = false)
	{
		if(!$id)
		{
			if($this->isGm || $this->isDev || $this->isAdmin || $this->isOwner)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$rankId = $this->getRank($id);

			$permissions = $this->CI->internal_user_model->getRankPermissions($rankId);

			if($permissions['is_gm'] || $permissions['is_dev'] || $permissions['is_admin'] || $permissions['is_owner'])
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	public function isGm()
	{
		return $this->isGm;
	}

	public function isDev()
	{
		return $this->isDev;
	}

	public function isAdmin()
	{
		return $this->isAdmin;
	}

	public function isOwner()
	{
		return $this->isOwner;
	}

	/**
	 * Get the CMS user rank (ranks table)
	 * @param Int $userId
	 * @return Int
	 */
	public function getRank($userId = false)
	{
		// Default is the current user
		if(!$userId)
		{
			$accessId = $this->rank;
		}
		else
		{
			$accessId = $this->CI->external_account_model->getRank($userId);
		}

		// If the CMS rank for the current user has already been loaded
		if($userId && $userId == $this->id && !empty($this->cms_rank))
		{
			$rank = $this->cms_rank;
		}
		else
		{
			$rank = $this->CI->internal_user_model->getRank($accessId);
		}

		return $rank;
	}
	
	public function getUserData()
	{
		// If they are logged in sync the settings with our object
		if($this->CI->session->userdata('online') == true)
		{
			$this->id = $this->CI->session->userdata('id');
			$this->nickname = $this->CI->session->userdata('nickname');
            $this->username = $this->CI->session->userdata('username');
			$this->password = $this->CI->session->userdata('password');
			$this->email = $this->CI->session->userdata('email');
			
			$this->expansion = $this->CI->session->userdata('expansion');
			$this->online = true;
			$this->rank = $this->CI->session->userdata('rank');
			$this->register_date = $this->CI->session->userdata('register_date');
			$this->last_ip = $this->CI->session->userdata('last_ip');
			
			$this->cms_rank = $this->CI->session->userdata('cms_rank');
            $this->isGm = $this->CI->session->userdata('isGm');
			$this->isDev = $this->CI->session->userdata('isDev');
			$this->isAdmin = $this->CI->session->userdata('isAdmin');
			$this->isOwner = $this->CI->session->userdata('isOwner');
            
            $this->activeChar = $this->CI->session->userdata('activeChar');
            $this->activeRealm = $this->CI->session->userdata('activeRealm');
			
			$this->vp = false;
			$this->dp = false;
		}
		else
		{
			$this->id = 0;
			$this->nickname = null;
            $this->username =  0;
			$this->password = 0;
			$this->email = null;
			
			$this->expansion = 0;
			$this->online = false;
			$this->rank = -1;  //Guest rank
			$this->register_date = null;
			$this->last_ip = null;
			
			$this->cms_rank = $this->CI->config->item("default_guest_rank");
			$this->isGm = false;
			$this->isDev = false;
			$this->isAdmin = false;
			$this->isOwner = false;
            
            $this->activeChar = 0;
            $this->activeRealm = 0;
            
            $this->vp = 0;
            $this->dp = 0;
		}
	}

	/**
	 * Get the user group name
	 * @return String
	 */
	public function getUserGroup()
	{
		$rank = $this->CI->external_account_model->getRank();

		return $this->CI->internal_user_model->getRankName($rank);
	}

	/**
	 * Check if the account is banned or active
	 * @return String
	 */
	public function getAccountStatus($id = false)
	{
		if($id == false)
		{
			$id = $this->id;
		}

		$result = $this->CI->external_account_model->getBannedStatus($id);

		if(!$result)
		{
			return 'Active';
		}
		else
		{
			if(array_key_exists("banreason", $result))
			{
				return '<span style="color:red;cursor:pointer;" data-tip="<b>Reason:</b> '.$result['banreason'].'">Banned (?)</span>';
			}
			else
			{
				return '<span style="color:red;">Banned</span>';
			}
		}
	}
	
	/**
	 * Get the nickname
	 * @param Int $id
	 * @return String
	 */
	public function getNickname($id = false)
	{
		return $this->CI->internal_user_model->getNickname($id);
	}

	/**
	 * Get the user's avatar
	 * @param Int $id
	 * @param String $size Small/normal
	 * @return String
	 */
	public function getAvatar($id = false, $size = "normal")
	{
		if(!$id)
		{
			$id = $this->id;
		}
		
		switch($size)
		{
			case "normal": $px = 120; break;
			case "small": $px = 44; break;
		}

		$default = $this->CI->template->image_path.$this->CI->template->theme_data[$size.'_avatar'];

		$email = $this->CI->external_account_model->getEmail($id);

		return "https://secure.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($default)."&s=".$px;
	}
	
	/**
	 * get the user it's characters, returns array with realmnames and character names and character id when specified realm is -1 or the default
	 * @param int $userId
	 * @param int $realmId
	 * @return Array
	 */
	public function getCharacters($userId, $realmId = -1)
	{
		if($realmId && $userId)
		{
			$out = array(); //Init the return param
			
			if($realmId == -1) //Get all characters
			{
				//Get the realms 
				$realms = $this->CI->realms->getRealms();
				
				foreach($realms as $realm)
				{
					//Init the vars of the databases
					$character = $realm->getCharacters();
					
					//Open the connection to the databases
					$character->connect();
					
					//Excute queries on it by getting the connection
					$characters = $character->getCharactersByAccount($this->id);
					
					$character_data = array('realmId' => $realm->getId(),'realmName' => $realm->getName(), 'characters' => $characters);
					
					array_push($out, $character_data);
				}
				
				return $out;
			}
			else //Get the characters for the specified realm
			{
				$realm = $this->realms->getRealm($realmId);

				$character = $realm->getCharacters();
					
				//Open the connection to the databases
				$character->connect();
				
				//Excute queries on it by getting the connection
				$characters = $character->getCharactersByAccount($this->id);
				
				$character_data = array('realmId' => $realm->getId(),'realmName' => $realm->getName(), 'characters' => $characters);
			
				return $character_data;
			}
		}
		else
		{
			return false;
		}
	}

	public function getId($username = false)
	{
		if(!$username)
		{
			return $this->id;
		}
		else
		{
			return $this->CI->external_account_model->getId($username);
		}
	}
	
	public function getUsername($id = false)
	{
		return $this->CI->external_account_model->getUsername($id);
	}
	
	public function getPassword()
	{
		$this->getUserData();
		return $this->password;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getExpansion()
	{
		$this->getUserData();
		return $this->expansion;
	}
	
	public function getOnline()
	{
		return $this->online;
	}
	
	public function getRegisterDate()
	{
		return $this->register_date;
	}
	
	public function getVp()
	{
		if($this->vp === false)
		{
			$this->vp = $this->CI->internal_user_model->getVp();
		}
	
		return $this->vp;
	}
	
	public function getDp()
	{
		if($this->dp === false)
		{
			$this->dp = $this->CI->internal_user_model->getDp();
		}

		return $this->dp;
	}
    
    public function getActiveRealm()
    {
        return $this->activeRealm;
    }
    
    public function getActiveChar()
    {
        return $this->activeChar;
    }

	public function getLastIP()
	{
		return $this->last_ip;
	}
	
	/*
	| -------------------------------------------------------------------
	|  Setters
	| -------------------------------------------------------------------
	*/
	public function setUsername($newUsername)
	{
		if(!$newUsername) return;
		$this->CI->external_account_model->setUsername($this->username, $newUsername);
		$this->CI->session->set_userdata('username', $newUsername);
	}
	
	public function setPassword($newPassword)
	{
		if(!$newPassword) return;
		$this->CI->external_account_model->setPassword($this->username, $newPassword);
		$this->CI->session->set_userdata('password', $newPassword);
	}
	
	public function setEmail($newEmail)
	{
		if(!$newEmail) return;
		$this->CI->external_account_model->setEmail($this->username, $newEmail);
		$this->CI->session->set_userdata('email', $newEmail);
	}
	
	public function setExpansion($newExpansion)
	{
		$this->CI->external_account_model->setExpansion($this->username, $newExpansion);
		$this->CI->session->set_userdata('expansion', $newExpansion);
	}
	
	public function setRank($newRank)
	{
		if(!$newRank) return;
		$this->CI->external_account_model->setRank($this->id, $newRank);
		$this->CI->session->set_userdata('rank', $newRank);
	}
	
	public function setVp($newVp)
	{
		$this->vp = $newVp;
		$this->CI->internal_user_model->setVp($this->id, $newVp);
	}
	
	public function setDp($newDp)
	{
		$this->dp = $newDp;
		$this->CI->internal_user_model->setDp($this->id, $newDp);
	}
    
    public function setActiveChar($charGUID, $charRealm){
        $this->activeChar = $charGUID;
        $this->activeRealm = $charRealm;
        $this->CI->internal_user_model->setActiveChar($this->id, $charGUID, $charRealm);
        $this->CI->session->set_userdata('activeChar', $charGUID);
        $this->CI->session->set_userdata('activeRealm', $charRealm);
    }
}
