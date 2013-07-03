<?php

class Sidebox extends MX_Controller
{
	private $sideboxModules;

	public function __construct()
	{
		// Make sure to load the administrator library!
		$this->load->library('administrator');
		$this->load->model('sidebox_model');
		$this->load->library('fusioneditor');

		parent::__construct();
	}

	public function index()
	{
		$this->sideboxModules = $this->getSideboxModules();

		// Change the title
		$this->administrator->setTitle("Sideboxes");

		$sideboxes = $this->cms_model->getSideboxes();

		if($sideboxes)
		{
			foreach($sideboxes as $key => $value)
			{
				$sideboxes[$key]['rank_name'] = $this->internal_user_model->getRankName($value['rank_needed'], true);
				$sideboxes[$key]['name'] = $this->sideboxModules["sidebox_".$value['type']]['name'];
                
                // Neater display of seperate pages
                $sideboxes[$key]['page'] = explode(";", $sideboxes[$key]['page']);
                $sideboxes[$key]['page'] = implode(";<br/>", $sideboxes[$key]['page']);
        
                
				if(strlen($sideboxes[$key]['displayName']) > 15)
				{
					$sideboxes[$key]['displayName'] = mb_substr($sideboxes[$key]['displayName'], 0, 15) . '...';
				}
			}
		}

		$fusionEditor = $this->fusioneditor->create("text");

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'sideboxes' => $sideboxes,
			'sideboxModules' => $this->sideboxModules,
			'fusionEditor' => $fusionEditor,
			'ranks' => $this->cms_model->getRanks()
		);

		// Load my view
		$output = $this->template->loadPage("sidebox.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Sideboxes', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/admin/js/sidebox.js");
	}

	private function getSideboxModules()
	{
		$sideboxes = array();
		
		$this->administrator->loadModules();

		foreach($this->administrator->getModules() as $name => $manifest)
		{
			if(preg_match("/sidebox_/i", $name))
			{
				$sideboxes[$name] = $manifest;
			}
		}

		return $sideboxes;
	}

	public function create()
	{
		$data["type"] = preg_replace("/sidebox_/", "", $this->input->post("type"));
		$data["displayName"] = $this->input->post("displayName");
		$data["rank_needed"] = $this->input->post("rank_needed");
        $data["page"] = $this->input->post("page");
        $data["css_id"] = $this->input->post("css_id");

		foreach($data as $key => $value)
		{
			if(!$value && !in_array($key, array("css_id")))
			{
				die("UI.alert('The fields can\'t be empty')");
			}
		}

		$this->sidebox_model->add($data);

		// Handle custom sidebox text
		if($data['type'] == "custom")
		{
			$text = $this->input->post("text");

			$this->sidebox_model->addCustom($text);
		}

		die('window.location.reload(true)');
	}

	public function edit($id = false)
	{
		if(!is_numeric($id) || !$id)
		{
			die();
		}

		$sidebox = $this->sidebox_model->getSidebox($id);
		$sideboxCustomText = $this->sidebox_model->getCustomText($id);

		if(!$sidebox)
		{
			show_error("There is no sidebox with ID ".$id);

			die();
		}

		$this->sideboxModules = $this->getSideboxModules();

		// Change the title
		$this->administrator->setTitle($sidebox['displayName']);

		$sidebox['rank_name'] = $this->internal_user_model->getRankName($sidebox['rank_needed'], true);
        
        // Neater display of seperate pages
        $onPages = explode(";", $sidebox["page"]);
        $sidebox["page"] = implode("; ", $onPages);
        
		$fusionEditor = $this->fusioneditor->create("text", false, 250, $sideboxCustomText);

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'sidebox' => $sidebox,
			'sideboxModules' => $this->sideboxModules,
			'fusionEditor' => $fusionEditor,
			'ranks' => $this->cms_model->getRanks()
		);

		// Load my view
		$output = $this->template->loadPage("edit_sidebox.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('<a href="'.$this->template->page_url.'admin/sidebox">Sideboxes</a> &rarr; '.$sidebox['displayName'], $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/admin/js/sidebox.js");
	}

	public function move($id = false, $direction = false)
	{
		if(!$id || !$direction)
		{
			die();
		}
		else
		{
			$order = $this->sidebox_model->getOrder($id);

			if(!$order)
			{
				die();
			}
			else
			{
				if($direction == "up")
				{
					$target = $this->sidebox_model->getPreviousOrder($order);
				}
				else
				{
					$target = $this->sidebox_model->getNextOrder($order);
				}

				if(!$target)
				{
					die();
				}
				else
				{
					$this->sidebox_model->setOrder($id, $target['order']);
					$this->sidebox_model->setOrder($target['id'], $order);
				}
			}
		}
	}

	public function save($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$data["type"] = preg_replace("/sidebox_/", "", $this->input->post("type"));
		$data["displayName"] = $this->input->post("displayName");
		$data["rank_needed"] = $this->input->post("rank_needed");
        $data["page"] = $this->input->post("page");
        $data["css_id"] = $this->input->post("css_id");
        
        
        
        $data["page"] = str_replace("; ", ";", $data["page"]);
        $onPages = explode(";", $data["page"]);
        
        $data["page"] = implode(";", $onPages);
        
		foreach($data as $key => $value)
		{
			if(!$value && !in_array($key, array("css_id")))
			{
				die("UI.alert('The fields can\'t be empty')");
			}
		}

		$this->sidebox_model->edit($id, $data);

		// Handle custom sidebox text
		if($data["type"] == "custom")
		{
			$text = $this->input->post("text");
			$this->sidebox_model->editCustom($id, $text);
		}

		die('window.location="'.$this->template->page_url.'admin/sidebox"');
	}

	public function delete($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$this->sidebox_model->delete($id);
	}
}