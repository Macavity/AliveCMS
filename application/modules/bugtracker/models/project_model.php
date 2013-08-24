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
            $project = $this->getProjectById($projectId);
        }

        $children = $this->project_model->getSubProjectIds($projectId);

        if($children){
            $searchFor = array_merge(array($projectId), $children);
            $project["counts"] = $this->getProjectBugStateCounts($searchFor);
        }
        else{
            $project["counts"] = $this->getProjectBugStateCounts($projectId);
        }
        debug($project);


        return $project;
    }

    public function getProjectBugStateCounts($projectId){

        $countStates = $this->bug_model->getBugCountByProject($projectId);

        $countStates["all"] = $countStates[BUGSTATE_ALL];

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

    public function getMaterializedPath($projectId, $parent = -1){

        if($parent === -1){
            if($projectRow = $this->getProjectById($projectId)){
                $parent = $projectRow['parent'];
            }
            else{
                return "error";
            }
        }

        $stringProjectId = str_pad($projectId, 4, "0", STR_PAD_LEFT);

        $path = array($stringProjectId);

        while($parentRow = $this->getProjectById($parent)){
            $path[] = str_pad($parentRow['id'], 4, "0", STR_PAD_LEFT);
            $parent = $parentRow['parent'];
        }

        //debug($path);

        sort($path);

        return implode(".",$path);


    }

    /**
     * Looks for a project based on its ID
     * Returns the FIRST result only.
     * @param integer $projectId
     * @param string $select
     * @return bool
     */
    public function getProjectById($projectId, $select = '*'){
        if(empty($projectId))
            return false;

        $query = $this->db->select($select)->from('bugtracker_projects')->where('id', $projectId)->get();
        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row;
        }
        return FALSE;
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

    /**
     * Find all sub projects of a provided project id
     * @param $projectId
     * @return bool|array
     */
    public function getSubProjects($projectId){

        if(empty($projectId) || !is_numeric($projectId)){
            return FALSE;
        }

        $searchId = str_pad($projectId, 4, "0", STR_PAD_LEFT);
        $query = $this->db
            ->select('id, title')
            ->like('matpath', $searchId)
            ->from('bugtracker_projects')
            ->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }

        return FALSE;
    }

    public function getSubProjectIds($projectId){
        $subProjects = $this->getSubProjects($projectId);
        $subProjectIds = array();
        if($subProjects){
            foreach($subProjects as $sub){
                $subProjectIds[] = $sub['id'];
            }
        }
        return $subProjectIds;
    }
}