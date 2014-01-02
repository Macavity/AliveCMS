<?php

/**
 * Class Admin
 * @package Alive/Bugtracker
 */
class Admin extends MX_Controller
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
     *
     */
    public function bug_list($projectId, $showState = "open")
    {

        requirePermission("canEditBugs");

        // Change the title
        $this->administrator->setTitle($this->mainTitle);

        $project = $this->project_model->getProjectById($projectId);

        if(!$project){
            show_error("Projekt $projectId nicht gefunden");
            die();
        }

        switch($showState){
            case "all":
                $restriction = "none";
                break;
            case "open":
            default:
                $restriction = "normal";
                break;
        }

        $bugs = $this->bug_model->getBugsByProject($projectId, $restriction);

        foreach($bugs as $key => $bug){

            $bug["state"] = $this->bug_model->getTypeLabel($bug["bug_state"]);

            // Changed Date
            if($bug["createdTimestamp"] == 0){
                $date = explode(".", $bug["createdDate"]);
                // 30.10.2011 => array( 0 => 30, 1 => 10, 2 => 2011)
                $bug["createdTimestamp"] = strtotime($date[2]."-".$date[1]."-".$date[0]);
            }
            if($bug["changedTimestamp"] == 0 && !empty($bug["changedDate"])){
                $date = explode(".", $bug["changedDate"]);
                // 30.10.2011 => array( 0 => 30, 1 => 10, 2 => 2011)
                $bug["changedTimestamp"] = strtotime($date[2]."-".$date[1]."-".$date[0]);
            }
            // No change yet? then use same date for both fields
            if($bug["changedTimestamp"] == 0){
                $bug["changedTimestamp"] = $bug["createdTimestamp"];
            }
            if(empty($bug["changedDate"])){
                $bug["changedDate"] = strftime("%d.%m.%Y", $bug["changedTimestamp"]);
            }
            $bug["changedSort"] = strftime("%Y-%m-%d", $bug["changedTimestamp"]);


            $bugs[$key] = $bug;
        }

        // Prepare my data
        $templateData = array(
            'url' => $this->template->page_url,
            'project' => $project,
            'bugs' => $bugs,
        );

        // Load my view
        $output = $this->template->loadPage("admin_bugs_list.tpl", $templateData);

        // Put my view in the main box with a headline
        $content = $this->administrator->box('Bugtracker', $output);

        $this->administrator->view($content, false, $this->jsPath);
    }

    /**
     * Change class/type for all bugs that were imported from the old system
     */
    public function import(){

        requirePermission("canEditProjects");
        echo 'Sollte keine "Abgeschlossen"-meldung kommen wurde das Zeitlimit überschritten, in diesem Fall bitte die Seite nochmal laden. Bereits importierte Datensätze werden dabei übersprungen.';

        $this->bug_model->importOldBugs();
        echo "<br>Alle alten Bugs wurden importiert.<hr>";

        $this->bug_model->importOldComments();
        echo "<br>Alle alten Kommentare wurden importiert.<hr>";

        echo "<hr><b>Abgeschlossen</b><hr>";

        return;

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

        $project = $this->project_model->getProjectById($id);

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