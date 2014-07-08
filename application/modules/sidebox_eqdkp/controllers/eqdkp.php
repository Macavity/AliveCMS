<?php
/**
 * Class Eqdkp
 */
class Eqdkp extends MX_Controller implements Sidebox{

    private $eqdkpApiUrl = "http://raid.senzaii.net/api.php";

    private $eqdkpUrl = "http://raid.senzaii.net/";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('sidebox_visitors/visitor_model');
    }

    public function view(){

        $events = $this->getNextEvents();
        $eventList = array();

        //debug($events);

        if($events){
            foreach($events as $event){
                $eventList[$event->eventid] = array(
                    'title' => $event->title,
                    'date' => strftime("%d. %b, %H:%M", $event->start_timestamp),
                    'note' => $event->note,
                    'url' => $event->url,
                    'type' => $event->type,
                );
            }
        }

        $data = array(
            "module" => "sidebox_eqdkp",
            "hasEvents" => count($eventList),
            "events" => $eventList,
            "eqdkpUrl" => $this->eqdkpUrl,
        );

        $page = $this->template->loadPage("sidebox_eqdkp.tpl", $data);

        return $page;
    }

    private function getNextEvents(){
        $json = file_get_contents($this->eqdkpApiUrl."?format=json&function=calevents_list&raids_only");

        $json = json_decode($json);

        if($json->status == 1){
            return $json->events;
        }

        return false;
    }
}
