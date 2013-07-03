<?php

class Admin extends MX_Controller
{
	public function __construct()
	{
		// Make sure to load the administrator library!
		$this->load->library('administrator');
		$this->load->helper('tinymce_helper');
		$this->load->model('page_model');

		parent::__construct();
	}

	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Pages");

		$pages = $this->page_model->getPages(true);

        $existingCats = $this->page_model->getPageCategories();
        
        $catTitles = array();
        
        foreach($existingCats as $topCat){
            $catTitles[$topCat['id']] = $topCat['title'];
            foreach($topCat['subCats'] as $subCat){
                $catTitles[$subCat['id']] = $topCat['title']."&rarr;".$subCat['title'];
            }
        }
        
		if($pages)
		{
			foreach($pages as $key => $value)
			{
				if(strlen($value['name']) > 20)
				{
					$pages[$key]['name'] = mb_substr($value['name'], 0, 20) . '...';
				}

				$pages[$key]['rank_name'] = $this->internal_user_model->getRankName($value['rank_needed'], true);
		      
                $pages[$key]['top_title'] = $catTitles[$value['top_category']];
        	}
		}
        
		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'pages' => $pages,
			'existingCats' => $existingCats,
			'ranks' => $this->cms_model->getRanks()
		);

		// Load my view
		$output = $this->template->loadPage("admin.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Custom pages', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/page/js/admin.js");
	}

	public function edit($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$page = $this->page_model->getPage($id);

		if($page == false)
		{
			show_error("There is no page with ID ".$id);

			die();
		}
        
        $existingCats = $this->page_model->getPageCategories();
        
		// Change the title
		$this->administrator->setTitle($page['name']);

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'page' => $page,
            'existingCats' => $existingCats,
			'ranks' => $this->cms_model->getRanks()
		);

		// Load my view
		$output = $this->template->loadPage("admin_edit.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('<a href="'.$this->template->page_url.'page/admin">Custom pages</a> &rarr; '.$page['name'], $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/page/js/admin.js");
	}

    public function edit_cat($id = false)
    {
        if(!$id || !is_numeric($id))
        {
            die();
        }

        $pageCat = $this->page_model->getPageCategory($id);

        if($pageCat == false)
        {
            show_error("There is no page category with ID ".$id);

            die();
        }
        
        $existingCats = $this->page_model->getPageCategories();
        
        // Change the title
        $this->administrator->setTitle($pageCat['title']);

        // Prepare my data
        $data = array(
            'url' => $this->template->page_url,
            'pageCat' => $pageCat,
            'existingCats' => $existingCats,
        );

        // Load my view
        $output = $this->template->loadPage("admin_edit_cat.tpl", $data);

        // Put my view in the main box with a headline
        $content = $this->administrator->box('<a href="'.$this->template->page_url.'page/admin">Custom page category</a> &rarr; '.$pageCat['title'], $output);

        // Output my content. The method accepts the same arguments as template->view
        $this->administrator->view($content, false, "modules/page/js/admin.js");
    }

	public function delete($id = false)
	{
		if(!$id)
		{
			die();
		}
		
		$this->cache->delete('page_*.cache');
		$this->page_model->delete($id);
	}

	public function create($id = false)
	{
		$headline = $this->input->post('name');
		$identifier = $this->input->post('identifier');
		$rank_needed = $this->input->post('rank_needed');
        $top_category = $this->input->post('top_category');
		$content = $this->input->post('content');
        
		if(strlen($headline) > 70 || empty($headline))
		{
			die("The headline must be between 1-70 characters long");
		}
        
		if(empty($content))
		{
			die("Content can't be empty");
		}
        
		if(empty($identifier) || !preg_match("/^[A-Za-z0-9]*$/", $identifier))
		{
			die("Identifier can't be empty and may only contain numbers and letters");
		}
        
		$identifier = strtolower($identifier);
        
		if($identifier == "admin")
		{
			die("The identifier <b>admin</b> is reserved by the system");
		}
        
        $existingCats = $this->page_model->getPageCategories();
        
        if($top_category != 0){
            $topCatFound = false;
            foreach($existingCats as $cat){
                if($cat["id"] == $top_category)
                    $topCatFound = true;
            }
            if(!$topCatFound){
                die("The selected page category ($top_category) doesn't exist.");
            }
        }

		if($this->page_model->pageExists($identifier, $id))
		{
			die("The identifier is already in use");
		}

		if($id)
		{
			$this->page_model->update($id, $headline, $identifier, $rank_needed, $top_category, $content);
			$this->cache->delete('page_*.cache');
		}
		else
		{
			$this->page_model->create($headline, $identifier, $rank_needed, $top_category, $content);
		}

		die("yes");
	}

    public function createCat($id = false)
    {
        $title = $this->input->post('title');
        $path = $this->input->post('path');
        $topCat = $this->input->post('top_cat');
        
        $existingCats = $this->page_model->getPageCategories();

        if(strlen($title) > 50 || empty($title)){
            die("The title must be between 1-50 characters long");
        }
        
        if(empty($path) || !preg_match("/^[A-Za-z0-9\/]*$/", $path)){
            die("Identifier can't be empty and may only contain numbers, letters and slashes.");
        }

        if($topCat != 0){
            $topCatFound = false;
            foreach($existingCats as $cat){
                if($cat["id"] == $topCat)
                    $topCatFound = true;
            }
            if(!$topCatFound){
                die("The selected page category doesn't exist.");
            }
        }

        if($this->page_model->pageCategoryExists($path, $id)){
            die("There is already a category with this path!");
        }

        if($id)
        {
            $this->page_model->updateCat($id, $path, $title, $topCat);
            $this->cache->delete('page_cat_*.cache');
        }
        else
        {
            $this->page_model->createCat($path, $title, $topCat);
        }

        die("yes");
    }
}