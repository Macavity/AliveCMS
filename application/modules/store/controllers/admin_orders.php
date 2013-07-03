<?php

class Admin_orders extends MX_Controller
{
	public function __construct()
	{
		// Make sure to load the administrator library!
		$this->load->library('administrator');
		$this->load->model('store_model');
		
		parent::__construct();
	}

	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Orders");
	
		$completed = $this->store_model->getOrders(1);
		$failed = $this->store_model->getOrders(0);

		if($completed)
		{
			foreach($completed as $k => $v)
			{
				$completed[$k]["username"] = $this->user->getUsername($v['user_id']);
				$completed[$k]["json"] = json_decode($v['cart'], true);

				foreach($completed[$k]["json"] as $key => $value)
				{
					$item = $this->store_model->getItem($value['id']);

					if(isset($value['character']))
					{
						$character = $this->realms->getRealm($item['realm'])->getCharacters()->getNameByGuid($value['character']);
					}
					
					$completed[$k]["json"][$key]['itemName'] = $item['name'];
					$completed[$k]["json"][$key]['characterName'] = (isset($character)) ? $character : "Unknown";
				}
			}
		}

		if($failed)
		{
			foreach($failed as $k => $v)
			{
				$failed[$k]["username"] = $this->user->getUsername($v['user_id']);
				$failed[$k]["json"] = json_decode($v['cart'], true);

				foreach($failed[$k]["json"] as $key => $value)
				{
					$item = $this->store_model->getItem($value['id']);

					if(isset($value['character']))
					{
						$character = $this->realms->getRealm($item['realm'])->getCharacters()->getNameByGuid($value['character']);
					}

					$failed[$k]["json"][$key]['itemName'] = $item['name'];
					$failed[$k]["json"][$key]['characterName'] = (isset($character)) ? $character : "Unknown";
				}
			}
		}

		// Prepare my data
		$data = array(
			'completed' => $completed,
			'failed' => $failed,
			'url' => $this->template->page_url,
		);

		// Load my view
		$output = $this->template->loadPage("admin_orders.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Orders', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/store/js/admin_orders.js");
	}

	public function refund($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$order = $this->store_model->getOrder($id);

		if($order)
		{
			$this->store_model->refund($order['user_id'], $order['vp_cost'], $order['dp_cost']);
			$this->store_model->deleteLog($id);
		}
	}
}