<?php

class Bug_model extends CI_Model
{
    public function getBugs()
    {
        $this->db->select('*')->from('bugs')->order_by('id', 'desc');
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
        $this->db->query("DELETE FROM bugs WHERE id=?", array($id));
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

        $this->db->insert("bugs", $data);
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
        $this->db->update("bugs", $data);
    }

    public function getBug($id)
    {
        $query = $this->db->query("SELECT * FROM bugs WHERE id=?", array($id));

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
}