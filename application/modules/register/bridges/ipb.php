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
		$salt = $this->generatePasswordSalt(5);
		$salt = str_replace( '\\', "\\\\", $salt );

		$username = mysql_real_escape_string($this->username);
		$password = mysql_real_escape_string($this->encryptPassword($salt));
		$email = mysql_real_escape_string($this->email);

		$key = $this->generateAutoLoginKey();
		$expire = time() + 86400;

		mysql_query("INSERT INTO ".$this->config['forum_table_prefix']."members(`name`, `members_pass_hash`, `email`, `members_display_name`, `joined`, `members_pass_salt`, `member_login_key`, `member_login_key_expire`, `members_l_display_name`, `members_l_username`, `members_seo_name`, `member_group_id`) VALUES('$username', '$password', '$email', '$username', '".time()."', '$salt', '$key', '$expire', '$username', '$username', '$username', '3')");
	}

	/**
	 * Encrypt the password with a specific algorithm
	 * @return String
	 */
	private function encryptPassword($salt)
	{
		return md5( md5($salt) . md5( $this->password ) );
	}

	private function generateAutoLoginKey( $len=60 )
	{
		$pass = $this->generatePasswordSalt( $len );

		return md5($pass);
	}

	private function generatePasswordSalt($len=5)
	{
		$salt = '';

		for ( $i = 0; $i < $len; $i++ )
		{
			$num   = mt_rand(33, 126);

			if ( $num == '92' )
			{
				$num = 93;
			}

			$salt .= chr( $num );
		}

		return $salt;
	}
}

$bridge = new Bridge($_POST['username'], $_POST['password'], $_POST['email']);