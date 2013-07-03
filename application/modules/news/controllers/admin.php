<?php

class Admin extends MX_Controller
{
	public function __construct()
	{
		// Make sure to load the administrator library!
		$this->load->library('administrator');
		$this->load->model('news_model');
		$this->load->helper('tinymce_helper');

		parent::__construct();
	}

	public function index()
	{
		// Change the title
		$this->administrator->setTitle("News");
        
		$articles = $this->news_model->getArticles(true);

		if($articles)
		{
			foreach($articles as $key => $value)
			{
				if(strlen($value['headline']) > 20)
				{
					$articles[$key]['headline'] = mb_substr($value['headline'], 0, 20) . '...';
				}	

				$articles[$key]['nickname'] = $this->user->getNickname($value['author_id']);
			}
		}

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'news' => $articles
		);

		// Load my view
		$output = $this->template->loadPage("admin.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('News articles', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/news/js/admin.js");
	}

	public function edit($id = false)
	{
		if(!$id || !is_numeric($id))
		{
			die();
		}

		$article = $this->news_model->getArticle($id);

		if($article == false)
		{
			show_error("There is no article with ID ".$id);
			die();
		}

		// Change the title
		$this->administrator->setTitle($article['headline']);

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'article' => $article
		);

		// Load my view
		$output = $this->template->loadPage("admin_edit.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('<a href="'.$this->template->page_url.'news/admin">News articles</a> &rarr; '.$article['headline'], $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/news/js/admin.js");
	}

	public function delete($id = false)
	{
		if(!$id)
		{
			die();
		}
		
		$this->cache->delete('news_*.cache');
		$this->news_model->delete($id);
	}

	public function create($id = false)
	{
		$headline = $this->input->post('headline');
		$avatar = $this->input->post('avatar');
		$comments = $this->input->post('comments');
		$content = $this->input->post('content');
        $page = $this->input->post('page');

		if(strlen($headline) > 70 || empty($headline))
		{
			die("The headline must be between 1-70 characters long");
		}

		if(empty($content))
		{
			die("Content can't be empty");
		}

		if(in_array($comments, array("1", "yes", "true")))
		{
			$comments = "0";
		}
		else
		{
			$comments = "-1";
		}

		if(in_array($avatar, array("1", "yes", "true")))
		{
			$avatar = $this->user->getAvatar();
		}
		else
		{
			$avatar = "";
		}
        
        // Article page
        if(!in_array($page, array("article", "news")))
        {
            $page = "article";
        }
        
		if($id)
		{
			$this->news_model->update($id, $headline, $avatar, $comments, $content, $page);
		}
		else
		{
			$this->news_model->create($headline, $avatar, $comments, $content, $page);
		}

		$this->cache->delete('news_*.cache');

		die("yes");
	}
}