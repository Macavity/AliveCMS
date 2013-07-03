<?php

class Menu extends MX_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library("administrator");
		$this->load->model("menu_model");
	}

	/**
	 * Loads the page
	 */
	public function index()
	{
		//Set the title to menu
		$this->administrator->setTitle("Menu links");
		
		$links = $this->menu_model->getMenuLinks();

		if($links)
		{
			foreach($links as $key => $value)
			{
				// Get the rank name
				$links[$key]['rank_name'] = $this->internal_user_model->getRankName($value['rank'], true);

				// Get the rank name
				if($value['specific_rank'] != 0)
				{
					$links[$key]['specific_rank_name'] = $this->internal_user_model->getRankName($value['specific_rank'], true);
				}

				// Shorten the link if necessary
				if(strlen($value['link']) > 12)
				{
					$links[$key]['link_short'] = mb_substr($value['link'], 0, 12) . '...';
				}
				else
				{
					$links[$key]['link_short'] = $value['link'];
				}

				// Add the website path if internal link
				if(!preg_match("/https?:\/\//", $value['link']))
				{
					$links[$key]['link'] = $this->template->page_url . $value['link'];
				}

				// Shorten the name if necessary
				if(strlen($value['name']) > 15)
				{
					$links[$key]['name'] = mb_substr($value['name'], 0, 15) . '...';
				}
			}
		}

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'links' => $links,
			'ranks' => $this->cms_model->getRanks(),
			'pages' => $this->menu_model->getPages()
		);

		// Load my view
		$output = $this->template->loadPage("menu.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Menu links', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/admin/js/menu.js");
	}
	
	public function create()
	{
		$name = $this->input->post('name');
		$link = $this->input->post('link');
		$side = $this->input->post('side');
		$rank = $this->input->post('rank');
		$direct_link = $this->input->post('direct_link');
		
		$specific_rank = $this->input->post('specific_rank');
		
		if($specific_rank != 0)
		{
			$rank = $specific_rank;
		}

		$this->menu_model->add($name, $link, $side, $rank, $specific_rank, $direct_link);

		die('window.location.reload(true)');
	}
	
	public function delete($id)
	{
		if($this->menu_model->delete($id))
		{
			die("success");
		}
		else
		{
			die("An error occurred while trying to delete this menu link.");
		}
		
	}

	public function edit($id = false)
	{
		if(!is_numeric($id) || !$id)
		{
			die();
		}

		$link = $this->menu_model->getMenuLink($id);
	
		if(!$link)
		{
			show_error("There is no link with ID ".$id);

			die();
		}

		// Change the title
		$this->administrator->setTitle($link['name']);

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'link' => $link,
			'ranks' => $this->cms_model->getRanks()
		);

		// Load my view
		$output = $this->template->loadPage("edit_menu.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('<a href="'.$this->template->page_url.'admin/menu">Menu links</a> &rarr; '.$link['name'], $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/admin/js/menu.js");
	}

	public function move($id = false, $direction = false)
	{
		if(!$id || !$direction)
		{
			die();
		}
		else
		{
			$order = $this->menu_model->getOrder($id);

			if(!$order)
			{
				die();
			}
			else
			{
				if($direction == "up")
				{
					$target = $this->menu_model->getPreviousOrder($order);
				}
				else
				{
					$target = $this->menu_model->getNextOrder($order);
				}

				if(!$target)
				{
					die();
				}
				else
				{
					$this->menu_model->setOrder($id, $target['order']);
					$this->menu_model->setOrder($target['id'], $order);
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

		$data['name'] = $this->input->post('name');
		$data['link'] = $this->input->post('link');
		$data['side'] = $this->input->post('side');
		$data['rank'] = $this->input->post('rank');
		$data['direct_link'] = $this->input->post('direct_link');
		$data['specific_rank'] = $this->input->post('specific_rank');

		if($data['specific_rank'] != 0)
		{
			$data['rank'] = $data['specific_rank'];
		}

		$this->menu_model->edit($id, $data);

		die('window.location="'.$this->template->page_url.'admin/menu"');
	}
}