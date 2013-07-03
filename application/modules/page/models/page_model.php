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
    
    public function getPageCategories()
    {
        $this->db->select('*')->from('page_category')->order_by('top_category', 'asc');
        $query = $this->db->get();
            
        if($query->num_rows() > 0)
        {
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
        else 
        {
            return false;
        }
    }

	public function delete($id)
	{
		$this->db->query("DELETE FROM pages WHERE id=?", array($id));
	}

	public function create($headline, $identifier, $rank_needed, $top_category, $content)
	{
		$data = array(
			'name' => $headline,
			'identifier' => $identifier,
			'rank_needed' => $rank_needed,
			'top_category' => $top_category,
			'content' => $content
		);

		$this->db->insert("pages", $data);
	}

	public function update($id, $headline, $identifier, $rank_needed, $top_category, $content)
	{
		$data = array(
			'name' => $headline,
			'identifier' => $identifier,
			'rank_needed' => $rank_needed,
            'top_category' => $top_category,
			'content' => $content
		);

		$this->db->where('id', $id);
		$this->db->update("pages", $data);
	}

    public function createCat($path, $title, $topCat)
    {
        $data = array(
            'path' => $path,
            'title' => $title,
            'top_category' => $topCat,
        );

        $this->db->insert("page_category", $data);
    }

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

    /**
     * Get the selected page category from the database
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