<?php

class Ranks_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getRanks()
	{
		$query = $this->db->query("SELECT * FROM ranks");
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else 
		{
			return false;	
		}
	}
	
	public function getRank($id = 0)
	{
		$query = $this->db->query("SELECT * FROM ranks WHERE id = ?", array($id));
		
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
	
	public function add($data)
	{
		$this->db->insert("ranks", $data);
	}

	public function edit($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('ranks', $data);
	}

	public function delete($id)
	{
		$this->db->query("DELETE FROM ranks WHERE id = ?", array($id));
	}
}
