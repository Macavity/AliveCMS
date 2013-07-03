<?php

class Install
{
	public function __construct()
	{
		if(!isset($_GET['step']))
		{
			die();
		}
		else
		{
			switch($_GET['step'])
			{
				case "config": $this->config(); break;
				case "database": $this->database(); break;
				case "realms": $this->realms(); break;
				case "ranks": $this->ranks(); break;
				case "folder": $this->check(); break;
			}
		}
	}

	private function check()
	{
		$folder = $_GET['test'];

		$file = fopen("../application/".$folder."/write_test.txt", "w");

		fwrite($file, "success");
		fclose($file);

		unlink("../application/".$folder."/write_test.txt");

		die("1");
	}

	private function config()
	{
		require_once('../application/libraries/configeditor.php');

		$config = new ConfigEditor("../application/config/fusion.php");

		$data['title'] = $_POST['title'];
		$data['server_name'] = $_POST['server_name'];
		$data['realmlist'] = $_POST['realmlist'];
		$data['keywords'] = $_POST['keywords'];
		$data['description'] = $_POST['description'];
		$data['analytics'] = ($_POST['analytics']) ? $_POST['analytics'] : false;
		$data['cdn'] = ($_POST['cdn'] == "yes") ? true : false;
		$data['licenseKey'] = $_POST['license'];
		$data['security_code'] = $_POST['security_code'];

		foreach($data as $key => $value)
		{
			$config->set($key, $value);
		}

		if(in_array($_POST['emulator'], array('arcemu', 'summitemu')))
		{
			switch($_POST['expansion'])
			{
				case "wotlk":
					$config->set('disabled_expansions', array(32));
				break;

				case "tbc":
					$config->set('disabled_expansions', array(24,32));
				break;

				case "vanilla":
					$config->set('disabled_expansions', array(8,24,32));
				break;
			}
		}
		else
		{
			switch($_POST['expansion'])
			{
				case "wotlk":
					$config->set('disabled_expansions', array(3));
				break;

				case "tbc":
					$config->set('disabled_expansions', array(2,3));
				break;

				case "vanilla":
					$config->set('disabled_expansions', array(1,2,3));
				break;

				default:
					$config->set('disabled_expansions', array());
				break;
			}
		}

		$config->save();

		$db = fopen("../application/config/database.php", "w");

		$raw = '<?php
$active_group = "cms";
$active_record = TRUE;

$db["cms"]["hostname"] = "'.$_POST['cms_hostname'].'";
$db["cms"]["username"] = "'.$_POST['cms_username'].'";
$db["cms"]["password"] = "'.$_POST['cms_password'].'";
$db["cms"]["database"] = "'.$_POST['cms_database'].'";
$db["cms"]["dbdriver"] = "mysqli";
$db["cms"]["dbprefix"] = "";
$db["cms"]["pconnect"] = TRUE;
$db["cms"]["db_debug"] = TRUE;
$db["cms"]["cache_on"] = FALSE;
$db["cms"]["cachedir"] = "";
$db["cms"]["char_set"] = "utf8";
$db["cms"]["dbcollat"] = "utf8_general_ci";
$db["cms"]["swap_pre"] = "";
$db["cms"]["autoinit"] = TRUE;
$db["cms"]["stricton"] = FALSE;

$db["account"]["hostname"] = "'.$_POST['realmd_hostname'].'";
$db["account"]["username"] = "'.$_POST['realmd_username'].'";
$db["account"]["password"] = "'.$_POST['realmd_password'].'";
$db["account"]["database"] = "'.$_POST['realmd_database'].'";
$db["account"]["dbdriver"] = "mysqli";
$db["account"]["dbprefix"] = "";
$db["account"]["pconnect"] = TRUE;
$db["account"]["db_debug"] = TRUE;
$db["account"]["cache_on"] = FALSE;
$db["account"]["cachedir"] = "";
$db["account"]["char_set"] = "utf8";
$db["account"]["dbcollat"] = "utf8_general_ci";
$db["account"]["swap_pre"] = "";
$db["account"]["autoinit"] = FALSE;
$db["account"]["stricton"] = FALSE;';

		fwrite($db, $raw);

		fclose($db);

		die('1');
	}

	private function connect()
	{
		require('../application/config/database.php');

		mysql_connect($db['cms']['hostname'], $db['cms']['username'], $db['cms']['password']) or die("MySQL connection could not be established: ".mysql_error());
		mysql_select_db($db['cms']['database']) or die("MySQL connection could not be established: ".mysql_error());
	}

	private function database()
	{
		$this->connect();

		$this->SplitSQL("SQL/fusion_final_full.sql");

		$updates = glob("SQL/updates/*.sql");

		if(count($updates))
		{
			foreach($updates as $update)
			{
				$this->SplitSQL($update);
			}
		}

		die('1');
	}

	private function SplitSQL($file, $delimiter = ';')
	{
		set_time_limit(0);

		if(is_file($file) === true)
		{
			$file = fopen($file, 'r');

			if(is_resource($file) === true)
			{
				$query = array();

				while(feof($file) === false)
				{
					$query[] = fgets($file);

					if(preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1)
					{
						$query = trim(implode('', $query));

						mysql_query($query) or die(mysql_error());

						while(ob_get_level() > 0)
						{
							ob_end_flush();
						}

						flush();
					}

					if(is_string($query) === true)
					{
						$query = array();
					}
				}

				return fclose($file);
			}
		}

		return false;
	}

	private function realms()
	{
		$this->connect();

		$realms = json_decode(stripslashes($_POST['realms']), true);
		$emulator = mysql_real_escape_string($_POST['emulator']);

		if(!is_array($realms))
		{
			die("Something went wrong, no realms were provided. Realms data: ".$realms);
		}

		foreach($realms as $realm)
		{
			mysql_query("INSERT INTO realms(`emulator`, `cap`, `char_database`, `console_password`,	`console_port`,	`console_username`,	`hostname`,	`password`, `realm_port`, `realmName`, `username`, `world_database`)
						VALUES('".$emulator."',
								'".mysql_real_escape_string($realm['cap'])."',
								'".mysql_real_escape_string($realm['characters'])."',
								'".mysql_real_escape_string($realm['console_password'])."',
								'".mysql_real_escape_string($realm['console_port'])."',
								'".mysql_real_escape_string($realm['console_username'])."',
								'".mysql_real_escape_string($realm['hostname'])."',
								'".mysql_real_escape_string($realm['password'])."',
								'".mysql_real_escape_string($realm['port'])."',
								'".mysql_real_escape_string($realm['realmName'])."',
								'".mysql_real_escape_string($realm['username'])."',
								'".mysql_real_escape_string($realm['world'])."')");
		}

		die('1');
	}

	private function ranks()
	{
		$this->connect();

		switch($_POST['emulator'])
		{
			case "arcemu":
				$this->SplitSQL("SQL/ranks_arcemu.sql");
			break;

			case "summitemu":
				$this->SplitSQL("SQL/ranks_arcemu.sql");
			break;

			case "mangos_ra":
				$this->SplitSQL("SQL/ranks_mangos.sql");
			break;

			case "mangos_soap":
				$this->SplitSQL("SQL/ranks_mangos.sql");
			break;

			case "mangosr2_ra":
				$this->SplitSQL("SQL/ranks_mangos.sql");
			break;

			case "mangosr2_soap":
				$this->SplitSQL("SQL/ranks_mangos.sql");
			break;
		}

		die('1');
	}
}

$install = new Install();