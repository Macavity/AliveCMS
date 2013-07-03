<?php

class Admin extends MX_Controller
{
	private $coreModules;

	public function __construct()
	{
		parent::__construct();

		$this->coreModules = array('admin', 'login', 'logout', 'error', 'news');

		// Make sure to load the administrator library!
		$this->load->library('administrator');

		// Load the JSON prettifier
		require_once('application/libraries/prettyjson.php');

		$this->load->model('dashboard_model');
	}

	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Dashboard");

		$this->administrator->loadModules();

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'enabled_modules' => $this->getEnabledModules(),
			'disabled_modules' => $this->getDisabledModules(),
			'theme' => $this->template->theme_data,
			'version' => $this->administrator->getVersion(),
			'php_version' => phpversion(),
			'header_url' => $this->config->item('header_url'),
			'theme_value' => $this->config->item('theme'),
			'unique' => $this->getUnique(),
			'views' => $this->getViews(),
			'income' => $this->getIncome(),
			'votes' => $this->getVotes(),
			'signups' => $this->getSignups(),
			'graph' => $this->getGraph()
		);

		// Load my view
		$output = $this->template->loadPage("dashboard.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Dashboard', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/admin/js/admin.js");
	}

	private function getUnique()
	{
		$data['today'] = $this->dashboard_model->getUnique("today");
		$data['month'] = $this->dashboard_model->getUnique("month");

		return $data;
	}

	private function getViews()
	{
		$data['today'] = $this->dashboard_model->getViews("today");
		$data['month'] = $this->dashboard_model->getViews("month");

		return $data;
	}

	private function getIncome()
	{
		$data['this'] = $this->dashboard_model->getIncome("this");
		$data['last'] = $this->dashboard_model->getIncome("last");

		return $data;
	}

	private function getVotes()
	{
		$data['this'] = $this->dashboard_model->getVotes("this");
		$data['last'] = $this->dashboard_model->getVotes("last");

		return $data;
	}

	private function getSignups()
	{
		$data['today'] = $this->dashboard_model->getSignupsDaily("today");
		$data['month'] = $this->dashboard_model->getSignupsDaily("month");
		$data['this'] = $this->dashboard_model->getSignupsMonthly("this");
		$data['last'] = $this->dashboard_model->getSignupsMonthly("last");
		
		$cache = $this->cache->get("total_accounts");

		if($cache !== false)
		{
			$data['total'] = $cache;
		}
		else
		{
			$data['total'] = $this->external_account_model->getAccountCount();
			$this->cache->save("total_accounts", $data['total'], 60*60*24);
		}

		return $data;
	}

	private function getGraph()
	{
		$cache = $this->cache->get("dashboard");

		if($cache !== false)
		{
			$data = $cache;
		}
		else
		{
			$row = $this->dashboard_model->getGraph();
			
			$data = array(
				'stack' => $this->arrayFormat($row),
				'top' => $this->getHighestValue($row),
				'first_date' => $this->getFirstDate($row),
				'last_date' => $this->getLastDate($row)
			);

			$this->cache->save("dashboard", $data, 60*60*24);
		}

		return $data;
	}

	private function getHighestValue($array)
	{
		if($array)
		{
			$highest = 0;

			foreach($array as $value)
			{
				if($value['ipCount'] > $highest)
				{
					$highest = $value['ipCount'];
				}
			}

			return $highest;
		}
		else
		{
			return false;
		}
	}

	private function arrayFormat($array)
	{
		if($array)
		{
			$output = "";
			$first = true;

			foreach($array as $month)
			{
				if($first)
				{
					$first = false;
					$output .= $month['ipCount'];
				}
				else
				{
					$output .= ",".$month['ipCount'];
				}
			}

			return $output;
		}
		else
		{
			return false;
		}
	}

	private function getLastDate($array)
	{
		if($array)
		{
			$value = preg_replace("/-/", " / ", $array[count($array)-1]['date']);

			return preg_replace("/ \/ [0-9]*$/", "", $value);
		}
		else
		{
			return false;
		}
	}

	private function getFirstDate($array)
	{
		if($array)
		{
			$value = preg_replace("/-/", " / ", $array[0]['date']);

			return preg_replace("/ \/ [0-9]*$/", "", $value);
		}
		else
		{
			return false;
		}
	}


	private function getEnabledModules()
	{
		$enabled = array();

		foreach($this->administrator->getModules() as $name => $manifest)
		{
			if($manifest['enabled'])
			{
				$enabled[$name] = $manifest;
			}
		}

		return $enabled;
	}

	private function getDisabledModules()
	{
		$disabled = array();

		foreach($this->administrator->getModules() as $name => $manifest)
		{
			if(!array_key_exists("enabled", $manifest) || !$manifest['enabled'])
			{
				$disabled[$name] = $manifest;
			}
		}

		return $disabled;
	}
	
	public function enable($moduleName)
	{
		$this->changeManifest($moduleName, "enabled", true);

		die('SUCCESS');
	}
	
	public function disable($moduleName)
	{
		if(!in_array($moduleName, $this->coreModules))
		{
			$this->changeManifest($moduleName, "enabled", false);

			die('SUCCESS');
		}
		else
		{
			die('CORE');
		}
	}
	
	public function changeManifest($moduleName, $setting, $newValue)
	{
		$filePath = "application/modules/".$moduleName."/manifest.json";
		$manifest = json_decode(file_get_contents($filePath), true);

		// Replace the setting with the newValue
		$manifest[$setting] = $newValue;

		$prettyJSON = new PrettyJSON($manifest);

		// Rewrite the file with the new data
		$fileHandle = fopen($filePath, "w");
		fwrite($fileHandle, $prettyJSON->get());
		fclose($fileHandle);
	}

	public function saveHeader()
	{
		$header_url = $this->input->post('header_url');

		require_once('application/libraries/configeditor.php');
		
		$fusionConfig = new ConfigEditor("application/config/fusion.php");

		$fusionConfig->set('header_url', $header_url);

		$fusionConfig->save();
	}

	public function remote()
	{
		$license = $this->config->item('licenseKey');

		$c = curl_init();

		curl_setopt($c, CURLOPT_URL, 'http://fusion.raxezdev.com/remote/');
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, 'version='.$this->administrator->getVersion().'&license='.$license);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_HEADER, 0);
		
		$response = curl_exec($c);
		
		curl_close($c);

		if($response == "2")
		{
			// Do nasty stuff
			$message_content = '<?php

/**
 * @package FusionCMS
 * @version 6.0
 * @author Jesper Lindström
 * @author Xavier Geerinck
 * @link http://raxezdev.com/fusioncms
 */

/*
|--------------------------------------------------------------------------
| Enable announcement message
|--------------------------------------------------------------------------
|
| Whether or not to show the announcement message
|
*/
$config["message_enabled"] = true;

/*
|--------------------------------------------------------------------------
| Message headline
|--------------------------------------------------------------------------
|
| ["message_headline"] Announcement headline
| ["message_headline_size"] Size of the headline in px
|
*/
$config["message_headline"] = "Unauthorized copy!";
$config["message_headline_size"] = 56;

/*
|--------------------------------------------------------------------------
| Message text
|--------------------------------------------------------------------------
*/
$config["message_text"] = "This copy of FusionCMS has been terminated due to illegal usage. If you actually own a legit copy, please contact us at <a href=\"http://fusion.raxezdev.com/\" style=\"text-decoration:none;color:white;\">fusion.raxezdev.com</a>";';

			$message_file = fopen("application/config/message.php", "w");
			fwrite($message_file, $message_content);
			fclose($message_file);

			// Log out
			$this->input->set_cookie("fcms_username", false);
			$this->input->set_cookie("fcms_password", false);
			
			delete_cookie("fcms_username");
			delete_cookie("fcms_password");

			$this->session->sess_destroy();
		}

		die($response);
	}
}