<?php

/**
 * @package FusionCMS
 * @author Jesper Lindström
 * @author Xavier Geerinck
 * @author Elliott Robbins
 * @link http://raxezdev.com/fusioncms
 */

class World_model
{
	private $db;
	private $config;
	private $CI;
	private $realmId;

	/**
	 * Initialize the realm
	 * @param Array $config Database config
	 */
	public function __construct($config)
	{
		$this->config = $config;
		$this->CI = &get_instance();
		$this->realmId = $this->config['id'];
	}

	/**
	 * Connect to the database if not already connected
	 */
	public function connect()
	{
		if(empty($this->db))
		{
			$this->db = $this->CI->load->database($this->config['world'], true);
		}
	}
	
	public function getConnection()
	{
		$this->connect();

		return $this->db;
	}

	/**
	 * Get a specific item row
	 * @param Int $realm
	 * @param Int $id
	 * @return Array
	 */
	public function getItem($id)
	{
		$cache = $this->CI->cache->get("items/item_".$this->realmId."_".$id);

		if($cache !== false)
		{
			return $cache;
		}
		else
		{
			$this->connect();

			$query = $this->db->query(query('get_item', $this->realmId), array($id));

			if($this->db->_error_message())
			{
				die($this->db->_error_message());
			}

			if($query->num_rows() > 0)
			{
				$row = $query->result_array();

                $itemRow = $row[0];

                $counterQuery = $this->db->select('alliance_id,horde_id')
                    ->from('player_factionchange_items')
                    ->where('alliance_id', $id)
                    ->or_where('horde_id', $id)
                    ->get();

                $itemRow['faction'] = "";
                $itemRow['counterpart'] = "";

                if($counterQuery->num_rows() > 0){
                    $row = $counterQuery->result_array();
                    $counterRow = $row[0];

                    if($counterRow["alliance_id"] == $id){
                        $itemRow['faction'] = 0;
                        $itemRow['counterpart'] = $counterRow["horde_id"];
                    }
                    else{
                        $itemRow['faction'] = 1;
                        $itemRow['counterpart'] = $counterRow["alliance_id"];
                    }
                }


				// Cache it forever
				$this->CI->cache->save("items/item_".$this->realmId."_".$id, $itemRow);

				return $itemRow;
			}
			else 
			{
				// Cache it for 24 hours
				$this->CI->cache->save("items/item_".$this->realmId."_".$id, 'empty', 60*60*24);

				return false;	
			}
		}
	}
}