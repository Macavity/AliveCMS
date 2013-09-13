<?php

/**
 * Class Admin_Bugs
 * @package Alive/Bugtracker
 */
class Admin_Bugs extends MX_Controller
{

    var $jsPath = "modules/bugtracker/js/bugtracker_admin.js";
    var $mainTitle = "Bugtracker";

    public function __construct()
    {
        // Dummys
        if(false){
            $this->bug_model = new Bug_model();
            $this->project_model = new Project_Model();
        }

        // Make sure to load the administrator library!
        $this->load->library('administrator');
        $this->load->model('project_model');
        $this->load->model('bug_model');

        parent::__construct();

        requirePermission("canEditBugs");
    }

    /**
     * not used
     */
    public function index()
    {

    }

    /**
     * Load the page to edit the item with the given id.
     * @param bool $id
     */
    public function edit($id = false)
    {
        // Check for the permission
        requirePermission("canEditBugs");

        if(!is_numeric($id) || !$id)
        {
            die();
        }

        $bug = $this->bug_model->getBug($id);

        if(!$bug)
        {
            show_error("Dieser Bug existiert nicht.");
            die();
        }

        // Change the title
        $this->administrator->setTitle($project['title']);

        $data = array(
            'url' => $this->template->page_url,
            'project' => $project,
        );

        // Load my view
        $output = $this->template->loadPage("admin_edit_project.tpl", $data);

        // Put my view in the main box with a headline
        $content = $this->administrator->box('<a href="'.$this->template->page_url.'bugtracker/admin_projects/">Bugtracker Projekte</a> &rarr; '.$project['title'], $output);

        // Output my content. The method accepts the same arguments as template->view
        $this->administrator->view($content, false, $this->jsPath);
    }

    /**
     * Save the edited details for the given item id.
     * @param bool $id
     */
    public function save($id = false)
    {
        // Check for the permission
        requirePermission("canEditProjects");

        if(!$id || !is_numeric($id))
        {
            die();
        }

        $data = array(
            "title" => $this->input->post("projectTitle"),
            "description" => $this->input->post("projectDesc"),
        );

        $output = array(
            "state" => "success",
            "message" => "",
            "debug" => print_r($data, true),
        );

        foreach($data as $value)
        {
            /* All fields are mandatory */
            if(empty($value))
            {
                $output["state"] = "error";
                $output["message"] = "Bitte fülle alle Felder aus!";
            }
        }

        if($output["state"] != "error"){
            $this->project_model->update($id, $data);
            $output["message"] = "Das Projekt wurde erfolgreich bearbeitet.";
        }

        // Add log
        $this->logger->createLog('Edited Bugtracker Project', "(".$id.") ".$data['title']);

        $this->plugins->onEditItem($id, $data);

        $this->outputJson($output);
    }

    public function delete($id = false)
    {
        // Check for the permission
        requirePermission("canRemoveProjects");

        if(!$id || !is_numeric($id))
        {
            die();
        }

        $this->project_model->delete($id);

        // Add log
        $this->logger->createLog('Deleted Bugtracker Project', $id);

        $this->plugins->onDeleteItem($id);

        $this->outputJson(array("state" => "success"));
    }

}