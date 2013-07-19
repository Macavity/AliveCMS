<?php

class Project_Model extends CI_Model {

    /**
     * Gets all projects
     * @return array
     */
    public function getProjects()
    {
        $this->db->select('*')->from('bugtracker_projects')
            ->order_by('parent', 'asc')
            ->order_by('order', 'asc');
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

    public function getAllProjectData($projectId, $project = array()){

        if(empty($project)){
            $project = $this->findProjectById($projectId);
        }


        $project["counts"] = $this->getProjectBugStateCounts($projectId);

        return $project;
    }

    public function getProjectBugStateCounts($projectId){

        $countStates = $this->bug_model->getBugCountByProject($projectId);
        //debug($countStates);

        $countStates[BUGSTATE_DONE] *= 1;
        $countStates[BUGSTATE_ACTIVE] *= 1;
        $countStates[BUGSTATE_OPEN] *= 1;

        $countStates["all"] = $countStates[BUGSTATE_DONE] + $countStates[BUGSTATE_ACTIVE] + $countStates[BUGSTATE_OPEN];

        if($countStates["all"] > 0){
            $countStates["percentage"][BUGSTATE_DONE] = round(($countStates[BUGSTATE_DONE]/$countStates["all"])*100);
            $countStates["percentage"][BUGSTATE_ACTIVE] = round(($countStates[BUGSTATE_DONE]/$countStates["all"])*100);
            $countStates["percentage"][BUGSTATE_OPEN] = round(($countStates[BUGSTATE_DONE]/$countStates["all"])*100);
        }
        else{
            $countStates["percentage"][BUGSTATE_DONE] = 0;
            $countStates["percentage"][BUGSTATE_ACTIVE] = 0;
            $countStates["percentage"][BUGSTATE_OPEN] = 0;
        }

        return $countStates;
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