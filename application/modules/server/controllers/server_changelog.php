<?php

class Server_Changelog extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->model('bugtracker/bug_model');

        $this->theme_path = base_url().APPPATH.$this->template->theme_path;

        $this->template->addBreadcrumb("Server", site_url(array("server")));
        $this->template->addBreadcrumb("Changelog", site_url(array("server", 'changelog')));

    }

    public function index($type = 0)
    {
        // Section Title
        $this->template->setTitle("Server Changelog");
        $this->template->setSectionTitle("Changelog");

        $yearData = $this->getDoneBugReports();

        $pageData = array(
            'years' => $yearData,
        );

        $out = $this->template->loadPage("server_changelog.tpl", $pageData);
        $this->template->view($out);
    }

    private function getDoneBugReports(){

        if(false){
            $this->bug_model = new Bug_model();
        }

        $closedBugs = $this->bug_model->getClosedBugs();

        $years = array();

        foreach($closedBugs as $row){

            $timestamp = (empty($row['changedTimestamp'])) ? $row['createdTimestamp'] : $row['changedTimestamp'];

            if($row["changedTimestamp"] == 0){
                $dateString = empty($row['changedDate']) ? $row['createdDate'] : $row['changedDate'];
                $date = explode(".", $dateString);
                $date = $date[2]."-".$date[1]."-".$date[0];
                $date = strtotime($date);
            }
            else{
                $date = $row["changedTimestamp"];
            }

            $week = strftime("%W", $date);
            $year = strftime("%Y", $date);

            if($year < 2012){
                $year = 2011;
            }

            $row['num'] = "#".str_pad($row["id"], 4, '0', STR_PAD_LEFT);
            $row['stateCss'] = $this->bug_model->getStateColorClass($row['bug_state']);
            $row['stateLabel'] = $this->bug_model->getStateLabel($row['bug_state']);

            $row['title'] = htmlentities($row["title"], ENT_QUOTES, 'UTF-8');

            if(!isset($years[$year]))
                $years[$year] = array();
            if(!isset($years[$year][$week]))
                $years[$year][$week] = array();
            $years[$year][$week][] = $row;

        }

        return $years;

    }



}
