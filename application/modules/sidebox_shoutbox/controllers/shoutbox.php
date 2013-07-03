<?php

class Shoutbox extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('sidebox_shoutbox/shoutbox_model');
		$this->load->config('sidebox_shoutbox/shoutbox_config');
	}
	
	public function view()
	{
		$shouts = $this->get();

		$data = array(
						"module" => "sidebox_shoutbox",
						"shouts" => $shouts,
						"logged_in" => $this->user->getOnline(),
						"count" => $this->getCount(),
						"shoutsPerPage" => $this->config->item("shouts_per_page")
					);
					
		$out = $this->template->loadPage("shoutbox_view.tpl", $data);
		
		return $out;
	}

	public function get($id = false)
	{
		// Is it loaded via ajax or not?
		if($id === false)
		{
			$id = 0;
			$die = false;
		}
		else
		{
			$die = true;
		}

		$cache = $this->cache->get("shoutbox_".$id);

		if($cache !== false)
		{
			$shouts = $cache;
		}
		else
		{
			// Load the shouts
			$shouts = $this->shoutbox_model->getShouts($id, $this->config->item('shouts_per_page'));
			
			// Format the shout data
			foreach($shouts as $key => $value)
			{
				$shouts[$key]['nickname']= $this->internal_user_model->getNickname($shouts[$key]['author']);
				$shouts[$key]['content'] = $this->template->format($shouts[$key]['content'], true, true, true, 40);
				$shouts[$key]['is_gm'] = $this->user->isStaff($shouts[$key]['author']);
			}

			$this->cache->save("shoutbox_".$id, $shouts);
		}

		foreach($shouts as $key => $value)
		{
			$shouts[$key]['date'] = $this->template->formatTime(time() - $shouts[$key]['date']);
		}
			
		// Prepare the data
		$data = array(
					"module" => "sidebox_shoutbox",
					"shouts" => $shouts,
					"url" => $this->template->page_url,
					"user_is_gm" => $this->user->isStaff()
				);
					
		$shouts = $this->template->loadPage("shouts.tpl", $data);

		// To be or not to be, that's the question :-)
		if($die)
		{
			die($shouts);
		}
		else
		{
			return $shouts;
		}
	}
	
	public function submit()
	{
		if($this->user->isOnline() && $this->input->post('message'))
		{
			$this->cache->delete('shoutbox_*');
			$content = $this->input->post('message');
			$this->shoutbox_model->insertShout($content);

			$data = array(
						'uniqueId' => uniqid(),
						'message' => wordwrap($this->template->format($content, true)),
						'name' => $this->user->getNickname(),
						'id' => $this->user->getId(),
						'time' => $this->template->formatTime(1)
					);

			die(json_encode($data));
		}
	}

	private function getCount()
	{
		$cache = $this->cache->get("shoutbox_count");

		if($cache !== false)
		{
			return $cache;
		}
		else
		{
			$count = $this->shoutbox_model->getCount();

			$this->cache->save("shoutbox_count", $count);

			return $count;
		}
	}

	public function delete($id = false)
	{
		if(!$id)
		{
			die();
		}
		else
		{
			if($this->user->isStaff())
			{
				$this->shoutbox_model->deleteShout($id);

				$this->cache->delete('shoutbox_*');

				die('Success');
			}
		}
	}
}
