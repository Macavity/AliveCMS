<?php

/**
 * Class Internal_user_model
 *
 * @package FusionCMS
 * @author Jesper Lindström
 * @author Xavier Geerinck
 * @author Elliott Robbins
 * @link http://fusion-hub.com
 *
 * @property External_account_model $external_account_model
 */

class Internal_user_model extends MY_Model
{
	private $connection;
	private $vp;
	private $dp;
	private $nickname;
	private $permissionCache;
	private $language;

    /**
     * GUID of the active Character of the user account
     * @alive
     * @var int
     */
    private $activeCharGUID = 0;

    /**
     * Realm ID on which the current active character is
     * @alive
     * @var int
     */
    private $activeRealm = 0;

    /**
     * @alive
     * @var int
     */
    private $forumAccountId = 0;

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
			$this->language = $this->config->item('language');
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
			$result = $query->row_array();


			$this->vp = $result['vp'];
			$this->dp = $result['dp'];
            $this->forumAccountId = $result['forum_account_id'];

            if($this->vp == -1)
            {
                $this->importAliveVotePoints($id);
            }

            if($this->forumAccountId == 0)
            {
                $this->importForumAccountId($id);
            }

			$this->location = $result['location'];
			$this->nickname = $result['nickname'];
			$this->language = $result['language'];

            $this->activeCharGUID = $result['active_char_guid']; /* @alive */
            $this->activeRealm = $result['active_realm_id']; /* @alive */

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
        $this->vp = $this->external_account_model->getOldVotePoints($this->external_account_model->getId());

        /**
         * Initial kriegt jeder neue Account 200 VP
         * @alive
         */
        if($this->vp == 0)
        {
            $this->vp = 200;
        }

        $this->dp = 0;
        $this->location = "Unknown";
        $this->nickname = $this->external_account_model->getUsername();

        $this->forumAccountId = $this->external_account_model->getForumAccountId($this->external_account_model->getId());

        if(empty($this->forumAccountId) || $this->forumAccountId == null)
        {
            $this->forumAccountId = 0;
        }

		$array = array(
			'id' => $this->external_account_model->getId(),
			'vp' => $this->vp,
			'dp' => $this->dp,
			'location' => $this->location,
			'nickname' => $this->nickname,
			'language' => $this->config->item('language'),
            'forum_account_id' => $this->forumAccountId,
		);

		$this->connection->insert("account_data", $array);

    }

    /**
     * Imports the old voting_points to the new FusionCMS table
     *
     * @param $id
     */
    public function importAliveVotePoints($id)
    {
        $oldPoints = $this->external_account_model->getOldVotePoints($id);
        $data = array(
            'vp' => $oldPoints,
        );
        $this->connection
            ->where('id', $id)
            ->update('account_data', $data);

        $this->vp = $oldPoints;
    }

    public function importForumAccountId($id)
    {
        $oldValue = $this->external_account_model->getForumAccountId($id);

        if(empty($oldValue) || $oldValue < 0){
            $oldValue = 0;
        }

        $data = array(
            'forum_account_id' => $oldValue,
        );
        $this->connection
            ->where('id', $id)
            ->update('account_data', $data);

        $this->forumAccountId = $oldValue;
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
	
	public function getTotalVotes()
	{
		$query = $this->connection->query("SELECT total_votes FROM account_data WHERE nickname = ?", array($this->nickname));

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
        if($this->vp === -1)
        {
            $this->importAliveVotePoints($id = $this->session->userdata('id'));
        }

		return $this->vp;
	}

	public function getDp()
	{
		return $this->dp;
	}

	public function getLocation()
	{
		return $this->location;
	}

	public function getLanguage()
	{
		return $this->language;
	}

    /**
     * @alive
     * @return Integer
     */
    public function getActiveChar()
    {
        return $this->activeCharGUID;
    }

    /**
     * @alive
     * @return Integer
     */
    public function getActiveRealm()
    {
        return $this->activeRealm;
    }


    /*
    | -------------------------------------------------------------------
    |  Setters
    | -------------------------------------------------------------------
    */
	public function setVp($userId, $vp)
	{
		$this->connection->query("UPDATE account_data SET vp = ? WHERE id = ?", array($vp, $userId));
	}

	public function setLanguage($userId, $language)
	{
		$this->connection->query("UPDATE account_data SET language = ? WHERE id = ?", array($language, $userId));
	}

	public function setDp($userId, $dp)
	{
		$this->connection->query("UPDATE account_data SET dp = ? WHERE id = ?", array($dp, $userId));
	}

	public function setLocation($userId, $location)
	{
		$this->connection->query("UPDATE account_data SET location = ? WHERE id = ?", array($location, $userId));
	}

    /**
     * Set the active character for the user account
     * @param $userId
     * @param $charGUID
     * @param $charRealm
     */
    public function setActiveCharacter($userId, $charGUID, $charRealm) {
        $this->connection->query("UPDATE `account_data` SET `active_char_guid` = ?, `active_realm_id` = ? WHERE id=?", array($charGUID, $charRealm, $userId));
    }

}