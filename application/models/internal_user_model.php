<?php
/**
 * This class takes care of the internal account system
 */
class Internal_user_model extends CI_Model
{
	private $connection;
	private $vp;
	private $dp;
	private $nickname;
	private $permissionCache;
    private $activeChar = 0;
    private $activeRealm = 0;
	
	public function __construct()
	{
		parent::__construct();

		$this->connection = $this->load->database("cms", true);
		$this->permissionCache = array();
		
		if($this->user->getOnline())
		{
			$this->initialize();
		}
		else
		{
			$this->vp = 0;
			$this->dp = 0;
			$this->location = "";
			$this->nickname = "";
		}
	}

	public function initialize($id = false)
	{
		if(!$id)
		{
			$id = $this->session->userdata('id');
		}

		$this->connection->select('*')->from('account_data')->where(array('id' => $id));
		$query = $this->connection->get();

		if($this->connection->_error_message())
		{
			die($this->connection->_error_message());
		}

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			
			$this->vp = $result[0]['vp'];
			$this->dp = $result[0]['dp'];
			$this->location = $result[0]['location'];
			$this->nickname = $result[0]['nickname'];
            $this->activeChar = $result[0]['activeChar'];
            $this->activeRealm = $result[0]['activeRealm'];
		}
		else 
		{
			$this->makeNew();
		}
	}

	/**
	 * Creates the internal-stored user info
	 */
	public function makeNew()
	{
		$array = array(
					'id' => $this->external_account_model->getId(),
					'vp' => 0,
					'dp' => 0,
					'Location' => "Unknown",
					"nickname" => $this->external_account_model->getUsername(),
					"activeChar" => 0,
					"activeRealm" => 0,
				);

		$this->connection->insert("account_data", $array);

		$this->vp = 0;
		$this->dp = 0;
		$this->location = "Unknown";
		$this->nickname = $this->external_account_model->getUsername();
	}
	
	public function nicknameExists($nickname)
	{
		$count = $this->connection->from('account_data')->where(array('nickname' => $nickname))->count_all_results();
		
		if($count)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*
	| -------------------------------------------------------------------
	|  Getters
	| -------------------------------------------------------------------
	*/
	
	/**
	 * Get the nickname
	 * @param Int $id
	 * @return String
	 */
	public function getNickname($id = false)
	{
		if(!$id)
		{
			return $this->nickname;
		}
		else
		{
			$this->connection->select('nickname')->from('account_data')->where(array('id' => $id));
			$query = $this->connection->get();
			
			if($query->num_rows() > 0)
			{
				$result = $query->result_array();
			}
			else
			{
				$result[0]['nickname'] = "";
			}

			if(strlen($result[0]['nickname']) > 0)
			{
				return $result[0]['nickname'];
			}
			else 
			{
				return $this->external_account_model->getUsername($id);
			}
		}
	}

	/**
	 * Gets the value of the specified table, column where value = value
	 * @param String $table
	 * @param String $column
	 * @param String $value
	 * @return String, bool
	 */
	public function getValue($table, $column, $value, $columns = "*") 
	{
		//Continue with selecting data.
		$this->connection->select($columns)->from($table)->where(array($column => $value));
		$query = $this->connection->get();
		$result = $query->result_array();

		if($query->num_rows() > 0)
		{
			return $result[0];
		}
		else
		{
			return "";
		}
	}

	/**
	 * Get the rank name
	 * @param Int $accessId
	 * @return String
	 */
	public function getRankName($accessId, $isId = false)
	{
		if($isId)
		{
			$result = $this->getValue("ranks", "id", $accessId);
		}
		else
		{
			$result = $this->getValue("ranks", "access_id", $accessId);
		}

		if(!is_array($result))
		{
			$result['rank_name'] = "Unknown rank";
		}

		return $result['rank_name'];
	}

	/**
	 * Get the rank ID
	 * @param Int $accessId
	 * @return String
	 */
	public function getRank($accessId)
	{
		$result = $this->getValue("ranks", "access_id", $accessId);
		
		if(!is_array($result) && !$accessId)
		{
			$result['id'] = $this->config->item("default_guest_id");
		}
		elseif(!is_array($result) && $accessId)
		{
			$result['id'] = $this->config->item("default_player_id");
		}

		return $result['id'];
	}

	public function getRankPermissions($rankId)
	{
		if(array_key_exists($rankId, $this->permissionCache))
		{
			return $this->permissionCache[$rankId];
		}
		else
		{
			$query = $this->connection->query("SELECT is_gm, is_dev, is_admin, is_owner FROM ranks WHERE id = ?", array($rankId));
		
			if($query->num_rows() > 0)
			{
				$result = $query->result_array();
				$arr = $result[0];
			}
			else
			{
				$arr = array('is_gm' => false, 'is_dev' => false, 'is_admin' => false, 'is_owner' => false);
			}

			$this->permissionCache[$rankId] = $arr;

			return $arr;
		}
	}

	public function getAccessId($rankId)
	{
		$query = $this->connection->query("SELECT access_id FROM ranks WHERE id = ?", array($rankId));
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result[0]['access_id'];
		}
		else
		{
			return false;
		}
	}
	
	public function getIdByNickname($nickname)
	{
		$query = $this->connection->query("SELECT id FROM account_data WHERE nickname = ?", array($nickname));

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			
			return $result[0]['id'];
		}
		else
		{
			return false;
		}
	}

	public function getVp()
	{
		return $this->vp;
	}

	public function getDp()
	{
		return $this->dp;
	}

    public function getActiveChar()
    {
        return $this->activeChar;
    }

    public function getActiveRealm()
    {
        return $this->activeRealm;
    }

	public function getLocation()
	{
		return $this->location;
	}
	
	/*
	| -------------------------------------------------------------------
	|  Setters
	| -------------------------------------------------------------------
	*/
	public function setVp($userId, $vp)
	{
		$this->connection->query("UPDATE account_data SET vp = ? WHERE id=?", array($vp, $userId));
	}

	public function setDp($userId, $dp)
	{
		$this->connection->query("UPDATE account_data SET dp = ? WHERE id=?", array($dp, $userId));
	}

	public function setLocation($userId, $location)
	{
		$this->connection->query("UPDATE account_data SET location = ? WHERE id=?", array($location, $userId));
	}
	
    public function setActiveChar($userId, $charGUID, $charRealm)
    {
        $this->connection->query("UPDATE account_data SET activeChar = ?, activeRealm = ? WHERE id=?", array($charGUID, $charRealm, $userId));
    }
}