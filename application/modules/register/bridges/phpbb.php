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
		
		mysql_connect($config['hostname'], $config['username'], $config['password']) or die(mysql_error());
		mysql_select_db($config['database']) or die(mysql_error());
	}

	/**
	 * Add the account
	 */
	private function process()
	{
		$username = mysql_real_escape_string($this->username);
		$password = mysql_real_escape_string($this->encryptPassword());
		$email = mysql_real_escape_string($this->email);

		mysql_query("INSERT INTO ".$this->config['forum_table_prefix']."users(`username`, `user_password`, `user_email`, `username_clean`, `user_regdate`, `user_new`, `group_id`) VALUES('$username', '$password', '$email', '$username', '".time()."', '1','2')") or die(mysql_error());

		$query = mysql_query("SELECT user_id FROM ".$this->config['forum_table_prefix']."users WHERE username='".$username."'") or die(mysql_error());
		$row = mysql_fetch_assoc($query);

		mysql_query("INSERT INTO ".$this->config['forum_table_prefix']."user_group(group_id, user_id, user_pending) VALUES('2', '".$row['user_id']."', '0')") or die(mysql_error());
		
		$query2 = mysql_query("SELECT group_colour FROM ".$this->config['forum_table_prefix']."groups WHERE group_id='2'") or die(mysql_error());
		$row2 = mysql_fetch_assoc($query2);

		mysql_query("UPDATE ".$this->config['forum_table_prefix']."config SET config_value='".$row['user_id']."' WHERE config_name='newest_user_id'");
		mysql_query("UPDATE ".$this->config['forum_table_prefix']."config SET config_value='".$username."' WHERE config_name='newest_username'");
		mysql_query("UPDATE ".$this->config['forum_table_prefix']."config SET config_value='".$row2['group_colour']."' WHERE config_name='newest_user_colour'");
		mysql_query("UPDATE ".$this->config['forum_table_prefix']."config SET config_value=config_value + 1 WHERE config_name='num_users'");
	}

	/**
	 * Encrypt the password with a specific algorithm
	 * @return String
	 */
	private function encryptPassword()
	{
		$password = $this->password;

		$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

		$random_state = uniqid();
		$random = '';
		$count = 6;

		if (($fh = @fopen('/dev/urandom', 'rb')))
		{
			$random = fread($fh, $count);
			fclose($fh);
		}

		if (strlen($random) < $count)
		{
			$random = '';

			for ($i = 0; $i < $count; $i += 16)
			{
				$random_state = md5(uniqid() . $random_state);
				$random .= pack('H*', md5($random_state));
			}
			$random = substr($random, 0, $count);
		}

		$hash = _hash_crypt_private($password, _hash_gensalt_private($random, $itoa64), $itoa64);

		if (strlen($hash) == 34)
		{
			return $hash;
		}

		return md5($password);
	}
}

/**
* The crypt function/replacement
*/
function _hash_crypt_private($password, $setting, &$itoa64)
{
	$output = '*';

	// Check for correct hash
	if (substr($setting, 0, 3) != '$H$' && substr($setting, 0, 3) != '$P$')
	{
		return $output;
	}

	$count_log2 = strpos($itoa64, $setting[3]);

	if ($count_log2 < 7 || $count_log2 > 30)
	{
		return $output;
	}

	$count = 1 << $count_log2;
	$salt = substr($setting, 4, 8);

	if (strlen($salt) != 8)
	{
		return $output;
	}

	/**
	* We're kind of forced to use MD5 here since it's the only
	* cryptographic primitive available in all versions of PHP
	* currently in use.  To implement our own low-level crypto
	* in PHP would result in much worse performance and
	* consequently in lower iteration counts and hashes that are
	* quicker to crack (by non-PHP code).
	*/
	if (PHP_VERSION >= 5)
	{
		$hash = md5($salt . $password, true);
		do
		{
			$hash = md5($hash . $password, true);
		}
		while (--$count);
	}
	else
	{
		$hash = pack('H*', md5($salt . $password));
		do
		{
			$hash = pack('H*', md5($hash . $password));
		}
		while (--$count);
	}

	$output = substr($setting, 0, 12);
	$output .= _hash_encode64($hash, 16, $itoa64);

	return $output;
}

/**
* Generate salt for hash generation
*/
function _hash_gensalt_private($input, &$itoa64, $iteration_count_log2 = 6)
{
	if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
	{
		$iteration_count_log2 = 8;
	}

	$output = '$H$';
	$output .= $itoa64[min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
	$output .= _hash_encode64($input, 6, $itoa64);

	return $output;
}

/**
* Encode hash
*/
function _hash_encode64($input, $count, &$itoa64)
{
	$output = '';
	$i = 0;

	do
	{
		$value = ord($input[$i++]);
		$output .= $itoa64[$value & 0x3f];

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 8;
		}

		$output .= $itoa64[($value >> 6) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 16;
		}

		$output .= $itoa64[($value >> 12) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		$output .= $itoa64[($value >> 18) & 0x3f];
	}
	while ($i < $count);

	return $output;
}

$bridge = new Bridge($_POST['username'], $_POST['password'], $_POST['email']);