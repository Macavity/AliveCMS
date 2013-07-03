<?php

class Admin extends MX_Controller
{
	public function __construct()
	{
		// Make sure to load the administrator library!
		$this->load->library('administrator');
		$this->load->library('fusioneditor');
		$this->load->model('changelog_model');
		
		parent::__construct();
	}

	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Changelog");
		
		$changes = $this->changelog_model->getChangelog();

		if($changes)
		{
			foreach($changes as $key => $value)
			{
				if(strlen($changes[$key]['changelog']) > 30)
				{
					$changes[$key]['changelog'] = mb_substr($changes[$key]['changelog'], 0, 30) . '...';
				}
			}
		}

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'changes' => $changes,
			'fusionEditor' => $this->fusioneditor->create("text"),
			'categories' => $this->changelog_model->getCategories()
		);

		// Load my view
		$output = $this->template->loadPage("admin.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Changelog', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/changelog/js/admin_changelog.js");
	}
	
	public function create()
	{
		$name = $this->input->post("typeName");

		$this->changelog_model->addCategory($name);

		die('window.location.reload(true)');
	}

	public function addChange($id)
	{
		$data['changelog'] = $this->input->post("change_message");
		$data['author'] = $this->user->getNickname();
		$data['type'] = $id;
		$data['time'] = time();

 		$data['id'] = $this->changelog_model->add($data);

 		$data['date'] = date("Y/m/d");

		die(json_encode($data));
	}
	
	public function edit($id = false)
	{
		if(!is_numeric($id) || !$id)
		{
			die();
		}

		$change = $this->changelog_model->getChange($id);

		if(!$change)
		{
			show_error("There is no change with ID ".$id);

			die();
		}

		// Change the title
		$this->administrator->setTitle("Change #".$id);
		
		$fusionEditor = $this->fusioneditor->create("text", false, 250, $change['changelog']);

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'fusionEditor' => $fusionEditor,
			'changelog' => $change
		);

		// Load my view
		$output = $this->template->loadPage("admin_edit_changelog.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('<a href="'.$this->template->page_url.'changelog/admin">Changelog</a> &rarr; Change #'.$id, $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/changelog/js/admin_changelog.js");
	}
	
	public function delete($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$this->changelog_model->deleteChange($id);
	}

	public function deleteCategory($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$this->changelog_model->deleteCategory($id);
	}
	
	public function save($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$data["changelog"] = $this->input->post("text");

		$this->changelog_model->edit($id, $data);

		die('window.location="'.$this->template->page_url.'changelog/admin"');
	}

	public function saveCategory($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$data['typeName'] = $this->input->post('typeName');

		$this->changelog_model->saveCategory($id, $data);
	}
}