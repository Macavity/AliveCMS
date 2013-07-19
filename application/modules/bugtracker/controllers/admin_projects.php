<?php

/**
 * Class Admin_Projects
 */
class Admin_Projects extends MX_Controller
{

    var $jsPath = "modules/bugtracker/js/bugtracker_admin.js";
    var $mainTitle = "Bugtracker";

	public function __construct()
	{
		// Make sure to load the administrator library!
		$this->load->library('administrator');
		$this->load->model('project_model');

		parent::__construct();

		requirePermission("canEditProjects");
	}

	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Bugtracker Projekte");

        $projectList = $this->project_model->getProjects();
        $projectCount = count($projectList);

        $projectChoices = array();
        $baseProjects = array();
        $projectsByParent = array();

        foreach($projectList as $project){
            if($project["parent"] != 0){
                $projectsByParent[$project["parent"]][$project["id"]] = $project;
            }
            else{
                $baseProjects[$project["id"]] = $project;
            }
        }

        $baseProjectIds = array_keys($baseProjects);
        $subProjectIds = array_keys($projectsByParent);

        /*
        foreach($projectsByParent as $parent => $subProjects){

            foreach($subProjects as $key => $project){
                $projectsByParent[$project["parent"]][] = $project;
            }

        }*/

        foreach($baseProjects as $projectId => $project){
            $projectChoices[$projectId] = $project["title"];

            if(!empty($projectsByParent[$projectId])){
                $subProjects = $projectsByParent[$projectId];

                foreach($subProjects as $key => $subProject){
                    if(!empty($projectsByParent[$key])){
                        $subsubs = $projectsByParent[$key];

                        $subProjects[$key]["projects"] = $subsubs;
                    }

                    $projectChoices[$key] = $project["title"].": ".$subProject["title"];

                }

                $baseProjects[$projectId]["projects"] = $subProjects;

            }

        }

		// Prepare my data
		$templateData = array(
			'url' => $this->template->page_url,
			'projects' => $baseProjects,
		    'projectCount' => $projectCount,
            'projectChoices' => $projectChoices,
        );

		// Load my view
		$output = $this->template->loadPage("admin_project_list.tpl", $templateData);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Bugtracker', $output);

		$this->administrator->view($content, false, $this->jsPath);
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
            "parent" => $this->input->post("projectParent"),
        );

        $output = array(
            "state" => "success",
            "message" => "",
            "debug" => print_r($data, true),
        );

        if(empty($data["title"]))
        {
            $output["state"] = "error";
            $output["message"] = "Bitte tragen einen Namen für diese Kategorie ein!";
        }


        if($output["state"] != "error"){
            $id = $this->project_model->add($data);
            $output["message"] = "Das Projekt wurde erfolgreich hinzugefügt.";

            $this->logger->createLog('Created Bugtracker Project', "(".$id.") ".$data['title']);
        }

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