<?php

class Cms_model extends CI_Model
{
	private $db;

	/**
	 * Connect to the database
	 */
	public function __construct()
	{
		parent::__construct();

		$this->db = $this->load->database("cms", true);

		$this->logVisit();
		$this->clearSessions();
	}

	private function logVisit()
	{
		if(!$this->input->is_ajax_request() && !isset($_GET['is_json_ajax']))
		{
			$this->db->query("INSERT INTO visitor_log(`date`, `ip`) VALUES(?, ?)", array(date("Y-m-d"), $_SERVER['REMOTE_ADDR']));
		}

		$session = array(
			'ip_address' => $this->input->ip_address(),
			'user_agent' => substr($this->input->user_agent(), 0, 120),
		);

		$this->db->where('ip_address', $session['ip_address']);
		$this->db->where('user_agent', $session['user_agent']);

		$query = $this->db->get("ci_sessions");
		
		$data = array(
			"ip_address" => $session['ip_address'],
			"user_agent" => $session['user_agent'],
			"last_activity" => time(),
			"user_data" => ""
		);

		if($this->session->userdata('online'))
		{
			$udata = array(
				'id' => $this->session->userdata('id'),
				'nickname' => $this->session->userdata('nickname'),
			);

			$data['user_data'] = serialize($udata);
		}

		if($query->num_rows() == 0)
		{
			$data['session_id'] = uniqid(time());
			$this->db->insert("ci_sessions", $data);
		}
		else
		{
			$this->db->where('ip_address', $session['ip_address']);
			$this->db->where('user_agent', $session['user_agent']);
			$this->db->update("ci_sessions", $data);
		}
	}

	private function clearSessions()
	{
		$this->db->query("DELETE FROM ci_sessions WHERE last_activity < ?", array(time() - 60*60));
	}
	
    /**
     * Returns all sideboxes for a specific (or default for all) pages
     * @param String $controller
     * @param String $method 
     */
	public function getSideboxes($controller = "all", $method = "*"){
        //debug("loadSideboxes for $controller/$method");
        
        $page = $controller."/".$method;
        $pageWildcard = $controller."/*";

	    // Get all sideboxes
        $query = $this->db->query("SELECT * FROM sideboxes ORDER BY `order` ASC");
        
        $matchingSideboxes = array();
        
        if($controller != "all"){
            foreach($query->result_array() as $row){
                
                $row["page"] = str_replace("; ", ";", $row["page"]);
                $onPages = explode(";", $row["page"]);
                
                if( in_array($page, $onPages) || in_array($pageWildcard, $onPages)){
                    $matchingSideboxes[] = $row;
                }
                
            }   
        }
        else{
            $matchingSideboxes = $query->result_array();
        }
        
        return $matchingSideboxes;
	}

	/**
	 * Load the slider images
	 * @return Array
	 */
	public function getSlides()
	{
		$query = $this->db->query("SELECT * FROM image_slider ORDER BY `order` ASC");
		$result = $query->result_array();

		return $result;
	}

	/**
	 * Get the links of one direction
	 * @param Int $side ID of the specific menu
	 * @return Array
	 */
	public function getLinks($side = "top")
	{
		//Get the rank of our user
		$rank = $this->user->getRank();

		//Get the menu for the rank that we give + the specified rank
		$query = $this->db->query("SELECT * FROM menu WHERE side = ? AND ((rank <= ? AND specific_rank = 0) OR specific_rank = ?) ORDER BY `order` ASC", array($side, $rank, $rank));

		//check if we got results
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else 
		{
			return array();	
		}
	}

	/**
	 * Get the id and rank_name of all ranks
	 * @return Array
	 */
	public function getRanks()
	{
		$query = $this->db->query("SELECT id, rank_name FROM ranks ORDER BY id ASC");
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}
	}
	
	/**
	 * Gets the access_id that we need  to access this page.
	 * @param String $link
	 * @return int access_id or the lowest rank that they can have
	 */
	public function getRankNeededForMenu($link)
	{
		$query = $this->db->query("SELECT access_id FROM menu, ranks WHERE menu.rank = ranks.id AND menu.link = ? LIMIT 1", array($link));
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result[0]['access_id'];
		}
		else
		{
			//Return the lowest rank that we can have (here it's guest)
			return -1;
		}
	}
	
	/**
	 * Get the selected page from the database
	 * @param String $page
	 * @return Array $result[0] IF we found the page else String "error"
	 */
	public function getPage($page)
	{
		$this->db->select('*')->from('pages')->where('identifier', $page);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result[0];
		}
		else
		{
			return false;
		}
	}
    
    /**
     * Calculates a path of breadcrumbs starting from a given top category
     * @param Integer $catId
     * @return Array 
     */
    public function getCategoryPath($catId){
        
        $cat = $this->getPageCategory($catId);
        if($cat && $cat["top_category"] > 0 && $topCat = $this->getPageCategory($cat["top_category"])){
            return array(
                $topCat,
                $cat
            );
        }
        else{
            return array(
                $cat
            );
        }
        
        return array();
        
    }
    
    /**
     * Get the selected page category from the database
     * @param Integer $id
     */
    public function getPageCategory($id){
        $query = $this->db->query("SELECT * FROM page_category WHERE id=?", array($id));

        if($query->num_rows() > 0){
            $result = $query->result_array();

            return $result[0];
        }
        else{
            return false;
        }
    }

	/**
	 * Get all data from the realms table
	 * @return Array
	 */
	public function getRealms()
	{
		$this->db->select('*')->from('realms');
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get the realm database information
	 * @param Int $id
	 * @return Array
	 */
	public function getRealm($id)
	{
		$this->db->select('*')->from('realms')->where('id', $id);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$row = $query->result_array();
			return $row[0];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get the amount of unread messages
	 * @return Int
	 */
	public function getMessagesCount()
	{
		$this->db->select('COUNT(*) as `total`')->from('private_message')->where(array('user_id' => $this->user->getId(), 'read' => 0));
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$row = $query->result_array();
			return $row[0]['total'];
		}
		else
		{
			return 0;
		}
	}
}