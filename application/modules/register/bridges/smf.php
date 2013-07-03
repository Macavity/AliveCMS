<?php

class Bridge
{
	/**
	 * Runtime values
	 */
	private $connection;
	private $username;
	private $password;
	private $email;
	private $config;

	/**
	 * Receive the user information
	 * @param String $username
	 * @param String $password
	 * @param String email
	 */	
	public function __construct($username, $password, $email)
	{
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;

		require_once("../config/bridge.php");

		$this->config = $config;

		$this->connect();
		$this->process();
	}

	/**
	 * Connect to the database using the bridge config
	 */
	private function connect()
	{
		$config['hostname'] = $this->config['forum_database_hostname'];
		$config['username'] = $this->config['forum_database_username'];
		$config['password'] = $this->config['forum_database_password'];
		$config['database'] = $this->config['forum_database_name'];
		
		mysql_connect($config['hostname'], $config['username'], $config['password']);
		mysql_select_db($config['database']);
	}

	/**
	 * Add the account
	 */
	private function process()
	{
		$username = mysql_real_escape_string($this->username);
		$password = mysql_real_escape_string($this->encryptPassword());
		$email = mysql_real_escape_string($this->email);

		mysql_query("INSERT INTO ".$this->config['forum_table_prefix']."members(`member_name`, `passwd`, `email_address`, `real_name`, `date_registered`) VALUES('$username', '$password', '$email', '$username', '".time()."')");
	}

	/**
	 * Encrypt the password with a specific algorithm
	 * @return String
	 */
	private function encryptPassword()
	{
		return sha1(strtolower($this->username) . $this->password);
	}
}

$bridge = new Bridge($_POST['username'], $_POST['password'], $_POST['email']);