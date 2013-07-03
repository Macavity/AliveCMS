<?php

class News_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function delete($id)
	{
		$this->db->query("DELETE FROM articles WHERE id=?", array($id));
	}
	
	/**
	 * Get news entries
	 * @param Int $start
	 * @param Int $limit
     * @param String $page default: article, different if this article is used as a content element on a specific page
	 * @return Array
	 */
	public function getArticles($start = 0, $limit = 1, $page = "article")
	{
		    
        if($page == "all"){
            $this->db->select('*')->from('articles')->order_by('id', 'desc');
        }
        elseif($start === true)
		{
			$this->db->select('*')->from('articles')->where(array("page" => $page))->order_by('id', 'desc');
		}
		else
		{
			$this->db->select('*')->from('articles')->where(array("page" => $page))->order_by('id', 'desc')->limit($limit, $start);
		}

		$query = $this->db->get();
		$result = $query->result_array();

		// Did we have any results?
		if($result)
		{
			return $this->template->format($result);
		}
		else
		{
			// Instead of showing a blank space, we show a default article
			return array(
						array(
							'id' => 0,
							'headline' => 'Willkommen zu FusionCMS V6!',
							'content' => 'Welcome to your new website! This news article will disappear as soon as you add a new one.',
							'author_id' => 0,
							'timestamp' => time(),
							'avatar' => null,
							'comments' => -1,
							'page' => 'article',
						)
					);
		}
	}

	public function getArticle($id)
	{
		$query = $this->db->query("SELECT * FROM articles WHERE id=?", array($id));

		if($query->num_rows() > 0)
		{
			$result = $query->result_array();

			return $result[0];
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Count the articles
	 * @return Int
	 */
	public function countArticles()
	{
		return $this->db->count_all('articles');
	}

	/**
	 * Check whether an article exists or not
	 * @param Int $id
	 * @param Boolean $comment Check if comments are enabled
	 */
	public function articleExists($id, $comment = false)
	{
		$this->db->select('comments')->from('articles')->where('id', $id);
		$query = $this->db->get();
		$result = $query->result_array();

		// If comments are enabled
		if($comment && count($result) && $result[0]['comments'] != -1)
		{
			return true;
		}
		// If article exists
		elseif(!$comment && count($result))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function create($headline, $avatar, $comments, $content, $page = "article")
	{
		$data = array(
			'headline' => $headline,
			'avatar' => $avatar,
			'comments' => $comments,
			'content' => $content,
            'page' => $page,
			'timestamp' => time(),
			'author_id' => $this->user->getId()
		);

		$this->db->insert("articles", $data);
	}

	public function update($id, $headline, $avatar, $comments, $content, $page = "article")
	{
		$data = array(
			'headline' => $headline,
			'avatar' => $avatar,
			'comments' => $comments,
			'content' => $content,
            'page' => $page,
		);

		if($data['comments'] == 0)
		{
			$query = $this->db->query("SELECT COUNT(*) as `total` FROM comments WHERE article_id=?", array($id));
			$result = $query->result_array();

			if($result[0]['total'] != 0)
			{
				$data['comments'] = $result[0]['total'];
			}
		}

		$this->db->where('id', $id);
		$this->db->update("articles", $data);
	}
}
