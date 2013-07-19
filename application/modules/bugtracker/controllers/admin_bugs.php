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
     * Startseite, zeigt alle Projekte und von jedem Projekt die Beschreibung und Anzahl der Bugs
     */
    public function index()
    {
        // Change the title
        $this->administrator->setTitle($this->mainTitle);

        $projects = $this->project_model->getProjects(true);
        $projectCount = count($projects);

        foreach($projects as $key => $project){

            $countBugs = $this->bug_model->getBugCountByProject($project["id"]);

            $project["done_tickets"] = $countBugs[BUGSTATE_DONE] * 1;
            $project["open_tickets"] = $countBugs[BUGSTATE_OPEN] * 1;
            $project["all_tickets"] = $countBugs[BUGSTATE_DONE] + $countBugs[BUGSTATE_OPEN] + $countBugs[BUGSTATE_ACTIVE];

            $project["percentage"] = ($project["all_tickets"] > 0) ? round($project["done_tickets"]/$project["all_tickets"])*100 : 0;
            //debug($project);

            // Update Original array element
            $projects[$key] = $project;
        }


        // Prepare my data
        $templateData = array(
            'url' => $this->template->page_url,
            'projects' => $projects,
            'projectCount' => $projectCount,
        );

        // Load my view
        $output = $this->template->loadPage("admin_bugs_index.tpl", $templateData);

        // Put my view in the main box with a headline
        $content = $this->administrator->box('Bugtracker', $output);

        $this->administrator->view($content, false, $this->jsPath);
    }

    public function import(){
        $this->bug_model->importOldBugs();

        $this->index();

    }

    /**
     * Create new Bugtracker project
     */
    public function create()
    {
        requirePermission("canCreateProjects");

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
            $id = $this->project_model->add($data);
            $output["message"] = "Das Projekt wurde erfolgreich hinzugefügt.";
        }

        $this->logger->createLog('Created Bugtracker Project', "(".$id.") ".$data['title']);

        $this->outputJson($output);

    }

    /**
     * Load the page to edit the item with the given id.
     * @param bool $id
     */
    public function edit($id = false)
    {
        // Check for the permission
        requirePermission("canEditProjects");

        if(!is_numeric($id) || !$id)
        {
            die();
        }

        $project = $this->project_model->findProjectById($id);

        if(!$project)
        {
            show_error("Dieses Projekt wurde nicht gefunden.");
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