<?php

class Admin extends MX_Controller
{
    private $pageCategories = array();

	public function __construct()
	{
		// Make sure to load the administrator library!
		$this->load->library('administrator');
		$this->load->helper('tinymce_helper');
		$this->load->model('page_model');

		requirePermission("canViewAdmin");

		parent::__construct();

        $this->pageCategories = $this->page_model->getPageCategories();

    }

	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Pages");

		$pages = $this->page_model->getPages(true);

        /**
         * Alive Page Categories
         * @alive
         */
        $catTitles = array(
            0 => "Nicht kategorisiert"
        );

        foreach($this->pageCategories as $topCat){
            $catTitles[$topCat['id']] = $topCat['title'];
            foreach($topCat['subCats'] as $subCat){
                $catTitles[$subCat['id']] = $topCat['title']."&rarr;".$subCat['title'];
            }
        }


        if($pages)
		{
			foreach($pages as $key => $value)
			{
                $pages[$key]['name'] = langColumn($pages[$key]['name']);

				if(strlen($pages[$key]['name']) > 20)
				{
					$pages[$key]['name'] = mb_substr($pages[$key]['name'], 0, 20) . '...';
				}
                $pages[$key]['top_title'] = $catTitles[$value['top_category']];
            }
		}

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
            'existingCats' => $this->pageCategories,
            'pages' => $pages
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
		requirePermission("canEdit");

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


        // Change the title
		$this->administrator->setTitle(langColumn($page['name']));

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
            'existingCats' => $this->pageCategories,
            'page' => $page
		);

		// Load my view
		$output = $this->template->loadPage("admin_edit.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('<a href="'.$this->template->page_url.'page/admin">Custom pages</a> &rarr; '.langColumn($page['name']), $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/page/js/admin.js");
	}

    /**
     * Edit a page category
     * @alive
     * @param bool $id
     */
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

        // Change the title
        $this->administrator->setTitle($pageCat['title']);

        // Prepare my data
        $data = array(
            'url' => $this->template->page_url,
            'pageCat' => $pageCat,
            'existingCats' => $this->pageCategories,
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
		requirePermission("canRemove");

		if(!$id)
		{
			die();
		}
		
		$this->cache->delete('page_*.cache');
		$this->page_model->delete($id);

		// Add log
		$this->logger->createLog('Deleted page', $id);

		$this->plugins->onDelete($id);
	}

	public function create($id = false)
	{
		requirePermission("canAdd");

		$headline = $this->input->post('name');
		$identifier = $this->input->post('identifier');
		$content = $this->input->post('content');
        $top_category = $this->input->post('top_category');

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

        if($top_category != 0){
            $topCatFound = false;
            foreach($this->pageCategories as $cat){
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
            $this->page_model->update($id, $headline, $identifier, $top_category, $content); /* @alive */
            $this->cache->delete('page_*.cache');

			$hasPermission = $this->page_model->hasPermission($id);

			if($this->input->post('visibility') == "group" && !$hasPermission)
			{
				$this->page_model->setPermission($id);
			}
			elseif($this->input->post('visibility') != "group" && $hasPermission)
			{
				$this->page_model->deletePermission($id);
			}

			// Add log
			$this->logger->createLog('Edited page', $identifier);

			$this->plugins->onUpdate($id, $headline, $identifier, $content);
		}
		else
		{
			$id = $this->page_model->create($headline, $identifier, $top_category, $content);

			if($this->input->post('visibility') == "group")
			{
				$this->page_model->setPermission($id);
			}

			// Add log
			$this->logger->createLog('Added page', $identifier);

			$this->plugins->onCreate($id, $headline, $identifier, $content);
		}

		die("yes");
	}

    public function createCat($id = false)
    {
        $title = $this->input->post('title');
        $identifier = $this->input->post('identifier');
        $path = $this->input->post('path');
        $topCat = $this->input->post('top_cat');

        $existingCats = $this->page_model->getPageCategories();

        if(strlen($title) > 50 || empty($title)){
            die("The title must be between 1-50 characters long");
        }

        if(empty($path) || !preg_match("/^[A-Za-z0-9\/]*$/", $path)){
            die("Path can't be empty and may only contain numbers, letters and slashes.");
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
            $this->logger->createLog('Added Category', $identifier);
            $this->page_model->createCat($path, $title, $topCat);
            $this->plugins->onCreate($title, $path, $topCat);

        }

        die("yes");

    }
}