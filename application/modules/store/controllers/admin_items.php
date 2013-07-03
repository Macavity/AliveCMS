<?php

class Admin_items extends MX_Controller
{
	private $sideboxModules;

	public function __construct()
	{
		// Make sure to load the administrator library!
		$this->load->library('administrator');
		$this->load->model('items_model');

		parent::__construct();
	}

	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Items");

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'items' => $this->items_model->getItems(),
			'groups' => $this->items_model->getGroups(),
			'realms' => $this->realms->getRealms()
		);

		// Load my view
		$output = $this->template->loadPage("items.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Store', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/store/js/admin_items.js");
	}

	public function createGroup()
	{
		$data["title"] = $this->input->post("title");

		if(!$data['title'])
		{
			die("UI.alert('The fields can\'t be empty')");
		}

		$this->items_model->addGroup($data);

		$this->cache->delete('store_items.cache');

		die('window.location.reload(true)');
	}

	public function create()
	{
		if($this->input->post("query"))
		{
			$data = $this->getQueryData();
		}
		else
		{
			$data = $this->getItemData();
		}

		$this->items_model->add($data);

		$this->cache->delete('store_items.cache');

		die('window.location.reload(true)');
	}

	private function getQueryData()
	{
		$data["name"] = $this->input->post("name");
		$data["description"] = $this->input->post("description");
		$data["quality"] = $this->input->post("quality");
		$data["query_database"] = $this->input->post("query_database");
		$data["query_need_character"] = ($this->input->post("query_need_character") == "true") ? 1 : 0;
		$data["query"] = $this->input->post("query");
		$data["realm"] = $this->input->post("realm");
		$data["group"] = $this->input->post("group");
		$data["vp_price"] = $this->input->post("vpCost");
		$data["dp_price"] = $this->input->post("dpCost");
		$data["icon"] = $this->input->post("icon");
		$data["tooltip"] = 0;

		return $data;
	}

	private function getItemData()
	{
		$data["itemid"] = $this->input->post("itemid");
		$data["description"] = $this->input->post("description");
		$data["realm"] = $this->input->post("realm");
		$data["group"] = $this->input->post("group");
		$data["vp_price"] = $this->input->post("vpCost");
		$data["dp_price"] = $this->input->post("dpCost");

		if(!is_numeric(preg_replace("/,/", "", $data["itemid"])))
		{
			die("UI.alert('Invalid item ID')");
		}

		if(preg_match("/,/", $data["itemid"]))
		{
			$data["name"] = $this->input->post("name");
			$data["tooltip"] = 0;
			$data["quality"] = 4;
			$data["icon"] = "inv_misc_questionmark";
		}
		else
		{
			$item_data = $this->realms->getRealm($data["realm"])->getWorld()->getItem($data["itemid"]);

			if(!$item_data)
			{
				die("UI.alert('Invalid item')");
			}

			$data["name"] = $item_data['name'];
			$data["tooltip"] = 1;
			$data["quality"] = $item_data['Quality'];
			$data["icon"] = file_get_contents($this->template->page_url."icon/get/".$data["realm"]."/".$data["itemid"]);
		}

		return $data;
	}

	public function edit($id = false)
	{
		if(!is_numeric($id) || !$id)
		{
			die();
		}

		$item = $this->items_model->getItem($id);

		if(!$item)
		{
			show_error("There is no item with ID ".$id);

			die();
		}

		// Change the title
		$this->administrator->setTitle($item['name']);

		$data = array(
			'url' => $this->template->page_url,
			'item' => $item,
			'groups' => $this->items_model->getGroups(),
			'realms' => $this->realms->getRealms()
		);

		// Load my view
		$output = $this->template->loadPage("edit_items.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('<a href="'.$this->template->page_url.'store/admin_items">Items</a> &rarr; '.$item['name'], $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/store/js/admin_items.js");
	}

	public function save($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		if($this->input->post("query"))
		{
			$data = $this->getQueryData();
		}
		else
		{
			$data = $this->getItemData();
		}

		$this->items_model->edit($id, $data);

		$this->cache->delete('store_items.cache');

		die('window.location="'.$this->template->page_url.'store/admin_items"');
	}

	public function saveGroup($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$data["title"] = $this->input->post("title");

		if(!$data["title"])
		{
			die();
		}

		$this->items_model->editGroup($id, $data);

		$this->cache->delete('store_items.cache');
	}

	public function delete($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$this->items_model->delete($id);

		$this->cache->delete('store_items');
	}

	public function deleteGroup($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$this->items_model->deleteGroup($id);

		$this->cache->delete('store_items');
	}
}