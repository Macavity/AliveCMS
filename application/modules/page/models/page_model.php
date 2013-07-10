<?php

class Page_model extends CI_Model
{
	public function getPages()
	{
		$this->db->select('*')->from('pages')->order_by('id', 'desc');
		$query = $this->db->get();
			
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
	
			return $result;
		}
		else 
		{
			return false;
		}
	}

	public function delete($id)
	{
		$this->db->query("DELETE FROM pages WHERE id=?", array($id));

		$this->deletePermission($id);
	}

    /**
     * Return all page categories
     * @alive
     * @return array|bool
     */
    public function getPageCategories(){
        $this->db->select('*')->from('page_category')->order_by('top_category', 'asc');
        $query = $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->result_array();
            $topCats = array();

            foreach($result as $key => $cat){
                // Top Categories
                if($cat["top_category"] == 0){
                    $topCats[$cat["id"]] = $cat;
                    $topCats[$cat["id"]]["subCats"] = array();
                }

                // Sub categories
                if($cat["top_category"] != 0){
                    if(array_key_exists($cat["top_category"], $topCats)){
                        $topCats[$cat["top_category"]]["subCats"][] = $cat;
                    }
                }
            }

            return $topCats;
        }
        else {
            return array();
        }

    }

    /**
     * Get the selected page category from the database
     * @alive
     * @param Integer $id
     */
    public function getPageCategory($id){
        $query = $this->db->query("SELECT * FROM page_category WHERE id=?", array($id));

        if($query->num_rows() > 0){
            $result = $query->result_array();

            return $result[0];
        }
        else{
            return false;
        }
    }



    public function setPermission($id)
	{
		$this->db->query("UPDATE pages SET `permission`=? WHERE id=?", array($id, $id));
		$this->db->query("INSERT INTO acl_roles(`name`, `module`) VALUES(?, '--PAGE--')", array($id));
		$this->db->query("INSERT INTO acl_roles_permissions(`role_name`, `permission_name`, `module`, `value`) VALUES(?, ?, '--PAGE--', 1)", array($id, $id));
	}

	public function deletePermission($id)
	{
		$this->db->query("UPDATE pages SET `permission`='' WHERE id=?", array($id));
		$this->db->query("DELETE FROM acl_roles WHERE module='--PAGE--' AND name=?", array($id));
	}

	public function hasPermission($id)
	{
		$query = $this->db->query("SELECT `permission` FROM pages WHERE id=?", array($id));
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			
			return $result[0]['permission'];
		}
		else 
		{
			return false;
		}
	}

	public function create($headline, $identifier,  /* @alive */ $top_category, $content)
	{
		$data = array(
			'name' => $headline,
			'identifier' => $identifier,
            'top_category' => $top_category, /* @alive */
			'content' => $content,
            'rank_needed' => $this->cms_model->getAnyOldRank()
		);

		$this->db->insert("pages", $data);

		return $this->db->insert_id();
	}

	public function update($id, $headline, $identifier,  /* @alive */ $top_category, $content)
	{
		$data = array(
			'name' => $headline,
			'identifier' => $identifier,
            'top_category' => $top_category, /* @alive */
            'content' => $content
		);

		$this->db->where('id', $id);
		$this->db->update("pages", $data);
	}

    /**
     * @alive
     * @param $path
     * @param $title
     * @param $topCat
     */
    public function createCat($path, $title, $topCat)
    {
        $data = array(
            'path' => $path,
            'title' => $title,
            'top_category' => $topCat,
        );

        $this->db->insert("page_category", $data);
    }

    /**
     * @alive
     * @param $id
     * @param $path
     * @param $title
     * @param $topCat
     */
    public function updateCat($id, $path, $title, $topCat)
    {
        $data = array(
            'path' => $path,
            'title' => $title,
            'top_category' => $topCat,
        );

        $this->db->where('id', $id);
        $this->db->update("page_category", $data);
    }

    public function getPage($id)
	{
		$query = $this->db->query("SELECT * FROM pages WHERE id=?", array($id));

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

	public function pageExists($identifier, $id)
	{
		if($id)
		{
			$query = $this->db->query("SELECT COUNT(*) as `total` FROM pages WHERE id !=? AND identifier=?", array($id, $identifier));
		}
		else
		{
			$query = $this->db->query("SELECT COUNT(*) as `total` FROM pages WHERE identifier=?", array($identifier));
		}

		if($query->num_rows())
		{
			$row = $query->result_array();

			if($row[0]['total'])
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

    /**
     * Checks if a specified category already exists
     * @alive
     * @param $path
     * @param $id
     * @return bool
     */
    public function pageCategoryExists($path, $id)
    {
        if(!empty($path) && !empty($id)){
            $query = $this->db->query("SELECT COUNT(*) as `total` FROM page_category WHERE id !=? AND path=?", array($id, $path));
        }
        elseif(empty($path) && $id){
            $query = $this->db->query("SELECT COUNT(*) as `total` FROM page_category WHERE id !=?", array($id));
        }
        else{
            $query = $this->db->query("SELECT COUNT(*) as `total` FROM page_category WHERE path=?", array($path));
        }

        if($query->num_rows())
        {
            $row = $query->result_array();

            if($row[0]['total'])
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

}