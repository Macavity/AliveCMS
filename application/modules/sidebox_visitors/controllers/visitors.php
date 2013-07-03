<?php

class Visitors extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('sidebox_visitors/visitor_model');
	}
	
	public function view()
	{
		$count = $this->visitor_model->getCount();	
		$word = ($count == 1) ? "visitor" : "visitors";

		$output = $this->template->loadPage("visitors.tpl", array('module' => "sidebox_visitors", 'count' => $count, 'word' => $word));

		return $output;
	}

	public function getAll()
	{
		$guests = $this->visitor_model->getGuestCount();
		$visitors = $this->visitor_model->get();

		if($visitors)
		{
			foreach($visitors as $key => $value)
			{
				if($value['user_data'])
				{
					$data = unserialize($value['user_data']);
					$visitors[$key]['user_id'] = $this->getUserId($data);
					$visitors[$key]['nickname'] = $this->getNickname($data);
				}
			}
		}

		$output = $this->template->loadPage("all_visitors.tpl", array('module' => "sidebox_visitors", 'guests' => $guests, 'visitors' => $visitors));

		die($output);
	}

	private function getUserId($data)
	{
		if(array_key_exists("id", $data))
		{
			return (int)$data['id'];
		}
	}

	private function getNickname($data)
	{
		if(array_key_exists("nickname", $data))
		{
			return $data['nickname'];
		}
	}
}
