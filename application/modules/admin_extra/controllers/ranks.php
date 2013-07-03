<?php

class Ranks extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('administrator');
		$this->load->model('ranks_model');
	}

	/**
	 * Display the admin panel if we have access
	 */
	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Ranks");

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'ranks' => $this->ranks_model->getRanks()
		);

		// Load my view
		$output = $this->template->loadPage("ranks.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Ranks', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/admin_extra/js/ranks.js");
	}
	
	public function edit($id = false)
	{
		if(!is_numeric($id) || !$id)
		{
			die();
		}

		$rank = $this->ranks_model->getRank($id);

		if(!$rank)
		{
			show_error("There is no rank with ID ".$id);

			die();
		}

		// Change the title
		$this->administrator->setTitle($rank['rank_name']);

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'rank' => $rank
		);

		// Load my view
		$output = $this->template->loadPage("ranks_edit.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('<a href="'.$this->template->page_url.'admin_extra/ranks">Ranks</a> &rarr; '.$rank['rank_name'], $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/admin_extra/js/ranks.js");
	}
	
	public function save($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$data["rank_name"] = $this->input->post("rank_name");
		$data["access_id"] = $this->input->post("access_id");
		$data["is_gm"] = ($this->input->post("is_gm") == "true") ? 1 : 0;
		$data["is_dev"] = ($this->input->post("is_dev") == "true") ? 1 : 0;
		$data["is_admin"] = ($this->input->post("is_admin") == "true") ? 1 : 0;
		$data["is_owner"] = ($this->input->post("is_owner") == "true") ? 1 : 0;

		if(!$data["rank_name"])
		{
			die("UI.alert('The fields can\'t be empty')");
		}

		$this->ranks_model->edit($id, $data);

		die('window.location="'.$this->template->page_url.'admin_extra/ranks"');
	}

	public function create()
	{
		$data["rank_name"] = $this->input->post("rank_name");
		$data["access_id"] = $this->input->post("access_id");
		$data["is_gm"] = ($this->input->post("is_gm") == "true") ? 1 : 0;
		$data["is_dev"] = ($this->input->post("is_dev") == "true") ? 1 : 0;
		$data["is_admin"] = ($this->input->post("is_admin") == "true") ? 1 : 0;
		$data["is_owner"] = ($this->input->post("is_owner") == "true") ? 1 : 0;

		if(!$data["rank_name"])
		{
			die("UI.alert('The fields can\'t be empty')");
		}

		$this->ranks_model->add($data);

		die('window.location.reload(true)');
	}

	public function delete($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$this->ranks_model->delete($id);
	}
}