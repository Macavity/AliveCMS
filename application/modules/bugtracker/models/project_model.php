<?php

class Project_Model extends CI_Model {

    /**
     * Gets all projects
     * @return array
     */
    public function getProjects()
    {
        $this->db->select('*')->from('bugtracker_projects')->order_by('order', 'asc');
        $query = $this->db->get();

        $baseProjects = array();

        if($query->num_rows() > 0)
        {
            $baseProjects = $query->result_array();
        }
        return $baseProjects;
    }

    /**
     * Adds a new project to the database
     * @param $data
     * @return id of newly created project
     */
    public function add($data)
    {
        $this->db->insert("bugtracker_projects", $data);
        return $this->db->insert_id();
    }

    /**
     * Edit an existing project
     * @param $id
     * @param $data
     */
    public function edit($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('bugtracker_projects', $data);
    }

    /**
     * Looks for a project based on its ID
     * Returns the FIRST result only.
     * @param $id
     */
    public function findProjectById($id){
        $this->db->select('*')->from('bugtracker_projects')->where('id', $id);
        $query = $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->result_array();
            return $result[0];
        }
        else {
            return FALSE;
        }
    }

    /**
     * Looks for a project based on its Title
     * Can be used to check for duplicates for example
     * Returns the FIRST result only.
     * @param $name
     */
    public function findProjectByTitle($title){
        $this->db->select('*')->from('bugtracker_projects')->where('title', $title);
        $query = $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->result_array();
            return $result[0];
        }
        else {
            return FALSE;
        }
    }

    /**
     * Get all projects and their order
     * @return bool
     */
    public function getProjectsOrder()
    {
        $query = $this->db->select("order, id")->from('bugtracker_projects')->order_by('order', 'desc');

        if($query->num_rows() > 0)
        {
            $result = $query->result_array();

            return $result;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Set the order of a project
     * @param $id
     * @param $order
     */
    public function setOrder($id, $order)
    {
        $this->db->where('id', $id);
        $this->db->update('bugtracker_projects', array(
            "order" => $order
        ));
    }
}