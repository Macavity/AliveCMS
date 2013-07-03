<?php

class Changelog extends MX_Controller
{
	private $changelog_days;
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('changelog_model');
		$this->load->config('changelog');
	}
	
	public function index()
	{
		$this->template->setTitle("Changelog");
		
		$changelog_items = $this->changelog_model->getChangelog($this->config->item('changelog_limit'));
		
		if($changelog_items)
		{
			//Sort by time, this will move every single item to an array with as key the time.
			$changelog_items = $this->sortByTime($changelog_items);
		}
		
		$data = array(
			"changes" => $changelog_items,
			"is_dev" => $this->user->isDev(),
			"url" => $this->template->page_url,
			"categories" => $this->changelog_model->getCategories(),
			'attributes' => array("id" => "category_form", "style" => "display:none;")
		);

		$content =  $this->template->loadPage("changelog.tpl", $data);
		
		$this->template->box("Changelog", $content, true, "modules/changelog/css/changelog.css", "modules/changelog/js/changelog.js");
	}
	
	public function sortByTime($changelog_items)
	{
		$new_array = array();
		
		foreach($changelog_items as $item)
		{
			//If we dont got the time yet add it to the new array
			if(!array_key_exists(date("Y/m/d", $item['time']), $new_array))
			{
				//Assign an array to that key
				$new_array[date("Y/m/d", $item['time'])] = array();
			}
			
			//Do the same but then for the typeName
			if(!array_key_exists($item['typeName'], $new_array[date("Y/m/d", $item['time'])]))
			{
				//Assign an array to that key
				$new_array[date("Y/m/d", $item['time'])][$item['typeName']] = array();
			}
			
			array_push($new_array[date("Y/m/d", $item['time'])][$item['typeName']], $item);
		}

		return $new_array;
	}

	public function addCategory()
	{
		$category = $this->input->post('category');

		if($category && $this->user->isDev())
		{
			$id = $this->changelog_model->addCategory($category);

			redirect('changelog');
		}
		else
		{
			$this->index();
		}
	}

	public function addChange()
	{
		$change = $this->input->post('change');
		$category = $this->input->post('category');

		if($category && $change && $this->user->isDev())
		{
			$id = $this->changelog_model->addChange($change, $category);

			die($id."");
		}
		else
		{
			$this->index();
		}
	}

	public function remove($id = false)
	{
		if($id && is_numeric($id) && $this->user->isDev())
		{
			$this->changelog_model->deleteChange($id);

			$this->index();
		}
		else
		{
			$this->index();
		}
	}
}
