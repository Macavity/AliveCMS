<?php

class Accounts_model extends CI_Model 
{
	private $connection;
	
	public function __construct()
	{
		parent::__construct();
		$this->connection = $this->external_account_model->getConnection();
	}
	
	public function getByEmail($email = "")
	{
		$query = $this->connection->query("SELECT ".allColumns("account")." FROM ".table("account")." WHERE ".column("account", "email")." = ?", array($email));
		
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
	
	public function getByUsername($username = "")
	{
		$query = $this->connection->query("SELECT ".allColumns("account")." FROM ".table("account")." WHERE ".column("account", "username")." = ?", array($username));
		
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
	
	public function getInternalDetails($userId = 0)
	{
		$query = $this->db->query("SELECT * FROM account_data WHERE id = ?", array($userId));
		
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
	
	public function getAccessId($userId = 0)
	{
		$query = $this->connection->query("SELECT ".column("account_access", "gmlevel", true)." FROM ".table("account_access")." WHERE ".column("account", "id")." = ?", array($userId));
		
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
	
	public function save($id, $external_account_data, $external_account_access_data, $internal_data)
	{
		//Update internal
		$this->connection->where(column('account', 'id'), $id);
		$this->connection->update(table('account'), $external_account_data);
		
		
		if($this->getAccessId($id))
		{
			//Update external
			$this->connection->where(column('account_access', 'id'), $id);
			$this->connection->update(table('account_access'), $external_account_access_data);
		}
		else
		{
			$external_account_access_data[column('account_access', 'id')] = $id;
			$this->connection->insert(table('account_access'), $external_account_access_data);
		}
		
		//Update internal
		$this->db->where('id', $id);
		$this->db->update('account_data', $internal_data);
	}
}
